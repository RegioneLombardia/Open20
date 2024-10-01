var MYMAP = {
    map: null
}

MYMAP.init = function (selector, centerView, zoom, min_zoom, max_zoom, ArrMarkers, styles, cardTemplate) {

    if(ArrMarkers.length == 0){
        centerView = {'lat': 41.710648,'lng': 12.7419672};
        zoom = min_zoom;
    }
    var myOptions = {
        zoom: zoom,
        center: centerView == '' ? null : centerView,
        /*scrollwheel: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        scaleControl: true,
        streetViewControl: false,
        overviewMapControl: true,
        panControl: true,       */
        gestureHandling: 'cooperative',
        maxZoom: max_zoom,
        minZoom: min_zoom, 
        styles: styles,
    }
    
    var map = new google.maps.Map($(selector)[0], myOptions);
    var bounds  = new google.maps.LatLngBounds();
    //Create and open InfoWindow.
    var infowindow = new google.maps.InfoWindow();

    markers = [];
    $.each(ArrMarkers, function (i, dati) {
        var point = new google.maps.LatLng(parseFloat(dati.latitudine), parseFloat(dati.longitudine));
        var icona = dati.icona;
        var title = dati.title;
        var content = dati.content;
        var img = dati.img;
        var link = dati.link;    
        var label = dati.label;  

        if(centerView == '')
            bounds.extend(point);
        
        var marker = new google.maps.Marker({
            position: point,
            map: map,
            title: title,
            bounds: true,
            icon: icona,
        });

        markers.push(marker);

        contenuto = cardTemplate.replace(/#img/g,img);       
        contenuto = contenuto.replace(/#title/g,title);
        contenuto = contenuto.replace(/#content/g,content);
        contenuto = contenuto.replace(/#link/g,link ? link : '#');
        contenuto = contenuto.replace(/#label/g,link ? label : '');
        
        var html = '<div class="marker-card">' + contenuto + '</div>';

        google.maps.event.addListener(marker, 'click', function () {
            infowindow.setContent(html);
            infowindow.open(map, marker);
            map.panTo(point);
        });
        google.maps.event.addListener(map, 'click', function () {
            infowindow.close();
        });
        
    });

    if(centerView == '' && ArrMarkers.length != 0){
        map.fitBounds(bounds);       // auto-zoom
        map.panToBounds(bounds);     // auto-center
    }

    var markerCluster = new MarkerClusterer(map, markers);
    minClusterZoom = 11;
    markerCluster.setMaxZoom(minClusterZoom);


};