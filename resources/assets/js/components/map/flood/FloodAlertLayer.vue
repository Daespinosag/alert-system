<template>
    <div id="flood-alert-layer" class="flood-alert-layer">
        <l-layer-group :layer-type="alertLayerType" :name="alertLayerName" :visible="alertLayerVisible">
            <div v-for="floodAlert in floodAlerts">
                <flood-alert-station-layer :specific-alert="floodAlert"> </flood-alert-station-layer>
            </div>
        </l-layer-group>
    </div>
</template>

<script>
    import floodAlert from "@alert-system-vue/store/models/alerts/flood/floodAlert";
    import floodAlertStationLayer from "@alert-system-vue/components/map/flood/FloodAlertStationLayer";
    import { LLayerGroup } from 'vue2-leaflet'

    export default {
        name: "flood-alert-layer",
        components: { LLayerGroup, floodAlertStationLayer},
        data(){
            return {
                alertLayerName: 'general-layer-flood-alert',
                alertLayerType: 'overlay',
                alertLayerVisible: true,
            }
        },
        computed: {
            floodAlerts(){
                return floodAlert.all()
            }
        }
    }
</script>

<style scoped>

</style>