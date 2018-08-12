<style lang="scss">

</style>

<template>
    <div  class="">
        <div class="col-md-6 col-md-offset-3">
            <b-form @submit.prevent="loadDataAlert" v-if="showForm" >
                <b-form-group id="alert"
                              label="Alerta : "
                              label-for="alert"
                              description="Seleccione la alerta que desea calcular.">
                    <b-form-select id="alerts" :options="getAlertsForForm"  v-model="alertSelected" required></b-form-select>
                </b-form-group>

                <b-form-group id="stations"
                              label="Estación : "
                              label-for="station"
                              description="Seleccione la estación a la cual desea calcular la alerta.">
                    <b-form-select id="alerts" :options="localStations"  v-model="stationSelected" required></b-form-select>
                </b-form-group>

                <b-form-group id="dates"
                              label="Rango de Fechas : "
                              label-for="dates"
                              description="Seleccione el Rango de fechas para las cuales desea calcular el indicador.">
                    <rangedate-picker></rangedate-picker>
                </b-form-group>
            </b-form>
        </div>
        <div class="result">
        </div>
    </div>
</template>

<script>
    import { EventBus } from "../event-bus";
    import Loader from '../components/global/Loader.vue';
    import RangedatePicker from '../components/external/vue-rangedate-picker/RangedatePicker';

    export default {
        components: {Loader,RangedatePicker},
        data(){
            return {
                showForm: true,
                alertSelected: null,
                stationSelected: null,
                localStations: [],
            }
        },
        computed: {
            getAlertsForForm(){
                return this.$store.getters.getAlertsForForm;
            },
            stations(){
                return this.$store.getters.getStations;
            }
        },
        watch: {
            alertSelected: function (alert) {
                let localStation = [];
                for (var i = 0; i < this.stations.length; i++) {
                    for (var j = 0; j < this.stations[i].alerts.length; j++) {
                        if (this.stations[i].alerts[j].code === alert){
                            localStation.push({'value': this.stations[i].id, 'text': this.stations[i].name });
                        }
                    }
                }

                this.localStations = localStation;
            },
        },
        methods: {
            loadDataAlert: function () {
                // TODO
            }
        }
    }
</script>