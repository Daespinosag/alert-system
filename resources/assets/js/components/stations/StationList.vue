<style lang="scss">

</style>

<template>
    <b-container class="station-list-container">
        <b-row class = "text-center">
            <b-col class="station-grid-container" id="station-grid">
                <station-card v-for="station in stations" :key="station.id" :station="station"></station-card>
                <b-col>
                    <span class="no-results" v-if="shownCount === 0">No Hay Resultados</span>
                </b-col>
            </b-col>
        </b-row>
    </b-container>

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