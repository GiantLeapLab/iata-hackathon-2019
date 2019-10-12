var map;
var userLocation;
var bounds = [];
var boundsVisited = [];
var boundsUserLocation = [];
var infoWindow;
var visitedCountries = ['Ukraine', 'Moldova', 'Poland', 'Belarus'];
var visitedColor = 'gray';
var labels = [];
var flightPath;

function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 5,
        center: { lat: 52.886, lng: 35.268 },
        mapTypeId: 'terrain'
    });

    infoWindow = new google.maps.InfoWindow;
    google.maps.event.addListener(infoWindow,'closeclick',function(){
        clearRoute()
     });
}

$(document).ready(function () {

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
                    }
                })
            }
        })

        // Fit bounds
        if (!bounds.isEmpty()) {
            map.fitBounds(bounds.union(boundsUserLocation));
        } else if(!boundsVisited.isEmpty()){
            map.fitBounds(boundsVisited.union(boundsUserLocation));
        }else{
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
                var color = 'gray';
            } else {
                var color = 'green';
            }
            var iconUrl = "http://maps.google.com/mapfiles/ms/icons/blue-dot.png";
        } else {
            var color = '#' + (0x1000000 + (Math.random()) * 0xffffff).toString(16).substr(1, 6);
            var iconUrl = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
        }

        return /** @type {!google.maps.Data.StyleOptions} */({
            title: feature.getProperty('name'),
            fillOpacity: 0.45,
            fillColor: color,
            strokeColor: color,
            strokeWeight: 3,
            strokeOpacity: 0.8,
            icon: {
                url: iconUrl
            },
            visible: ((typeof feature.getProperty('selected') !== 'undefined' && feature.getProperty('selected')) || typeof feature.getProperty('visited') !== 'undefined'),
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
            if(cityInfo.hasAirports){
                buildRouteToUser(event.feature.getGeometry().get());
            }else{
                clearRoute();
            }
        }else{
            clearRoute();
        }
    });

    initBounds();
    showVisitedCountries(featuresCountries, featuresPlaces);
    showUserLocation();
    setTimeout(() => {
        makeVisitedCountriesUnwanted(featuresCountries);
    }, 4000);

    var recordButton = document.getElementById("recordButton");
    var stopButton = document.getElementById("stopButton");
    var pauseButton = document.getElementById("pauseButton");

//add events to those 2 buttons
    recordButton.addEventListener("click", startRecording);
    stopButton.addEventListener("click", stopRecording);
    pauseButton.addEventListener("click", pauseRecording);

});

/* RECORD PART */

//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec; 							//Recorder.js object
var input; 							//MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb.
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

/*
var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
pauseButton.addEventListener("click", pauseRecording);
*/

function startRecording() {
    console.log("recordButton clicked");

    /*
        Simple constraints object, for more advanced audio features see
        https://addpipe.com/blog/audio-constraints-getusermedia/
    */

    var constraints = { audio: true, video:false }

    /*
       Disable the record button until we get a success or fail from getUserMedia()
   */

    recordButton.disabled = true;
    stopButton.disabled = false;
    pauseButton.disabled = false

    /*
        We're using the standard promise based getUserMedia()
        https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
    */

    navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
        console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

        /*
            create an audio context after getUserMedia is called
            sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
            the sampleRate defaults to the one set in your OS for your playback device

        */
        audioContext = new AudioContext();

        //update the format
        document.getElementById("formats").innerHTML="Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"

        /*  assign to gumStream for later use  */
        gumStream = stream;

        /* use the stream */
        input = audioContext.createMediaStreamSource(stream);

        /*
            Create the Recorder object and configure to record mono sound (1 channel)
            Recording 2 channels  will double the file size
        */
        rec = new Recorder(input,{numChannels:1})

        //start the recording process
        rec.record()

        console.log("Recording started");

    }).catch(function(err) {
        //enable the record button if getUserMedia() fails
        recordButton.disabled = false;
        stopButton.disabled = true;
        pauseButton.disabled = true
    });
}

function pauseRecording(){
    console.log("pauseButton clicked rec.recording=",rec.recording );
    if (rec.recording){
        //pause
        rec.stop();
        pauseButton.innerHTML="Resume";
    }else{
        //resume
        rec.record()
        pauseButton.innerHTML="Pause";

    }
}

function stopRecording() {
    console.log("stopButton clicked");

    //disable the stop button, enable the record too allow for new recordings
    stopButton.disabled = true;
    recordButton.disabled = false;
    pauseButton.disabled = true;

    //reset button just in case the recording is stopped while paused
    pauseButton.innerHTML="Pause";

    //tell the recorder to stop the recording
    rec.stop();

    //stop microphone access
    gumStream.getAudioTracks()[0].stop();

    //create the wav blob and pass it on to createDownloadLink
    rec.exportWAV(createDownloadLink);
}

function createDownloadLink(blob) {
    f(blob, function (response) {
        console.log(response)
    })
}

function f(blob, callback) {
    jQuery.ajax({
        url: "https://api.wit.ai/speech?v=20170307",
        type:"POST",
        headers: {"Authorization": "Bearer DQPPDWQB5IMTRQFPIJ7QZKNTP4F2MQV4", "Content-Type":"audio/wav"},
        data: blob,
        processData: false,
        success: function (response) {
            if (typeof callback == "function") {
                callback(response)
            }
        }
    })
}
