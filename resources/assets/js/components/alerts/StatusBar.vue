<style lang="scss">
    div#status-bar{
        position: absolute;
        z-index: 9999;
        right: 10px;
        bottom: 60px;
    }
</style>

<template>
    <div>
        <div id="status-bar">
            <p>{{message}}</p>
        </div>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
        name: 'status-bar',
        data() {
            return {
                message: null,
                error: false,
            };
        },
        mounted() {
            EventBus.$on("message-logged", function( payload ){
                this.updateMessage(payload);
            }.bind(this));
        },
        created() {

        },
        methods: {
            updateMessage(payload) {
                this.message = payload.message;
                this.error = payload.error;
            }
        }
    }
</script>