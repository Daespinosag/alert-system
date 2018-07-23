<style>
        div.show-filters {
            height: 90px;
            width: 23px;
            position: absolute;
            left: 0px;
            background-color: white;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            line-height: 90px;
            top: 50%;
            cursor: pointer;
            margin-top: -45px;
            z-index: 9;
            text-align: center;
        }
</style>

<template>
    <div id="app-layout">
        <div class="show-filters" v-show="!showFilters" v-on:click="toggleShowFilters()">
            <img src="images/grey-right.svg"/>
        </div>
        <navigation></navigation>

        <router-view></router-view>

        <filters></filters>
    </div>
</template>

<script>
    import { EventBus } from '../event-bus.js';

    import Navigation from '../components/global/Navigation.vue';
    import Filters from '../components/global/Filters.vue';


    export default {
        components: {Navigation,Filters},
        created(){
            this.$store.dispatch( 'loadAlerts' );
            this.$store.dispatch( 'loadTypeStation' );
            this.$store.dispatch( 'loadStations' );
        },
        computed: {
            showFilters(){
                return this.$store.getters.getShowFilters;
            },
        },
        methods: {
            toggleShowFilters(){
                this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
            }
        }
    }
</script>