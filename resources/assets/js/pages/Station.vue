<style lang="scss">

</style>

<template>
    <div id="station" class="page">
        <div class="col-md-12">
                <div class="col-md-4">
                    <loader v-show="stationLoadStatus === 1" :width="100" :height="100"></loader>

                    <div class="station-page" v-show="stationLoadStatus === 2">
                        <h2>{{ station.name }}</h2>
                        <h3 v-if="station.localization !== ''">{{ station.localization }}</h3>

                        <span class="address">
                          {{ station.code }}<br>
                          {{ station.code }}, {{ station.code }}<br>
                          {{ station.code }}
                        </span>

                        <a class="website" v-bind:href="station.code" target="_blank">{{ station.code }}</a>

                        <div class="brew-methods-container">
                            <div class="grid-x grid-padding-x">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <individual-station-map></individual-station-map>
                </div>
        </div>

    </div>
</template>

<script>
    import Loader from '../components/global/Loader.vue';
    import IndividualStationMap from '../components/stations/IndividualStationMap.vue';

    export default {
        components: {IndividualStationMap,Loader},
        created(){
            this.$store.dispatch( 'loadStation', {
                id: this.$route.params.id
            });
        },
        computed: {
            stationLoadStatus() {
                return this.$store.getters.getStationLoadStatus;
            },
            station() {
                return this.$store.getters.getStation;
            }
        }
    }
</script>