<template>
        <div v-if="stationData">
            <b-sidebar id="sidebar-right" no-header shadow right :visible="true" width="550px">
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
                                            <b-col><p><b>Nombre alerta:</b> {{ stationData.data.stationData[0].name}}</p></b-col>
                                            <b-col><p><b>Nombre estación:</b> {{ stationData.data.stationData[0].name}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Red:</b> {{ stationData.data.stationData[0].name }}</p></b-col>
                                            <b-col><p><b>Administrador red:</b> {{ stationData.data.stationData[0].name }}</p></b-col>
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
                                                    :title="`Indicadores para estación ${stationData.data.stationData[0].id}`"
                                                    y-axis="Nivel prueba"
                                                    :limits="stationData.data.tracking"
                                                    :series="stationData.data.tracking" :key="stationData.data.stationData[0].id">
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
                                            <b-col><p><b>Nombre alerta:</b> {{ station.name}}</p></b-col>
                                            <b-col><p><b>Nombre estación:</b> {{ station.name}}</p></b-col>
                                        </b-row>
                                        <b-row>
                                            <b-col><p><b>Red:</b> {{ station.name }}</p></b-col>
                                            <b-col><p><b>Administrador red:</b> {{ station.name }}</p></b-col>
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
                    </b-tabs>
                </div>
            </b-sidebar>
        </div>
</template>

<script>
    import Charts from "../components/alerts/Charts";

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
        create(){

        },
        watch:{
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