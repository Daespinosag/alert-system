<style>

</style>

<template>
    <div id="home">
        <span v-show="stationsLoadStatus == 1">Loading</span>
        <span v-show="stationsLoadStatus == 2">Stations loaded successfully!</span>
        <span v-show="stationsLoadStatus == 3">Stations loaded unsuccessfully!</span>
        <ul>
            <li v-for="station in stations">{{ station.name }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        created(){
            console.log('entre');
            this.$store.dispatch( 'loadStations' );
        },
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