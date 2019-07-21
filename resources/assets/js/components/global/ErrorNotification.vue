<style lang="scss">

    div.error-notification-container{
        position: fixed;
        z-index: 999999;
        left: 0;
        right: 0;
        top: 0;
        div.error-notification{
            background: #FFFFFF;
            box-shadow: 0 0 4px 0 rgba(0,0,0,0.12), 0 4px 4px 0 rgba(0,0,0,0.24);
            border-left: 5px solid #FF0000;
            height: 50px;
            line-height: 50px;
            margin: auto;
            width: 600px;
            margin-top: 150px;
            color: #242E38;
            font-size: 16px;

            .error-icon{
                margin-right: 20px;
                margin-left: 20px;
                height: 20px;
            }

            .close-icon{
                float: right;
                cursor: pointer;
                margin-top: 15px;
                margin-right: 15px;
            }
        }
    }
</style>

<template>
    <transition name="slide-in-top">
        <div class="error-notification-container" v-show="show">
            <div class="error-notification">
                <img class="error-icon" src="images/alert-icons/error.svg"/>
                {{ errorMessage }}
                <img class="close-icon" v-on:click="closeAction()" src="images/close-icon.svg"/>
            </div>
        </div>
    </transition>
</template>

<script>
    import { EventBus } from '../../event-bus.js';
    export default {
        data(){
            return {
                errorMessage: '',
                show: false,
                collapsible: true,
            }
        },
        mounted(){
            EventBus.$on('show-error', function( data ){
                
                this.errorMessage = data.notification;
                this.collapsible = data.collapsible;
                this.show = true;

                if (this.collapsible){ setTimeout( function(){this.show = false;}.bind(this), 6000);}

            }.bind(this));
        },
        methods:{
            closeAction(){
                this.errorMessage = '';
                this.collapsible = true;
                this.show = false;
            }
        }
    }
</script>