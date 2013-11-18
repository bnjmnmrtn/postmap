var map = null;
var datapoints = null;
var infoWindow = null;

var LATITUDE_INDEX  = 0;
var LONGITUDE_INDEX = 1;
var DATE_INDEX      = 2;
var TITLE_INDEX     = 3;
var LINK_INDEX      = 4;
var YEAR_INDEX      = 5;

function generateInfoWindow(data, position) {
    return '<h4>' + data[TITLE_INDEX] + '</h4>' + 
             '<div style="float: left">' + data[DATE_INDEX] + '</div>' +
             '<div style="float: right"><a href="' + data[LINK_INDEX] + '">View post</a></div>';
}   

function showInfoWindow(marker, data) {
    infoWindow.setContent(generateInfoWindow(data));
    infoWindow.open(map, marker);
}

function initialize() {

    var container = jQuery('#map_canvas').first();
    if (!container) {
        return;
    }

    var zoom = parseInt(container.attr('data-zoom'), 10);

    var pos = new google.maps.LatLng(45, 0);
    var mapOptions = {
        center: pos,
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(container[0], mapOptions);
    infoWindow = new google.maps.InfoWindow();
    datapoints = getDatapoints();

    var i;
    for (i = 0; i < datapoints.length; i++) {
        var datapoint = datapoints[i];
        var position = new google.maps.LatLng(datapoint[LATITUDE_INDEX], datapoint[LONGITUDE_INDEX])
        var marker = new google.maps.Marker({
            map: map,
            position: position
        });
        marker.data = datapoint;

        google.maps.event.addListener(marker, 'click', (function(marker, datapoint) { return function() {
            showInfoWindow(marker, datapoint);
        }})(marker, datapoint));
    }
}
google.maps.event.addDomListener(window, "load", initialize);
