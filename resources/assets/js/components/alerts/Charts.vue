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
    Highcharts.setOptions({
        global: {
            timezone: "America/Bogota",
        }
    });

    export default {
        name: 'Charts',
        props:['title', 'yAxis', 'limits', 'seriesData'],
        data() {
            return {
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
                        text: this.title,
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
                            text: "mm"
                        },
                        plotLines: this.limits
                    },
                    series: [
                        {
                            type: 'area',
                            name: `${this.yAxis[1]}`,
                            data: null,
                        },
                        {
                            type: 'column',
                            name: `${this.yAxis[0]}`,
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
            this.chartOptions.series[0].data = this.getSeriesArray("indicator_value");
            this.chartOptions.series[1].data = this.getSeriesArray("rainfall");
        },
        computed:{


        },
        methods:{
            parseDate(dateString) {
                return parse(dateString, "YYYY-MM-DD HH:mm:ss");
            },
            getSeriesArray(value){
                return this.seriesData.map((a) => [
                    this.parseDate(a.date_time_homogenization).getTime(),
                    parseFloat(a[value])
                ])
            },
        }
    }

</script>

<style>

</style>