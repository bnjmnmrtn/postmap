function initialize() {

    var container = jQuery('#map_canvas').first();
    if (!container) {
        return;
    }

    var zoom = parseInt(container.attr('data-zoom'), 10);
    var latitude = parseFloat(container.attr('data-latitude'));
    var longitude = parseFloat(container.attr('data-longitude'));

    var pos = new google.maps.LatLng(latitude, longitude);
    var mapOptions = {
        center: pos,
        zoom: zoom,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(container[0], mapOptions);
    new google.maps.Marker({
        map: map,
        position: pos,
        content: 'Current Location'
    });
}
google.maps.event.addDomListener(window, "load", initialize);
