<h2><?php echo $headline; ?></h2>

<div id="map-wrapper" style="position: relative">
    <div id="tooltip" style="position: absolute; z-index: 99999"></div>
    <div id="map-canvas" style="width: 100%; height: 425px"></div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" crossorigin=""/>
<script type="text/javascript" src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" crossorigin=""></script>
<script type="text/javascript" src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js" crossorigin=""></script>

<script type="text/javascript">
    var data = <?php echo $this->get('locations'); ?>;


    var baseLayer = L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
        }
    );

    var markers = L.markerClusterGroup({ chunkedLoading: true });

    for (var i = 0; i < data.length; i++) {
        var marker = L.marker(L.latLng(data[i].latitude, data[i].longitude));
        marker.bindPopup(data[i].popup_content);
        markers.addLayer(marker);
    }

    var map = new L.Map('map-canvas', {
        center: new L.LatLng(0, 0),
        zoom: 1,
        layers: [baseLayer, markers]
    });
</script>