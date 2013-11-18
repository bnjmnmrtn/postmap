var map = null;
var currentMarker = null;
var clickTimeout = null;

function initialize() {
    var mapOptions = {
        center: new google.maps.LatLng(48, 16),
        zoom: 5,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var mapContainer = document.getElementById('location-picker-map');
    map = new google.maps.Map(mapContainer, mapOptions);
    google.maps.event.addListener(map, 'click', mapClicked);
    google.maps.event.addListener(map, 'dblclick', mapDoubleClicked);
}

function updateCurrentLocation(pos) {
    if (currentMarker) {
        currentMarker.setPosition(pos);
    }
    else {
        currentMarker = new google.maps.Marker({
            map: map,
            position: pos,
            content: 'Current Location'
        });
    } 

    jQuery('.coordinate_viewer').val(pos.toString());
}

function mapDoubleClicked(event) {
    if (clickTimeout) {
        clearTimeout(clickTimeout);
    }
}

function mapClicked(event) {
    clickTimeout = setTimeout(function() {
        updateCurrentLocation(event.latLng);
    }, 300);
}

function goToMyLocation() {
    navigator.geolocation.getCurrentPosition(function(position) {
        var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
        map.setCenter(pos);
        map.setZoom(9);
        updateCurrentLocation(pos);
    }, function() {
        alert("Error determining location");
    });
}


if (!navigator.geolocation) {
    jQuery('.my-position').hide();
}
jQuery('.my-position').click(goToMyLocation);

google.maps.event.addDomListener(window, "load", initialize);
