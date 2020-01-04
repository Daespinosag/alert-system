<style>
        div.show-filters {
            height: 90px;
            width: 23px;
            position: absolute;
            left: 0px;
            background-color: #2F3133;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            line-height: 90px;
            top: 50%;
            cursor: pointer;
            margin-top: -45px;
            z-index: 9;
            text-align: center;
        }
</style>

<template>
    <div id="app-layout">
        <div class="show-filters" v-show="!showFilters && $route.name  === 'stations'" v-on:click="toggleShowFilters()">
            <img src="images/grey-right.svg"/>
        </div>

        <navigation></navigation>

        <error-notification></error-notification>
        <success-notification></success-notification>

        <float-button></float-button>

        <router-view></router-view>

        <filters></filters>
    </div>
</template>

<script>
    import { EventBus } from '../event-bus.js';

    import Navigation from '../components/global/Navigation.vue';
    import Filters from '../components/global/Filters.vue';
    import ErrorNotification from '../components/global/ErrorNotification.vue';
    import SuccessNotification from '../components/global/SuccesNotification.vue';
    import FloatButton from '../components/global/FloatButton';

    export default {
        props: { user: null },
        components: {Navigation,Filters,ErrorNotification,SuccessNotification,FloatButton},
        created(){
            /** Se carga la información inicial para que la pagina funcione correctamente**/
            this.loadInitialInformation();

            /** Se sealiza la suscripcion a los eventos de actualizacion cincominutales para las alertas**/
            this.subscribeEventFiveMinutes();
        },
        computed: {
            showFilters(){
                return this.$store.getters.getShowFilters;
            },
            stationInStation(){
                return this.$store.getters.getStation;
            },
        },
        methods: {
            toggleShowFilters(){
                this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
            },
            sendEvenError(notification,collapsible){
                EventBus.$emit('show-error', { notification : notification, collapsible : collapsible });
            },
            sendEventUpdateAlerts(values){
                /** Se valida la existencia de la alerta entrante**/
                if (this.validateExistenceAlert('alert-'+values[0].alert)){

                    /** Se realiza un clico por el numero de valores entrantes**/
                    for (let i = 0; i < values.length; i++) {

                        /** Se consulta la eztacion que requiere actualización de información de alertas**/
                        let station = this.$store.getters.getStationById(values[i].station);

                        /** Se valida que la eztación exista en el array global**/
                        if (station !== null)
                        {
                            /** Se actualiza la información refente a las alertas en el array global stations**/
                            this.$store.commit('updateStationAlert',{value: values[i], position: station.position});

                            /** Se cambia el color y el valor de cada estacion en el componente STATION CARD**/
                            this.sendEventChangeAlertMarkerColor(station.id,values[i]);

                            /** Se cambia el valor en las graficas para Station page**/
                            this.sendEventChangeAlertStationPage(station.id,values[i]);

                        }else {
                            this.sendEvenError( 'Error: No se encontró la estación [ id: '+values[i].station+' ]',true);
                        }
                    }
                }else {
                    this.sendEvenError('Error: No se encontró la alerta [ code: '+values[i].alert+' ]',true);
                }
                // TODO Cambiar el siguiente succes por notification !! v2
                EventBus.$emit('show-success', { notification : 'Actualización de datos para la : '+(this.$store.getters.getAlertFromCode('alert-'+values[0].alert)).name, collapsible : true });
            },
            sendEventChangeAlertStationPage(stationId,value){
                if (this.stationInStation !== null){
                    if (this.stationInStation.id === stationId){
                        EventBus.$emit('change-alert-station-page', {
                            code            : value.alert,
                            date_execution  : value.values['date_execution'],
                            value           : value.values[value.alert+'_value'],
                            stationId       : stationId,
                        });
                    }
                }
            },
            sendEventChangeAlertMarkerColor(stationId,value){
                EventBus.$emit('changeAlertMarketColor', {
                    code        : value.alert,
                    alert       : value.values['alert'],
                    stationId   : stationId,
                });
            },
            validateExistenceAlert(alert) {
                return this.$store.getters.getValidateAlertExistence(alert)
            },
            loadInitialInformation(){
                if (this.$store.userLoadStatus !== 2 && this.user !== null){

                    this.$store.commit('setUser', this.user);

                    this.$store.dispatch('loadTypeStation').then(
                        response => {
                            this.$store.dispatch('loadAlerts',{ permissions : this.$store.getters.getAlertPermissions}).then(
                                response => {
                                    this.$store.dispatch('loadStations',{ alerts : this.$store.getters.getAlerts }).then(
                                        response => { this.$router.push({ name: 'stations' }); },
                                        error    => { this.sendEvenError('No fue posible cargar la información referente a las estaciones. ',false); }
                                    );
                                },
                                error    => { this.sendEvenError('No fue posible cargar la información referente a los tipos de alertas. ',false); }
                            );
                        },
                        error    => { this.sendEvenError('No fue posible cargar la información referente a los tipos de estación. ',false); }
                    );
                };
            },
            subscribeEventFiveMinutes(){
                Echo.channel('alert-system').listen('AlertEchoCalculatedEvent', (e) => { this.sendEventUpdateAlerts(e.data);});
            }
        },
    }
</script>2
