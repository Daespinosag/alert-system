<template>
        <div>
        <b-button ref="controlButton" style="display: none" v-b-toggle.sidebar-right ></b-button>
            <b-sidebar id="sidebar-right" right shadow visible="true" width="550px">
                <div class="px-3 py-2">
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
                                            <b-col><charts></charts></b-col>
                                        </b-row>
                                    </b-container>

                                </b-card-text>

                            </b-card>

                            <b-container>

                            </b-container>

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
            }
        },
        components:{
          charts: Charts
        },
        watch:{
            '$route.params.id': function () {
                this.$refs.controlButton.click();
                this.$store.dispatch('currentStationData', {
                    "id": 31,
                    "alertId": 4,
                    "stationType": "flood"
                });
            },
        },
        mounted() {
            this.$store.dispatch('currentStationData',{
                "id": 31,
                "alertId": 4,
                "stationType": "flood"
            });
        },
        computed: {
            stationData(){
                return this.$store.getters.getCurrentStationData;
            }
        },

       methods:{


        }
    }
</script>

<style lang="scss">

</style>