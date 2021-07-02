<template>
        <div v-if="stationData && showAlertInfo">
            <b-sidebar id="sidebar-right" no-header shadow right :visible="true" width="750px">
                <div class="px-3 py-4">
                    <p class="h4 mb-2">
                        <router-link to="/PrintAlerts"">
                            <b-icon-x v-b-toggle.sidebar-right></b-icon-x>
                        </router-link>
                    </p>
                    <b-tabs content-class="mt-3">
                        <b-tab title="Información estación" active>
                            <b-card :title="`Estación ${stationData.data.stationData[0].id}`">
                                <b-card-text>
                                    <b-container>
                                        <b-row>
                                            <b-col><p><b>Nombre alerta:</b> {{ alertData.name}}</p></b-col>
                                            <b-col><p><b>Nombre estación:</b> {{ stationData.data.stationData[0].name}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Red:</b> {{ net.name }}</p></b-col>
                                            <b-col><p><b>Administrador red:</b> {{net.administrator_name}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Ubicación:</b> {{stationData.data.stationData[0].localization}}</p></b-col>
                                            <b-col><p><b>Ciudad:</b> {{stationData.data.stationData[0].city}}</p></b-col>
                                        </b-row>
                                        <template v-if="$attrs.stationType === `flood`">
                                                <b-row>
                                                    <b-col><p><b>Cuenca:</b> {{stationData.data.stationData[0].basin}}</p></b-col>
                                                    <b-col><p><b>Subcuenca:</b> {{stationData.data.stationData[0].sub_basin}}</p></b-col>
                                                </b-row>
                                        </template>
                                        <b-row v-else>
                                            <b-col>
                                                <p><b>Zona:</b> {{stationData.data.stationData[0].zone}}</p>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Tipo de estación:</b> {{stationData.data.stationData[0].name_type}}</p></b-col>
                                            <b-col><p><b>Código estación:</b> {{stationData.data.stationData[0].code}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Descripción:</b> {{stationData.data.stationData[0].description}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col> <charts
                                                    :title="`Indicador para estación ${stationData.data.stationData[0].name}`"
                                                    :y-axis="chartLabels"
                                                    :limits="alertLimits"
                                                    :series-data="stationData.data.tracking" :key="stationData.data.stationData[0].id">
                                            </charts></b-col>
                                        </b-row>
                                    </b-container>
                                </b-card-text>
                            </b-card>
                        </b-tab>

                        <b-tab title="Estaciones secundarias">
                            <b-card v-for="station in stationData.data.stationData[0].StationsSeconds" :key="station.id" :title="`Estación ${station.id}`">
                                <b-card-text>
                                    <b-container>
                                        <b-row>
                                            <b-col><p><b>Nombre alerta:</b> {{ alertData.name}}</p></b-col>
                                            <b-col><p><b>Nombre estación:</b> {{ station.name}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Red:</b> {{ net.name }}</p></b-col>
                                            <b-col><p><b>Administrador red:</b> {{net.administrator_name}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Ubicación:</b> {{station.localization}}</p></b-col>
                                            <b-col><p><b>Ciudad:</b> {{station.city}}</p></b-col>
                                        </b-row>
                                        <template v-if="$attrs.stationType === `flood`">
                                            <b-row>
                                                <b-col><p><b>Cuenca:</b> {{station.basin}}</p></b-col>
                                                <b-col><p><b>Subcuenca:</b> {{station.sub_basin}}</p></b-col>
                                            </b-row>
                                        </template>
                                        <b-row v-else>
                                            <b-col>
                                                <p><b>Zona:</b> {{station.zone}}</p>
                                            </b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Tipo de estación:</b> {{station.name_type}}</p></b-col>
                                            <b-col><p><b>Código estación:</b> {{station.code}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Descripción:</b> {{station.description}}</p></b-col>
                                        </b-row>
                                    </b-container>
                                </b-card-text>
                            </b-card>
                        </b-tab>
                        <b-tab title="Zonas de afectación" v-if="this.$attrs.stationType === `landslide`">
                            <div class="col-md-12 col-md-offset-1">
                                <b-card no-body class="col-md-5 m-1 text-center" style="font-size: 100%; min-height: 110px" v-for="zone in stationData.data.affectationZone" :key="zone.id">
                                    <b-card-text class="m-1 p-1">
                                        <b-container>
                                            <b-row>
                                                <b-col>
                                                    <p><b>Comuna:</b> {{zone.commune}}</p>
                                                </b-col>
                                            </b-row>
                                            <b-row>
                                                <b-col>
                                                    <p><b>Barrio:</b> {{zone.name}}</p>
                                                </b-col>
                                            </b-row>
                                        </b-container>
                                    </b-card-text>
                                </b-card>
                            </div>
                        </b-tab>
                    </b-tabs>
                </div>
            </b-sidebar>
        </div>
</template>

<script>
    import Charts from "../components/alerts/Charts";
    import LandslideStation from "../store/models/alerts/landslide/landslideStation";
    import Net from "../store/models/alerts/net";
    import FloodStation from "../store/models/alerts/flood/floodStation";
    import FloodAlert from "../store/models/alerts/flood/floodAlert";
    import LandslideAlert from "../store/models/alerts/landslide/landslideAlert";


    export default {
        name: 'Alert',
        data(){
            return {
                visible: false,
            }
        },
        components:{
          charts: Charts
        },
        watch: {
            '$route.params.id': function () {
                this.$refs.sidebarAlert.visible = true;
                if (this.$attrs.stationType == 'flood'){
                    this.$store.dispatch('currentFloodStationData',this.$attrs);
                }
                else{
                    this.$store.dispatch('currentLandslideStationData',this.$attrs);
                }
            },
        },
        mounted() {
            this.$store.dispatch( 'toggleShowFilters', { showFilters : false } );
            this.toggleShowAlertInfo(true);
            if (this.$attrs.stationType == 'flood'){
                this.$store.dispatch('currentFloodStationData',this.$attrs);
            }
            else{
                this.$store.dispatch('currentLandslideStationData',this.$attrs);
            }
        },
        computed: {
            stationData(){
                if (this.$attrs.stationType == 'flood') {
                    return this.$store.getters.getCurrentFloodStationData;
                }
                else{
                    return this.$store.getters.getCurrentLandslideStationData;
                }
            },
           alertData(){
                if (this.$attrs.stationType == 'flood'){
                    return FloodAlert.query().where('id', this.$attrs.alertId).first();
                }
                else {
                    return LandslideAlert.query().where('id', this.$attrs.alertId).first();
                }
            },
            primaryStation(){
                if (this.$attrs.stationType == 'flood'){
                    return FloodStation.query().where('primary',true).where('alert_id', this.$attrs.alertId).with('').first();
                }
                else {
                    return LandslideStation.query().where('primary', true).where('alert_id', this.$attrs.alertId).get()[0];
                }
            },
            net(){
                return Net.query().find(this.primaryStation.net_id);
            },
            chartLabels(){
                if (this.$attrs.stationType === 'flood'){
                    return ["Precipitación (mm)", "Indicador A10-min"];
                }
                else{
                    return ["Precipitación (mm)", "Indicador A25"]
                }
            },
            alertLimits() {
                if (this.$attrs.stationType === 'flood') {
                    return [{color:'#cc3300', width: 2, value: parseFloat(this.alertData.limitRed)}];
                }
                else{
                    return [
                            {color: '#ffcc00', width: 2, value:  parseFloat(this.alertData.limitYellow)},
                            {color: '#e2580b', width: 2, value:  parseFloat(this.alertData.limitOrange)},
                            {color: '#cc3300', width: 2, value:  parseFloat(this.alertData.limitRed)},
                            ];
                }
            },
            showAlertInfo(){
                return this.$store.getters.getShowAlertInfo;
            }
            },


        beforeDestroy() {
            this.toggleShowAlertInfo(false);
        },

       methods:{
           toggleShowAlertInfo(show){
               this.$store.dispatch( 'toggleShowAlertInfo', { showAlertInfo : show } );
           }

        }
    }
</script>

<style lang="scss">

</style>