<template>
    <Drawer :title="title" placement="right" 
            :width="width" :before-close="close" 
            :value="active" @input="value => $emit('input', value)">
        <slot></slot>
    </Drawer>
</template>

<script>

export default {
    props:{
        title:String,
        width:{
            type: [String, Number],
            default: 20
        }
    },
    data:()=>({
        active:false
    }),
    methods:{
        open(){
            this.active = true
        },
        close() {
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
        }
    },
}
</script>
