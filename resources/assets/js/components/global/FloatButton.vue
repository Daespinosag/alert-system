<style lang="scss">
    .menu-button{
        background-color: #196c4b;
        width: 100px;
        height: 25px;
        border-radius: 5px;
        -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
        box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
        text-align: center;
        z-index: 9;
        cursor: pointer;
        position: absolute;
        top: 60px;
        color: white;
        padding: 5px 20px;
        left: 250px;
    }
    .initial-position{
        left: 250px;
    }
    .active-position{
        left: 570px;
    }
</style>

<template>
    <div
            v-bind:class="[{'initial-position': !this.showFilters, 'active-position': this.showFilters }, 'menu-button']"
            v-show="$route.name == 'stations'"
            v-on:click="toggleShowFilters()"
    ><i  v-bind:class="{'fa fa-angle-double-right': !this.showFilters, 'fa fa-angle-double-left': this.showFilters } " aria-hidden="true"> Filtros</i></div> <!--&plus;-->
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
        data(){
            return {
                initialClass: 'initial-position',
                activeClass: 'active-position',
                menuButton: 'menu-button',
                isActive : true
            }
        },
        computed: {
            showFilters(){
                return this.$store.getters.getShowFilters;
            }
        },
        methods: {
            toggleShowFilters(){
                this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
            },
            clearFilters(){
                EventBus.$emit('clear-filters');
            }
        }
    }
</script>