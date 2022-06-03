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
				<a @click="onDelete" class="ml-2">
					<Icon type="md-trash" size="20" class="danger"/>
				</a>
			</div>
			<template slot="sidebar-body">
				<div class="mt-2">
					<h6>Cargos</h6>
					<ul class="mt-4">
						<li v-for="item in getRoles" :key="item.value" class="mt-1">
							<vs-radio v-model="role" vs-name="role" :vs-value="item.value">{{item.label}}</vs-radio>
						</li>
					</ul>
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
				<form-person
					ref="user"
					:id="id"
					:type="0"
					@change="onChange"
					@input="onSaveRegister"
				></form-person>
			</div>
		</vx-popup>
		<div class="floating-btn">
			<vs-button
				color="primary"
				type="flat"
				icon="add"
				@click.prevent="onNew"
			>Adicionar</vs-button>
		</div>
	</div>
</template>

<script>
	export default {
		name: "user",
		components: {
			FormPerson: () => import("../components/forms/FormPerson")
		},
		data: () => ({
			data: [],
			columns: [],
			selected:[],
			role:null,
			title: "",
			id: null,
			show: false,
			isChecked: false,
			action: null,
			page_info: {},
			loading: false,
			process: false
		}),
		computed: {
			getRoles() {
				let roles = this.$store.getters["roles"].map(item => ({
					value: item.id,
					label: item.name
				}))
				roles.unshift({
					value: 0,
					label: "Todos"
				})
				return roles
			}
		},
		methods: {
			async getData(search = '') {
				this.loading = true
				this.page_info.page = this.page_info.page || 1
				const { data } = await this.$axios.get("people", {
					params: {
						type: [0,3],
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
						label: "Nome",
						key: "name",
						sortable: true,
						render: (h, params) => {
							return h("div",
								{
									class:'flex inline'
								},
								[
									h(
										"div",
										[
											h(
												"a",
												{
													class: "table-edit primary",
													on: {
														click: () => {
															this.onUpdate(params.row.id)
														}
													}
												},
												[
													params.row.name.substr(0, 30), 
													params.row.name.length > 30 ? '...':'',
													h("Icon", {
														props: {
															type: "md-create",
															size: 20
														}
													})
												]
											)
										]
									)
								]
							)
						}
					},
					{
						label: "Cargo",
						key: "role",
						sortable: true,
						render: (h, params) => {
							return h("span", [params.row.role ? params.row.role.name : ""])
						}
					},
					{
						label: "E-mail",
						key: "email",
						sortable: true
					}
				)
			},

			/**
			 * Responsável abrir o Popup para cadastrar um novo registro
			 */
			async onNew() {
				this.action = 0
				this.title = `Novo usuário`
				this.id = null
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
				this.$refs.user.formValidate()
			},

			/**
			 * Responsável por salvar as alterações dos registros
			 */
			async onSaveRegister(form, id) {
				this.process = true
				this.isChecked = false
				try {
					if (!id) {
						await this.$axios.post("people/create", form)
					} else {
						if (form.length) {
							this.$Spin.show()
							for (let index = 0; index < form.length ;index++) {
								await this.$axios.put(`people/update`, form[index])
							}
							this.$Spin.hide()
						} else {
							await this.$axios.put(`people/update`, form)
						}
					}
					this.$vx.notify({
						title: "Sucesso",
						text: "Dados salvos!",
						color: "success",
						time: 5000,
						icon: "check"
					})
					this.page_info.page = 1
					this.show = false
					this.$refs.popup.active = false
					this.getData()
				} catch (error) {
					this.$vx.notify({
						title: "Erro",
						text: error,
						color: "danger",
						fixed: true,
						icon: "error"
					})
				}
				this.process = false
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

			async onDelete(){
				try {
					this.$vx.dialog({
						type:'confirm',
						color: 'danger',
						title: `Exluir registro(s).`,
						text: 'Deseja realmente excluir?',
						acceptText:'Confirmar',
						cancelText:'Cancelar',   
						accept: async () => {
							await this.$axios.put("people/delete", {
								ids:this.selected
							})
							this.$vx.notify({
								title: "Sucesso",
								text: "Dados excluido!",
								color: "success",
								time: 5000,
								icon: "check"
							})
							this.page_info.page = 1
							this.show = false
							this.$refs.popup.active = false
							this.getData()
						}
					})
				} catch (error) {
					this.$vx.notify({
						title: "Erro",
						text: error,
						color: "danger",
						fixed: true,
						icon: "error"
					})
				}
			},

			clearFilters(){
				this.role = null
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