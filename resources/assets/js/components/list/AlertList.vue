<template>
    <div  id="alert-list" class="alert-list"  v-bind:class="[ this.showFilters ? 'load-with-filters' : 'load-without-filters', 'alert-list-container' ]">
        <b-container>
            <b-row class = "text-center">
                <b-col class="alert-grid-container" id="alert-grid">

                    <flood-alert-list> </flood-alert-list>
                    <landslide-alert-list> </landslide-alert-list>

                    <b-col>
                        <span class="no-results" v-if="shownCount === 0">No Hay Resultados</span>
                    </b-col>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    import LandslideAlert from "../../store/models/alerts/landslide/landslideAlert";
    import FloodAlertList from "./flood/FloodAlertList";
    import LandslideAlertList from "./landslide/LandslideAlertList";

    export default {
        name: "alert-list",
        components: { FloodAlertList, LandslideAlertList },
        data(){
            return {
                shownCount: 1
            }
        },
        mounted(){
            EventBus.$on('filters-updated', function( filters ){
                this.computeShown();
            }.bind(this));
        },

        computed: {
            landslideAlerts(){
                return LandslideAlert.all();
            },
            showFilters(){
                return this.$store.getters.getShowFilters;
            }
        },

        methods: {
            computeShown(){
                this.shownCount = $('.alert-card-container').filter(function() {
                    return $(this).css('display') !== 'none';
                }).length;
            }
        }
    }
</script>

<style lang="scss">
    .alert-list-container{
        align-items: center;
        display: flex;
        margin-top: 100px;

    }
    .load-without-filters{
        width: 90%;
        margin-left: 10%
    }

    .load-with-filters{
        width: 55%;
        margin-left: 45%
    }

</style>