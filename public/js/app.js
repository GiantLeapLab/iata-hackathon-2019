var map;
var userLocation;
var bounds = [];
var boundsVisited = [];
var boundsUserLocation = [];
var infoWindow;
var visitedCountries = {  
    'Poland': {'Kraków':  'May 10, 2018 - May 13, 2018', 'Warsaw': 'June 2, 2019 - June 10, 2019' , 'Gdansk': 'July 12, 2018 - July 19, 2018' }, 
    'Lithuania': { 'Vilnius': 'May 2, 2019 - May 8, 2019'},
    'Czechia': { 'Prague': 'April 15, 2019 - April 23, 2019'},
    'Slovenia': { 'Ljubljana': 'July 14, 2019 - July 22, 2019'},
    'Germany': { 'Dresden': 'May 2, 2019 - May 8, 2019', 'Hamburg' : 'April 15, 2019 - April 23, 2019', 'Berlin' : 'April 15, 2019 - April 23, 2019', 'Munich' : 'April 15, 2019 - April 23, 2019', 'Frankfurt' : 'April 15, 2019 - April 23, 2019'}
};
var forgottenVisitedCountry = {'Italy': { 'Rome': 'August 3, 2018 - August 10, 2018', 'Naples': 'October 1, 2019 - October 10, 2019'}}
var labels = [];
var weatherMarkers = [];
var flightPath;
var featuresCountries;
var featuresPlaces;

// selected city names
var selectedCities = [];
var selectedCountries = ['Italy', 'Greece', 'Turkey', 'Croatia'];

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

    /* Load JSON countried to DATA layer */
    featuresCountries = map.data.addGeoJson(countries, { idPropertyName: "ADMIN" });

    /* Load JSON ccities to DATA layer */
    featuresPlaces = map.data.addGeoJson(places);

    map.data.setStyle(function (feature) {
        /* Random color */
        if (feature.getProperty('visited') && !feature.getProperty('selected')) {
            if (feature.getProperty('unwanted')) {
                var fillColor = '#e2e2e2';
                var strokeColor = '#a7a7a7';
            } else {
                var fillColor = '#7eb300';
                var strokeColor = '#537700';
            }
            var iconUrl = "img/visited-city.png";
            var scaledSize = new google.maps.Size(16, 16);
        } else {
            //var fillColor = '#' + (0x1000000 + (Math.random()) * 0xffffff).toString(16).substr(1, 6);
            var fillColor = '#e96c3d';
            var strokeColor = '#bd3400';
            var iconUrl = "img/suggested-city.png";
            var scaledSize = new google.maps.Size(32, 32)
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
                url: iconUrl,
                scaledSize: scaledSize,
            },
            visible: ((typeof feature.getProperty('selected') !== 'undefined' && feature.getProperty('selected')) || (typeof feature.getProperty('alwaysVisible') !== 'undefined' && feature.getProperty('alwaysVisible'))),
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
    //showVisitedCountries();
   //makeVisitedCountriesUnwanted();
    showUserLocation();

    $('#controls').on('click', '#stopButton', function(){
        SunnyBot.processRequest()
    })
    $('#controls').on('click', '#recordButton', function(){
        SunnyBot.listen()
    })

    $(document).keydown(function(event){

        if (event.which == 83 ) {
            SunnyBot.listen()
        }
        if (event.which == 68 ) {
            SunnyBot.processRequest()
        }

    })

});
var AnswerActions = {
    j: jQuery,
    defaultAnswerIndex: 100,
    prevAnswer: 'default',
    answers: [
        {
            index: 1,
            entity: 'hi',
            text: 'Hi',
            execute: function (params) {
                //silence is golden
            }
        },
        {
            index: 2,
            entity: "where_to_go",
            text: 'Ok! Are you up to some sightseeing, basking in the sun, or maybe rural tourism?',
            execute: function (params) {
                console.log(params)
                AnswerActions.j('.date--separator').text('')
                AnswerActions.j('.date--second-part').text('')
                SunnyBot.say(this.text)
                var dateFrom = false
                var dateTo = false

                if ( params.datetime && params.datetime.length > 0) {
                    dateFrom = params.datetime[0].value
                    var durationDay = 5

                    if (params.duration && params.duration.length > 0) {

                        durationDay = params.duration[0].value
                    }

                    dateTo = moment(dateFrom).add(durationDay, 'days')
                }

                if (dateFrom == false && dateTo == false) {
                    dateFrom = '2019-10-14'
                    dateTo = '2019-10-18'
                }
                TripData.dateFrom = moment(dateFrom).format("YYYY-MM-DD")
                TripData.dateTo = moment(dateTo).format("YYYY-MM-DD")
                AnswerActions.j('.date--first-part').text(moment(dateFrom).format("MMM D, YYYY") + ' - ' + moment(dateTo).format("MMM D, YYYY"))
                AnswerActions.j('.top-bar').addClass('active')

            }
        },
        {
            index: 3,
            entity: 'proximity',
            text: 'Understood. Let me see...',
            execute: function (params) {
                map.setZoom(5)
                var text = this.text
                setTimeout(function () {
                    SunnyBot.say(text)
                }, 500)
                var text2 = 'According to my records, here are the countries and cities you visited before. Should I exclude them from the search?'
                setTimeout(function () {
                    showVisitedCountries()
                    SunnyBot.say(text2)
                }, 1500)
            }
        },
        {
            index: 4,
            entity: 'yes_no',
            text: 'Ok!',
            execute: function (params) {
                SunnyBot.say(this.text)
                makeVisitedCountriesUnwanted()
                var text2 = 'Have a look at the south coast. Italy, Greece,  Croatia, and Turkey have high tourist rating and famous historical sightseeings. I’ve prepared the weather forecast as well.'

                setTimeout(function () {
                    showSelectedCountries()
                    SunnyBot.say(text2)
                }, 2000)
            }
        },
        {
            index: 5,
            entity: 'location_visited',
            text: 'Oh, sorry.',
            execute: function (params) {
                SunnyBot.say(this.text)
                var text2 = 'I’ve selected a few attractive cities for you. Please check the map.'
                setTimeout(function () {
                    addCountryToVisited()
                    showSelectedCities()
                    SunnyBot.say(text2)
                }, 300)
            }
        },
        {
            index: 6,
            entity: 'location_info',
            text: 'Sure, here is a brief summary.',
            execute: function (params) {
                SunnyBot.say(this.text)
                setTimeout(function () {
                    AnswerActions.j('.popup--antalya').fadeIn()
                }, 200)
            }
        },
        {
            index: 7,
            entity: 'flights_to_location',
            text: 'Ok. One minute please…',
            execute: function (params) {
                SunnyBot.say(this.text)
                AnswerActions.j('.date--separator').text(' / ')
                AnswerActions.j('.date--second-part').text(' Antalya, Turkey ')
                var text2 = 'There is a number of flights to Antalya for your dates. Please review and choose.'
                var airport = getAirportByCity('Antalya')
                console.log(airport)
                buildRouteToUser({
                    lat: Number(airport.latitude),
                    lng: Number(airport.longitude)
                })
                TripData.arrCode = airport.iata
                TripData.arrCity = 'Antalya'
                TripData.dateFrom = '2019-10-15'
                TripData.dateTo = '2019-10-20'
                setTimeout(function () {
                    AnswerActions.j( ".popup--flight" ).load( "/api.php?action=search&depCode="
                        + TripData.depCode
                        + "&arrCode="+TripData.arrCode
                        + "&depDate=" + TripData.dateFrom
                        + "&arrDate" + TripData.dateTo,
                        function() {
                            AnswerActions.j('.popup--flight').fadeIn()
                            SunnyBot.say(text2)
                        })
                }, 2000)

            }
        },
        {
            index: 8,
            entity: 'book',
            text: 'Ok, booking…',
            execute: function (params) {
                SunnyBot.say(this.text)

                AnswerActions.j('div.flight').not('div.flight[data-offer-number="1"]').addClass('inactive')
                setTimeout(function () {
                    /*TripData.arrCode = 'AYT'
                TripData.depCode = 'FRA'*/
                    var text2 = 'Did you know that this flight would release 1 ton of carbon dioxide into the atmosphere? The airline participates in a program for … in … city to compensate . Would you like to donate 1 euro to cover your part in this flight ….'
                    AnswerActions.j.get('/api.php?action=carbon&depCode=' + TripData.depCode + '&arrCode='+TripData.arrCode + '&passengersAmount=1', function (res) {
                        AnswerActions.j('.button-block').show()
                        AnswerActions.j('.popup--flight').hide()
                        AnswerActions.j('.popup--emissions--distance').text(res.distance_km.toFixed() + ' km distance')
                        AnswerActions.j('.popup--emissions--weight').text(res.co2_kg_total.toFixed(2) + ' kg')
                        AnswerActions.j('.popup--emissions').fadeIn()
                    })
                    SunnyBot.say(text2)
                }, 2000)

            }
        },
        {
            index: 9,
            entity: 'last_step',
            text: 'Thank you! Your booking is now confirmed!',
            execute: function (params) {
                SunnyBot.say(this.text)
                AnswerActions.j('.button-block').show()
                AnswerActions.j('.popup--flight.fligt-ready').fadeIn()
                TripData.arrCity = 'Antalya'
                resetMap(TripData.arrCity)
            }
        }
    ],
    defaultAnswer: {
        index: 100,
        entity: 'default',
        text: 'I am not sure that I understood your question',
        execute: function () {
            SunnyBot.say(this.text)
        }
    },
    findAnswer: function(entities){
        console.log(entities)
        var result = this.defaultAnswer
        this.answers.forEach(function (answer) {
            if (entities.indexOf(answer.entity) != -1) {
                console.log('find answer', answer)
                result = Object.assign({}, answer)
            }
        })
        this.prevAnswer = result.index != this.defaultAnswerIndex ? result.entity : 'default'

        return result
    }
}
var TripData = {
    dateFrom: '',
    dateTo: '',
    depCode: 'FRA',
    arrCode: '',
    arrCity: ''
}
var SunnyBot = {
    j: jQuery,
    toastContainer: '#sunny-message-board',

    listen: function(){
        RecordSpeech.startRecording()
        //AnswerActions.findAnswer(['last_step','datetime']).execute({default:'ffffffff'})
    },

    processRequest: function () {
        //do request to WIT
        WitTool.speechResponseCallback = this.parseWITResponse.bind(this)
        RecordSpeech.stopRecording(WitTool.sendSpeech.bind(WitTool))
    },
    parseWITResponse: function(response) {
        console.log('sunny parse start')
        console.log(response)
        if (response._text) {
            this.j('.popup-force-hide').hide()
            this.displayToast(response._text, true)
            AnswerActions.findAnswer(Object.keys(response.entities)).execute(response.entities)
        }
    },

    say: function (message) {
        this.displayToast(message, false)
        this.voiceAnswer(message)
    },
    displayToast: function(message, isUser) {
        isUser = isUser?isUser:false
        var template = '<div class="toast #isUser#" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">\n' +
            '                                <div class="toast-body">\n' +
            '                                    <b>#name#:</b>\n' +
            '                                    #message#\n' +
            '                                </div>\n' +
            '                            </div>'

        this.j(this.toastContainer).append(
            template
                .replace('#isUser#', isUser?'user':'bot')
                .replace('#name#', isUser?'You':'Sunny')
                .replace('#message#', message)
        )
        this.j('.toast').toast('show')
    },
    voiceAnswer: function (message) {
        console.log('there should be ajax request to the IBM API to transform text into speech')
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
        if(this.rec) {
            //tell the recorder to stop the recording
            this.rec.stop();
            //stop microphone access
            this.gumStream.getAudioTracks()[0].stop();
            //create the wav blob and pass it on to the callback
            var that = this
            this.rec.exportWAV(this.onStopCallback);
        }
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
                //console.log(response)
                if (typeof that.speechResponseCallback == "function") {
                    that.speechResponseCallback(response)
                }
            }
        })
    },
    speechResponseCallback: function (response) { console.log('WIT Speech response received', response) }
}

