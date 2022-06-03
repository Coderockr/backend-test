<template>
    <div class="h-screen flex w-full bg-img">
        <div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 mx-auto self-center">
            <vx-card>
                <div class="full-page-bg-color" slot="no-body">
                    <div class="vx-row">
                        <div class="vx-col hidden sm:hidden md:hidden lg:block lg:w-1/2 mx-auto self-center">
                            <img alt="login" class="mx-auto" src="../../assets/images/pages/forgot-password.png">
                        </div>
                        <div class="vx-col sm:w-full md:w-full lg:w-1/2 mx-auto self-center bg-white">
                            <form class="p-8" @submit="e => e.preventDefault()">
                                <div class="vx-card__title mb-8">
                                    <div class="logo flex flex-wrap justify-center mb-4">
                                        <img :src="require('@/assets/images/logo/logotype.png')" alt="logo" class="w-32 mr-4">
                                    </div>
                                    <h4 class="mb-4">Autenticar</h4>
                                    <p>Seja bem-vindo, por favor informe seu e-mail/usuário e senha.</p>
                                </div>
                                <vs-input autofocus :color="errors.has('email') ? 'danger' : 'success'"
                                          class="w-full mb-6 no-icon-border" icon="icon icon-user"
                                          icon-pack="feather" label-placeholder="E-mail / Usuário" name="email"
                                          v-model="form.email"
                                          v-validate="'required'"
                                />
                                <span class="text-danger">{{ errors.first('email') }}</span>
                                <vs-input :color="errors.has('password') ? 'danger' : 'success'" class="w-full mb-4 no-icon-border" icon="icon icon-lock" icon-pack="feather"
                                          label-placeholder="Senha" name="password"
                                          type="password" v-model="form.password" @keypress="keyEnter"
                                          v-validate="'required|min:6'"/>
                                <span class="text-danger">{{ errors.first('password') }}</span>
                                <vs-button  type="border" to="/register" class="">Criar nova conta</vs-button>
                                <vs-button
                                    @click="validate"
                                    color="var(--primary)" type="filled" class="float-right" :disabled="loading">
                                    <span v-if="!loading">Entrar</span>
                                    <span v-else class="inline-flex">
                                        <Spin fix class="spin-col mr-4">
                                            <Icon type="ios-loading" class="spin-icon-load"></Icon>
                                        </Spin>
                                        <span style="margin-top:-15px;">Carregando...</span>
                                    </span>
                                </vs-button>
                            </form>
                        </div>
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>
<script>
    import {mapActions} from "vuex"
    export default {
        name:"login",
        data:()=>({
            loading:false,
            form: {
                email: '',
                password: ''
            },
            error:false
        }),
        methods: {
            ...mapActions(['login', 'updateRoles', 'updateUsers']),
            validate() {
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.auth()
                    } 
                })
            },
            setAxiosToken(authToken) {
                this.$axios.defaults.headers.common.Authorization = `Bearer ${authToken}`;
            },
            async auth() {
                this.loading = true
                this.$Loading.start()
                const response = await this.$axios.post('auth/login', this.form)
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
                    const {token:authToken, user:user} = response.data.data
                    this.setAxiosToken(authToken)
                    await this.login({
                        authToken,
                        user
                    })
                    this.getRoles()
                    this.getUsers()
                    this.$router.push(this.$route.query.redirect || "/investments")
                } else {
                    this.loading = false
                    this.$Loading.error()
                    this.$vx.notify({
                        title:'Erro',
                        text:response.data.error,
                        color:'danger',
                        fixed: true,
                        icon:'error'
                    })
                }
            },
            async getRoles(){
                const {data} = await this.$axios.get('people/roles')
                    .catch((error) => {
                        this.$vx.notify({
                            title: "Erro",
                            text: error,
                            color: "danger",
                            fixed: true,
                            icon: "error"
                        })
                    })
                if(data){
                    await this.updateRoles(data)
                }
            },
            async getUsers(){
				const { data } = await this.$axios.get("people", {
					params: {
						type: 0
					}
				})
                .catch((error) => {
                    this.$vx.notify({
                        title: "Erro",
                        text: error,
                        color: "danger",
                        fixed: true,
                        icon: "error"
                    })
                })
                if(data){
                    await this.updateUsers(data)
                }
			},
            keyEnter(event){
                if(event.keyCode==13){
                    this.validate()
                }
            }
        },
    }
</script>               
<style>
    .spin-icon-load{
        animation: ani-spin 1s linear infinite;
    }
    @keyframes ani-spin {
        from { transform: rotate(0deg);}
        50%  { transform: rotate(180deg);}
        to   { transform: rotate(360deg);}
    }
    .spin-col{
        background: transparent;
        color:#fff;
        position: relative;
        top:-5px;
    }
    .vs-input--icon.feather{
        top:5px;
    }
</style>