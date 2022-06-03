<template>
    <chrome v-model="colors" @on-change="onChange"></chrome> 
</template>

<script>
import { Chrome } from 'vue-color'
export default {
    name:'vx-color',
    props:{
        index:{
            default:-1,
            type:Number
        }
    },
    components: {
        Chrome
    },
    data:()=>({
        colors: {},
    }),
    methods:{
        onChange (value) {
            this.colors = value
        },
    },
    watch:{
        colors(value){
            this.$emit("input", this.index,value.hex)
        }
    }
}
</script>