<style lang="scss">
    div#station-page{
        position: absolute;
        right: 20px;
        top: 30px;
        background: rgb(255, 255, 255);
        box-shadow: 0 2px 4px 0 rgba(3,27,78,0.10);
        width: 100%;
        height: 95%;
        max-width: 880px;
        padding: 10px 20px 20px;
        display: flex;

        div.container-information{
            width: 40%;
            height: 100%;

            img.close-icon{
                float: right;
                cursor: pointer;
                margin-top: 10px;
            }
            h2.station-title{
                color: #342C0C;
                font-size: 20px;
                font-family: "Lato", sans-serif;
                font-weight: bolder;
            }
            span.location-number{
                display: inline-block;
                color: #8E8E8E;
                font-size: 18px;
                span.location-image-container{
                    width: 35px;
                    text-align: center;
                    display: inline-block;
                }
            }
            label.station-label{
                font-family: "Lato", sans-serif;
                text-transform: uppercase;
                font-weight: bold;
                color: black;
                margin-top: 20px;
                margin-bottom: 10px;
            }
            div.location-type{
                color: white;
                font-family: "Lato", sans-serif;
                font-size: 16px;
                width: 105px;
                height: 45px;
                text-align: center;
                line-height: 45px;
                border-radius: 3px;
                img{
                    margin-right: 5px;
                }
                &.roaster{
                    background-color: #a6ffa1;
                }
                &.station{
                    background-color: #3D281E;
                    img{
                        margin-top: -6px;
                    }
                }
            }
            div.brew-method{
                font-size: 16px;
                color: #666666;
                font-family: "Lato", sans-serif;
                border-radius: 4px;
                background-color: #F9F9FA;
                width: 150px;
                height: 57px;
                float: left;
                margin-right: 10px;
                margin-bottom: 10px;
                padding: 5px;
                cursor: pointer;
                position: relative;
                div.brew-method-container{
                    position: absolute;
                    top: 50%;
                    transform: translateY(-50%);
                    img.brew-method-icon{
                        display: inline-block;
                        margin-right: 10px;
                        margin-left: 5px;
                        width: 20px;
                        max-height: 30px;
                    }
                    span.brew-method-name{
                        display: inline-block;
                        width: calc( 100% - 40px);
                        vertical-align: middle;
                    }
                }
            }
            div.address-container{
                color: #666666;
                font-size: 18px;
                line-height: 23px;
                font-family: "Lato", sans-serif;
                margin-bottom: 5px;
                span.address{
                    display: block;
                }
                span.city-state{
                    display: block;
                }
                span.zip{
                    display: block;
                }
            }
            a.station-website{
                font-family: "Lato", sans-serif;
                color: #543729;
                font-size: 18px;
            }
            a.suggest-station-edit{
                font-family: "Lato", sans-serif;
                color: #054E7A;
                font-size: 14px;
                display: inline-block;
                margin-top: 30px;
                text-decoration: underline;
            }

        }

        div.container-graphics{
            width: 60%;
            height: 100%;

            .graphics{
                height: 50%;
            }

        }
    }
    /* Small only */
    @media screen and (max-width: 39.9375em) {
        div#station-page{
            position: fixed;
            right: 0px;
            left: 0px;
            top: 0px;
            bottom: 0px;
            z-index: 99999;
        }
    }
    /* Medium only */
    @media screen and (min-width: 40em) and (max-width: 63.9375em) {
    }
    /* Large only */
    @media screen and (min-width: 64em) and (max-width: 74.9375em) {
    }
</style>


<template>
    <div id="station-page" v-if="stationLoadStatus === 2">
        <div class="container-information">
            <router-link :to="{ name: 'stations' }"> <img class="close-icon" src="images/close-icon.svg" v-on:click="clearStation()"/></router-link>
            <h2 class="station-title">{{ station.name }}</h2>

            <div class="grid-x">
                <div class="large-12 medium-12 small-12 cell">
                    <label class="station-label">{{ station.netName }}</label>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-12 medium-12 small-12 cell">
                    <label class="station-label">{{ station.city }}</label>
                </div>
            </div>
            <div class="grid-x">
                <div class="large-12 medium-12 small-12 cell">
                    <label class="station-label">{{ station.localization }}</label>
                </div>
            </div>
        </div>

        <div class="container-graphics">
            <highcharts :options="chartOptionsA10" v-show="(this.alertA10StationInStations !== undefined) ? this.alertA10StationInStations.active : false" title="titulo del grafico" class="graphics"></highcharts>
            <highcharts :options="chartOptionsA25" v-show="(this.alertA25StationInStations !== undefined) ? this.alertA25StationInStations.active : false" title="titulo del grafico" class="graphics"></highcharts>
        </div>
    </div>
</template>

<script>
    import { EventBus } from "../event-bus";

    import Loader from '../components/global/Loader.vue';
    import {Chart} from 'highcharts-vue';
    import IndividualStationMap from '../components/stations/IndividualStationMap.vue';

    export default {
        components: {IndividualStationMap,Loader, highcharts: Chart },
        data() {
            return {
                chartOptionsA10: {
                    chart: {
                        zoomType: 'x',
                    },
                    title: {
                        text: 'Alerta de Inindación'
                    },
                    yAxis: {
                        title: {
                            text: 'Precipitación'
                        },
                        labels: {
                            format: '{value} mm'
                        }
                    },
                    xAxis: {
                        categories: [],
                        minTickInterval : 80

                    },
                    series: [{
                        type: 'area',
                        name: 'indicador alerta inundación',
                        data: [] // sample data
                    }]
                },
                chartOptionsA25: {
                    chart: {
                        zoomType: 'x',
                    },
                    title: {
                        text: 'Alerta de Deslizamiento'
                    },
                    yAxis: {
                        title: {
                                text: 'Precipitación'
                        },
                        labels: {
                            format: '{value} mm'
                        }
                    },
                    xAxis: {
                        categories: [],
                        minTickInterval : 80
                    },
                    series: [{
                        type: 'area',
                        name: 'Indicador alerta deslizamiento',
                        data: []
                    }]
                }
            }
        },
        created(){
            this.$store.dispatch( 'changeStationsView', 'map' );

            this.$store.dispatch( 'loadStation', { id: this.$route.params.id, alerts: this.alerts });

            EventBus.$on('change-alert-station-page', function( data ){ this.changeDataAlert( data );}.bind(this));
        },
        watch: {
            '$route.params.id': function(){
                this.$store.dispatch( 'loadStation', { id: this.$route.params.id, alerts: this.alerts });
            },
            'stationLoadStatus': function(){
                if( this.stationLoadStatus === 2 ){ this.setAlertChat();}
                if( this.stationLoadStatus === 3 ){
                    this.$router.push({ name: 'stations' });
                    EventBus.$emit('show-error', { notification: 'No fue posible cargar la información para la estación', collapsible : true} );
                }
            }
        },
        computed: {
            stationLoadStatus(){
                return this.$store.getters.getStationLoadStatus;
            },
            /*station(){
                return this.$store.getters.getStation;
            },*/
            alerts(){
                return this.$store.getters.getAlerts;
            },
            alertA25Station(){
                return this.$store.getters.getAlertsStation('alert-a25');
            },
            alertA10Station(){
                return this.$store.getters.getAlertsStation('alert-a10');
            },
            station(){
                return this.$store.getters.getStationById(this.$route.params.id);
            },
            alertA25StationInStations(){
                return this.station.alerts.find(alert => alert.code === 'alert-a25');
            },
            alertA10StationInStations(){
                return this.station.alerts.find(alert => alert.code === 'alert-a10');
            }
        },
        methods:{
            setAlertChat(){
                this.chartOptionsA25.xAxis.categories = this.alertA25Station.dates;
                this.chartOptionsA25.series[0].data = this.alertA25Station.values;
                this.chartOptionsA10.xAxis.categories = this.alertA10Station.dates;
                this.chartOptionsA10.series[0].data = this.alertA10Station.values;
            },
            changeDataAlert(data){
                if (data.code === 'a10'){ this.updateA10AlertStation(data);}
                if (data.code === 'a25'){this.updateA25AlertStation(data);}
            },
            updateA10AlertStation(data){
                this.chartOptionsA10.xAxis.categories.push(data.date_execution);
                this.chartOptionsA10.series[0].data.push(data.value);
            },
            updateA25AlertStation(data){
                this.chartOptionsA25.xAxis.categories.push(data.date_execution);
                this.chartOptionsA25.series[0].data.push(data.value);
            },
            clearStation(){
                this.$store.commit('setStation',{alerts:null,station:null});
            }
        }
    }
</script>