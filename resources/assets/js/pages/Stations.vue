<style>

</style>

<template>
    <div id="stations" class="page">
        <div class="grid-x">
            <div class="large-9 medium-9 small-12 cell">

                <station-map v-show="stationsView === 'map'"></station-map>
                <station-list v-show="stationsView === 'list' "></station-list>

                <toggle-stations-view></toggle-stations-view>
            </div>
            <div class="large-3 medium-3 small-12 cell">
                <router-view></router-view>
            </div>
        </div>
    </div>
</template>

<script>
    import StationMap from '../components/stations/StationMap.vue';
    import StationList from '../components/stations/StationList.vue';
    import ToggleStationsView from '../components/stations/ToggleStationsView';

    export default {
        components: {StationMap,StationList,ToggleStationsView},

        computed: {
            stationsView(){
                return this.$store.getters.getStationsView;
            }
        }
    }
</script>