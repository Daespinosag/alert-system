<style lang="scss">
    /*div#station-map{
        width: 100%;
        height: 400px;
    }*/
    div#station-map-container{
        position: absolute;
        top: 100px;
        left: 0px;
        right: 0px;
        bottom: 0px;

        div#station-map{
            position: absolute;
            top: 0px;
            left: 0px;
            right: 0px;
            bottom: 0px;
        }

        div.cafe-info-window{
            div.cafe-name{
                display: block;
                text-align: center;
                color: #2b542c;
                font-family: 'Josefin Sans', sans-serif;
            }
            div.cafe-address{
                display: block;
                text-align: center;
                margin-top: 5px;
                color: #1f648b;
                font-family: 'Lato', sans-serif;
                span.street{
                    font-size: 14px;
                    display: block;
                }
                span.city{
                    font-size: 12px;
                }
                span.state{
                    font-size: 12px;
                }
                span.zip{
                    font-size: 12px;
                    display: block;
                }
                a{
                    color: #FFFFFF;
                    font-weight: bold;
                }
            }
        }
    }
</style>

<template>
    <div id="station-map-container">
        <div id="station-map">

        </div>
        <station-map-filter></station-map-filter>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';
    import { StationTextFilter } from '../../mixins/filters/StationTextFilter.js';

    import StationMapFilter from './StationMapFilter.vue';

    export default {
        components: { StationMapFilter },
        mixins: [ StationTextFilter ],
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
                for( let i = 0; i < this.stations.length; i++ ){

                    let marker = new google.maps.Marker({
                        position: { lat: parseFloat( this.stations[i].latitude ), lng: parseFloat( this.stations[i].longitude ) },
                        map: this.map,
                        icon: this.getIconAlert(this.stations[i].alertA25.alert),
                        station: this.stations[i]
                    });

                    this.markers.push( marker );

                    let overWindow = new google.maps.InfoWindow({
                        content: this.stations[i].name
                    });

                    this.infoWindows.push( overWindow );

                    marker.addListener('mouseover', function() {
                        overWindow.open(this.map, this);
                    });

                    marker.addListener('mouseout', function() {
                        overWindow.close(this.map, this);
                    });

                    let contentString = '<div class="station-info-window">' +
                                        '<div class="station-name">'+this.stations[i].name + '</div>' +
                                        '<div class="station-address">' +
                                        '<span class="street">'+this.stations[i].netName + '</span>' +
                                        '<span class="city">'+this.stations[i].city+'</span> <span class="state">'+this.stations[i].localization + '</span>' +
                                        '<span class="zip">Alerta inundacion : '+this.stations[i].alertInundation + '</span>' +
                                        '<span class="zip">Alerta deslizamientos: '+this.stations[i].alertLandslide + '</span>' +
                                        '</div>'+
                                        '</div>';

                    let infoWindow = new google.maps.InfoWindow({
                        content: contentString
                    });

                    this.infoWindows.push( infoWindow );

                    marker.addListener('click', function() {
                        infoWindow.open(this.map, this);
                    });
                }
            },

            clearMarkers(){
                for(let i = 0; i < this.markers.length; i++ ){
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
            },
            updateFilterDisplay(){
                EventBus.$emit('filters-updated', {
                    text: this.textSearch
                });
            },
            processFilters( filters ){
                for( let i = 0; i < this.markers.length; i++ ){
                    if( filters.text === null ){
                        this.markers[i].setMap( this.map );
                    }else{
                        var textPassed = false;

                        if( filters.text !== null && this.processStationTextFilter( this.markers[i].station, filters.text ) ){
                            textPassed = true;
                        }else if( filters.text === null ){
                            textPassed = true;
                        }
                    }
                    if( textPassed){
                        this.markers[i].setMap( this.map );
                    }else{
                        this.markers[i].setMap( null );
                    }
                }
            },
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

            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));
        }
    }
</script>