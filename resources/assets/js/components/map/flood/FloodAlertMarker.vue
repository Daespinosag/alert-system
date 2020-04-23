<template>
    <div id="flood-alert-marker" class="flood-alert-marker">
        <l-marker :lat-lng.sync="stationAlert.completeCoordinates" @mouseover="openPopup" @mouseleave="closePopup">
            <l-tooltip> {{ stationAlert.name }} </l-tooltip>
            <!--<l-icon :icon-url="require(`@alert-system-vue/assets/alerts/flood_alert_${this.iconColor}.png`)"> </l-icon>-->
            <l-icon :icon-url="`images/assets/alerts/flood_alert_${this.iconColor}.png`"> </l-icon>
        </l-marker>
    </div>
</template>

<script>
    import FloodAlert from "../../../store/models/alerts/flood/floodAlert";
    import FloodStation from "../../../store/models/alerts/flood/floodStation";

    import { LMarker, LIcon , LPopup, LTooltip } from 'vue2-leaflet'

    export default {
        name: "flood-alert-marker",
        props: { specificAlert : FloodAlert, stationAlert : FloodStation},
        components: {  LMarker, LIcon, LPopup, LTooltip },
        data(){
            return {
                iconColor: 'black',
                iconsOptions: ['black','red'],
            }
        },
        methods: {
            openPopup: function (event) { this.$nextTick(() => event.target.openPopup())},
            closePopup: function (event) { this.$nextTick(()=> event.target.closePopup())},
        },
        created(){

        }
    }
</script>

<style scoped>

</style>