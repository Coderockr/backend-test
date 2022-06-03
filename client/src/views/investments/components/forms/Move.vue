<template>
    <div>
        <i-form>
            <vs-row>
                <vs-col vs-type="flex" vs-justify="flex-start" vs-lg="4" vs-sm="12">
                    <form-item :error="errors.first('conta')" label="Conta">
                        <i-select v-model="form.account_id" v-validate="'required'" name="conta">
                            <Option
                                v-for="item in currentUser.account"
                                :value="item.id"
                                :key="`${item.number} ${item.id}`" >
                                    {{ item.number }}
                            </Option>
                        </i-select>
                    </form-item>
                </vs-col>
                <vs-col vs-type="flex" vs-justify="flex-start" vs-lg="4" vs-sm="12">
                    <form-item :error="errors.first('valor')" label="Valor">
                        <i-input
                            prefix="logo-usd"
                            v-money="'money'"
                            v-model="form.value"
                        ></i-input>
                    </form-item>
                </vs-col>
                <vs-col vs-type="flex" vs-justify="flex-start" vs-lg="4" vs-sm="12">
                    <form-item label="Data">
                        <date-picker
                            type="date"
                            format="dd/MM/yyyy"
                            v-model="registered_at"
                        ></date-picker>
                    </form-item>
                </vs-col>
            </vs-row>
        </i-form>
    </div>
</template>

<script>
const { formatterCoin, moment, toDecimal  } = require("@/helpers/utils")
import { mapGetters } from "vuex"
export default {
    name:"form-move",
    data:()=>({
        form:{
            type:0,
            account_id:null,
            value:null,
            registered_at:null
        },
		isChecked: false,
        registered_at:'',
        formatterCoin:formatterCoin,
        changes:0,
        loading:false
    }),
    computed: {
		...mapGetters(['currentUser'])
    },
    methods:{
        /*
        * Reponsável por validar os campos do formulario
        */
        formValidate() {
            let data = {}
            let form = Object.assign({}, this.form)
            form.value = toDecimal(form.value)
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
        }
    },
    watch:{
        /**
         * Observa alterações nos dados, para exibir o botão de salvar
         */
        form: {
            handler() {
                this.changes == 1 ? this.$emit("change") :''
            },
            deep: true
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
        },
        'registered_at'(value){
            this.form.registered_at = moment(value).format("YYYY-MM-DD")
        },
        isChecked(value) {
            this.$refs.popup.isChecked = value
        }
    },
    updated() {
        this.changes = 1
    }
}
</script>