<template>
  <td
    ref="td"
    :class="{'td-edit': $slots.edit}"
    class="td vs-table--td" :style="`${styleTd}`">
    <span @click="clicktd">
      <vs-icon
        v-if="$slots.edit"
        class="icon-edit"
        icon="edit">
      </vs-icon>
      <slot></slot>
    </span>
  </td>
</template>
<script>
import Vue from 'vue';
import trExpand from './VxTrExpand.vue'
export default {
  name: 'vx-td',
  props:{
    data:{
      default: null
    },
    styleTd:{
      type:String,
      default: null
    }
  },
  data: () => ({
    activeEdit: false
  }),
  watch:{
    activeEdit() {
      this.$parent.activeEdit = this.activeEdit
    }
  },
  methods:{
    insertAfter(e,i){
      if(e.nextSibling){
        e.parentNode.insertBefore(i,e.nextSibling);
      } else {
        e.parentNode.appendChild(i);
      }
    },
    clicktd (evt) {
      if(this.$slots.edit) {
        let tr = evt.target.closest('tr')
        if(!this.activeEdit) {
          let trx = Vue.extend(trExpand);
          let instance = new trx();
          instance.$props.colspan = 5
          instance.$props.close = true
          instance.$slots.default = this.$slots.edit
          instance.vm = instance.$mount();
          instance.$on('click', this.close)
          var nuevo_parrafo = document.createElement('tr').appendChild(instance.vm.$el);
          this.insertAfter(tr, nuevo_parrafo)
          this.activeEdit = true
          setTimeout(()=>{
            window.addEventListener('click', this.closeEdit)
          }, 20)
        }
      }
    },
    closeEdit (evt) {
      if (!evt.target.closest('.tr-expand') && !evt.target.closest('.vs-select--options')) {
        this.close()
      }
    },
    close(){
      let tr = this.$refs.td.closest('tr')
      this.activeEdit = false
      tr.parentNode.removeChild(tr.nextSibling)
      window.removeEventListener('click', this.closeEdit)
      this.$emit("close")
    },
    saveEdit () {
      this.activeEdit = false
    }
  }
}
</script>

<style lang="scss">
  .td-edit{
    text-decoration:none;
  }
  .td-edit:hover{
    text-decoration:underline;
    div{
      text-decoration: underline;
    }
  }
</style>