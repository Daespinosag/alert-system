<template>
    <div class="alert-card-container" v-show="show && alert.active">
        <router-link :to="{ name: 'Alert', params: { id: floodPrimaryStation.id, alert_id: floodPrimaryStation.alert_id, stationType: `flood` } }" v-on:click.native="panToLocation( alert )" style="text-decoration: none; color: inherit;">
            <b-card v-bind:class="[this.cardOptions[floodPrimaryStation.alert_tag]]" class="col-md-5 m-2 border-75 shadow-lg rounded-75" style="height: 150px; border-radius: 10px">
                <b-card-title class="title">{{ alert.name }}</b-card-title>

                <b-card-text>
                    <span class="address">
                        <span class="city"> {{ floodPrimaryStation.city }} </span>
                    </span>
                        <span class="pull-right"><img v-bind:src="`images/assets/alerts/flood_alert_${this.iconColor}.png` " ></span>
                    </b-card-text>

                    <b-card-text class="small text-muted">
                        <h6>
                            <span class="address" v-show="floodPrimaryStation.tracking_values">
                                <span class="badge badge-dark">{{ floodPrimaryStation.date_time_homogenization }}</span> :
                                <span class="badge badge-dark">{{ floodPrimaryStation.alert_status }}</span> |
                                <span class="badge badge-dark">{{ floodPrimaryStation.indicator_value }}</span>
                            </span>
                        </h6>
                </b-card-text>
            </b-card>
        </router-link>
    </div>
</template>

<script>
    import { EventBus } from '../../../event-bus.js';
    import { StationTextFilter } from "../../../mixins/filters/StationTextFilter";
    import FloodStation from '../../../store/models/alerts/flood/floodStation';
    import Net from  "../../../store/models/alerts/net";


    export default {
        name: "flood-alert-card",
        props: ['alert'],
        mixins: [ StationTextFilter ],
        data(){
            return {
                show: true,
                iconsOptions: {green: 'black', grey: 'error_red', red: 'red'},
                cardOptions: {
                    grey: 'alert-card bg-grey',
                    green: 'alert-card bg-success',
                    red:' alert-card bg-danger',
                },
                searchArray: [],
            }
        },
        mounted(){
            this.searchArray = [this.floodPrimaryStation.name, this.net.name, this.floodPrimaryStation.city];
            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));

        },
        created(){

        },
        computed: {
            floodPrimaryStation(){
                return FloodStation.query().where('primary',true).where('alert_id', this.alert.id).with('').first();
            },
            net(){
                return Net.query().find(this.floodPrimaryStation.net_id);
            },
            showFilters(){
                return this.$store.getters.getShowFilters;
            },
            iconColor(){
                return this.iconsOptions[this.floodPrimaryStation.alert_tag];
            },
        },
        watch: {
          floodPrimaryStation() {
              if (this.floodPrimaryStation.alert_tag == "red" && this.floodPrimaryStation.alert_status == "increase") {
                  EventBus.$emit("play-alarm");
                  return true;
              }
          }
        },
        methods: {
            processFilters: function (filters) {
                this.show = this.processStationTextFilter(this.searchArray, filters.text);
            },
            panToLocation( station ){

            },
        }
    }
</script>

<style lang="scss">
    div.alert-card{
        border-radius: 5px;
        box-shadow: 0 2px 2px 0 rgba(0,0,0,0.16), 0 0 0 1px rgba(0,0,0,0.08);
        padding: 15px 5px;
        margin-top: 20px;
        cursor: pointer;
        height: 120px;
        -webkit-transform: scaleX(1) scaleY(1);
        transform: scaleX(1) scaleY(1);
        transition: .2s;

        &.bg-grey{
<<<<<<< HEAD
            background-color: #d2d2d2;
        }

        &.bg-success{
            background-color: rgba(66, 203, 152, 0.89) !important;
        }

        &.bg-danger{
            background-color: rgba(239, 83, 80, 0.84) !important;
=======
            background-color: #d3d3d3;
>>>>>>> e97c2fdf55d9dc054d49cd5fd45b26786e84e7d0
        }

        span.title{
            display: block;
            text-align: center;
            color: #000000;
            font-size: 18px;
            font-weight: bold;
            font-family: 'Lato', sans-serif;
        }

        span.address{
            display: block;
            text-align: center;
            margin-top: 5px;
            color: #3d3d3d;
            font-family: 'Lato', sans-serif;

            span.street{
                font-size: 14px;
                display: block;
            }

            span.city{
                font-size: 14px;
            }

            span.state{
                font-size: 14px;
            }

            span.zip{
                font-size: 14px;
                display: block;
            }
        }

        .alert-state{
            border-radius: 10px;
            width: 20px;
            height: 20px;
        }

        .icon-alert-with-filters{
            height: 2em;
            width: 2em;
        }

        .icon-alert-without-filters{
            height: 3em;
            width: 3em;
        }

        &:hover{
            -webkit-transform: scaleX(1.041) scaleY(1.041);
            transform: scaleX(1.041) scaleY(1.041);
            transition: .2s;
        }
    }
</style>