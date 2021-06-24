<template>
    <transition name="slide-in-left">
        <div class="filters-container" id="filters-container" v-show="showFilters">
            <div class="close-filters mb-5" v-on:click="toggleShowFilters()">
                <img src="images/grey-left.svg"/>
            </div>

            <div class="col-md-12 mb-5 mt-5" id="text-container">
                <input type="text" class="search-filters form-control" v-model="textSearch" placeholder="Buscar Estaciones"/>
            </div>
            <div class="col-md-12 m-1" id="flood-layer-toggle">
                <div class="col-md-5 m-1 shadow p-2 bg-white">
                    <h4>Alertas Inundación</h4>

                    <b-form-checkbox v-model="floodLayerVisible" name="flood-layer-visible" switch size="mg">
                        Capa de alertas de inundacón
                    </b-form-checkbox>

                    <b-form-checkbox v-model="floodIconsVisible" name="flood-icons-visible" switch size="mg">
                        Iconos de alertas de inundacón
                    </b-form-checkbox>

                    <b-form-checkbox v-model="floodPolygonsVisible" name="flood-polygons-visible" switch size="mg">
                        Poligonos de alertas de inundación
                    </b-form-checkbox>
                </div>

                <div class="col-md-6 m-1 shadow p-2 bg-white rounded">
                    <h4>Alertas Deslizamientos</h4>

                    <b-form-checkbox v-model="landslideLayerVisible" name="flood-layer-visible" switch size="mg">
                        Capa de alertas de deslizamiento
                    </b-form-checkbox>

                    <b-form-checkbox v-model="landslideIconsVisible" name="landslide-icons-visible" switch size="mg">
                        Iconos de alertas de deslizamiento
                    </b-form-checkbox>

                    <b-form-checkbox v-model="landslidePolygonsVisible" name="landslide-polygons-visible" switch size="mg">
                        Poligonos de alertas de deslizamiento
                    </b-form-checkbox>
                </div>
            </div>

            <div class="col-md-11 ml-3  mt-5 shadow p-2 bg-white rounded text-right" id="toggle-sound-container">
                <b-form-checkbox v-model="soundAlertEnabled" name="landslide-polygons-visible" switch size="mg">
                    Alerta Sonora
                </b-form-checkbox>
            </div>
            <div class="col-md-11 ml-3  mt-5 shadow p-2 bg-white rounded text-center" id="alert-conventions-container">
                <b-button v-b-toggle="'conventions'" class="m-1 bg-primary">Mostrar convenciones</b-button>

                <b-collapse id="conventions">
                    <b-card title="Deslizamiento"><b-card-text>
                        <b-container class="text-center" align-v="center">
                            <b-row>
                                <b-col>
                                    <img src="images/assets/alerts/landslide_alert_black.png" alt=""> Alerta verde
                                </b-col>
                                <b-col>
                                    <img src="images/assets/alerts/landslide_alert_yellow.png" alt=""> Alerta amarilla
                                </b-col>
                                <b-col>
                                    <img src="images/assets/alerts/landslide_alert_orange.png" alt=""> Alerta naranja
                                </b-col>
                                <b-col>
                                    <img src="images/assets/alerts/landslide_alert_red.png" alt=""> Alerta roja
                                </b-col>
                            </b-row>
                        </b-container>
                    </b-card-text></b-card>
                    <b-card title="Inundación"><b-card-text>
                        <b-container class="text-center" align-v="center">
                            <b-row>
                                <b-col>
                                    <img src="images/assets/alerts/flood_alert_black.png" alt=""> Alerta verde
                                </b-col>
                                <b-col>
                                        <img src="images/assets/alerts/flood_alert_red.png" alt=""> Alerta roja
                                </b-col>
                            </b-row>
                        </b-container>
                    </b-card-text></b-card>
                    <b-card title="General"><b-card-text>
                        <b-container class="text-center" align-v="center">
                            <b-row>
                                <b-col>
                                    <img src="images/assets/alerts/flood_alert_connection_error.png" alt=""> Error de conexión
                                </b-col>
                                <b-col>
                                    <img src="images/assets/alerts/flood_alert_error_red.png" alt=""> Error de cálculo
                                </b-col>
                            </b-row>
                        </b-container>
                    </b-card-text></b-card>

                </b-collapse>
            </div>
        </div>
    </transition>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
        name: 'filters',
        data(){
            return {
                textSearch: '',
                activeAlertFilter: 'all',
                activeTypeStationFilter: [],
            }
        },

        watch: {
            textSearch(){
                this.updateFilterDisplay();
            },
            activeAlertFilter(){
                this.updateFilterDisplay();
            },
            activeTypeStationFilter(){
                this.updateFilterDisplay();
            },
        },

        mounted(){
            EventBus.$on('show-filters', function(){
                this.show = true;
            }.bind(this));

            EventBus.$on('clear-filters', function(){
                this.clearFilters();
            }.bind(this));
        },

        computed: {
            showFilters(){
                return this.$store.getters.getShowFilters;
            },
            existenceFiltersActive(){
                return this.$store.getters.getExistenceFiltersActive;
            },
            floodLayerVisible: {
                get() { return this.$store.getters.getFloodLayerVisible; },
                set(value) { this.$store.dispatch('toggleFloodLayerVisible', { floodLayerVisible: value }); },
            },
            landslideLayerVisible: {
                get() { return this.$store.getters.getLandslideLayerVisible; },
                set(value) {  this.$store.dispatch('toggleLandslideLayerVisible', { landslideLayerVisible: value }); }
            },
            floodIconsVisible: {
                get(){ return this.$store.getters.getFloodIconsVisible; },
                set(value){ this.$store.dispatch('toggleFloodIconsVisible', { floodIconsVisible: value }); }
            },
            floodPolygonsVisible:{
                get(){ return this.$store.getters.getFloodPolygonsVisible; },
                set(value){ this.$store.dispatch('toggleFloodPolygonsVisible', { floodPolygonsVisible: value }); }
            },
            landslideIconsVisible: {
                get(){ return this.$store.getters.getLandslideIconsVisible; },
                set(value){ this.$store.dispatch('toggleLandslideIconsVisible', { landslideIconsVisible: value }); }

            },
            landslidePolygonsVisible: {
                get(){ return this.$store.getters.getLandslidePolygonsVisible; },
                set(value){ this.$store.dispatch('toggleLandslidePolygonsVisible', { landslidePolygonsVisible: value }); }
            },
            soundAlertEnabled: {
                get(){ return this.$store.getters.getSoundAlertEnabled; },
                set(value){
                    EventBus.$emit("sound-enabled", {muted: !value });
                    this.$store.dispatch('toggleSoundAlert', {soundAlertEnabled: value});
                }
            }

        },

        methods: {
            setActiveAlertFilter( filter ){
                this.activeAlertFilter = filter;
            },
            updateFilterDisplay(){
                this.$store.commit('setExistenceFiltersActive',!(this.textSearch === '' && this.activeAlertFilter === 'all' && this.activeTypeStationFilter.length === 0));

                EventBus.$emit('filters-updated', {
                    text: this.textSearch,
                    alert: this.activeAlertFilter,
                    typeStation: this.activeTypeStationFilter
                });
            },
            toggleShowFilters(){
                this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
            },
            /*toggleFloodLayer(){
                this.$store.dispatch('toggleFloodLayerVisible', {floodLayerVisible: !this.floodLayerVisible});
            },*/
            /*toggleLandslideLayer(){
                this.$store.dispatch('toggleLandslideLayerVisible', {landslideLayerVisible: !this.landslideLayerVisible});
            },*/
            /*toggleFloodIcons(){
                this.$store.dispatch('toggleFloodIconsVisible', {floodIconsVisible: !this.floodIconsVisible});
            },*/
            /*toggleFloodPolygons(){
                this.$store.dispatch('toggleFloodPolygonsVisible', {floodPolygonsVisible: !this.floodPolygonsVisible});
            },*/
            /*toggleLandslideIcons(){
                this.$store.dispatch('toggleLandslideIconsVisible', {landslideIconsVisible: !this.landslideIconsVisible});
            },*/
            /*toggleLandslidePolygons(){
                this.$store.dispatch('toggleLandslidePolygonsVisible', {landslidePolygonsVisible: !this.landslidePolygonsVisible});
<<<<<<< HEAD
            },*/
            /*toggleSoundAlert(){
                EventBus.$emit("sound-enabled", {muted: this.soundAlertEnabled});
                this.$store.dispatch('toggleSoundAlert', {soundAlertEnabled: !this.soundAlertEnabled});
            },*/

            clearFilters(){
                this.textSearch = '';
                this.activeAlertFilter = 'all';
                this.activeTypeStationFilter = [];
            },

            toggleAlertFilter( alert ){
                this.setActiveAlertFilter(alert);
            },

            toggleTypeStationFilter(code){

                var i = this.activeTypeStationFilter.indexOf(code);

                if ( i >= 0 ){
                    this.activeTypeStationFilter.splice(i,1);
                }else {
                    this.activeTypeStationFilter.push(code);
                }
            }
        }
    }
</script>

<style lang="scss">
    @import '~@/abstracts/variables.scss';

    div.filters-container{
        background-color: white;
        position: fixed;
        left: 0;
        bottom: 0;
        top: 30px;
        max-width: 550px;
        width: 100%;
        box-shadow: 0 2px 4px 0 rgba(3,27,78,0.10);
        z-index: 999;

        span.clear-filters{
            font-size: 16px;
            color: #3e8f3e;
            font-family: "Lato", sans-serif;
            cursor: pointer;
            display: block;
            float: left;
            margin-bottom: 20px;

            img{
                margin-right: 10px;
                float: left;
                margin-top: 6px;
            }
        }

        input[type="text"].search-filters{
            box-shadow: none;
            border-radius: 3px;
            color: #BABABA;
            font-size: 16px;
            font-family: "Lato", sans-serif;
            background-repeat: no-repeat;
            background-position: 6px;
            padding-left: 35px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        label.filter-label{
            font-family: "Lato", sans-serif;
            text-transform: uppercase;
            font-weight: bold;
            color: black;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        div.location-filter{
            text-align: center;
            font-family: "Lato", sans-serif;
            font-size: 16px;
            color: #3e8f3e;
            border-bottom: 1px solid #3e8f3e;
            border-top: 1px solid #3e8f3e;
            border-left: 1px solid #3e8f3e;
            border-right: 1px solid #3e8f3e;
            width: 30%;
            display: inline-block;
            height: 55px;
            line-height: 55px;
            cursor: pointer;
            margin-bottom: 5px;

            &.active{
                color: white;
                background-color: #3e8f3e;
            }

            &.all-locations{
                border-top-left-radius: 3px;
                border-bottom-left-radius: 3px;
            }

            &.roasters{
                border-left: none;
                border-right: none;
            }

            &.stations{
                border-top-right-radius: 3px;
                border-bottom-right-radius: 3px;
            }
        }

        div.brew-method{
            color: #666666;
            border-radius: 4px;
            background-color: #e4e4e4;
            width: 180px;
            height: 35px;
            float: left;
            margin-right: 10px;
            margin-bottom: 10px;
            padding: 7px;
            cursor: pointer;
            position: relative;

            &.active{
                color: white;
                background-color: #3e8f3e;
            }

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

        span.liked-location-label{
            color: #666666;
            font-size: 16px;
            font-family: "Lato", sans-serif;
            margin-left: 10px;
        }

        div.station-grid-container{
            /*overflow: auto;
            padding-bottom: 10px;*/
        }

        div.close-filters{
            height: 90px;
            width: 23px;
            position: absolute;
            right: -20px;
            background-color: white;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            line-height: 90px;
            top: 50%;
            cursor: pointer;
            margin-top: -82px;
            text-align: center;
        }

        span.no-results{
            display: block;
            text-align: center;
            margin-top: 50px;
            color: #666666;
            text-transform: uppercase;
            font-weight: 600;
        }
    }

    /* Small only */
    @media screen and (max-width: 39.9375em) {
        div.filters-container{
            padding-top: 25px;
            overflow-y: auto;

            span.clear-filters{
                display: block;
            }

            div.station-grid-container{
                height: inherit;
            }

            div.close-filters{
                display: none;
            }
        }
    }

    /* Medium only */
    @media screen and (min-width: 40em) and (max-width: 63.9375em) {

    }

    /* Large only */
    @media screen and (min-width: 64em) and (max-width: 74.9375em) {

    }
</style>