<template>
    <div id="dashboard">
        <vs-row>
            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="12" class="pr-4">
                <vs-card class="mb-6">
                    <div class="vx-card__header">
                        <!-- card title -->
                        <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                            <h4>{{getName}}!</h4>
                        </vs-col>
                        <!-- card subtitle -->
                        <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                            <h6 class="text-grey">Veja aqui suas pendências</h6>
                        </vs-col>
                    </div>
                    <vs-row style="padding-top: 1.05rem;">
                        <vs-col vs-type="flex" vs-justify="start" vs-align="center" vs-lg="6" vs-sm="6">
                            <h3>
                                <a href="#" target="_self">
                                    <font style="vertical-align: inherit;">
                                        <font style="vertical-align: inherit;" class="danger">
                                            4 pendências
                                        </font>
                                    </font>
                                </a>
                            </h3>
                        </vs-col>
                        <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="6">
                            <vs-button class="mt-2" color="primary" type="filled">Ver</vs-button>
                        </vs-col>
                    </vs-row>
                </vs-card>
            </vs-col>
            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="12">
                <vs-card class="mb-6">
                    <div class="vx-card__header">
                        <!-- card title -->
                        <vs-col vs-type="flex" vs-justify="flex-start" vs-w="7" class="vx-card__title">
                            <h4>Estatisticas</h4>
                        </vs-col>
                        <!-- card subtitle -->
                        <vs-col vs-type="flex" vs-justify="flex-end" vs-w="5" class="vx-card__title">
                            <vs-row>
                                <vs-col vs-type="flex" vs-justify="flex-end" vs-align="center" vs-w="12">
                                    <date-picker v-model="statistics_date" type="daterange" format="dd/MM/yyyy" placement="bottom-end" style="width: 200px"></date-picker>
                                </vs-col>
                            </vs-row>
                        </vs-col>
                    </div>
                    <vs-row style="padding-top: 0.7rem;">
                        <vs-col vs-type="flex" vs-justify="start" vs-align="center" vs-lg="4" vs-sm="6">
                             <div class="con-vs-avatar large" style="background: #eeedfd;" >
                                <span translate="translate" class="vs-avatar--text notranslate" style="transform: translate(-50%, -50%) scale(1); color: #7a70da;"> 
                                    <feather-icon icon="TrendingUpIcon" class="mt-1"/>
                                </span>
                            </div>
                            <div class="ml-2">
                                <h4 class="font-weight-bolder mb-0"> {{statistics.proposal}} </h4>
                                <p class="card-text font-small-3 mb-0"> Propostas </p>
                            </div>
                        </vs-col>
                        <vs-col vs-type="flex" vs-justify="start" vs-align="center" vs-lg="4" vs-sm="6">
                             <div class="con-vs-avatar large" style="background: #e2f9f8;" >
                                <span translate="translate" class="vs-avatar--text notranslate" style="transform: translate(-50%, -50%) scale(1); color: #2dc3dc;"> 
                                    <feather-icon icon="CheckIcon" class="mt-1"/>
                                </span>
                            </div>
                            <div class="ml-2">
                                <h4 class="font-weight-bolder mb-0"> {{statistics.finalized}} </h4>
                                <p class="card-text font-small-3 mb-0"> Finalizadas </p>
                            </div>
                        </vs-col>
                        <vs-col vs-type="flex" vs-justify="start" vs-align="center" vs-lg="4" vs-sm="6">
                             <div class="con-vs-avatar large" style="background: #f8eaea;" >
                                <span translate="translate" class="vs-avatar--text notranslate" style="transform: translate(-50%, -50%) scale(1); color: #dc5a58;"> 
                                    <feather-icon icon="AlertTriangleIcon" class="mt-1"/>
                                </span>
                            </div>
                            <div class="ml-2">
                                <h4 class="font-weight-bolder mb-0"> {{statistics.pendent}} </h4>
                                <p class="card-text font-small-3 mb-0"> Pendentes </p>
                            </div>
                        </vs-col>                                                
                    </vs-row>
                </vs-card>
            </vs-col>
            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="4" vs-sm="6" class="pr-4">
                <vs-row>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="12" class="pr-2">
                        <vs-card class="mb-6">
                            <div class="vx-card__header">
                                <!-- card title -->
                                <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                                    <h4>Finalizadas</h4>
                                </vs-col>
                                <!-- card subtitle -->
                                <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                                    <h6 class="text-grey">203</h6>
                                </vs-col>
                            </div>
                            <vs-row>
                                <echart :options="chart_finalized_options" @hover="hover" @out="out" style="height:150px;"></echart>
                            </vs-row>
                        </vs-card>
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="6" vs-sm="12" class="pl-2">
                        <vs-card class="mb-6">
                            <div class="vx-card__header">
                                <!-- card title -->
                                <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                                    <h4>Pendentes</h4>
                                </vs-col>
                                <!-- card subtitle -->
                                <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                                    <h6 class="text-grey">54</h6>
                                </vs-col>
                            </div>
                            <vs-row>
                                <apexchart type="bar" height="138" :options="chart_pending_options" :series="chart_pending_series"></apexchart>
                            </vs-row>
                        </vs-card>
                    </vs-col>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="12" vs-sm="12">
                        <vs-card class="mb-6">
                            <div class="vx-card__header">
                                <!-- card title -->
                                <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                                    <h4>Tempo médio</h4>
                                </vs-col>
                            </div>
                            <vs-row>
                                <apexchart type="line" height="167" style="width:95%" :options="chart_time_options" :series="chart_time_series"></apexchart>
                            </vs-row>
                        </vs-card>
                    </vs-col>
                </vs-row>
            </vs-col>
            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-lg="8" vs-sm="12">
                <vs-card class="mb-6">
                    <div class="vx-card__header">
                        <!-- card title -->
                        <vs-col vs-type="flex" vs-justify="flex-start" vs-w="7" class="vx-card__title">
                            <h4>Relatório de receita</h4>
                        </vs-col>
                    </div>
                    <vs-row style="padding-top: 0.7rem;">
                        <vs-col vs-type="flex" vs-justify="flex-start" vs-align="center" vs-lg="8" vs-sm="12" style="border-right: 0.1px dotted">
                            <apexchart type="line" height="405" style="width:95%" :options="chart_revenue_options" :series="chart_revenue_series"></apexchart>
                        </vs-col>
                        <vs-col vs-type="flex" vs-justify="center" vs-align="top" vs-lg="4" vs-sm="12">
                            <vs-row class="m-16">
                                <vs-col vs-type="flex" vs-justify="center" vs-align="top" vs-w="12">
                                    <i-select v-model="filter.year" clearable style="width:50%">
                                        <Option v-for="item in [{label:'2021', value:2021}]" :value="item.value" :key="`${item.label} ${item.value}`" :label="item.label"></Option>
                                    </i-select>
                                </vs-col>
                                <vs-col vs-type="flex" vs-justify="center" vs-align="top" vs-w="12">
                                    <div class="text-center">
                                        <h3>R$ 250.000,00</h3>
                                        <label>Aprovada: R$ 175.000,00</label>
                                    </div>
                                </vs-col>
                                <vs-col vs-type="flex" vs-justify="center" vs-align="top" vs-w="12">
                                    <apexchart type="line" height="80" style="width:100%" :options="chart_revenue_approved_options" :series="chart_revenue_approved_series"></apexchart>
                                </vs-col>
                                <vs-col vs-type="flex" vs-justify="center" vs-align="top" vs-w="12">
                                    <vs-button class="mt-2" color="danger" type="filled">Reprovada</vs-button>
                                </vs-col>
                            </vs-row>
                        </vs-col>
                    </vs-row>
                </vs-card>
            </vs-col>
            <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="12">
                <vs-card class="mb-6">
                    <div class="vx-card__header">
                        <!-- card title -->
                        <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                            <h4>Fila de tarefas automatizada</h4>
                        </vs-col>
                        <!-- card subtitle -->
                        <vs-col vs-type="flex" vs-justify="flex-start" vs-w="12" class="vx-card__title">
                            <h6 class="text-grey">Movimentações</h6>
                        </vs-col>
                    </div>
                    <vs-row style="height:400px;" class="vx-table vs-con-loading__container">
                        <vx-table
                            :data="data" :select="false" draggable
                            :columns="columns"
                            :loading="loading" @queue="queue">
                        </vx-table>
                    </vs-row>
                </vs-card>
            </vs-col>
        </vs-row>
    </div>
</template>

<script>
import _ from 'lodash';
import { moment } from '../../helpers/utils'
import {mapGetters} from "vuex"
import VueApexCharts from 'vue-apexcharts'
    export default {
        name:"dashboard",
        components: {
            echart: () => import("../../components/vx-charts/VxChart"),
            apexchart: VueApexCharts
        },
        data:()=>({
            data:[],
			columns:[],
            filter:{
                statistics_area:4,
                date_start:null,
                date_end:null,
                year:2021
            },
            statistics_date: [moment().subtract(30, 'days').format("DD/MM/YYYY"), moment().format("DD/MM/YYYY")],
            statistics_area:[
                {
                    code:1,
                    label:"RPA"
                },
                {
                    code:2,
                    label:"Ciclo"
                },
                {
                    code:3,
                    label:"Comitê"
                },
                {
                    code:4,
                    label:"Todos"
                }
            ],
            statistics:{
                finalized: null,
                pendent: null,
                proposal: null
            },
            chart_finalized_options:null,
            chart_pending_options:null,
            chart_pending_series: null,
            chart_revenue_options:null,
            chart_revenue_series:null,
            chart_revenue_approved_options:null,
            chart_revenue_approved_series:null,
            chart_time_options:null,
            chart_time_series:null,
            destroyed:false,
            loading:false
        }),
        computed:{
            ...mapGetters(['currentUser']),
            getName(){
                const first_name = this.currentUser.name.substr(0, this.currentUser.name.indexOf(' '))
                if(first_name){
                    return first_name + this.currentUser.name.substr(first_name.length, first_name.length + this.currentUser.name.indexOf(' '))
                }else{
                    return this.currentUser.name
                }
            }
        },
        methods:{
            async getData() {
				this.loading = true
				const { data } = await this.$axios.get("proposals", {
					params: {
                        query: true
                    }
				})
				if(!data.error) {
					this.data = data.map(item =>{
                        item.title = "Agência "+item.agency+" (codigo "+item.code+")",
                        item.op = "Denodo",
						item.created_at = moment.utc(item.created_at).utcOffset("-04:00").format("DD/MM/YYYY HH:mm")
                        item.user = "RPA"
                        return item
                    })
                    this.loop()
				}
				this.loading = false
			},

            async getStatistics() {
				this.loading = true
				const { data } = await this.$axios.get("system/statistics", {
					params: this.filter
				})
				if(!data.error) {
					this.statistics = data
				}
				this.loading = false
			},

            getChartData(){
                this.chart_finalized_options = {
                    series: [
                        {
                            name: 'Finalizadas',
                            type: 'pie',
                            radius: ['50%', '70%'],
                            avoidLabelOverlap: false,
                            label: {
                                normal: {
                                    show: false,
                                    position: 'center'
                                },
                                emphasis: {
                                    show: true,
                                    textStyle: {
                                        fontSize: 11,
                                        fontWeight: 'bold'
                                    },
                                    formatter: (param) => (param.name+'\n'+param.value+'\n('+param.percent+'%)').replace('.',',')
                                }
                            },
                            labelLine: {
                                normal: {
                                    show: false
                                }
                            },
                            color:["#4ea397","#EA5455"],
                            data: [
                                {value: 173, name: 'Aprovadas'},
                                {value: 30, name: 'Reprovadas'},
                            ]
                        },
                        {
                            type:'pie',
                            radius: ['0%'],
                            color:['black'],
                            label: {
                                position: 'center',
                                show: true,
                                fontSize:18,
                                fontWeight: 'bolder',
                                formatter: (params) => params.value || '',
                            },
                            data:[
                                {value:203},
                            ]
                        }
                    ]
                }
                this.chart_pending_options = {
                    chart: {
                        type: 'bar',
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                        }
                    },
                    grid:{
                        show:false
                    },
                    dataLabels: {
                        enabled: true
                    },
                    xaxis: {
                        categories: ['RPA', 'Agência', 'Ciclo', 'Comitê'],
                        labels:{
                            show:false
                        }
                    }
                }
                this.chart_pending_series = [
                    {
                        name:'Pendentes',
                        data: [
                            {
                                x: 'RPA',
                                y:2,
                                fillColor: '#EB8C87'
                            },
                            {
                                x: 'Agência',
                                y:5,
                                fillColor: '#FFCD00'
                            },
                            {
                                x: 'Ciclo',
                                y:27,
                                fillColor: '#64C832'
                            },
                            {
                                x: 'Comitê',
                                y:20,
                                fillColor: '#146E37'
                            }
                        ]
                    }
                ]
                this.chart_revenue_options =  {
                    chart: {
                        type: 'line',
                        dropShadow: {
                            enabled: true,
                            color: '#000',
                            top: 18,
                            left: 7,
                            blur: 10,
                            opacity: 0.2
                        },
                        toolbar: {
                            show: false
                        }
                    },
                    colors: ["#4ea397","#EA5455"],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    grid: {
                        borderColor: '#e7e7e7',
                        row: {
                            colors: ['#f3f3f3', 'transparent'],
                            opacity: 0.5
                        },
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
                    },
                    yaxis: {
                        title: {
                            text: 'Propostas (R$)'
                        }
                    },
                    legend: {
                        show:false
                    }
                }
                this.chart_revenue_series = [
                    {
                        name: "Aprovada - 2021",
                        data: [28, 29, 33, 36, 32, 32, 33]
                    },
                    {
                        name: "Reprovada - 2021",
                        data: [12, 11, 14, 18, 17, 13, 13]
                    }
                ]
                this.chart_revenue_approved_options =  {
                    chart: {
                        type: 'line',
                        zoom: {
                            enabled: false
                        },
                        toolbar:{
                            show:false
                        }
                    },
                    colors: ["#4ea397"],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    grid: {
                        show:false
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                        labels:{
                            show:false
                        }
                    },
                    yaxis:{
                        labels:{
                            show:false
                        }
                    }
                }
                this.chart_revenue_approved_series = [
                    {
                        name: "Aprovada - 2021",
                        data: [28, 29, 33, 36, 32, 32, 33]
                    }
                ]
                this.chart_time_options =  {
                    chart: {
                        type: 'line',
                        dropShadow: {
                            enabled: true,
                            color: '#000',
                            top: 18,
                            left: 7,
                            blur: 10,
                            opacity: 0.2
                        },
                        toolbar: {
                            show: false
                        }
                    },
                    colors: ['#EB8C87', '#FFCD00' , '#64C832', '#146E37'],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: 'smooth'
                    },
                    grid: {
                        borderColor: '#e7e7e7',
                        row: {
                            colors: ['#f3f3f3', 'transparent'],
                            opacity: 0.5
                        }
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']
                    },
                    yaxis: {
                        title: {
                            text: 'Tempo médio (m)'
                        }
                    },
                    legend: {
                        show:false
                    }
                }
                this.chart_time_series = [
                    {
                        name: "RPA - 2021",
                        data: [10, 11, 8, 9, 11, 14, 13]
                    },
                    {
                        name: "Agência - 2021",
                        data: [1440, 1400, 1640, 2440, 940, 1400, 1840]
                    },
                    {
                        name: "Ciclo - 2021",
                        data: [240, 440, 200, 640, 540, 300, 260]
                    },
                    {
                        name: "Comitê - 2021",
                        data: [140, 340, 100, 340, 240, 150, 360]
                    }
                ]
            },

            getColumns() {
				this.columns.push(
					{
						label: "Código",
						key: "id",
						sortable: true
					},
					{
						label: "Titulo",
						key: "title",
						sortable: true
					},
                    {
						label: "Data",
						key: "created_at",
						sortable: true,
					},
                    {
						label: "Operação",
						key: "op",
						sortable: true
					},
                    {
						label: "Usuário",
						key: "user",
						sortable: true
					},
				)
			},

            async queue(data){
                if(data.length){
                    let ids = []
                    data.forEach(item => {
                        ids.push(item.id)
                    })
                    this.$Loading.start()
                    const response = await this.$axios.put('proposals/queue', {ids})
                    .catch((error) => {
                        this.$Loading.error()
                        this.$vx.notify({
                            title: "Erro",
                            text: error,
                            color: "danger",
                            fixed: true,
                            icon: "error"
                        })
                        this.$router.push("/error-500")
                    })
                    if (response.data && !response.data.error) {
                        this.$vx.notify({
                            title:response.data.status,
                            text:response.data.message,
                            color:"success",
                            time:5000,
                            icon:"check"
                        })
                        this.$Loading.finish()
                    } else {
                        this.$Loading.error()
                        this.$vx.notify({
                            title:response.data.status,
                            text:response.data.error,
                            color:"danger",
                            time:5000,
                            fixed: true,
                            icon:"error"
                        })
                    }
                }
            },

            hover(params) {
                if(params.seriesIndex == 0 ){
                    if(params.seriesName === "Finalizadas"){
                        this.chart_finalized_options.series[1].data[0].value = ''
                    }
                }
            },

            out() {
                this.chart_finalized_options.series[1].data[0].value = 203
            },

            loop:_.debounce(function () {
                if(!this.destroyed){
                    this.getData()
                }
            }, 120000)
        },
        watch:{
            statistics_date(value){
                this.filter.date_start = value[0]
                this.filter.date_end = value[1]
                this.getStatistics()
            }
        },
        created(){
            this.getChartData()
            this.getColumns()
            this.getData()
        },
        destroyed(){
            this.destroyed = true
        }
    }
</script>

<style lang="scss">
    #dashboard {
		#data-list {
			.vs-con-table{
				.vs-con-tbody{
					background:rgb(255, 255, 255);
				}
				.not-data-table{
					background: #fff;
				}
			}
		}
    }
</style>