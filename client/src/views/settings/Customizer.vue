<template>
	<div >
		<vx-card>
			<div class="m-2">
				<div>
					<h5 class="mb-4">Cor Principal</h5>
					<ul class="clearfix">
						<li
							@click="setPrimaryColor(color)"
							v-for="color in themeColors"
							class="w-10 cursor-pointer h-10 rounded-lg m-2 float-left"
							:style="{ backgroundColor: color }"
							:class="{ 'shadow-outline': color == themePrimaryColor }"
							:key="color"
						></li>
						<li
							@click="setPrimaryColor(customPrimaryColor)"
							class="w-10 cursor-pointer h-10 rounded-lg m-2 float-left"
							:style="{ backgroundColor: customPrimaryColor }"
							:class="{
								'shadow-outline': customPrimaryColor == themePrimaryColor
							}"
						></li>
						<li class="float-left">
							<input
								class="w-10 cursor-pointer h-10 rounded-lg m-2"
								v-model="customPrimaryColor"
								type="color"
							/>
						</li>
					</ul>
				</div>
				<vs-divider></vs-divider>
				<div class="mt-4">
					<h5 class="mb-2">Tema</h5>
					<div>
						<vs-radio class="mr-4" v-model="setTheme" vs-value="light"
							>Claro</vs-radio
						>
						<vs-radio class="mr-4" v-model="setTheme" vs-value="dark"
							>Escuro</vs-radio
						>
						<vs-radio v-model="setTheme" vs-value="semi-dark"
							>Semi escuro</vs-radio
						>
					</div>
				</div>
				<vs-divider></vs-divider>
				<div class="mt-4">
					<h5>Cor Navbar</h5>
					<ul class="clearfix">
						<li
							@click="navbarColorLocal = '#fff'"
							class="w-10 cursor-pointer h-10 rounded-lg m-2 float-left bg-white border border-solid border-grey-light"
							:class="{ 'shadow-outline': '#fff' == navbarColorLocal }"
						></li>
						<li
							@click="navbarColorLocal = color"
							v-for="color in themeColors"
							class="w-10 cursor-pointer h-10 rounded-lg m-2 float-left"
							:style="{ backgroundColor: color }"
							:class="{ 'shadow-outline': color == navbarColorLocal }"
							:key="color"
						></li>
						<li
							@click="navbarColorLocal = customNavbarColor"
							class="w-10 cursor-pointer h-10 rounded-lg m-2 float-left"
							:style="{ backgroundColor: customNavbarColor }"
							:class="{
								'shadow-outline': customNavbarColor == navbarColorLocal
							}"
						></li>
						<li class="float-left">
							<input
								class="w-10 cursor-pointer h-10 rounded-lg m-2"
								v-model="customNavbarColor"
								type="color"
							/>
						</li>
					</ul>
				</div>
				<vs-divider></vs-divider>
				<div class="mt-4">
					<h5 class="mb-2">Tipo Navbar</h5>
					<div>
						<vs-radio class="mr-4" v-model="navbarTypeLocal" vs-name="navbarTypeLocal" vs-value="sticky"
							>Pegajosa</vs-radio
						>
						<vs-radio v-model="navbarTypeLocal" vs-name="navbarTypeLocal" vs-value="floating"
							>Flutuando</vs-radio
						>
					</div>
				</div>
				<vs-divider></vs-divider>
				<div class="mt-4">
					<h5 class="mb-2">Tipo Footer</h5>
					<div>
						<vs-radio class="mr-4" v-model="footerTypeLocal" vs-name="footerTypeLocal" vs-value="hidden"
							>Escondido</vs-radio
						>
						<vs-radio class="mr-4" v-model="footerTypeLocal" vs-name="footerTypeLocal" vs-value="static"
							>Estático</vs-radio
						>
						<vs-radio v-model="footerTypeLocal" vs-name="footerTypeLocal" vs-value="sticky"
							>Pegajoso</vs-radio
						>
					</div>
				</div>
				<vs-divider></vs-divider>
				<div class="mt-4">
					<h5 class="mb-2">Animação de roteador {{ routerTransitionLocal }}</h5>
					<vs-select v-model="routerTransitionLocal">
						<vs-select-item
							:key="index"
							:value="item.value"
							:text="item.text"
							v-for="(item, index) in routerTransitionsList"
						/>
					</vs-select>
				</div>
			</div>
		</vx-card>
	</div>
</template>

<script>
	import { mapGetters, mapActions } from "vuex"
	export default {
		data() {
			return {
				active: false,
				themeColors: [
					'#64C832',
					'#46D7FF',
					'#28C76F',
					'#FF9F43',
					'#EA5455',
					'#E6EBF1',
					'#2D8CF0',
					'#808695',
					'#F5A623',
					'#6e6b7b'
				],
				customPrimaryColor: '#3DC9B3',
				customNavbarColor: '#3DC9B3',
				routerTransitionsList: [
					{ text: 'Zoom Fade', value: 'zoom-fade' },
					{ text: 'Slide Fade', value: 'slide-fade' },
					{ text: 'Fade Bottom', value: 'fade-bottom' },
					{ text: 'Fade', value: 'fade' },
					{ text: 'Zoom Out', value: 'zoom-out' },
					{ text: 'None', value: 'none' },
				],
				settings: { // perfectscrollbar settings
					maxScrollbarLength: 60,
					wheelSpeed: .60,
				},
			}
		},
		computed: {
			...mapGetters(['theme', 'themePrimaryColor','navbarType', 'navbarColor', 'routerTransition', 'footerType']),
			setTheme: {
				get() { return this.theme },
				set(value) { this.updateTheme(value) }
			},
			navbarTypeLocal: {
				get() { return this.navbarType; },
				set(value) { this.updateNavbar(value) }
			},
			navbarColorLocal: {
				get() { return this.navbarColor; },
				set(value) { this.updateNavbarColor(value) }
			},
			footerTypeLocal: {
				get() { return this.footerType; },
				set(value) { this.updateFooter(value) }
			},
			routerTransitionLocal: {
				get() { return this.routerTransition; },
				set(value) { this.updateRouterTransition(value) }
			},
		},
		methods: {
			...mapActions(['updateTheme', 'updatePrimaryColor', 'updateNavbar', 'updateNavbarColor', 'updateRouterTransition', 'updateFooter']),
			setPrimaryColor(color) {
				document.documentElement.style.setProperty('--primary', color)
				this.updatePrimaryColor(color)
				this.$vs.theme({ primary: color })
			}
		}
	}
</script>