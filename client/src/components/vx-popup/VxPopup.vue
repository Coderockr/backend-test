<template>
    <div v-if="active" :class="custtomClass">
        <div class="vs-popup--background" @click="close">
        </div>
        <div class="vs-popup" :style="'width:'+custtomWidth+'%;'">
            <header class="vs-popup--header">
                <div class="vs-popup--title">
                    <span class="after"></span>
                    <h3>{{title}}</h3>
                </div>
                <a @click="close"><vs-icon icon="close" class="vs-popup--close" :style="'color:var(--'+custtomColor+')'"/></a>
            </header>
            <div class="vs-popup--content">
                <slot></slot>
            </div>
            <footer v-if="isChecked">
                <vs-button color="primary" type="flat" icon="check" @click.prevent="submit">Salvar</vs-button>
            </footer>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        title:String,
        color:String,
        fullscreen:Boolean,
        loading:{
            type:Boolean,
            default:false
        },
        width:{
            type: [String, Number],
            default: 400
        }
    },
    data:()=>({
        active:false,
        custtomClass:'vs-component con-vs-popup vs-popup-',
        custtomColor:'',
        custtomWidth:0,
        isChecked:false
    }),
    methods:{
        open(){
            this.active = true
        },
        close(){
            return new Promise(() => {
                if (this.$parent.isChecked) {
                        this.$vx.dialog({
                        type:'confirm',
                        color: 'warning',
                        title: `Há alterações que não foram salvas.`,
                        text: 'Deseja realmente fechar?',
                        acceptText:'Confirmar',
                        cancelText:'Cancelar',   
                        accept:() => {
                            this.$parent.isChecked = false
                            this.active = false
                        }
                    })
                } else {
                    this.active = false
                }
            })
        },
        submit(){
            this.$emit("input")
        },
        openLoading(){
            if(this.loading){
                this.$vx.loading({
                    container: '.con-vs-popup',
                    background: 'transparent',
                    type:'sound'
                })
            }else{
                this.$vx.loading.close('.con-vs-popup > .con-vs-loading')
            }
        }
    },
    watch:{
      loading(){
        this.openLoading()
      }
    },
    created(){
        this.color ? this.custtomColor = this.color : this.custtomColor='primary'
        this.custtomClass += this.custtomColor
        this.fullscreen ? this.custtomClass += ' fullscreen' : ''
        this.width ? this.custtomWidth = this.width : this.custtomWidth = 50
    }
}
</script>