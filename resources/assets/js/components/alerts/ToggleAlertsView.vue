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
        <div id="toggle-alerts-view" v-show="$route.name === 'PrintAlerts'">
            <span class="map-view toggle-button" v-bind:class="{ 'active': alertsView === 'map' }" v-on:click="displayView('map')">Map</span><span class="list-view toggle-button" v-bind:class="{ 'active': alertsView === 'list' }" v-on:click="displayView('list')">List</span>
        </div>
        <div id="status-icon">
            <i class="fa-2x" v-bind:class="{'fa fa-plug text-success': isOnline, 'fa fa-times-circle text-danger' : !isOnline} " aria-hidden="true"></i>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'toggle-alerts-view',
        data() {
            return {
                isOnline: true,
            };
        },
        created() {
            this.checkInternetConnection();
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
            getConnectionStatus: async () => {
                try {
                    const online = await fetch("http://ipv4.icanhazip.com/");
                    return online.status >= 200 && online.status < 300;
                } catch (err) {
                    return false;
                }
            },
            checkInternetConnection(){
                setInterval(async () => {
                    this.isOnline = await this.getConnectionStatus();
                }, 60000)
            }
        }
    }
</script>