<template>
    <EChart
            ref="chart"
            theme="ovilia-green"
            autoresize
            v-bind="chartOptions"
            @click="select"
            @mouseover="hover"
            @mouseout="out"
    />
</template>

<script>
import 'echarts/lib/chart/bar'
import 'echarts/lib/chart/line'
import 'echarts/lib/chart/pie'
import 'echarts/lib/component/tooltip'
import 'echarts/lib/component/legend'
import 'echarts/lib/component/title'

//custom theme
import ECharts from 'vue-echarts'
import theme from './theme.json'
ECharts.registerTheme('ovilia-green', theme)
export default {
    name: 'vxChart',
    data () {
        return {
            selected: {
                seriesIndex: -1,
                dataIndex: -1
            },
            chartOptions: {}
        }
    },
    props: {
        options: Object,
        theme: [String, Object],
        initOptions: Object,
        group: String,
        autoresize: Boolean,
        watchShallow: Boolean,
        manualUpdate: Boolean
    },
    components: {
        EChart: ECharts,
    },

    methods: {
        select (params) {
            let clearHighlight = false
            this.chartOptions.options.series.forEach((serie, serieIndex) => {
                serie.data = serie.data.map((value, valueIndex) => {
                    if (this.selected.seriesIndex === params.seriesIndex && this.selected.dataIndex === params.dataIndex) { //unhighlight
                        clearHighlight = true
                        value.itemStyle.opacity = 1
                    }
                    if (valueIndex == params.dataIndex && params.seriesIndex == serieIndex) { //highlight
                        value.itemStyle.opacity = 1
                    }

                    if (!(this.selected.seriesIndex === params.seriesIndex && this.selected.dataIndex === params.dataIndex) && !(valueIndex == params.dataIndex && params.seriesIndex == serieIndex)){
                        value.itemStyle.opacity = 0.5
                    }
                    return value
                })
            })
            if (clearHighlight) {
                this.selected.seriesIndex = -1
                this.selected.dataIndex = -1
            } else {
                this.selected.seriesIndex = params.seriesIndex
                this.selected.dataIndex = params.dataIndex
            }
            params.selected = clearHighlight
            this.$emit("click", params)
        },

        hover(params){
            this.$emit("hover", params)
        },

        out(params){
            this.$emit("out", params)
        },

        defaultingData () {
            if (this.chartOptions) {
                if (this.chartOptions.options.series) {
                    this.chartOptions.options.series.map(serie => {
                        serie.data = serie.data.map(value => value = (!isNaN(value) ? {value: value, itemStyle: {opacity:1}} : {...value, value: value.value, itemStyle: {opacity:1}}))
                    })
                }
            }
        }
    },

    created() {
        //Defaulting values
        this.chartOptions =  this.$props
        this.defaultingData()
    },

    watch: {
        "options.series": {
            handler () {
                this.chartOptions =  this.$props
                this.defaultingData()
            },
        }
    },
}

</script>

<style>

</style>
