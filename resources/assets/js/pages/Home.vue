<style>

</style>

<template>
    <div id="home">
        <div class="card">
            <div class="card-body">
                <loader v-show="stationsLoadStatus == 1" :width="100" :height="100"></loader>

                <ul class="list-group col-md-6 col-md-offset-3">
                    <li class="list-group-item list-group-item-success">Lista de Estaciones</li>
                    <station-card v-for="station in stations" :key="station.id" :station="station"></station-card>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import StationCard from '../components/stations/StationCard.vue';
    import Loader from '../components/global/Loader.vue';

    export default {
        components: {StationCard,Loader},
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