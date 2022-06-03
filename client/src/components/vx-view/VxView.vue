<template>
  <transition>
    <div
      ref="view"
      class="view-upload"
      @click="closeView">
      <img
        :src="src"
        alt="image">
    </div>
  </transition>
</template>
<script>
  const { view  } = require("@/helpers/utils")
  export default {
    name:'VxView',
    props:{
      active:{
        default:false,
        type:Boolean
      },
      src:{
        default:null,
        type:String
      }
    },
    mounted(){
      view.insertBody(this.$refs.view)
    },
    methods:{
      closeView(evt){
        if(evt.target.tagName != 'IMG'){
          this.$parent.view_active = false
        }
      }
    }
  }
</script>

<style lang="scss">
.view-upload{
  z-index: 52000;
}
</style>