<style lang="scss">
    div#sound-alert{
        position: absolute;
        z-index: 999999;
        right: 10px;
        bottom: 30px;
    }
</style>

<template>
        <div id="sound-alert">
            <audio id="alarm" style=“display:none”><source src= type="audio-mpeg"></audio>
            <button @click="play">Play!</button>
            <button @click="mute">Mute me!</button>
        </div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    export default {
        name: 'sound-alert',
        data() {
            return {
                alarm: new Audio("images/assets/sound-alarm.mp3"),
            };
        },
        mounted() {

        },
        created() {

        },
        methods: {
            play () {
                if (!this.alarm.muted){
                    this.alarm.play();
                }
            },

            mute() {
                this.alarm.muted = !this.alarm.muted;
            }
        }
    }
</script>