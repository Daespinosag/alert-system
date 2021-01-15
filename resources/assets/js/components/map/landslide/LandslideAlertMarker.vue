<template>
    <div id="landslide-alert-marker" class="landslide-alert-marker">
        <l-marker
                :lat-lng.sync="stationAlert.completeCoordinates"
                @mouseover="openPopup"
                @mouseleave="closePopup"
        >
            <l-tooltip> {{ stationAlert.name }} </l-tooltip>
            <!--<l-icon :icon-url="require(`@alert-system-vue/assets/alerts/landslide_alert_${this.iconColor}.png`)"> </l-icon>-->
            <l-icon :icon-url="`images/assets/alerts/landslide_alert_${this.iconColor}.png`"> </l-icon>
        </l-marker>
    </div>
</template>

<script>
    import LandslideAlert from "../../../store/models/alerts/landslide/landslideAlert";
    import LandslideStation from "../../../store/models/alerts/landslide/landslideStation";
    import { LMarker, LIcon , LPopup, LTooltip } from 'vue2-leaflet'

    export default {
        name: "landslide-alert-marker",
        props: { specificAlert : LandslideAlert, stationAlert : LandslideStation },
        components:{ LMarker, LIcon, LPopup, LTooltip },
        data(){
            return {
                iconColor: 'black',
                iconsOptions: ['black','yellow','orange','red'],
            }
        },
        methods: {
            openPopup: function (event) { this.$nextTick(() => event.target.openPopup())},
            closePopup: function (event) { this.$nextTick(()=> event.target.closePopup())},
        }
    }
</script>

<style scoped>

</style>