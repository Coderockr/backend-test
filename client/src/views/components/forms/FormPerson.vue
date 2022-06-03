<template>
	<div ref="content">
		<i-form v-if="!loading">
			<vs-row>
				<Collapse v-model="panel" accordion simple>
					<Panel name="1">
						Dados básicos
						<div slot="content">
							<vs-row>
								<vs-col vs-type="flex" vs-justify="center" vs-align="center">
									<ul class="con-s">
										<li>
											<label>{{
												form.person == false ? "Pessoa" : "Empresa"
											}}</label>
											<vs-switch
												v-model="form.person"
												vs-icon-off="person"
												vs-icon-on="store"
											/>
										</li>
									</ul>
								</vs-col>
							</vs-row>
							<vs-row v-if="!form.person">
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
								>
									<form-item :error="errors.first('nome')" label="Nome">
										<i-input
											clearable
											prefix="md-person"
											name="nome"
											v-validate="'required|min:3'"
											v-model="form.name"
										></i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="3"
									vs-sm="6"
									vs-xs="12"
								>
									<form-item label="Nascimento">
										<date-picker
											type="date"
											format="dd/MM/yyyy"
											v-model="form.date_birth"
										></date-picker>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="3"
									vs-sm="6"
									vs-xs="12"
								>
									<form-item label="Genero">
										<i-select v-model="form.gender" clearable>
											<Option v-for="item in options_gender" :value="item.value" :key="`${item.label} ${item.value}`" :label="item.label"></Option>
										</i-select>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12">
									<form-item :error="errors.first('cpf')" label="CPF">
										<i-input
											clearable
											prefix="ios-card"
											name="cpf"
											v-mask="['###.###.###-##']"
											v-validate="'cpf'"
											v-model="form.cpf_cnpj">
										</i-input>
									</form-item>
								</vs-col>
								<vs-col
									v-if="type == 0"
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12">
									<form-item :error="errors.first('cargo')">
										<a @click="openPopup('Cargos', 0)">
											Cargo
											<feather-icon icon="EditIcon" class="breadcrumb-item w-4" />
										</a>
										<i-select
											filterable
											clearable
											v-validate="'required'"
											name="cargo"
											v-model="form.role_id">
											<Option
												v-show="item.active"
												v-for="(item,index) in roles_table"
												:value="item.id"
												:key="index"
											>{{ item.name_temp }}</Option>
										</i-select>
									</form-item>
								</vs-col>
							</vs-row>
							<vs-row v-if="form.person">
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12">
									<form-item :error="errors.first('nome')" label="Nome">
										<i-input
											clearable
											prefix="md-person"
											name="nome"
											v-validate="'required|min:3'"
											v-model="form.name">
										</i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12">
									<form-item
										:error="errors.first('razão social')"
										label="Razão social">
										<i-input
											clearable
											prefix="md-person"
											name="razão social"
											v-validate="'min:3'"
											v-model="form.reason_social">
										</i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12">
									<form-item :error="errors.first('cnpj')" label="CNPJ">
										<i-input
											clearable
											prefix="ios-card"
											name="cnpj"
											v-mask="['##.###.###/####-##']"
											v-validate="'cnpj'"
											v-model="form.cpf_cnpj">
										</i-input>
									</form-item>
								</vs-col>
								<vs-col
									v-if="type == 0"
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="3"
									vs-sm="6"
									vs-xs="12">
									<form-item :error="errors.first('cargo')">
										<a @click="openPopup('Cargos', 0)">
											Cargo
											<feather-icon icon="EditIcon" class="breadcrumb-item w-4" />
										</a>
										<i-select
											filterable
											clearable
											name="cargo"
											v-validate="'required'"
											v-model="form.role_id">
											<Option
												v-show="item.active"
												v-for="(item,index) in roles_table"
												:value="item.id"
												:key="index"
											>{{ item.name_temp }}</Option>
										</i-select>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="3"
									vs-sm="6"
									vs-xs="12">
									<form-item label="Limite de crédito">
										<i-input
											prefix="logo-usd"
											v-money="'money'"
											v-model="form.limit">
										</i-input>
									</form-item>
								</vs-col>
							</vs-row>
							<vs-row v-if="type == 0">
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12">
									<form-item :error="errors.first('senha')" label="Senha">
										<i-input
											prefix="md-key"
											type="password"
											password
											name="senha"
											v-validate="id == 0 ? 'required|min:6' : 'min:6'"
											ref="senha"
											v-model="form.password">
										</i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12">
									<form-item
										:error="errors.first('confirmar senha')"
										label="Confirmar Senha">
										<i-input
											prefix="md-key"
											type="password"
											password
											name="confirmar senha"
											v-validate="
												id == 0
													? 'required|min:6|confirmed:senha'
													: 'min:6|confirmed:senha'
											"
											v-model="confirm_password">
										</i-input>
									</form-item>
								</vs-col>
							</vs-row>
						</div>
					</Panel>
					<Panel name="2">
						Dados de contato
						<div slot="content">
							<vs-row>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
								>
									<form-item :error="errors.first('email')" label="E-mail">
										<i-input
											clearable
											prefix="ios-mail"
											name="email"
											v-validate="'required|email'"
											v-model="form.email"
										></i-input>
									</form-item>
								</vs-col>
								<vs-col
									v-for="(item, index) in form.mobile_phone"
									:key="`mobile ${index}`"
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
									:vs-offset="index > 0 ? '6' :''"
								>
									<form-item
										:error="errors.first(`${'celular ' + index}`)"
										label="Celular"
									>
										<i-input
											@input="handleRemove(index, 0)"
											v-mask="['+55 (##) #####-####']"
											v-validate="{
												regex: /\([1-9][0-9]\)\s[9-9]\d{4}\-\d{4}/
											}"
											:name="'celular ' + index"
											v-model="item.number"
										>
											<vs-icon
												icon-pack="fas"
												icon="fa-mobile-alt"
												slot="prefix"
											></vs-icon>
											<a @click="handleAdd(0)" slot="suffix">
												<vs-icon
													v-if="
														index == form.mobile_phone.length - 1 &&
															errors.first(`${'celular ' + index}`) == null &&
															item.number !=''
													"
													icon="add"
												></vs-icon>
											</a>
										</i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
								>
									<form-item :error="errors.first('whatsapp')" label="Whatsapp">
										<i-input
											clearable
											v-mask="['+55 (##) #####-####']"
											v-validate="{
												regex: /\([1-9][0-9]\)\s[9-9]\d{4}\-\d{4}/
											}"
											name="whatsapp"
											v-model="form.whatsapp"
										>
											<vs-icon
												icon-pack="fab"
												icon="fa-whatsapp"
												slot="prefix"
											></vs-icon>
										</i-input>
									</form-item>
								</vs-col>
								<vs-col
									v-for="(item, index) in form.phone"
									:key="`phone ${index}`"
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
									:vs-offset="index > 0 ? '6' :''"
								>
									<form-item
										:error="errors.first(`${'telefone ' + index}`)"
										label="Telefone"
									>
										<i-input
											@input="handleRemove(index, 1)"
											v-mask="['+55 (##) ####-####']"
											v-validate="{ regex: /\([1-9][0-9]\)\s\d{4}\-\d{4}/ }"
											:name="'telefone ' + index"
											v-model="item.number"
										>
											<vs-icon
												icon-pack="fas"
												icon="fa-phone-square-alt"
												slot="prefix"
											></vs-icon>
											<a @click="handleAdd(1)" slot="suffix">
												<vs-icon
													v-if="
														index == form.phone.length - 1 &&
															errors.first(`${'telefone ' + index}`) == null &&
															item.number !=''
													"
													icon="add"
												></vs-icon>
											</a>
										</i-input>
									</form-item>
								</vs-col>
							</vs-row>
						</div>
					</Panel>
					<Panel name="3">
						Endereço
						<div slot="content">
							<vs-row>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="4"
									vs-sm="12"
								>
									<form-item :error="errors.first('cep')" label="Cep">
										<i-input
											clearable
											prefix="ios-search"
											name="cep"
											v-mask="['#####-###']"
											v-validate="{ regex: /^[0-9]{5}-[0-9]{3}/ }"
											v-model="form.address.cep"
											@input="searchCep"
										></i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
								>
									<form-item :error="errors.first('rua')" label="Rua">
										<i-input
											clearable
											prefix="ios-document"
											name="rua"
											v-validate="'min:6'"
											v-model="form.address.street"
										></i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="2"
									vs-sm="12"
								>
									<form-item :error="errors.first('numero')" label="Numero">
										<i-input
											clearable
											prefix="ios-document"
											name="numero"
											v-validate="'min:2'"
											v-model="form.address.number"
										></i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
								>
									<form-item
										:error="errors.first('complemento')"
										label="Complemento"
									>
										<i-input
											clearable
											prefix="ios-document"
											name="complemento"
											v-validate="'min:2'"
											v-model="form.address.complement"
										></i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
								>
									<form-item :error="errors.first('bairro')" label="Bairro">
										<i-input
											clearable
											prefix="ios-document"
											name="bairro"
											v-validate="'min:2'"
											v-model="form.address.district"
										></i-input>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
								>
									<form-item :error="errors.first('estado')" label="Estado">
										<i-select
											clearable
											filterable
											v-model="form.address.uf"
											@on-change="searchCities"
										>
											<Option
												v-for="item in ufs"
												:value="item.id"
												:key="`${item.nome} ${item.id}`"
												>{{ item.nome }}</Option
											>
										</i-select>
									</form-item>
								</vs-col>
								<vs-col
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="6"
									vs-sm="12"
								>
									<form-item :error="errors.first('cidade')" label="Cidade">
										<i-select clearable filterable v-model="form.address.city">
											<Option
												v-for="item in cities"
												:value="item.id"
												:key="`${item.nome} ${item.id}`"
												>{{ item.nome }}</Option
											>
										</i-select>
									</form-item>
								</vs-col>
							</vs-row>
						</div>
					</Panel>
					<Panel name="5">
						Outras informações
						<div slot="content">
							<vs-row>
								<vs-col vs-type="flex" vs-justify="flex-start">
									<form-item :error="errors.first('note')">
										<label class="ivu-form-item-label">Observações</label>
										<br />
										<vue-editor
											:editorToolbar="editor"
											v-model="form.note"
											placeholder="Descreva..."
										></vue-editor>
									</form-item>
								</vs-col>
								<vs-col
									v-if="type == 1"
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="4"
									vs-sm="12"
								>
									<form-item label="Desconto">
										<i-input
											clearable
											v-money="'money'"
											v-model="form.discount"
										>
											<vs-icon
												icon-pack="fas"
												icon="fa-percentage"
												slot="prefix"
											/>
										</i-input>
									</form-item>
								</vs-col>
								<vs-col
									v-if="type == 1"
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="4"
									vs-sm="12"
								>
									<form-item label="ISS Retido">
										<i-select clearable v-model="form.iss_withheld">
											<Option
												v-for="item in [
													{ label: 'Sim', value: 1 },
													{ label: 'Não', value: 0 }
												]"
												:value="item.value"
												:key="`${item.label} ${item.value}`"
												>{{ item.label }}</Option
											>
										</i-select>
									</form-item>
								</vs-col>
								<vs-col
									v-if="type == 1"
									vs-type="flex"
									vs-justify="flex-start"
									vs-lg="4"
									vs-sm="12"
								>
									<form-item label="Inscrição municipal">
										<i-input
											clearable
											prefix="ios-document"
											v-model="form.town_register"
										></i-input>
									</form-item>
								</vs-col>
							</vs-row>
						</div>
					</Panel>
				</Collapse>
			</vs-row>
		</i-form>
		<vx-upload
			:limit="1"
			text="Upload arquivo"
			accept="image/*"
			:showUploadButton="false"
			@add="function(value){
				form.photo = value[0].src
			}"
			@update:vsFile="teste"
		/>
		<vx-popup ref="popup" :title="popup.title" :width="65">
			<!-- Cargo -->
			<div v-if="popup.value == 0">
				<div class="flex justify-center">
				<table class="table-auto">
					<tbody>
					<tr v-for="(item,index) in roles_table" :key="index">
						<td class="px-4 py-2" style="min-width:220px">
						<a
							v-if="!item.checkout"
							@click="function(){
												item.checkout = true   
											}"
							:class="item.active?'':'danger'"
						>{{item.name}}</a>
						<i-input v-else clearable prefix="md-menu" v-model="roles_table[index].name"></i-input>
						</td>
						<td class="px-4 py-2">
							<a
								v-if="item.active && !item.checkout"
								@click="onOffActivate('roles_table', index)"
								class="danger"
							>
								<Icon type="md-trash" size="20" />
							</a>
							<a
								v-else-if="!item.active && !item.checkout"
								@click="onOffActivate('roles_table', index)"
							>
								<Icon type="md-refresh" size="20" />
							</a>
							<div v-if="item.checkout">
								<a class="primary" @click="onSaveRegister('roles_table', item, index)">
								<Icon type="md-checkmark" size="20" />
								</a>
								<a
								class="danger"
								@click="function(){
														item.checkout = false 
														if(item.name=='' || item.name_temp==''){
															if(item.id == 0){
																removeListItem('roles_table', index)
															}
														}
														item.name = item.name_temp
													}"
								>
								<Icon type="md-close" size="20" />
								</a>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
				</div>
				<div class="flex flex-row-reverse">
				<div class="text-center px-4 py-2 m-2">
					<vs-button
						color="success"
						type="flat"
						icon="add"
						@click.prevent="addListItem('roles_table')">
						Adicionar
					</vs-button>
				</div>
				</div>
			</div>
		</vx-popup>
	</div>
</template>

<script>
	const { queryCep, editorToolbar, moment } = require("@/helpers/utils")
	import { mapGetters, mapActions } from "vuex"
	export default {
		name: "form-person",
		props: {
			id: {
				type:Number,
				default:null
			},
			type: Number
		},
		data: () => ({
			panel: '1',
			editor:editorToolbar,
			popup:{
				title:'',
				value:''
			},
			form:{
				company_id:null,
				type: null,
				person: false,
				name:'',
				photo: null,
				reason_social:'',
				limit:'',
				cpf_cnpj:'',
				date_birth:'',
				gender: 0,
				sale_commission:'',
				role_id:'',
				max_sale_discount:'',
				email:'',
				mobile_phone: [
					{
						number:''
					}
				],
				whatsapp:'',
				phone: [
					{
						number:''
					}
				],
				address: {
					cep:'',
					street:'',
					number:'',
					complement:'',
					district:'',
					city:'',
					uf:'',
				},
				crc_counter:'',
				cnpj_office:'',
				type_id: null,
				note:'',
				discount:'',
				iss_withheld:'',
				state_register:'',
				town_register:'',
				password:''
			},
			roles_table:[],
			options_gender:[
				{
					label: "Não informar",
					value: 0
				},
				{
					label: "Masculino",
					value: 1
				},
				{
					label: "Feminino",
					value: 2
				}
			],
			confirm_password:'',
			ufs:[],
			cities:[],
			changes:0,
			loading: false,
		}),
		computed: {
			...mapGetters(['roles', 'currentUser'])
		},
		methods: {
			...mapActions(['updateRoles']),

			success(response) {
				this.form.files = JSON.parse(response.currentTarget.response).data
			},

			error(response) {
				const error = JSON.parse(response.currentTarget.response)
				this.$vx.notify({
					title: "Erro",
					text: error,
					color: "danger",
					fixed: true,
					icon: "error"
				})
			},

			async getFile(){
				const { data } = await this.$axios.get("files/view", {
					params: {
						url:"files/2022030808271462274ba284fa5"
					}
				})
				this.file = `data:image/*;base64, ${btoa(encodeURI(data))}`;
			},

			/**
			 * Responsável abrir popup
			 */
			openPopup(title, value) {
				this.$refs.popup.active = true
				this.popup.title = title
				this.popup.value = value
			},

			/*
			 * Reponsável por validar os campos do formulario
			 */
			formValidate() {
				let data = {}
				let form = Object.assign({}, this.form)
				if (form.date_birth != "Invalid date" && form.date_birth !='') {
					form.date_birth = moment(form.date_birth).format("YYYY-MM-DD")
				} else {
					form.date_birth = null
				}
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
				this.$emit("input", form, this.id)
			},

			/*
			 * Reponsável preencher os dados de endereço pelo do cep
			 */
			async searchCep() {
				if (this.form.address.cep.length == 9) {
					this.loading = true
					try {
						const data = await queryCep(this.form.address.cep)
						const uf = this.ufs.filter(item =>{
							return item.sigla == data.uf
						})[0].id
						await this.searchCities(uf)
						const city = this.cities.filter(item =>{
							return item.nome == data.localidade
						})[0].id
						this.form.address.street = data.logradouro
						this.form.address.district = data.bairro
						this.form.address.uf = uf
						this.form.address.city = city						
					} catch (error) {
						this.error = error
					}
                    this.loading = false
				}
			},

			async searchUf() {
				const url = `${process.env.VUE_APP_API_URL}system/api/states`
				const {data} = await this.$axios.get(url)
				this.ufs = data 
			},

			async searchCities(uf) {
				const url = `${process.env.VUE_APP_API_URL}system/api/cities`
				const {data} = await this.$axios.get(url,{
					params:{
						id_state: uf
					}
				})
				this.cities = data
				!this.cities.length ? this.form.address.city ='' :''
			},

			/*
			 * Reponsável por obter as funções para usuário
			 */
			getRoles() {
				this.roles_table = []
				this.roles.map(item => {
					this.roles_table.push({
						id: item.id,
						active: item.active,
						checkout: false,
						name: item.name,
						name_temp: item.name
					})
				})
			},

			/*
			 * Reponsável adiconar um novo campo de preenchimento de telefone/celular
			 */
			handleAdd(type) {
				this.form.mobile_phone.push({
					number:''
				})
				this.form.phone.push({
					number:''
				})
				if (type == 0) {
					this.form.phone.pop()
				} else {
					this.form.mobile_phone.pop()
				}
			},

			/*
			 * Reponsável remover um novo campo de preenchimento de telefone/celular
			 */
			handleRemove(index, type) {
				if (type == 0) {
					if (index > 0) {
						if (this.form.mobile_phone[index].number =='') {
							this.form.mobile_phone.splice(index, 1)
						}
					}
				} else {
					if (index > 0) {
						if (this.form.phone[index].number =='') {
							this.form.phone.splice(index, 1)
						}
					}
				}
			},

			async onSaveRegister(type, item, index) {
				if (type == 'roles_table') {
					const form = {
						id: item.id,
						name: item.name,
						active: item.active
					}
					this.roles_table.map((value, key) => {
						if (key == index) {
							value.checkout = false
							value.name_temp = value.name
						}
						return item
					})
					if (item.name !='') {
						try {
							if (form.id == 0) {
								const { data } = await this.$axios.post(
									"people/roles/create",
									form
								)
								this.roles_table[index].id = data
								await this.updateRoles(this.roles_table)
								this.getRoles()
								this.$vx.notify({
									title: "Sucesso",
									text: "Dados salvos!",
									color: "success",
									time: 5000,
									icon: "check"
								})
							} else {
								if (item.name != item.name_temp) {
									await this.$axios.put(`people/groups/update`, form)
									await this.updateRoles(this.roles_table)
									this.getRoles()
									this.$vx.notify({
										title: "Sucesso",
										text: "Dados salvos!",
										color: "success",
										time: 5000,
										icon: "check"
									})
								}
							}
						} catch (error) {
							this.$vx.notify({
								title: "Erro",
								text: error.message,
								color: "danger",
								fixed: true,
								icon: "error"
							})
							console.warn(error.message)
							this.removeListItem("roles_table", index)
						}
						} else {
						this.removeListItem("roles_table", index)
					}
				}
			},

			/**
			 * Responsável adiconar na lista
			 */
			addListItem(type) {
				if (type == "roles_table") {
					this.roles_table.push({
						id: 0,
						active: true,
						checkout: true,
						name:'',
						name_temp:''
					})
				}
			},

			/**
			 * Responsável inativar o registro
			 */
			async onOffActivate(type, index) {
				if (type == "roles_table") {
					this.roles_table.map((item, key) => {
					key == index
						? item.active
						? (item.active = false)
						: (item.active = true)
						:''
					return item
					})
					const item = this.roles_table[index]
					const form = {
						id: item.id,
						active: item.active
					}
					try {
						await this.$axios.put(`people/roles/update`, form)
						await this.updateRoles(this.roles_table)
						this.getRoles()
						this.$vx.notify({
							title: "Sucesso",
							text: "Dados salvos!",
							color: "success",
							time: 5000,
							icon: "check"
						})
					} catch (error) {
						this.$vx.notify({
							title: "Erro",
							text: error.message,
							color: "danger",
							fixed: true,
							icon: "error"
						})
						console.warn(error.message)
					}
				}
			},

			/**
			 * Responsável remover da lista
			 */
			removeListItem(type, index) {
				if (type == "roles_table") {
					this.roles_table.splice(index, 1)
				}
			},

			fillData(data){
				const mobile_phone = data.phone.filter(item => {
					return item.type == 1
				})
				const phone = data.phone.filter(item => {
					return item.type == 0
				})
				const whatsapp = data.phone.filter(item => {
					return item.type == 2
				})
				data.mobile_phone = mobile_phone.length
					? mobile_phone
					: [
						{
							number: ""
						}
					]
				data.phone = phone.length
					? phone
					: [
						{
							number: ""
						}
					]
				data.whatsapp = whatsapp.length ? whatsapp[0].number : ""
				data.whatsapp_id = whatsapp.length ? whatsapp[0].id : ""
				data.date_birth = data.date_birth
					? moment(data.date_birth).format("DD-MM-YYYY")
					: ""
				return data
			},

			/**
			 * Responsável por buscar o registro
			 */
			async getPerson(id) {
				this.$Loading.start()
				try {
					const { data } = await this.$axios.get(`people/item/${id}`)
					this.$Loading.finish()
					return this.fillData(data)
				} catch (error) {
					this.$vx.notify({
						title: "Erro",
						text: error,
						color: "danger",
						fixed: true,
						icon: "error"
					})
                    this.$Loading.error()
				}
			},

			/*
			 * Reponsável por tratar os dados para exibir no formulario
			 */
			async toForm() {
				this.form = await this.getPerson(this.id)
				// limpar password
				this.form.password =''
				// address
				if (!this.form.address) {
					this.form.address = {
						cep:'',
						street:'',
						number:'',
						complement:'',
						district:'',
						city:'',
						uf:'',
					}
				}
				this.getFile()
			}
		},
		watch: {
			/**
			 * Observa alterações nos dados, para exibir o botão de salvar
			 */
			form: {
				handler() {
					this.changes == 1 ? this.$emit("change") :''
				},
				deep: true
			},
			"popup.isOpen"() {
				// roles_table
				this.roles_table.map(item => {
					item.checkout = false
					item.name = item.name_temp
					return item
				})
				this.roles_table = this.roles_table.filter(item => {
					return item.name_temp !=''
				})
				// roles_table end
			},
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
			}
		},
		async created() {
			this.loading = true
			this.getRoles()
			// await this.searchUf()
			this.form.type = this.type
			this.form.company_id = this.currentUser.company_id
			if (this.id) {
				await this.toForm()
			}
			this.loading = false
		},
		updated() {
			this.changes = 1
		}
	}
</script>