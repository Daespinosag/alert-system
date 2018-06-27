<style lang="scss">
    div#station-page{
        position: absolute;
        right: 30px;
        top: 125px;
        background: #FFFFFF;
        box-shadow: 0 2px 4px 0 rgba(3,27,78,0.10);
        width: 100%;
        max-width: 480px;
        padding: 20px;
        padding-top: 10px;
        img.close-icon{
            float: right;
            cursor: pointer;
            margin-top: 10px;
        }
        h2.station-title{
            color: #342C0C;
            font-size: 36px;
            line-height: 44px;
            font-family: "Lato", sans-serif;
            font-weight: bolder;
        }
        span.location-number{
            display: inline-block;
            color: #8E8E8E;
            font-size: 18px;
            span.location-image-container{
                width: 35px;
                text-align: center;
                display: inline-block;
            }
        }
        label.station-label{
            font-family: "Lato", sans-serif;
            text-transform: uppercase;
            font-weight: bold;
            color: black;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        div.location-type{
            color: white;
            font-family: "Lato", sans-serif;
            font-size: 16px;
            width: 105px;
            height: 45px;
            text-align: center;
            line-height: 45px;
            border-radius: 3px;
            img{
                margin-right: 5px;
            }
            &.roaster{
                background-color: #a6ffa1;
            }
            &.station{
                background-color: #3D281E;
                img{
                    margin-top: -6px;
                }
            }
        }
        div.brew-method{
            font-size: 16px;
            color: #666666;
            font-family: "Lato", sans-serif;
            border-radius: 4px;
            background-color: #F9F9FA;
            width: 150px;
            height: 57px;
            float: left;
            margin-right: 10px;
            margin-bottom: 10px;
            padding: 5px;
            cursor: pointer;
            position: relative;
            div.brew-method-container{
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                img.brew-method-icon{
                    display: inline-block;
                    margin-right: 10px;
                    margin-left: 5px;
                    width: 20px;
                    max-height: 30px;
                }
                span.brew-method-name{
                    display: inline-block;
                    width: calc( 100% - 40px);
                    vertical-align: middle;
                }
            }
        }
        div.address-container{
            color: #666666;
            font-size: 18px;
            line-height: 23px;
            font-family: "Lato", sans-serif;
            margin-bottom: 5px;
            span.address{
                display: block;
            }
            span.city-state{
                display: block;
            }
            span.zip{
                display: block;
            }
        }
        a.station-website{
            font-family: "Lato", sans-serif;
            color: #543729;
            font-size: 18px;
        }
        a.suggest-station-edit{
            font-family: "Lato", sans-serif;
            color: #054E7A;
            font-size: 14px;
            display: inline-block;
            margin-top: 30px;
            text-decoration: underline;
        }
    }
    /* Small only */
    @media screen and (max-width: 39.9375em) {
        div#station-page{
            position: fixed;
            right: 0px;
            left: 0px;
            top: 0px;
            bottom: 0px;
            z-index: 99999;
        }
    }
    /* Medium only */
    @media screen and (min-width: 40em) and (max-width: 63.9375em) {
    }
    /* Large only */
    @media screen and (min-width: 64em) and (max-width: 74.9375em) {
    }
</style>


<template>
    <div id="station-page" v-if="stationLoadStatus === 2">
        <router-link :to="{ name: 'stations' }"> <img class="close-icon" src="images/close-icon.svg"/></router-link>
        <h2 class="station-title">{{ station.name }}</h2>

        <div class="grid-x">
            <div class="large-12 medium-12 small-12 cell">
                <label class="station-label">------</label>
            </div>
        </div>
        <div class="grid-x">
            <div class="large-12 medium-12 small-12 cell">
                <label class="station-label">------</label>
            </div>
        </div>
        <div class="grid-x">
            <div class="large-12 medium-12 small-12 cell">
                <label class="station-label">------------</label>
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from "../event-bus";

    import Loader from '../components/global/Loader.vue';
    import IndividualStationMap from '../components/stations/IndividualStationMap.vue';

    export default {
        components: {IndividualStationMap,Loader},
        created(){
            this.$store.dispatch( 'loadStation', {
                id: this.$route.params.id
            });
        },
        watch: {
            '$route.params.id': function(){
                this.$store.dispatch( 'loadStation', {
                    id: this.$route.params.id
                });
            },
            'stationLoadStatus': function(){
                if( this.stationLoadStatus === 2 ){
                    EventBus.$emit('location-selected', { lat: parseFloat( this.station.latitude ), lng: parseFloat( this.station.longitude ) });
                }
                if( this.stationLoadStatus === 3 ){
                    EventBus.$emit('show-error', { notification: 'No fue posible cargar la estación!'} );
                    this.$router.push({ name: 'stations' });
                }
            }
        },
        computed: {
            stationLoadStatus(){
                return this.$store.getters.getStationLoadStatus;
            },
            station(){
                return this.$store.getters.getStation;
            }
        },
    }
</script>