<style lang="scss">
    div#individual-station-map{
        width: 100%;
        height: 400px;
    }
</style>

<template>
    <div id="individual-station-map"></div>
</template>

<script>
    export default {
        computed: {
            stationLoadStatus(){
                return this.$store.getters.getStationLoadStatus;
            },
            station(){
                return this.$store.getters.getStation;
            }
        },
        watch: {
            stationLoadStatus(){
                if( this.stationLoadStatus === 2 ){
                    this.displayIndividualStationMap();
                }
            }
        },
        methods: {
            displayIndividualStationMap(){
                this.map = new google.maps.Map(document.getElementById('individual-station-map'), {
                    center: {lat: this.station.latitude, lng: this.station.longitude},
                    zoom: 13
                });

                var image = 'images/alert-icons/station-marker-default.svg';

                var marker = new google.maps.Marker({
                    position: { lat: parseFloat( this.station.latitude ), lng: parseFloat( this.station.longitude )},
                    map: this.map,
                    icon: image
                });
            }
        }
    }
</script>