<template>
    <div class="col-md-5 alert-card-container" v-show="show && alert.active">
        <router-link :to="{ name: 'Alert', params: { id: alert.id } }" v-on:click.native="panToLocation( alert )">
            <div v-bind:class="[floodPrimaryStation.alert_tag !== 'green' ? 'alert-card bg-danger' : 'alert-card bg-success']">
                <span class="title">{{ alert.name }}</span>
                <span class="address">
                    <span class="street">{{ floodPrimaryStation.city }}</span>
                    <span class="city"> {{ floodPrimaryStation.city }} </span>
                    <span class="state"> {{ floodPrimaryStation.city }}</span>
                    <span><img v-bind:src="`images/assets/alerts/flood_alert_${this.iconColor}.png` " ></span>


                </span>

                <span class="address" v-show="floodPrimaryStation.tracking_values">
                   <span>{{ floodPrimaryStation.date_time_homogenization }}</span> :
                    <span>{{ floodPrimaryStation.alert_status }}</span> |
                    <span>{{ floodPrimaryStation.indicator_value }}</span>
<!--                    <span class="pull-right">-->
<!--                        <img v-bind:class="[ this.showFilters ? 'icon-alert-with-filters' : 'icon-alert-without-filters']" :src="`images/assets/alerts/flood_alert_${this.iconColor}.png`" >-->
<!--                    </span>-->
                   <br>
                </span>
            </div>
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
                iconColor: 'black',
                iconsOptions: {green: 'black', grey: 'black', red: 'red'},
                searchArray: [],
            }
        },
        mounted(){
            this.searchArray = [this.floodPrimaryStation.name, this.net.name, this.floodPrimaryStation.city];
            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));
            this.iconColor = this.iconsOptions[this.floodPrimaryStation.alert_tag];
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