<style lang="scss">
    div#sound-alert{
        position: absolute;
        z-index: 9999;
        right: 10px;
        bottom: 30px;
    }
</style>

<template>
        <div id="sound-alert">
            <i class="fa-2x" @click="stop" v-bind:class="{'fa fa-volume-up text-success': !isPlaying, 'fa fa-volume-off text-danger' : isPlaying} " aria-hidden="true"></i>
            <button @click="testSound">Test me!</button>
        </div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
        name: 'sound-alert',
        data() {
            return {
                alarm: new Audio("images/assets/sound-alarm.mp3"),
                isPlaying: false,
            };
        },
        mounted() {
            EventBus.$on('sound-enabled', function(payload){
                this.mute( payload );
            }.bind(this));
            EventBus.$on('play-alarm', function(){
                this.play( );
            }.bind(this));
            EventBus.$on('playing-sound', function (payload) {
                this.changePlayingState(payload);
            }.bind(this));
        },
        created() {

        },
        methods: {
            play () {
                if (!this.alarm.muted){
                    this.stop();
                    this.alarm.play();
                    EventBus.$emit("playing-sound", {isPlaying: true});
                    setTimeout(this.stop, 5100);
                }
            },

            mute(payload) {
                this.alarm.muted = payload.muted;
            },

            stop() {
                this.alarm.pause();
                this.alarm.currentTime = 0;
                EventBus.$emit("playing-sound", {isPlaying: false});
            },

            changePlayingState(payload){
                this.isPlaying = payload.isPlaying;
            },
            testSound(){
                EventBus.$emit("play-alarm");
            }
        }
    }
</script>