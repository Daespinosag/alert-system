<template>
    <div id="alert-map" class="alert-map">
        <l-map v-if="showMap" :zoom="zoom" :center="center" :options="mapOptions">
            <!--<l-control-layers :collapsed="false" :sort-layers="true"/>-->
            <l-tile-layer :url="url" :attribution="attribution"/>
            <l-control-zoom position="bottomleft"  ></l-control-zoom>
            <flood-alert-layer> </flood-alert-layer>
            <landslide-alert-layer> </landslide-alert-layer>
        </l-map>
    </div>
</template>

<script>
    import { latLng } from "leaflet"
    import { LMap, LTileLayer, LMarker, LPopup, LTooltip, LGeoJson , LControlZoom, LControlLayers } from "vue2-leaflet"
    import LandslideAlertLayer from "./landslide/LandslideAlertLayer"
    import FloodAlertLayer from "./flood/FloodAlertLayer";

    export default {
        name: "alert-map",
        components: {
            LMap, LTileLayer, LMarker, LPopup, LTooltip, LandslideAlertLayer, FloodAlertLayer, LGeoJson , LControlZoom/** LControlLayers **/
        },
        data() {
            return {
                zoom: 13,
                center: latLng(5.0670, -75.5046),
                //url: "http://{s}.tile.osm.org/{z}/{x}/{y}.png",
                //url: "https://cartodb-basemaps-{s}.global.ssl.fastly.net/rastertiles/voyager/{z}/{x}/{y}.png",
                url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
                attribution: '&copy; Sistema De Alerta Temprana - Manizales',
                mapOptions: {
                    zoomSnap: 0.5,
                    zoomControl: false,
                },
                showMap: true,
            };
        },
        computed: {
        },
        created() {

        },
        methods: {

        }
    };
</script>