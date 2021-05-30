<template>
    <div class="col-md-5 alert-card-container" v-show="show && alert.active"><!-- v-show="show" -->
        <router-link :to="{ name: 'Alert', params: { id: alert.id } }" v-on:click.native="panToLocation( alert )">
            <div v-bind:class="[this.cardOptions[landslidePrimaryStation.alert_tag]]">
                <span class="title">{{ alert.name }}</span>
                <span class="address">
                    <span class="street"> Alert City </span>
                    <span class="city">  More information </span> <span class="state"> more information</span>

                </span>
                <span class="pull-right"><img v-bind:src="`images/assets/alerts/landslide_alert_${this.iconColor}.png` " ></span>
                <!--<span v-for="alert in station.alerts" :key="alert.id" class="address">
                   <span>{{ alert.name }}</span> : <span>{{ (alert.value !== null) ? alert.value[alert.code.replace('alert-','')+'_value'] : '-' }}</span> <span class="pull-right alert-state" v-bind:style="{'background-color': getColorAlert((alert.value !== null) ? alert.value.alert : -1)}"></span>
                   <br>
                </span>-->
            </div>
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
                iconColor: 'black',
                iconsOptions:{
                    grey:'error',
                    green: 'black',
                    yellow: 'yellow',
                    orange: 'orange',
                    red: 'red'
                },
                cardOptions: {
                    grey: 'alert-card bg-success',
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
            }
        },
        mounted(){
            this.searchArray = [this.landslidePrimaryStation.name, this.net.name, this.landslidePrimaryStation.city];
            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));
            this.iconColor = this.iconsOptions[this.landslidePrimaryStation.alert_tag];
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

        &.bg-orange{
            background-color: #ffc4a3;
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