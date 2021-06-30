<style lang="scss">
    div#status-icon{
        position: absolute;
        z-index: 999999;
        right: 10px;
        top: 60px;
    }
    div#toggle-alerts-view{
        position: absolute;
        z-index: 999999;
        right: 45px;
        top: 60px;
        -webkit-box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.16), 0 0 0 1px rgba(0, 0, 0, 0.08);
        border-radius: 5px;

        span.toggle-button{
            cursor: pointer;
            display: inline-block;
            padding: 5px 20px;
            background-color: #FFFFFF;
            font-family: "Lato", sans-serif;
            text-align: center;

            &.map-view{
                border-top-left-radius: 5px;
                border-bottom-left-radius: 5px;

                &.active{
                    color: white;
                    background-color: #3d3d3d;
                }
            }

            &.list-view{
                border-top-right-radius: 5px;
                border-bottom-right-radius: 5px;

                &.active{
                    color: white;
                    background-color: #3d3d3d;
                }
            }
        }
    }
</style>

<template>
    <div>
        <div id="toggle-alerts-view" >
            <span class="map-view toggle-button" v-bind:class="{ 'active': alertsView === 'map' }" v-on:click="displayView('map')">Map</span><span class="list-view toggle-button" v-bind:class="{ 'active': alertsView === 'list' }" v-on:click="displayView('list')">List</span>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'toggle-alerts-view',
        data() {
            return {
            };
        },

        computed: {
            alertsView(){
                return this.$store.getters.getAlertsView;
            }
        },

        methods: {
            displayView( type ){
                this.$store.dispatch( 'changeAlertsView', type );
            },
        }
    }
</script>