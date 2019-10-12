function makeVisitedCountriesUnwanted(featuresCountries) {
    featuresCountries.forEach(function (item) {
        if (item.getProperty('visited')) {
            item.setProperty('unwanted', true);
        }
    })
}

function showVisitedCountries(featuresCountries, featuresPlaces) {
    featuresCountries.forEach(function (item) {
        if (visitedCountries.indexOf(item.getProperty('ADMIN')) !== -1) {
            item.getGeometry().forEachLatLng(function (latLng) {
                boundsVisited.extend(latLng);
            })
            item.setProperty('visited', true);
            featuresPlaces.forEach(function (place) {
                if (place.getProperty('sov_a3') == item.getProperty('ISO_A3')) {
                    place.setProperty('visited', true);
                }
            })
        }
    })
}

function addCityLabel(place) {
    var mapLabel = new MapLabel({
        text: place.getProperty('name'),
        position: new google.maps.LatLng(place.getProperty('latitude'), place.getProperty('longitude')),
        map: map,
        fontSize: 16,
        align: 'center'
    });
    labels[place.getProperty('geonameid')] = mapLabel;
}

function removeCityLabel(place) {
    if (typeof labels[place.getProperty('geonameid')] !== 'undefined') {
        labels[place.getProperty('geonameid')].setMap(null);
        delete labels[place.getProperty('geonameid')];
    }
}

function clearCityLabels() {
    labels.forEach(function (label) {
        label.setMap(null);
        delete label;
    })
    labels = [];
}

function initBounds(){
    bounds = new google.maps.LatLngBounds();
    boundsUserLocation = new google.maps.LatLngBounds();
    boundsVisited = new google.maps.LatLngBounds();
}

function addBound(latLng) {
    bounds.extend(latLng);
}

function clearBounds() {
    bounds = new google.maps.LatLngBounds();
}

function showUserLocation(){
    navigator.geolocation.getCurrentPosition(function (position) {
        userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
        };
        var marker = new google.maps.Marker({
            position: userLocation,
            map: map,
            icon: 'https://www.google.com/mapfiles/arrow.png',
            title: 'You are here'
        });
        boundsUserLocation.extend(userLocation);
        map.fitBounds(boundsVisited.union(boundsUserLocation));
    }, function () {
        handleLocationError(true, infoWindow, map.getCenter());
    });
}

function buildRouteToUser(fromLatLng) {
    if (userLocation) {
        addPolyline(fromLatLng, userLocation);
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, map.getCenter());
    }
}

function clearRoute(){
    if(typeof flightPath !== 'undefined'){
        flightPath.setMap(null);
    }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
    infoWindow.setPosition(pos);
    infoWindow.setContent(browserHasGeolocation ?
        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.');
    infoWindow.open(map);
}

function addPolyline(fromLatLng, toLatLng) {
    var flightPlanCoordinates = [
        fromLatLng,
        toLatLng
    ];
  
    if(typeof flightPath === 'undefined'){
        var lineSymbol = {
            path: 'M 0,-1 0,1',
            strokeOpacity: 1,
            strokeWeight: 4,
            scale: 4
          };

        flightPath = new google.maps.Polyline({
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 0.0,
            strokeWeight: 4,
            icons: [{
                icon: lineSymbol,
                offset: '0',
                repeat: '20px'
              }],
        });
    }
    flightPath.setPath(flightPlanCoordinates);
    flightPath.setMap(map);
}

function getAirportsByCity(city){
    var result = {};
    Object.keys(airports).forEach(function (key) {
        if(airports[key].city == city){
            result[key] = airports[key];
        }
    })
    return result;
}

function getCityInfo(city){
    var result = {
        'title': city,
        'hasAirports': false
    }

    var cityAirports = getAirportsByCity(city);
    
    if(Object.keys(cityAirports).length){
        result.hasAirports = true;
        result.title += '<br>' + Object.keys(cityAirports).join(', ');
    }

    return result;

}