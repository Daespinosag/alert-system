<template>
    <div  id="flood-alert-marker" class="flood-alert-marker">
            <l-marker :lat-lng.sync="stationAlert.completeCoordinates" @mouseover="openPopup" @mouseleave="closePopup" :visible="this.visible" @click="changeRoute">
                <l-tooltip>
                    {{ stationAlert.name }}
                </l-tooltip>
                <!--<l-icon :icon-url="require(`@alert-system-vue/assets/alerts/flood_alert_${this.iconColor}.png`)"> </l-icon>-->
                <l-icon :icon-url="`images/assets/alerts/flood_alert_${this.iconColor}.png`" :icon-size="this.iconSize"> </l-icon>
            </l-marker>

    </div>
</template>

<script>
    import FloodAlert from "../../../store/models/alerts/flood/floodAlert";
    import FloodStation from "../../../store/models/alerts/flood/floodStation";
    import Net from "../../../store/models/alerts/net";
    import { EventBus } from '../../../event-bus.js';
    import { LMarker, LIcon , LPopup, LTooltip } from 'vue2-leaflet';
    import { StationTextFilter } from "../../../mixins/filters/StationTextFilter";

    export default {
        name: "flood-alert-marker",
        props: { specificAlert : FloodAlert, stationAlert : FloodStation},
        mixins: [StationTextFilter],
        components: {  LMarker, LIcon, LPopup, LTooltip },
        data(){
            return {
                iconSize: [25, 41],
                iconsOptions: {
                    grey: 'error_red',
                    green: 'black',
                    red: 'red'
                },
                visible: true,
                searchArray: [],
            }
        },
        computed:{
            net(){
                return Net.query().find(this.stationAlert.net_id);
            },
            iconColor(){
                return this.iconsOptions[this.stationAlert.alert_tag];
            }
        },
        mounted(){
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
            changeRoute: function () {
                this.$router.push({ name: 'Alert', params: { id: this.stationAlert.id, alert_id: this.stationAlert.alert_id, stationType: `flood` } })
            },
        },
        created(){

        }
    }
</script>

<style scoped>

</style>