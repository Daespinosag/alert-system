<template>
    <div id="print-alerts-component" class="print-alerts-component">
        <alert-map id="alert-map-container" class="alert-map-container" v-show="alertsView === 'map'"> </alert-map>
        <alert-list v-show="alertsView === 'list'"> </alert-list>

        <toggle-alerts-view> </toggle-alerts-view>
        <check-internet-connection></check-internet-connection>
        <status-bar></status-bar>
        <sound-alert></sound-alert>
        <div class="large-3 medium-3 small-12 cell">
            <router-view :key="$route.fullPath"></router-view>
        </div>
    </div>
</template>

<script>
    import ToggleAlertsView from "./alerts/ToggleAlertsView";
    import CheckInternetConnection from "./alerts/CheckInternetConnection";
    import AlertMap from "@alert-system-vue/components/map/AlertMap";
    import AlertList from './list/AlertList';
    import StatusBar from "./alerts/StatusBar";
    import SoundAlert from "./alerts/SoundAlert";


    export default {
        name: "print-alerts-component",
        components: {ToggleAlertsView, AlertMap, AlertList, CheckInternetConnection, StatusBar, SoundAlert},
        computed: {
            alertsView(){
                return this.$store.getters.getAlertsView;
            }
        }
    }
</script>

<style lang="scss">
    div#print-alerts-component{
        position: absolute;
        top: 5px;
        left: 0px;
        right: 0px;
        bottom: 0px;
        z-index:0;

        div#alert-map-container{
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
        }
    }
</style>