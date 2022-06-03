<template>
  <div>
      view
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
		page_info: {},
        loading:false
    }),
    methods:{
        async getData() {
            this.loading = true
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
				this.loading = false
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
                    label: "Valor inicial",
                    key: "value",
                    align:"right",
                    sortable: true,
                    render: (h, params) => {
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
                },
                {
                    label: "",
                    key: "actions",
                    sortable: true,
                    align:"center",
                    render: (h, params) => {
                        return h("a",
                            {
                                class: "primary",
                                on: {
                                    click: () => {
                                        this.onView(params.row.id)
                                    }
                                }
                            },
                            [
                                h("Icon", {
                                    props: {
                                        type: "md-open",
                                        size: 20
                                    }
                                })
                            ]
                        )
                    }
                }
            )
        }
    },
    async created() {
        console.log("aqui");
        this.getColumns()
        await this.getData()
    },
}
</script>