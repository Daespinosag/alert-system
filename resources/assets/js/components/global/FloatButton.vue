<style lang="scss">
    div#menu-button{
        background-color: #196c4b;
        width: 56px;
        height: 56px;
        border-radius: 50px;
        -webkit-box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
        box-shadow: 0 2px 2px 0 rgba(0,0,0,0.14), 0 3px 1px -2px rgba(0,0,0,0.12), 0 1px 5px 0 rgba(0,0,0,0.2);
        text-align: center;
        z-index: 9;
        cursor: pointer;
        position: absolute;
        right: 60px;
        bottom: 30px;
        color: white;
        line-height: 50px;
        font-size: 40px;
    }
</style>

<template>
    <div id="menu-button" v-on:click="toggleShowFilters()"> &plus;</div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
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