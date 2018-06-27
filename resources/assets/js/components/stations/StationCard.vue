<template>
    <div class="station-card-container">
        <li class="list-group-item"  v-show="show">
            <router-link :to="{ name: 'station', params: { id: station.id} }">
                {{ station.name }}
            </router-link>
        </li>
    </div>
</template>

<script>
    import { StationTextFilter } from '../../mixins/filters/StationTextFilter.js';
    import { EventBus } from '../../event-bus.js';

    export default {
        mixins: [StationTextFilter],
        props: ['station'],
        data(){
            return {
                show: true
            }
        },
        mounted(){
            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));
        },
        methods: {
            processFilters( filters ){

                if( filters.text === null ){
                    this.show = true;
                }else{
                    var textPassed = false;

                    if( filters.text !== null && this.processStationTextFilter(this.station, filters.text )){
                        textPassed = true;
                    }else if( filters.text === null ){
                        textPassed = true;
                    }
                }

                if(textPassed){
                    this.show = true;
                }else{
                    this.show = false;
                }
            }
        }
    }
</script>

<style>

</style>