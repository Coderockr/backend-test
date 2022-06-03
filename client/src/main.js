
// Icons
import 'material-design-icons/iconfont/material-icons.css'
import '@fortawesome/fontawesome-free/css/all.css'
import './assets/css/iconfont.css'
// 

// Main style
import './assets/scss/main.scss'
import '@/assets/css/main.css'

import Vue from 'vue'
import App from './containers/App'
import router from './router'
import store from './store/store'
import '../themeConfig.js'

// Iview
import 'view-design/dist/styles/iview.css'
import '@/assets/less/index.less'
import ViewUI from 'view-design'
import locale from 'view-design/dist/locale/pt-BR'
Vue.use(ViewUI, { locale })
// 

// Prismjs
import 'prismjs'
import 'prismjs/themes/prism-tomorrow.css'
// 

// V-money
import money from 'v-money'
Vue.use(money, {
  decimal: ',',
  thousands: '.',
  precision: 2,
})
// 

// Vee-validate
import VeeValidate, { Validator } from 'vee-validate'
import CpfValidator from './components/validators/cpf.validator'
import CnpjValidator from './components/validators/cnpj.validator'
Validator.extend('cpf', CpfValidator)
Validator.extend('cnpj', CnpjValidator)
Vue.use(VeeValidate, {fieldsBagName: 'veeFields'})
// 

// V-Mask
import VueMask from 'v-mask'
Vue.use(VueMask)
// 

// Axios
import axios from "./helpers/axios"
import VueAxios from 'vue-axios'
Vue.use(VueAxios,axios)
// 

// Vuesax
import 'vuesax/dist/vuesax.css'
import Vuesax from 'vuesax'
Vue.use(Vuesax)
// 

//
import DefineVuesaxMixin from './helpers/defineGlobalMixin'

const install = DefineVuesaxMixin(Vue, {});

if (typeof window !== 'undefined' && window.Vue) {
  install(window.Vue)
}

// Style
import "./assets/css/style.css"

Vue.config.productionTip = process.env.NODE_ENV === "development"
Object.defineProperty(Vue.prototype, '$axios', {value: axios})

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')
