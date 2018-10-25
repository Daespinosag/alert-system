<style lang="scss">

    div.station-card{
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

        &:hover{
            -webkit-transform: scaleX(1.041) scaleY(1.041);
            transform: scaleX(1.041) scaleY(1.041);
            transition: .2s;
        }
    }
</style>

<template>
    <div class="col-md-5 station-card-container" v-show="show && station.active"><!-- v-show="show" -->
        <router-link :to="{ name: 'station', params: { id: station.id } }" v-on:click.native="panToLocation( station )">
            <div class="station-card">
                <span class="title">{{ station.name }}</span>
                <span class="address">
                    <span class="street">{{ station.city }}</span>
                    <span class="city">{{ station.basin }}</span> <span class="state">{{ station.sub_basin }}</span>
                </span>

                <span v-for="alert in station.alerts" :key="alert.id" class="address">
                   <span>{{ alert.name }}</span> : <span>{{ (alert.value !== null) ? alert.value[alert.code.replace('alert-','')+'_value'] : '-' }}</span> <span class="pull-right alert-state" v-bind:style="{'background-color': getColorAlert((alert.value !== null) ? alert.value.alert : -1)}"></span>
                   <br>
                </span>
            </div>
        </router-link>
    </div>
</template>

<script>
    import { EventBus } from '../../event-bus.js';

    import { StationTextFilter } from '../../mixins/filters/StationTextFilter.js';
    import { StationAlertFilter } from "../../mixins/filters/StationAlertFilter";
    import { StationTypeFilter } from "../../mixins/filters/StationTypeFilter";

    export default {
        mixins: [StationTextFilter,StationAlertFilter,StationTypeFilter],
        props: ['station'],
        data(){
            return {
                show: true
            }
        },
        mounted(){
            EventBus.$on('filters-updated', function( filters ){
                this.processFilters( filters );
            }.bind(this));

        },
        methods: {
            processFilters: function (filters) {
                if (filters.text === null && filters.alert === 'all' && filters.typeStation.length === 0) {
                    this.show = true;
                } else {
                    this.show = (this.processStationTextFilter(this.station, filters.text ) && this.processStationAlertFilter(this.station, filters.alert) && this.processStationTypeFilter(this.station, filters.typeStation));
                }
            },
            panToLocation( station ){

            },
            getColorAlert(value){
                let val = 'gray';
                switch(value) {
                    case 0:
                        val = 'green';
                        break;
                    case 1:
                        val = 'yellow';
                        break;
                    case 2:
                        val = 'orange';
                        break;
                    case 3:
                        val = 'red';
                        break;
                    default:
                        val = 'gray';
                }
                return val;
            }
        }
    }
</script>
