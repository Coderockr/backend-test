<template>
    <div id="move" v-if="!loading">
        <vs-row>
			<vs-col vs-type="flex" vs-justify="flex-start" vs-w="6">
                <vs-list>
                    <vs-list-item title="Investimento inicial" :subtitle="'R$ ' + formatterCoin(parseFloat(item.value))"></vs-list-item>
                    <vs-list-item title="Redimentos" :subtitle="'R$ ' + formatterCoin(item.gain)"></vs-list-item>
                    <vs-list-item title="Saldo atual" :subtitle="'R$ ' + formatterCoin(item.current_value)"></vs-list-item>
                </vs-list>
            </vs-col>
            <vs-col vs-type="flex" vs-justify="flex-start" vs-w="6">
                <vs-list>
                    <vs-list-item title="Impostos" :subtitle="'R$ ' + formatterCoin(item.tax)"></vs-list-item>
                    <div class="list-titles">
                        <div class="vs-list--title" style="color:var(--success);">Saldo disponível</div>
                        <div class="vs-list--subtitle">{{'R$ ' + formatterCoin(item.balance)}}</div>
                        <vs-button class="mt-2" size="small" @click="$refs.popup.open()">Sacar</vs-button>
                    </div>
                </vs-list>
            </vs-col>
        </vs-row>
        <vs-row style="height:200px;">
            <vx-table
                :data="data" :select="false"
                :columns="columns"
                @scroll="handleScroll">
            </vx-table>
        </vs-row>
        <vx-popup ref="popup" title="Data do saque" :width="30" @input="formValidate">
			<div class="m-4">
				<date-picker
                    v-validate="'required'"
                    name="data"
                    type="date"
                    format="dd/MM/yyyy"
                    v-model="registered_at"
                ></date-picker>
                <span class="text-danger text-sm">{{ errors.first('data') }}</span>
			</div>
		</vx-popup>
    </div>
</template>

<script>
const { formatterCoin, moment  } = require("@/helpers/utils")
export default {
    name:"view-move",
    props:{
        id:{
            type:Number,
			required:true
        }
    },
    data:()=>({
        data:[],
        columns:[],
        item:null,
        form:{
            id:null,
            type:1,
            registered_at:null
        },
		isChecked: false,
        registered_at:'',
        formatterCoin:formatterCoin,
		page_info: {},
        loading:false
    }),
    methods:{
        async getItem() {
            const { data } = await this.$axios.get(`investments/moves/item/${this.id}`)
            if (!data.error) {
                this.item = data
            }
        },

        async getData() {
            this.page_info.page = this.page_info.page || 1
            const { data } = await this.$axios.get("investments/moves", {
                params: {
                    move_id: this.id,
                    page: this.page_info.page,
                }
            })
            if (!data.data.error) {
                if (data.current_page === 1) {
                    this.data = data.data
                } else {
                    this.data = this.data.concat(data.data)
                }
                if (data.last_page == data.current_page) {
                    this.page_info.last_page = true
                }
                this.page_info.current_page = data.current_page
                this.page_info.last_page = data.last_page
                this.page_info.total = data.total
            }
        },

        getColumns() {
            this.columns.push(
                {
                    label: "Código",
                    key: "id",
                    align: "center",
                    sortable: true
                },
                {
                    label: "Operação",
                    key: "type",
                    align: "center",
                    sortable: true,
                    render: (h, params) => {
                        const span =  params.row.type === 0 ? 'Depósito' :
                        params.row.type === 1 ? "Saque" :
                        params.row.type === 2 ? "Rendimento" :
                        params.row.type === 3 ? "Imposto" : ''
                        return h("span",[
                            span
                        ])
                    }
                },
                {
                    label: "Valor",
                    key: "value",
                    align:"right",
                    sortable: true,
                    render: (h, params) => {
                        const color = parseFloat(params.row.value) > 0 ? 'var(--success)' : 'var(--danger)'
                        return h("vs-row",
                        [
                            h("vs-col",
                            {
                                props:{
                                    vsType:"flex",
                                    vsW:"2"
                                }
                            },
                            [
                                'R$'
                            ]),
                            h("vs-col",
                            {
                                style:{
                                    color:color
                                },
                                props:{
                                    vsType:"flex",
                                    vsJustify:"flex-end",
                                    vsW:"10"
                                }
                            },
                            [
                                formatterCoin(parseFloat(params.row.value))
                            ])
                        ])
                    }
                },
                {
                    label: "Data registro",
                    key: "registered_at",
                    sortable: true,
                    align:"center",
                    render: (h, params) => {
                        return h("span",
                            [
                                params.row.registered_at ? moment(params.row.registered_at).format("DD/MM/YYYY") : null
                            ]
                        )
                    }
                }
            )
        },

        /*
        * Reponsável por validar os campos do formulario
        */
        formValidate() {
            let data = {}
            let form = Object.assign({}, this.form)
            Object.keys(form).map(function (key) {
                data[key] = form[key]
            })
            this.$validator.validateAll().then(result => {
                if (result) {
                    this.onSave(data)
                }
                this.$validator.errors.items.forEach(item => {
                    this.$vx.notify({
                        title: "Erro",
                        text: item.msg,
                        color: "danger",
                        fixed: true,
                        icon: "error"
                    })
                })
            })
        },

        /*
        * Reponsável emit o formlario para salvar
        */
        async onSave(form) {
            this.$emit("input", form)
        },

        /**
         * Responsável pela paginação dos tickets quando alcança o fim da página
         */
        handleScroll() {
            if (this.page_info.current_page < this.page_info.last_page) {
                this.page_info.page++
                this.getData()
            }
        }
    },
    watch:{
        loading(value){
            if(value){
                this.$vx.loading({
                    container: this.$refs.content,
                    background: 'transparent',
                    type:'sound'
                })
            }else{
                this.$vx.loading.close(this.$refs.content)
            }
        },
        'registered_at'(value){
            this.isChecked = true
            this.form.id = this.id
            this.form.registered_at = moment(value).format("YYYY-MM-DD")
        },
        isChecked(value) {
            this.$refs.popup.isChecked = value
        }
    },
    async created() {
		this.loading = true
        this.getColumns()
        await this.getItem()
        await this.getData()
		this.loading = false
    },
}
</script>

<style lang="scss">
    #move {
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