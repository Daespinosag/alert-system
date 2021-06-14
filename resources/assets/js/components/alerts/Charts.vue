<template>
    <div>
        <highcharts class="hc" :options="chartOptions" ref="chart"></highcharts>
    </div>
</template>

<script>
    import {Chart} from 'highcharts-vue';
    import Highcharts from 'highcharts';
    import exportingInit from "highcharts/modules/exporting";
    import offlineExporting from "highcharts/modules/offline-exporting";
    import {parse} from 'fecha';

    exportingInit(Highcharts);
    offlineExporting(Highcharts);

    export default {
        name: 'Charts',
        props:['title', 'yAxis', 'limits'],
        data() {
            return {
                myFile: require("../../assets/tracking_test.json"),
                chartOptions: {
                    exporting: {
                        chartOptions: { // specific options for the exported image
                            plotOptions: {
                                series: {
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            }
                        },
                        fallbackToExportServer: false
                    },
                    title: {
                        text: this.title
                    },
                    xAxis: {
                        type: "datetime",
                        labels: {
                            formatter: function () {
                                return Highcharts.dateFormat('%d-%b-%Y',
                                    this.value);
                            }
                        },
                    },
                    yAxis:{
                        title: {
                            text: this.yAxis
                        },
                        plotLines: [{
                            color: 'red',
                            width: 2,
                            value: 9.4,
                        }]
                    },
                    series: [
                        {
                            type: 'area',
                            name: 'Indicator',
                            data: null,
                        },
                        {
                            type: 'column',
                            name: 'Rainfall',
                            data: null,
                        },

                    ],

                }

            }
        },
        components:{
            highcharts: Chart,
        },
        mounted(){
            this.chartOptions.series[0].data = this.getSeriesArray("rainfall");
            this.chartOptions.series[1].data = this.getSeriesArray("indicator_value");
        },
        computed:{


        },
        methods:{
            parseDate(dateString) {
                return parse(dateString, "DD/MM/YYYY HH:mm");
            },
            getSeriesArray(value){
                return this.myFile.map((a) => [
                    this.parseDate(a.date_time_homogenization).getTime(),
                    a[value]
                ])
            },
        }
    }

</script>

<style>

</style>