<template>
    <div class="h-screen flex w-full bg-img">
        <div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 mx-auto self-center">
            <vx-card>
                <div slot="no-body" class="full-page-bg-color">
                    <div class="vx-row">
                        <div class="vx-col hidden sm:hidden md:hidden lg:block lg:w-1/2 mx-auto self-center">
                            <img src="@/assets/images/pages/register.jpg" alt="register" class="mx-auto">
                        </div>
                        <div class="vx-col sm:w-full md:w-full lg:w-1/2 mx-auto self-center bg-white bg-dark">
                            <div class="p-8">
                                <div class="vx-card__title">
                                    <h4 class="mb-4">Criar Conta</h4>
                                    <p>Preencha o formulário abaixo para criar uma nova conta.</p>
                                </div>
                                <div class="clearfix">
                                    <vs-input
                                        v-validate="'required|min:3'"
                                        data-vv-validate-on="blur"
                                        label-placeholder="Nome"
                                        name="nome"
                                        placeholder="Nome"
                                        v-model="form.name"
                                        class="w-full mt-6" />
                                    <span class="text-danger text-sm">{{ errors.first('nome') }}</span>

                                    <vs-input
                                        v-validate="'required|'+validateCpfCnpj(form.cpf_cnpj)"
										v-mask="maskCpfCnpj(form.cpf_cnpj)"
                                        data-vv-validate-on="blur"
                                        label-placeholder="cpf/cnpj"
                                        name="cpf/cnpj"
                                        placeholder="cpf/cnpj"
                                        v-model="form.cpf_cnpj"
                                        class="w-full mt-6" />
                                    <span class="text-danger text-sm">{{ errors.first('cpf/cnpj') }}</span>

                                    <vs-input
                                        v-validate="'required|alpha_dash|min:3'"
                                        data-vv-validate-on="blur"
                                        label-placeholder="Username"
                                        name="nickname"
                                        placeholder="Username"
                                        v-model="form.nickname"
                                        class="w-full mt-6" />
                                    <span class="text-danger text-sm">{{ errors.first('nickname') }}</span>

                                    <vs-input
                                        v-validate="'required|email'"
                                        data-vv-validate-on="blur"
                                        name="email"
                                        type="email"
                                        label-placeholder="Email"
                                        placeholder="Email"
                                        v-model="form.email"
                                        class="w-full mt-6" />
                                    <span class="text-danger text-sm">{{ errors.first('email') }}</span>

                                    <vs-input
                                        ref="password"
                                        type="password"
                                        data-vv-validate-on="blur"
                                        v-validate="'required|min:6|max:10'"
                                        name="password"
                                        label-placeholder="Password"
                                        placeholder="Password"
                                        v-model="form.password"
                                        class="w-full mt-6" />
                                    <span class="text-danger text-sm">{{ errors.first('password') }}</span>

                                    <vs-input
                                        type="password"
                                        v-validate="'min:6|max:10|confirmed:password'"
                                        data-vv-validate-on="blur"
                                        data-vv-as="password"
                                        name="confirm_password"
                                        label-placeholder="Confirm Password"
                                        placeholder="Confirm Password"
                                        v-model="form.confirm_password"
                                        class="w-full mt-6" />
                                    <span class="text-danger text-sm">{{ errors.first('confirm_password') }}</span>

                                    <vs-checkbox v-model="form.isTermsConditionAccepted" class="mt-6">Eu aceito os termos e condições.</vs-checkbox>
                                    <vs-button  type="border" to="/login" class="mt-6">Login</vs-button>
                                    <vs-button class="float-right mt-6" @click="formValidate">Cadastre-se</vs-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>

<script>
const {  maskCpfCnpj, validateCpfCnpj  } = require("@/helpers/utils")
export default {
    data:()=>({
        maskCpfCnpj: maskCpfCnpj,
		validateCpfCnpj: validateCpfCnpj,
        form:{
            name: null,
            nickname: null,
            cpf_cnpj: null,
            email: null,
            password: null,
            confirm_password: null,
            isTermsConditionAccepted: true
        }
    }),
    methods: {
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
            this.$Loading.start()
            const response = await this.$axios.post("people/create", form)
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
                this.$vx.notify({
                    title:response.data.status,
                    text:response.data.message,
                    color:"success",
                    time:5000,
                    icon:"check"
                })
                this.$Loading.finish()
                this.$router.push("/login")
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
    }
}
</script>
