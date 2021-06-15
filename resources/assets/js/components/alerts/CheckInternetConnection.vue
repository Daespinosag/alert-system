<style lang="scss">
    div#status-icon{
        position: absolute;
        z-index: 999;
        right: 10px;
        top: 60px;
    }
</style>

<template>
    <div>
        <div id="status-icon">
            <i class="fa-2x" v-bind:class="{'fa fa-plug text-success': isOnline, 'fa fa-times-circle text-danger' : !isOnline} " aria-hidden="true"></i>
        </div>
    </div>
</template>

<script>

    export default {
        name: 'check-internet-connection',
        data() {
            return {
                isOnline: true,
            };
        },
        created() {
            this.checkInternetConnection();
        },
        methods: {
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