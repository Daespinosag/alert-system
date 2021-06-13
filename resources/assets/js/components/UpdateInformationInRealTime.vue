<template>
    <div id="update-information-in-real-time" class="update-information-in-real-time"> </div>
</template>

<script>
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
                for (const [key, value] of Object.entries(data)) {

                    this.$store.dispatch('updateFloodInformation', this.formatUpdateTracking(value))
                        .then( function(response) {
                            /** TODO: Actualizar Barra de notificaciones de que se actualizó correctamente una alerta*/
                        })
                        .catch( function(error){
                            /** TODO: Actualizar Barra de notificaciones de que se actualizó correctamente una alerta*/
                        });

                    /**
                     * TODO :
                     * Validar si la value.primary_station_id esta actualmente activa en currentStation
                     * Si esta activa actualizar la información segun corresponda.
                     */
                }
            },
            sendEventsUpdateLandslideAlerts(data){
                for (const [key, value] of Object.entries(data)) {

                    this.$store.dispatch('updateLandslideInformation',this.formatUpdateTracking(value))
                        .then( function(response) {
                            /** TODO: Actualizar Barra de notificaciones de que se actualizó correctamente una alerta*/
                        })
                        .catch( function(error){
                            /** TODO: Actualizar Barra de notificaciones de que se actualizó correctamente una alerta*/
                        });

                    /**
                     * TODO :
                     * Validar si la value.primary_station_id esta actualmente activa en currentStation
                     * Si esta activa actualizar la información segun corresponda.
                     */
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