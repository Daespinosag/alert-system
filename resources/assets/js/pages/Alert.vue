<template>
    <transition name="slide-in-right">
        <div class="station-info">
            <div class="filters-container" id="filters-container" v-show="showFilters">
                <div class="close-filters" v-on:click="toggleShowAlertInfo()">
                    <img src="images/grey-left.svg"/>
                </div>
            <p>HOLA SOY EL ID {{ this.id }} </p>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        props: ['id'],
        mounted() {
        },
        computed:{
            showFilters(){
                return this.$store.getters.getShowFilters;
            },
        },
        methods:{
            toggleShowAlertInfo(){
                this.$store.dispatch( 'toggleShowAlertinfo', { showAlertInfo : !this.showAlertInfo } );
            }
        }
    }
</script>

<style lang="scss">
    div.station-info{
        background-color: white;
        position: fixed;
        right: 0;
        bottom: 0;
        top: 30px;
        max-width: 550px;
        width: 100%;
        box-shadow: 0 2px 4px 0 rgba(3,27,78,0.10);
        z-index: 99;
    }

    div.filters-container {
        background-color: white;
        position: fixed;
        right: 0;
        bottom: 0;
        top: 30px;
        max-width: 550px;
        width: 100%;
        box-shadow: 0 2px 4px 0 rgba(3, 27, 78, 0.10);
        z-index: 99;

        div.close-filters {
            height: 90px;
            width: 23px;
            position: absolute;
            left: -20px;
            background-color: white;
            border-top-right-radius: 3px;
            border-bottom-right-radius: 3px;
            line-height: 90px;
            top: 50%;
            cursor: pointer;
            margin-top: -82px;
            text-align: center;
        }
    }

</style>