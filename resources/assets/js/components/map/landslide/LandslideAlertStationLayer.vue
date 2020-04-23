<template>
    <div id="landslide-alert-station-layer" class="landslide-alert-station-layer">
        <l-layer-group :layer-type="stationAlertType" :name="stationAlertName" :visible="stationAlertVisible">
            <landslide-alert-marker
                    v-for="landslideStation in landslideStations"
                    :key="landslideStation.id"
                    :stationAlert="landslideStation"
                    :specificAlert="specificAlert"
            >
            </landslide-alert-marker>
        </l-layer-group>

        <l-layer-group v-if="false" :layer-type="polygonAlertType" :name="polygonAlertName" :visible="polygonAlertVisible">
            <div v-if="zone.kml !== null">
                <l-geo-json :geojson="require(`@alert-system-vue/assets/geojson/zone/${ this.zone.code }.json`)" :options="{ color : polygonColor}"> </l-geo-json>
            </div>
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
                stationAlertVisible: true,
                stationAlertType: 'overlay',

                polygonAlertName : 'kml_', // + this.specificAlert.name,
                polygonAlertVisible: true,
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
        },
        methods: {

        }
    }
</script>

<style scoped>

</style>