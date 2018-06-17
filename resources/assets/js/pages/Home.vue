<style>

</style>

<template>
    <div id="home">
        <div class="card">
            <div class="card-body">

                <div class="col-md-4">
                    <station-filter></station-filter>
                </div>

                <div class="col-md-8">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-success">Lista de Estaciones</li>
                        <loader v-show="stationsLoadStatus == 1" :width="100" :height="100"></loader>
                        <station-card v-for="station in stations" :key="station.id" :station="station"></station-card>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import StationFilter from '../components/stations/StationFilter.vue';
    import StationCard from '../components/stations/StationCard.vue';
    import Loader from '../components/global/Loader.vue';

    export default {
        components: {StationFilter,StationCard,Loader},
        computed: {
            stationsLoadStatus() {
                return this.$store.getters.getStationsLoadStatus;
            },
            stations() {
                return this.$store.getters.getStations;
            }
        }
    }
</script>