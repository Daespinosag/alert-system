<template>
    <div>
        <highcharts class="hc" :options="chartOptions" ref="chart"></highcharts>
    </div>
</template>

<script>
    import {Chart} from 'highcharts-vue';

    export default {
        name: 'Charts',
        data() {
            return {
                chartOptions: {
                    series: [{
                        data: [1, 2, 3] // sample data
                    }]
                }

            }
        },
        components:{
            highcharts: Chart,
        }
    }

</script>

<style>

</style>