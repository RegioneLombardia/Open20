var MYMAP = {
    map: null//,
    //bounds: null
}

MYMAP.init = function (selector, latLng, zoom, filename) {
    var myOptions = {
        zoom: zoom,
        center: latLng,
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: true,
        overviewMapControl: false,
        panControl: false,
        gestureHandling: 'greedy',
        zoomControl: true,
        zoomControlOptions: {
            style: google.maps.ZoomControlStyle.SMALL,
            position: google.maps.ControlPosition.TOP_RIGHT
        },
        maxZoom: 21,
        minZoom: 10,
        styles:
            [
                {
                    featureType: "poi",
                    elementType: "labels",
                    stylers:
                        [
                            {
                                visibility: "off"
                            }
                        ]
                }
            ]
    }

    var map = new google.maps.Map($(selector)[0], myOptions);
    var markers = [];
    //var bounds = new google.maps.LatLngBounds();
    $.getJSON(filename, function (data) {
        if (data) {
            //console.log(data);
            $.each(data.markers, function (i, dati) {
                var point = new google.maps.LatLng(parseFloat(dati.latitude), parseFloat(dati.longitude));
                var icona = dati.icona;
                var contenuto = dati.content;
                var marker = new google.maps.Marker({
                    position: point,
                    map: map,
                    //'bounds': true,
                    icon: '/images/' + icona
                });
                markers.push(marker);
                var html = '<div class="infowindow">' + contenuto + '</div>';
                var infowindow = new google.maps.InfoWindow({
                    content: html
                });
                if ((icona == 'dove_dormire0.png')) {
                    infowindow.open(map, marker);
                    if (selector != "#mappa_dettaglio") {
                        map.setZoom(21);
                    } else {
                        map.setZoom(13);
                    }
                    // centro la mappa un pò + in basso per non sovrapporsi alla scritta in hover
                    latM = marker.getPosition().lat() + 5;
                    lonM = marker.getPosition().lng();
                    newCenter = new google.maps.LatLng(latM, lonM);
                    map.setCenter(newCenter);
                } else {
                    google.maps.event.addListener(marker, 'click', function () {
                        infowindow.open(map, marker);
                        map.panTo(point);
                    });
                    google.maps.event.addListener(map, 'click', function () {
                        infowindow.close();
                    });
                }
                //bounds.extend(point);
                //map.fitBounds(bounds);
            });
            /* each */
            var markerCluster = new MarkerClusterer(map, markers);
            minClusterZoom = 11;
            markerCluster.setMaxZoom(minClusterZoom);
        }
    });
    /* getJSON */
} 