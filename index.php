<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet Geocoder (SEARCH) CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <title>WebGIS Sleman</title>

    <style>
        body {
            margin: 0;
        }

        #map {
            height: 100vh;
            width: 100%;
        }

        /* LEGEND MINI + SCROLL */
        #legend {
            position: absolute;
            bottom: 15px;
            left: 15px;
            z-index: 9999;
            background: rgba(255, 255, 255, 0.92);
            padding: 8px;
            border-radius: 8px;
            box-shadow: 0 0 6px rgba(0, 0, 0, 0.30);
            font-family: Arial, sans-serif;
            font-size: 11px;
            width: 120px;
            max-height: 120px;
            overflow-y: auto;
        }

        #legend img {
            width: 90px;
            margin-top: 4px;
            display: block;
        }

        /* COORDS */
        #coords {
            position: absolute;
            bottom: 5px;
            right: 10px;
            background: rgba(255, 255, 255, 0.85);
            padding: 3px 6px;
            border-radius: 5px;
            font-size: 11px;
            font-family: Arial, sans-serif;
            z-index: 9999;
        }
    </style>
</head>

<body>

    <div id="map"></div>

    <!-- LEGEND -->
    <div id="legend">
        <b>Legenda</b>
        <img
            src="http://localhost:8080/geoserver/ne/wms?service=WMS&request=GetLegendGraphic&format=image/png&layer=ne:ADMINISTRASIDESA_AR_25K">
        <img
            src="http://localhost:8080/geoserver/polyline_pgweb10/wms?service=WMS&request=GetLegendGraphic&format=image/png&layer=polyline_pgweb10:JALAN_LN_25K">
        <img
            src="http://localhost:8080/geoserver/pgwebsleman/wms?service=WMS&request=GetLegendGraphic&format=image/png&layer=pgwebsleman:data_sleman">
    </div>

    <!-- COORDS -->
    <div id="coords">Lat: -, Lng: -</div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Geocoder (SEARCH) JS -->
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script>
        // MAP
        var map = L.map("map").setView([-7.732521, 110.402376], 11);

        // BASEMAP
        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            maxZoom: 19
        }).addTo(map);

        // ================
        // SEARCH BOX 🔍
        // ================
        L.Control.geocoder({
            defaultMarkGeocode: true,
            placeholder: "Cari lokasi...",
            errorMessage: "Tidak ditemukan!",
            showResultIcons: true
        }).addTo(map);

        // ================
        // WMS LAYERS
        // ================
        var desa = L.tileLayer.wms("http://localhost:8080/geoserver/ne/wms", {
            layers: "ne:ADMINISTRASIDESA_AR_25K",
            format: "image/png",
            transparent: true
        }).addTo(map);

        var jalan = L.tileLayer.wms("http://localhost:8080/geoserver/polyline_pgweb10/wms", {
            layers: "polyline_pgweb10:JALAN_LN_25K",
            format: "image/png",
            transparent: true
        }).addTo(map);

        var kecamatan = L.tileLayer.wms("http://localhost:8080/geoserver/pgwebsleman/wms", {
            layers: "pgwebsleman:data_sleman",
            format: "image/png",
            transparent: true
        }).addTo(map);

        // LAYER CONTROL
        var overlays = {
            "Administrasi Desa": desa,
            "Jalan 25K": jalan,
            "Data Kecamatan": kecamatan
        };

        L.control.layers(null, overlays).addTo(map);

        // KOORDINAT
        map.on("mousemove", function (e) {
            document.getElementById("coords").innerHTML =
                `Lat: ${e.latlng.lat.toFixed(5)}, Lng: ${e.latlng.lng.toFixed(5)}`;
        });

        //https://geoportal.slemankab.go.id/geoserver/geonode/jalan_kabupaten_sleman_2023/ows
        var wmsLayer2 = L.Geoserver.wms("https://geoportal.slemankab.go.id/geoserver/wms", {
            layers: "geonode:jalan_kabupaten_sleman_2023",
            transparent: true,
        });
        wmsLayer2.addTo(map);
    </script>

</body>

</html>