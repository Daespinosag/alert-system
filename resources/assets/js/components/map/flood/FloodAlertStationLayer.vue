<template>
    <div id="flood-alert-station-layer" class="flood-alert-station-layer">
        <l-layer-group :layer-type="stationAlertType" :name="stationAlertName" :visible="stationAlertVisible">
            <flood-alert-marker
                    v-for="floodStation in FloodStations"
                    :key="floodStation.id"
                    :stationAlert="floodStation"
                    :specificAlert="specificAlert"
            >
            </flood-alert-marker>
        </l-layer-group>

        <l-layer-group  :layer-type="polygonAlertType" :name="polygonAlertName" :visible="polygonAlertVisible">
            <l-geo-json :geojson="require(`@alert-system-vue/assets/geojson/basin/${ basin.code }`)" :options="{ color : polygonColor}"> </l-geo-json>
        </l-layer-group>
    </div>
</template>

<script>
    import FloodAlert from "../../../store/models/alerts/flood/floodAlert";
    import FloodStation from "../../../store/models/alerts/flood/floodStation";
    import FloodAlertMarker from "./FloodAlertMarker";
    import { LLayerGroup, LGeoJson } from "vue2-leaflet"
    import Basin from "../../../store/models/alerts/flood/Basin";

    export default {
        name: "flood-alert-stations-layer",
        props: { specificAlert : FloodAlert },
        components: {FloodAlertMarker,LLayerGroup,LGeoJson},
        data(){
            return {
                stationAlertName: 'marker_', //+ this.stationAlertName.name,
                stationAlertVisible: true,
                stationAlertType: 'overlay',

                polygonAlertName : 'kml_', // + this.specificAlert.name,
                polygonAlertVisible: true,
                polygonAlertType: 'overlay',

                polygonColor: '#000',
                polygonOptionsColor: {grey: '#000', 'green': '#339900','yellow': '#ffcc00','orange': '#e2580b','red': '#cc3300'}, // TODO
            }
        },
        created() {

        },
        mounted() {
            this.polygonColor = this.polygonOptionsColor[this.FloodStations[0].alert_tag];
        },
        computed: {
            FloodStations(){
                return FloodStation.query().where('primary',true).where('alert_id', this.specificAlert.id).get()
            },
            basin(){
                return Basin.query().where('id',this.specificAlert.basin_id).first()
            },
        }
    }
</script>

<style scoped>

</style>