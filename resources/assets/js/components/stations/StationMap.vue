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

        div.station-info-window{
            div.station-name{
                display: block;
                text-align: center;
                color: #2b542c;
                font-family: 'Josefin Sans', sans-serif;
            }
            div.station-address{
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
        <div id="station-map"> </div>
    </div>
</template>

<script>
    import { ALERT_SYSTEM_CONFIG } from "../../config";
    import { EventBus } from '../../event-bus.js';
    import { StationTextFilter } from '../../mixins/filters/StationTextFilter.js';
    import { StationAlertFilter} from "../../mixins/filters/StationAlertFilter";
    import { StationTypeFilter} from "../../mixins/filters/StationTypeFilter";

    export default {
        mixins: [ StationTextFilter, StationAlertFilter, StationTypeFilter ],
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
                infoWindows: [],
                markerImg: 'images/alert-icons/'
            }
        },
        computed: {
            stations(){
                return this.$store.getters.getStations;
            }
        },
        watch: {
            stations(){
                this.clearMarkers();
                this.buildMarkers();
            }
        },

        methods: {
            buildMarkers(){

                this.$markers = [];

                for( let i = 0; i < this.stations.length; i++ ){

                    let marker = new google.maps.Marker({
                        position: { lat: parseFloat( this.stations[i].latitude ), lng: parseFloat( this.stations[i].longitude ) },
                        map: this.$map,
                        icon: this.getIconAlert(this.stations[i]),
                        station: this.stations[i]
                    });

                    this.$markers.push( marker );

                    let overWindow = new google.maps.InfoWindow({
                        content: this.stations[i].name
                    });

                    this.infoWindows.push( overWindow );

                    marker.addListener('mouseover', function() {
                        overWindow.open(this.$map, this);
                    });

                    marker.addListener('mouseout', function() {
                        overWindow.close(this.$map, this);
                    });

                    let router = this.$router;

                    marker.addListener('click', function() {
                        router.push( { name: 'station', params: { id: this.station.id } } );
                    });
                }
            },

            clearMarkers(){
                for(let i = 0; i < this.$markers.length; i++ ){
                    this.$markers[i].setMap( null );
                }
            },

            getIconAlert(station){
                let icon = this.markerImg;
                
                if (station.dataMaximumAlert !== null){
                    switch(station.maximumAlert) {
                        case 0:
                            icon += 'green-' + station.dataMaximumAlert.icon;
                            break;
                        case 1:
                            icon += 'yellow-'+ station.dataMaximumAlert.icon;
                            break;
                        case 2:
                            icon += 'orange-'+ station.dataMaximumAlert.icon;
                            break;
                        case 3:
                            icon += 'red-'+ station.dataMaximumAlert.icon;
                            break;
                        default:
                            icon += 'default-' + station.dataMaximumAlert.icon;
                    }
                }else{
                    icon += 'default-station-marker.svg'
                }

                return icon;
            },
            updateFilterDisplay(){
                EventBus.$emit('filters-updated', {
                    text: this.textSearch,
                    alert: this.activeAlertFilter,
                    typeStation : this.activeTypeStationFilter
                });
            },
            processFilters: function (filters) {
                for (let i = 0; i < this.$markers.length; i++) {
                    if (filters.text === null && filters.alert === 'all' && filters.typeStation.length === 0) {
                        this.$markers[i].setMap(this.$map);
                    } else {
                        var textPassed = false;
                        var alertPassed = false;
                        var typeStationPassed = false;

                        if (filters.text !== null && this.processStationTextFilter(this.$markers[i].station, filters.text)) {
                            textPassed = true;
                        } else if (filters.text === null) {
                            textPassed = true;
                        }

                        if (filters.alert.length !== 0 && this.processStationAlertFilter(this.$markers[i].station, filters.alert)) {
                            alertPassed = true;
                        } else if (filters.alert.length === 0) {
                            alertPassed = true;
                        }

                        if (filters.typeStation.length !== 0 && this.processStationTypeFilter(this.$markers[i].station, filters.typeStation)) {
                            typeStationPassed = true;
                        } else if (filters.typeStation.length === 0) {
                            typeStationPassed = true;
                        }
                    }

                    if (textPassed && alertPassed && typeStationPassed) {
                        this.$markers[i].setMap(this.$map);
                    } else {
                        this.$markers[i].setMap(null);
                    }
                }
            },
        },

        mounted(){

            this.$markers = [];

            this.$map = new google.maps.Map(document.getElementById('station-map'), {
                center: {lat: this.latitude, lng: this.longitude},
                zoom: this.zoom,
                streetViewControl: false,
                mapTypeControl: false,
                disableDefaultUI: true
            });

            var CaldasDeptoKMZ = new google.maps.KmlLayer({
                url: ALERT_SYSTEM_CONFIG.URL_IMAGES + 'Caldas_Departamento.kmz',
                map: this.$map,
                zoom: this.zoom,
                suppressInfoWindows: true,
            });

            var CaldasMunicipiosKMZ = new google.maps.KmlLayer({
                url: ALERT_SYSTEM_CONFIG.URL_IMAGES + 'Caldas_Municipios_v2.kmz',
                map: this.$map,
                zoom: this.zoom,
                suppressInfoWindows: true,
            });

            var PerimetroUrbanoManizalesKMZ = new google.maps.KmlLayer({
                url: ALERT_SYSTEM_CONFIG.URL_IMAGES + 'Manizales_PUrbanoAzul.kmz',
                map: this.$map,
                zoom: this.zoom,
                suppressInfoWindows: true,
            });

            this.clearMarkers();
            this.buildMarkers();

            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));
        }
    }
</script>