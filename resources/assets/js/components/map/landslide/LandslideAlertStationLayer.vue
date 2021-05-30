<template>
    <div id="landslide-alert-station-layer" class="landslide-alert-station-layer">
        <l-layer-group :layer-type="stationAlertType" :name="stationAlertName" :visible="this.landslideIconsVisible">
            <landslide-alert-marker
                    v-for="landslideStation in landslideStations"
                    :key="landslideStation.id"
                    :stationAlert="landslideStation"
                    :specificAlert="specificAlert"
            >
            </landslide-alert-marker>
        </l-layer-group>

        <l-layer-group :layer-type="polygonAlertType" :name="polygonAlertName" :visible="this.landslidePolygonsVisible">
<!--            //<div v-if="zone.kml !== null">-->
                <l-geo-json :geojson="require(`@alert-system-vue/assets/geojson/zone/${ this.zone.code }-2vs.json`)" :options="{ color : polygonColor}"> </l-geo-json>
<!--            </div>-->
        </l-layer-group>
    </div>
</template>

<script>
    import LandslideStation from "../../../store/models/alerts/landslide/landslideStation";
    import { LLayerGroup, LGeoJson } from "vue2-leaflet"
    import LandslideAlert from "../../../store/models/alerts/landslide/landslideAlert";
    import LandslideAlertMarker from "./LandslideAlertMarker";
    import Zone from "../../../store/models/alerts/landslide/zone";

    export default {
        name: "landslide-alert-station-layer",
        props: { specificAlert : LandslideAlert },
        components: {LandslideAlertMarker,LLayerGroup, LGeoJson},
        data(){
            return {
                stationAlertName: 'marker_', //+ this.stationAlertName.name,
                stationAlertType: 'overlay',

                polygonAlertName : 'kml_', // + this.specificAlert.name,
                polygonAlertType: 'overlay',

                polygonExist: false,
                polygonColor: '#000',
                polygonOptionsColor: [], // TODO
            }
        },
        computed: {
            landslideStations(){
                return LandslideStation.query().where('primary',true).where('alert_id', this.specificAlert.id).get()
            },
            zone(){
                return Zone.query().where('id',this.specificAlert.zone_id).first()
            },
            landslideIconsVisible(){
                return this.$store.getters.getLandslideIconsVisible;
            },
            landslidePolygonsVisible(){
                return this.$store.getters.getLandslidePolygonsVisible;
            },
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>