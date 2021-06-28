 <style lang="scss">
    .menu-button{
        width: 235px;
        height: 25px;
        z-index: 9;
        position: absolute;
        top: 60px;
        display: flex;

        .filters-button{
            background-color: #196c4b;
            width: 90px;
            height: 25px;
            border-radius: 5px;
            -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
            box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
            text-align: center;
            cursor: pointer;
            color: white;
            padding: 5px 20px;
        }

        .clear-filters{
            background-color: #7e0712;
            width: 140px;
            height: 25px;
            border-radius: 5px;
            -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
            box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
            cursor: pointer;
            color: white;
            text-align: center;
            padding: 5px 20px;
        }
    }


    .initial-position{
        left: 250px;
    }
    .active-position{
        left: 570px;
    }
</style>

<template>

    <div v-bind:class="[ this.showFilters ? 'active-position': 'initial-position' , 'menu-button']"  >
        <div class="filters-button" v-on:click="toggleShowFilters()" >
            <i  v-bind:class="{'fa fa-angle-double-right': !this.showFilters, 'fa fa-angle-double-left': this.showFilters } " aria-hidden="true">
                Filtros
            </i>
        </div>

        <div class="clear-filters" v-show="this.existenceFiltersActive" v-on:click="clearFilters()">
            <i class="fa fa-close" aria-hidden="true"> limpiar Filtros</i>
        </div>
    </div>

</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
        computed: {
            showFilters(){
                return this.$store.getters.getShowFilters;
            },
            existenceFiltersActive(){
                return this.$store.getters.getExistenceFiltersActive;
            }

        },
        methods: {
            toggleShowFilters(){
                this.$store.dispatch( 'toggleShowAlertInfo', { showAlertInfo : false } );
                this.$store.dispatch( 'toggleShowFilters', { showFilters : !this.showFilters } );
            },
            clearFilters(){
                EventBus.$emit('clear-filters');
            }
        }
    }
</script>