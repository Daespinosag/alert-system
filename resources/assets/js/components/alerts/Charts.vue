<template>
    <div>
        <highcharts class="hc" :options="chartOptions" ref="chart"></highcharts>
    </div>
</template>

<script>
    import {Chart} from 'highcharts-vue';

    export default {
        name: 'Charts',
        props:['title', 'yAxis', 'limits'],
        data() {
            return {
                myFile: require("../../assets/tracking_test.json"),
                chartOptions: {
                    title: {
                        text: this.title
                    },
                    yAxis:{
                        title: {
                            text: this.yAxis
                        }
                    },
                    series: [{
                        name: '',
                        data: this.testSeries,
                       // data: [1, 2, 3] // sample data
                    }]
                }

            }
        },
        components:{
            highcharts: Chart,
        },
        mounted(){
            console.log(this.testSeries);
        },
        computed:{
            testSeries(){
                return this.getSeries();
            }

        },
        methods:{
            getSeries(){
                return this.myFile.map(a => a.rainfall)
            }
        }
    }

</script>

<style>

</style>