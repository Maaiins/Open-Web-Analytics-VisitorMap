<h2><?php echo $headline; ?></h2>

<div id="map-wrapper" style="position: relative">
    <div id="tooltip" style="position: absolute; z-index: 99999; background-color: #000000; color: #ffffff; padding: 5px"></div>
    <div id="map-canvas" style="width: 100%; height: 425px"></div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ==" crossorigin=""/>
<script type="text/javascript" src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew==" crossorigin=""></script>
<script type="text/javascript" src="https://unpkg.com/heatmap.js@2.0.5/build/heatmap.js" crossorigin=""></script>
<script type="text/javascript" src="https://unpkg.com/leaflet-heatmap@1.0.0/leaflet-heatmap.js" crossorigin=""></script>
<script type="text/javascript">
    var data = {
        data: <?php echo $this->get('locations'); ?>
    };


    var baseLayer = L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>',
        }
    );

    var cfg = {
        // radius should be small ONLY if scaleRadius is true (or small radius is intended)
        'radius': 2,
        'maxOpacity': .8,
        // scales the radius based on map zoom
        'scaleRadius': true,
        // if set to false the heatmap uses the global maximum for colorization
        // if activated: uses the data maximum within the current map boundaries
        //   (there will always be a red spot with useLocalExtremas true)
        'useLocalExtrema': true,
        // which field name in your data represents the latitude - default "lat"
        latField: 'latitude',
        // which field name in your data represents the longitude - default "lng"
        lngField: 'longitude',
        // which field name in your data represents the data value - default "value"
        valueField: 'total_revenue'
    };

    var heatmapLayer = new HeatmapOverlay(cfg);

    var map = new L.Map('map-canvas', {
        center: new L.LatLng(0, 0),
        zoom: 1,
        layers: [baseLayer, heatmapLayer]
    });

    var wrapper = document.querySelector('#map-wrapper');
    var tooltip = document.querySelector('#tooltip');

    var formatter = new Intl.NumberFormat('<?php echo str_replace('_', '-', owa_coreAPI::getSetting( 'base', 'currencyLocal' )); ?>', {
        style: 'currency',
        currency: '<?php echo owa_coreAPI::getSetting( 'base', 'currencyISO3' ); ?>',
        minimumFractionDigits: 2
    });

    function updateTooltip(x, y, value) {
        var transl = 'translate(' + x + 'px, ' + (y + 15) + 'px)';
        tooltip.style.webkitTransform = transl;
        tooltip.innerHTML = formatter.format(value / 100);
    }

    wrapper.onmousemove = function(ev) {
        var x = ev.layerX;
        var y = ev.layerY;
        var value = heatmapLayer._heatmap.getValueAt({
            x: x,
            y: y
        });

        if (value <= 0) {
            tooltip.style.display = 'none';
            return;
        }

        tooltip.style.display = 'block';
        updateTooltip(x, y, value);
    };

    wrapper.onmouseout = function() {
        tooltip.style.display = 'none';
    };

    heatmapLayer.setData(data);
</script>