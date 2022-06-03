<template>
  <div id="app">
    <router-view></router-view>
  </div>
</template>

<script>
import validate_pt from 'vee-validate/dist/locale/pt_BR'
import {mapGetters,mapActions} from "vuex"
export default {
  name: 'app',
  computed: {
    ...mapGetters(['authToken','idUser', 'theme','themePrimaryColor'])
  },
  methods: {
    ...mapActions(['logout']),
    toggleClassInBody(className) {
      if (className == 'dark') {
        if (document.body.className.match('theme-semi-dark')) document.body.classList.remove('theme-semi-dark')
        document.body.classList.add('theme-dark')
      } else if (className == 'semi-dark') {
        if (document.body.className.match('theme-dark')) document.body.classList.remove('theme-dark')
        document.body.classList.add('theme-semi-dark')
      } else {
        if (document.body.className.match('theme-dark')) document.body.classList.remove('theme-dark')
        if (document.body.className.match('theme-semi-dark')) document.body.classList.remove('theme-semi-dark')
      }
    }
  },
  watch: {
    theme(value) {
      this.toggleClassInBody(value);
    },
  },
  async created() {
    document.documentElement.style.setProperty('--primary', this.themePrimaryColor)
    this.$validator.localize('pt_BR', validate_pt)
    const authToken = this.authToken
    if (authToken) {
      this.$axios.defaults.headers.common.Authorization = `Bearer ${authToken}`
    }
    this.$axios.interceptors.response.use(
      config => config,
        async error => {
          if (error.response) {
            let message = error.response.data.message || ""
            /**
            * Erro de autenticação
            */
            if (error.response.status === 401) {
              error.config.headers['Authorization'] = null;
              await this.logout()
              this.$router.push({name: 'login'})
              message = 'Erro de autenticação'
            }
            /**
            * Erro de validação
            */
            if (error.response.status === 422) {
              const erros = (typeof error.response.data === 'object')
                ? Object.keys(error.response.data.errors).map(item => `<b>${item}:</b> ${error.response.data.errors[item][0]}`)
                : [JSON.stringify(error.response.data)]
              message = `<p>${error.response.data.message}</p><br>${erros.join("<br>")}`
            }
            /**
            * Demais erros
            */
            if (error.response.data.error) {
              message = error.response.data.error
            }

            return Promise.reject({message})
          }
          return Promise.reject(error)
        }
    ) 
    if (this.idUser) {
        await this.$axios.get('auth/ping')
    }  
  },
  mounted() {
    this.toggleClassInBody(this.theme)
  },
}
</script>