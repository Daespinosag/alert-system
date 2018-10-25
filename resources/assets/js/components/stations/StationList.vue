<style lang="scss">
    .station-list-container{
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

<template>
    <div  v-bind:class="[ this.showFilters ? 'load-with-filters' : 'load-without-filters', 'station-list-container' ]">
        <b-container>
            <b-row class = "text-center">
                <b-col class="station-grid-container" id="station-grid">
                    <station-card v-for="station in stations" :key="station.id" :station="station"></station-card>
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

    import StationCard from '../../components/stations/StationCard.vue';

    export default {
        data(){
            return {
                shownCount: 1
            }
        },
        components: { StationCard},
        mounted(){
            EventBus.$on('filters-updated', function( filters ){
                this.computeShown();
            }.bind(this));
        },

        computed: {
            stations(){
                return this.$store.getters.getStations;
            },
            showFilters(){
                return this.$store.getters.getShowFilters;
            }
        },

        methods: {
            computeShown(){
                this.shownCount = $('.station-card-container').filter(function() {
                    return $(this).css('display') !== 'none';
                }).length;
            }
        }
    }
</script>