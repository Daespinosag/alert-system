<style lang="scss">
    div#station-map{
        width: 100%;
        height: 400px;
    }
</style>

<template>
    <div id="station-map">

    </div>
</template>

<script>
    export default {
        props: {
            'latitude': {
                type: Number,
                default: function(){
                    return 5.0670
                }
            },
            'longitude': {
                type: Number,
                default: function(){
                    return -75.5046
                }
            },
            'zoom': {
                type: Number,
                default: function(){
                    return 12
                }
            }
        },

        data(){
            return {
                markers: [],
                infoWindows: [],
                markerImg: 'images/alert-icons/station-marker'
            }
        },
        computed: {
            stations(){
                return this.$store.getters.getStations;
            }
        },
        methods: {
            buildMarkers(){
                this.markers = [];
                for( var i = 0; i < this.stations.length; i++ ){

                    var marker = new google.maps.Marker({
                        position: { lat: parseFloat( this.stations[i].latitude ), lng: parseFloat( this.stations[i].longitude ) },
                        map: this.map,
                        icon: this.getIconAlert(this.stations[i].alertA25.alert)
                    });
                    this.markers.push( marker );

                    let infoWindow = new google.maps.InfoWindow({
                        content: this.stations[i].name
                    });
                    this.infoWindows.push( infoWindow );

                    marker.addListener('mouseover', function() {
                        infoWindow.open(this.map, this);
                    });

                    marker.addListener('mouseout', function() {
                        infoWindow.close(this.map, this);
                    });
                }
            },

            clearMarkers(){
                for( var i = 0; i < this.markers.length; i++ ){
                    this.markers[i].setMap( null );
                }
            },

            getIconAlert(alert){
                let icon = this.markerImg;
                switch(alert) {
                    case 0:
                        icon += '-green.svg';
                        break;
                    case 1:
                        icon += '-yellow.svg';
                        break;
                    case 2:
                        icon += '-orange.svg';
                        break;
                    case 3:
                        icon += '-red.svg';
                        break;
                    default:
                        icon += '-default.svg';
                }
                return icon;
            }
        },
        watch: {
            stations(){
                this.clearMarkers();
                this.buildMarkers();
            }
        },

        mounted(){

            this.map = new google.maps.Map(document.getElementById('station-map'), {
                center: {lat: this.latitude, lng: this.longitude},
                zoom: this.zoom
            });

            this.clearMarkers();
            this.buildMarkers();
        }
    }
</script>