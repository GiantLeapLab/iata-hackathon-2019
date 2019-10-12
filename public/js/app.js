var map;
var userLocation;
var bounds = [];
var boundsVisited = [];
var boundsUserLocation = [];
var infoWindow;
var visitedCountries = { 
    'Hungary': {'Budapest':'June 2, 2019 - June 10, 2019' }, 
    'Poland': {'Kraków':  'June 2, 2019 - June 10, 2019', 'Warsaw': 'June 2, 2019 - June 10, 2019' , 'Gdansk': 'June 2, 2019 - June 10, 2019' }, 
    'Belarus': { 'Brest': 'June 2, 2019 - June 10, 2019', 'Minsk': 'June 2, 2019 - June 10, 2019' } 
};
var visitedColor = 'gray';
var labels = [];
var weatherMarkers = [];
var flightPath;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 5,
        center: { lat: 52.886, lng: 35.268 },
        mapTypeId: 'roadmap'
    });

    infoWindow = new google.maps.InfoWindow;
    google.maps.event.addListener(infoWindow, 'closeclick', function () {
        clearRoute()
    });
}

$(document).ready(function () {

    $('#start-button').click(function () {
        $(this).closest('.start-page').hide();
    })

    showPastTrips();

    $('#countrySelect').change(function () {

        infoWindow.close();
        clearBounds();
        clearRoute();

        var selectedCountries = $(this).val();
        featuresCountries.forEach(function (item) {
            // Show selected country
            if (selectedCountries.indexOf(item.getProperty('ADMIN')) !== -1) {
                item.getGeometry().forEachLatLng(function (latLng) {
                    addBound(latLng);
                })
                if (!item.getProperty('selected')) {
                    item.setProperty('selected', true);

                    // Show cities
                    featuresPlaces.forEach(function (place) {
                        if (place.getProperty('sov_a3') == item.getProperty('ISO_A3')) {
                            place.setProperty('selected', true);
                            addCityLabel(place);
                            addCityWeather(place);
                        }
                    })
                }
                // Hide unselected countries
            } else {
                item.setProperty('selected', false);
                // Hide cities
                featuresPlaces.forEach(function (place) {
                    if (place.getProperty('sov_a3') == item.getProperty('ISO_A3')) {
                        place.setProperty('selected', false);
                        removeCityLabel(place);
                        removeCityWeather(place);
                    }
                })
            }
        })

        // Fit bounds
        if (!bounds.isEmpty()) {
            map.fitBounds(bounds.union(boundsUserLocation));
        } else if (!boundsVisited.isEmpty()) {
            map.fitBounds(boundsVisited.union(boundsUserLocation));
        } else {
            map.setCenter(new google.maps.LatLng(50, 10));
            map.setZoom(5)
        }
    });

    /* Load JSON countried to DATA layer */
    var featuresCountries = map.data.addGeoJson(countries, { idPropertyName: "ADMIN" });

    /* Get all countries names */
    featuresCountries.forEach(function (feature) {
        $('#countrySelect').append($('<option>', {
            value: feature.getId(),
            text: feature.getId()
        }));
    })

    /* Load JSON ccities to DATA layer */
    var featuresPlaces = map.data.addGeoJson(places);

    map.data.setStyle(function (feature) {
        /* Random color */
        if (feature.getProperty('visited') && !feature.getProperty('selected')) {
            if (feature.getProperty('unwanted')) {
                var fillColor = '#7eb300';
                var strokeColor = '#537700';
            } else {
                var fillColor = 'green';
                var strokeColor = 'green';
            }
            var iconUrl = "/public/img/visited-city.png";
        } else {
            //var fillColor = '#' + (0x1000000 + (Math.random()) * 0xffffff).toString(16).substr(1, 6);
            var fillColor = '#3cad4c';
            var strokeColor = '#2c870c';
            var iconUrl = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
        }

        return /** @type {!google.maps.Data.StyleOptions} */({
            title: feature.getProperty('name'),
            fillOpacity: 0.85,
            fillColor: fillColor,
            strokeColor: strokeColor,
            strokeWeight: 1,
            strokeOpacity: 1,
            animation: google.maps.Animation.DROP,
            icon: {
                url: iconUrl
            },
            visible: ((typeof feature.getProperty('selected') !== 'undefined' && feature.getProperty('selected')) || typeof feature.getProperty('alwaysVisible') !== 'undefined'),
        });
    });

    // Data layer item click listener
    map.data.addListener('click', function (event) {
        console.log('click ' + event.feature.getGeometry().getType())

        // Show info and build route to city
        if (event.feature.getGeometry().getType() == 'Point') {
            console.log(getAirportsByCity(event.feature.getProperty('name')));
            var cityInfo = getCityInfo(event.feature.getProperty('name'));
            infoWindow.setContent(cityInfo.title);
            infoWindow.setPosition(event.feature.getGeometry().get());
            infoWindow.setOptions({
                pixelOffset: new google.maps.Size(0, -30)
            });
            infoWindow.open(map);
            if (cityInfo.hasAirports) {
                buildRouteToUser(event.feature.getGeometry().get());
            } else {
                clearRoute();
            }
        } else {
            clearRoute();
        }
    });

    initBounds();
    showVisitedCountries(featuresCountries, featuresPlaces);
    showUserLocation();
    setTimeout(() => {
        makeVisitedCountriesUnwanted(featuresCountries);
    }, 4000);



    $('#controls').on('click', '#stopButton', function(){
        RecordSpeech.stopRecording(WitTool.sendSpeech.bind(WitTool))
    })
    $('#controls').on('click', '#recordButton', function(){
        SunnyBot.listen()
    })

});

var SunnyBot = {
    listen: function(){
        RecordSpeech.startRecording()
    },
    say: function () {

    }
}

/* RECORD PART */
var RecordSpeech = {
    url: window.URL || window.webkitURL,
    gumStream: null,
    input: null,
    AudioContext: window.AudioContext || window.webkitAudioContext,
    audioContext: null,
    startButton: null,
    stopButton: null,
    onStartCallback: function () { console.log("Recording started") },
    onStopCallback: function () { console.log('stop record') },
    initButtons: function(){
        if (!this.startButton) {
            this.startButton = document.getElementById('recordButton')
        }
        if (!this.stopButton) {
            this.stopButton = document.getElementById('stopButton')
        }
    },
    beforeStartRecord: function() {
        this.input = null
        this.audioContext = null
        this.gumStream = null
        this.initButtons()
        console.log("recordButton clicked")
    },
    beforeStopRecord: function(){
        this.initButtons()
        this.onStopCallback = function () { console.log('stop record. empty callback') }
        console.log("stopButton clicked");
    },
    startRecording: function() {
        var constraints = { audio: true, video:false }
        this.beforeStartRecord();
        this.startButton.disabled = true;
        this.stopButton.disabled = false;
        var that = this
        navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
            console.log("getUserMedia() success, stream created, initializing Recorder.js ...");
            that.audioContext = new that.AudioContext();
            that.gumStream = stream;
            that.input = that.audioContext.createMediaStreamSource(stream);
            that.rec = new Recorder(that.input,{numChannels:1})
            //start the recording process
            that.rec.record()

            that.onStartCallback();
        }).catch(function(err) {
            //enable the record button if getUserMedia() fails
            that.stopButton.disabled = true;
            that.startButton.disabled = false;
            console.log(err)
        });
    },
    stopRecording: function (stopCallback) {
        this.beforeStopRecord()

        if(typeof stopCallback == "function") {
            this.onStopCallback = stopCallback;
        }
        //disable the stop button, enable the record too allow for new recordings
        this.stopButton.disabled = true;
        this.startButton.disabled = false;
        //tell the recorder to stop the recording
        this.rec.stop();
        //stop microphone access
        this.gumStream.getAudioTracks()[0].stop();
        //create the wav blob and pass it on to the callback
        var that = this
        this.rec.exportWAV(this.onStopCallback/*function (blob) {
            that.onStopCallback(blob)
        }*/);
    }
}


var WitTool = {
    speechURL: "https://api.wit.ai/speech?v=20170307",
    apiKey: "HDFNOE33ILJWP2YHRUSAWIZE52SKQKSL",
    sendSpeech:function (speechBlob) {
        var that = this

        jQuery.ajax({
            url: this.speechURL,
            type:"POST",
            headers: {"Authorization": "Bearer " + this.apiKey, "Content-Type":"audio/wav"},
            data: speechBlob,
            processData: false,
            success: function (response) {
                console.log(response)
                if (typeof that.speechResponseCallback == "function") {
                    that.speechResponseCallback(response)
                }
            }
        })
    },
    speechResponseCallback: function (response) { console.log('WIT Speech response received', response) }
}

function f(blob) {
    jQuery.ajax({
        url: "https://api.wit.ai/speech?v=20170307",
        type:"POST",
        headers: {"Authorization": "Bearer HDFNOE33ILJWP2YHRUSAWIZE52SKQKSL", "Content-Type":"audio/wav"},
        data: blob,
        processData: false,
        success: function (response) {
            if (typeof callback == "function") {
                callback(response)
            }
        }
    })
}
