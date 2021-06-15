<template>
    <div class="alert-card-container" v-show="show && alert.active">
        <router-link :to="{ name: 'Alert', params: { id: landslidePrimaryStation.id, alert_id: landslidePrimaryStation.alert_id, stationType: `landslide` } }" v-on:click.native="panToLocation( alert )" style="text-decoration: none; color: inherit;">
            <b-card v-bind:class="[this.cardOptions[landslidePrimaryStation.alert_tag]]" class="col-md-5 m-2 border-75 shadow-lg rounded-75" style="height: 150px; border-radius: 10px">
                <b-card-title class="title" style="font-size: 20px;">{{ alert.name }}</b-card-title>

                <b-card-text>
                    <span class="address">
                        <span class="city"> {{ landslidePrimaryStation.city }} </span>
                    </span>
                    <span class="pull-right"><img v-bind:src="`images/assets/alerts/landslide_alert_${this.iconColor}.png` " ></span>
                </b-card-text>

                <b-card-text class="small text-muted">
                    <h6>
                        <span class="address" v-show="landslidePrimaryStation.tracking_values">
                            <span class="badge badge-dark">{{ landslidePrimaryStation.date_time_homogenization }}</span> :
                            <span class="badge badge-dark">{{ landslidePrimaryStation.alert_status }}</span> |
                            <span class="badge badge-dark">{{ landslidePrimaryStation.indicator_value }}</span>
                        </span>
                    </h6>
                </b-card-text>
            </b-card>
        </router-link>
    </div>
</template>

<script>
    import { EventBus } from '../../../event-bus.js';
    import LandslideStation from "../../../store/models/alerts/landslide/landslideStation";
    import { StationTextFilter } from "../../../mixins/filters/StationTextFilter.js";
    import Net from "../../../store/models/alerts/net";

    export default {
        name: "landslide-alert-card",
        props: ['alert'],
        mixins: [ StationTextFilter ],
        data(){
            return {
                show: true,
                iconsOptions:{
                    grey:'error_red',
                    green: 'black',
                    yellow: 'yellow',
                    orange: 'orange',
                    red: 'red'
                },
                cardOptions: {
                    grey: 'alert-card bg-grey',
                    green: 'alert-card bg-success',
                    yellow: 'alert-card bg-warning',
                    red:' alert-card bg-danger',
                    orange:'alert-card bg-orange',
                },
                searchArray: [],
            }
        },
        computed: {
            landslidePrimaryStation(){
                return LandslideStation.query().where('primary',true).where('alert_id', this.alert.id).get()[0]
            },
            net(){
                return Net.query().find(this.landslidePrimaryStation.net_id);
            },
            showFilters(){
                return this.$store.getters.getShowFilters;
            },
            iconColor(){
                return this.iconsOptions[this.landslidePrimaryStation.alert_tag];
            },
        },
        mounted(){
            this.searchArray = [this.landslidePrimaryStation.name, this.net.name, this.landslidePrimaryStation.city];
            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));
        },
        methods: {
            processFilters: function (filters) {
                this.show = this.processStationTextFilter(this.searchArray, filters.text);
            },
            panToLocation( station ){

            },
        },
        watch: {
            landslidePrimaryStation() {
                if (this.landslidePrimaryStation.alert_tag == "red" && this.landslidePrimaryStation.alert_status == "increase") {
                    EventBus.$emit("play-alarm");
                    return true;
                }
            }
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

        &.bg-orange {
            background-color: #ffc4a3 !important;
        }

        &.bg-grey{
            background-color: #d3d3d3 !important;
        }

        &.bg-warning{
            background-color: #ffff7a !important;
        }

        &.bg-success{
            background-color: rgba(66, 203, 152, 0.89) !important;
        }

        &.bg-danger{
            background-color: rgba(239, 83, 80, 0.84) !important;
        }

        &.bg-grey{
            background-color: #d3d3d3;
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