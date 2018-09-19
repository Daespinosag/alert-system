<style lang="scss">
    .consult-container{
        display: flex;
        align-items: center;
        height: 100%;
    }

    .local{
        display: flex;
        justify-content: center;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
    }

    .sub-container{
        display: inline-block;
        padding: 1%;
        margin: 1%;
    }

    .small-result{
        width: 70%;
    }

    .big-result{
        width: 100%;
    }

    .small-form{
        width: 30%;
    }

    .big-form{
        width: 70%;
    }
    .table-locale{
        height: 400px;
        overflow: auto;
    }

</style>
<template>
    <div  class="consult-container">
        <div class="local">
            <div :class="{'sub-container': true, 'big-form': bigForm, 'small-form': !bigForm }" id="form" v-if="showForm">
                <h1> Consultar indicadores de alerta </h1>
                <b-card title="" sub-title="">
                    <b-form @submit.prevent="loadDataAlert"  @reset.prevent="onReset">
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
                            <rangedate-picker @selected="selectedDate"></rangedate-picker>
                        </b-form-group>

                        <b-button type="submit" variant="primary" >Submit</b-button>
                        <b-button type="reset" variant="danger">Reset</b-button>

                    </b-form>
                </b-card>
            </div>
            <div :class="{'sub-container': true, 'big-result': bigResult, 'small-result': !bigResult }" v-if="showResult">
                    <h1>Resultado de la consulta</h1>
                    <loader v-show="resultAlert === 1" :width="100" :height="100"></loader>

                    <data-tables :data="result" v-if="resultAlert ===2" class="table-locale">
                        <el-table-column type="selection" width="55"></el-table-column>
                        <el-table-column v-for="column in resultColumns" :prop="column.prop" :label="column.label" :key="column.prop" sortable="custom">
                        </el-table-column>
                    </data-tables>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from "../event-bus";
    import alertAPI from '../api/alert.js';
    import Loader from '../components/global/Loader.vue';
    import RangedatePicker from '../components/external/vue-rangedate-picker/RangedatePicker';

    export default {
        components: {Loader,RangedatePicker,alertAPI},
        data(){
            return {
                bigForm: true,
                bigResult:false,
                showResult: false,
                showForm: true,
                alertSelected: null,
                stationSelected: null,
                localStations: [],
                startDate: '',
                endDate: '',
                resultAlert:  0,
                result: [],
                resultColumns :[],
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

                // se validan los datos para ser enviados TODO

                // se cambian las dimensiones del div que contiene el formulario
                this.reduceSizeForm();

                // se activa la visualizacion del div para insertar el resultado de la consulta
                this.showResult  = true;

                this.resultAlert = 1;

                // se extraen los datos del API TODO
                var that = this;

                alertAPI.genetareAlert({
                    'alert'         : this.alertSelected,
                    'station'       : this.stationSelected,
                    'initialDate'   : this.startDate,
                    'finalDate'     : this.endDate
                }).then(function (response) {
                    that.result = response.data.result;
                    that.resultColumns = response.data.columns;
                    that.resultAlert = 2;
                }).catch(function (error) {
                    that.resultAlert = 3;
                    console.log(error);
                });

                // se dibujan los datos en la tabla TODO

            },
            onReset (evt) {

                this.resultAlert = 0;

                this.alertSelected = null;
                this.alertSelected = null;
                this.stationSelected= null;
                this.localStations =  [];


                // TODO colocar en null el rango de fechas
            },
            selectedDate(data){
                this.startDate = data.start;
                this.endDate = data.end;
            },
            reduceSizeForm(){
                this.bigForm = false;
            }
        }
    }
</script>