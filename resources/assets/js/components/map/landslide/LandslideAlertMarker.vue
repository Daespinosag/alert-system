<template>
    <div id="landslide-alert-marker" class="landslide-alert-marker">
        <l-marker
                :lat-lng.sync="stationAlert.completeCoordinates"
                @mouseover="openPopup"
                @mouseleave="closePopup"
                :visible="this.visible"
        >
            <l-tooltip> {{ stationAlert.name }} </l-tooltip>
            <!--<l-icon :icon-url="require(`@alert-system-vue/assets/alerts/landslide_alert_${this.iconColor}.png`)"> </l-icon>-->
            <l-icon :icon-url="`images/assets/alerts/landslide_alert_${this.iconColor}.png`" :icon-size="this.iconSize"> </l-icon>
        </l-marker>
    </div>
</template>

<script>
    import LandslideAlert from "../../../store/models/alerts/landslide/landslideAlert";
    import LandslideStation from "../../../store/models/alerts/landslide/landslideStation";
    import Net from "../../../store/models/alerts/net";
    import { EventBus } from '../../../event-bus.js';
    import { StationTextFilter } from "../../../mixins/filters/StationTextFilter";
    import { LMarker, LIcon , LPopup, LTooltip } from 'vue2-leaflet'

    export default {
        name: "landslide-alert-marker",
        props: { specificAlert : LandslideAlert, stationAlert : LandslideStation },
        mixins: [StationTextFilter],
        components:{ LMarker, LIcon, LPopup, LTooltip },
        data(){
            return {
                iconsOptions:{
                    grey:'error_red',
                    green: 'black',
                    yellow: 'yellow',
                    orange: 'orange',
                    red: 'red'
                },
                iconSize: [25, 41],
                visible: true,
                searchArray: [],
            }
        },
        computed: {
            net(){
                return Net.query().find(this.stationAlert.net_id);
            },

            iconColor(){
                return this.iconsOptions[this.stationAlert.alert_tag];
            }
        },
        mounted() {
            this.searchArray = [this.stationAlert.name, this.net.name, this.stationAlert.city];
            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));
        },
        methods: {
            openPopup: function (event) { this.$nextTick(() => event.target.openPopup())},
            closePopup: function (event) { this.$nextTick(()=> event.target.closePopup())},
            processFilters: function (filters) {
                this.visible = this.processStationTextFilter(this.searchArray, filters.text);
            },
        }
    }
</script>

<style scoped>

</style>