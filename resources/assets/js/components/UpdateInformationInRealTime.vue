<template>
    <div id="update-information-in-real-time" class="update-information-in-real-time"> </div>
</template>

<script>
    import {EventBus} from "../event-bus";

    export default {
        name: "update-information-in-real-time",
        created() {
            this.subscribeFloodAlertUpdaterEvent();
            this.subscribeLandslideAlertUpdaterEvent();
        },
        methods: {
            subscribeFloodAlertUpdaterEvent(){
                Echo.channel('alert-flood').listen('AlertFloodEvent', (e) => {
                    this.sendEventsFloodAlertUpdateAlerts(e.data);
                });
            },
            subscribeLandslideAlertUpdaterEvent(){
                Echo.channel('alert-landslide').listen('AlertLandslideEvent', (e) => {
                    this.sendEventsUpdateLandslideAlerts(e.data);
                });
            },
            sendEventsFloodAlertUpdateAlerts(data) {
                let playAlarm = false;
                for (const [key, value] of Object.entries(data)) {

                    this.$store.dispatch('updateFloodInformation', this.formatUpdateTracking(value))
                        .then( function(response) {
                            /** TODO: Actualizar Barra de notificaciones de que se actualizó correctamente una alerta*/
                            if (value.alert_tag === "red" && value.alert_status === "increase"){
                                playAlarm = true;
                            }
                            EventBus.$emit("message-logged", {error: false, message: `Alerta con identificador ${value.primary_station_id} actualizada`})
                        })
                        .catch( function(error){
                            /** TODO: Actualizar Barra de notificaciones de que se actualizó correctamente una alerta*/
                            EventBus.$emit("message-logged", {error: true, message: `Error actualizando alerta con identificador ${value.primary_station_id}`})
                        });

                    /**
                     * TODO :
                     * Validar si la value.primary_station_id esta actualmente activa en currentStation
                     * Si esta activa actualizar la información segun corresponda.
                     */
                }
                if (playAlarm){
                    EventBus.$emit("play-alarm");
                }
            },
            sendEventsUpdateLandslideAlerts(data){
                let playAlarm = false;
                for (const [key, value] of Object.entries(data)) {

                    this.$store.dispatch('updateLandslideInformation',this.formatUpdateTracking(value))
                        .then( function(response) {
                            /** TODO: Actualizar Barra de notificaciones de que se actualizó correctamente una alerta*/
                            if (value.alert_tag === "red" && value.alert_status === "increase"){
                                playAlarm = true;
                            }
                            EventBus.$emit("message-logged", {error: false, message: `Alerta con identificador ${value.primary_station_id} actualizada`})

                        })
                        .catch( function(error){
                            /** TODO: Actualizar Barra de notificaciones de que se actualizó correctamente una alerta*/
                            EventBus.$emit("message-logged", {error: true, message: `Error actualizando alerta con identificador ${value.primary_station_id}`})

                        });

                    /**
                     * TODO :
                     * Validar si la value.primary_station_id esta actualmente activa en currentStation
                     * Si esta activa actualizar la información segun corresponda.
                     */
                }
                if (playAlarm){
                    EventBus.$emit("play-alarm");
                }
            },
            formatUpdateTracking(data){
                return {
                    id                              : data.primary_station_id,
                    tracking_values                 : true,
                    secondary_calculate             : data.secondary_calculate,
                    rainfall                        : data.rainfall,
                    rainfall_recovered              : data.rainfall_recovered,
                    indicator_value                 : data.indicator_value,
                    indicator_previous_difference   : data.indicator_previous_difference,
                    alert_level                     : data.alert_level,
                    alert_tag                       : data.alert_tag,
                    alert_status                    : data.alert_status,
                    date_time_homogenization        : data.date_time_homogenization,
                    error                           : data.error,
                    comment                         : data.comment
                }
            }
        }
    }
</script>

<style scoped>

</style>