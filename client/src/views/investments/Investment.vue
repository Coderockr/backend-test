<template>
	<div>
		<vx-list-view @searching="onSearch">
			<template slot="totalizer">
				<span
					>Exibindo {{ data.length }} de
					{{ page_info.total || 0 }} registros
				</span>
				<badge :count="selected.length" class-name="bg-primary">
					<tooltip placement="bottom" content="Contagem" :delay="300">
						<Icon class="ml-2"  type="md-checkbox-outline"/>
					</tooltip>
				</badge>
			</template>
			<div slot="actions" v-if="selected.length">
				
			</div>
			<template slot="sidebar-body">
				<div class="mt-2">
					
				</div>
			</template>
			<template slot="sidebar-footer">
				<vs-button color="primary" type="flat" icon="filter_list" @click="clearFilters">
					Limpar Filtros
				</vs-button>
			</template>
			<template slot="container-body">
				<vx-table
					:data="data"
					:columns="columns"
					:loading="loading"
					@selected="isSelected" 
					@scroll="handleScroll">
				</vx-table>
			</template>
		</vx-list-view>
		<vx-popup ref="popup" :title="title" :width="80" @input="submit">
			<div v-if="show">
				<div v-if="action === 0">
					<form-move ref="formMove" @input="onSaveRegister" @change="onChange"></form-move>
				</div>
				<div v-if="action === 1">
					<view-move :id="id" @input="onSaveRegister"></view-move>
				</div>
			</div>
		</vx-popup>
		<div class="floating-btn">
			<vs-button
				color="primary"
				type="flat"
				icon="add"
				@click.prevent="onNew"
			>Depositar</vs-button>
		</div>
	</div>
</template>

<script>
	const { formatterCoin, moment  } = require("@/helpers/utils")
	export default {
		name: "investment",
		components: {
			FormMove: () => import("./components/forms/Move"),
			ViewMove: () => import("./components/view/Move")
		},
		data: () => ({
			data: [],
			columns: [],
			selected:[],
			title: "",
			id: null,
			show: false,
			isChecked: false,
			action: null,
			page_info: {},
			loading: false,
			process: false
		}),
		methods: {
			async getData(search = '') {
				this.loading = true
				this.page_info.page = this.page_info.page || 1
				const { data } = await this.$axios.get("investments/moves", {
					params: {
						type:0,
						page: this.page_info.page,
						search: search
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
			},

			/**
			 * Responsável abrir o Popup para cadastrar um novo registro
			 */
			async onNew() {
				this.action = 0
				this.title = `Novo investimento`
				this.show = true
				this.$refs.popup.open()
			},

			/**
			 * Responsável abrir o registro para edição
			 */
			async onUpdate(id) {
				const name = this.data.find(item =>{
					return item.id == id
				}).name
				this.id = id
				this.title = `${id} - ${name}`
				this.show = true
				this.$refs.popup.active = true
			},

			submit() {
				this.$refs.formMove.formValidate()
			},

			/**
			 * Responsável por salvar as alterações dos registros
			 */
			async onSaveRegister(form) {
				this.$Loading.start()
				this.isChecked = false
				const response = await this.$axios.post("investments/moves/create", form)
				.catch((error) => {
					this.$Loading.error()
					this.$vx.notify({
						title: "Erro",
						text: error,
						color: "danger",
						fixed: true,
						icon: "error"
					})
				})
				if (response.data && !response.data.error) {
					this.page_info.page = 1
					this.show = false
					this.$refs.popup.close()
					this.$vx.notify({
						title:response.data.status,
						text:response.data.message,
						color:"success",
						time:5000,
						icon:"check"
					})
					this.getData()
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
			},

			/**
			 * Responsável por capturar alterações nos formularios
			 */
			onChange() {
				this.isChecked = true
			},

			isSelected(value){
				this.selected = value
			},

			onSearch(value) {
				this.getData(value)
			},

			onView(id) {
				this.action = 1
				this.title = `Movimentação investimento #${id}`
				this.id = id
				this.show = true
				this.$refs.popup.open()
			},

			clearFilters(){
				
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
		async created() {
			this.getColumns()
			await this.getData()
		},
		watch: {
			isChecked(value) {
				this.$refs.popup.isChecked = value
			}
		}
	}
</script>