<template>
  <transition name="tr-expand">
    <tr
      v-if="active"
      class="tr-expand"
      :class="{'is-selected':isChecked}">
      <td :colspan="colspan">
        <div class="flex items-center">
          <slot></slot>
          <button v-if="close" class="tr-expand--close" @click="$emit('click', $event)">
            <i class="material-icons">
              clear
            </i>
          </button>
        </div>
      </td>
    </tr>
  </transition>
</template>

<script>
export default {
  name:'vx-tr-expand',
  props: {
    close: {
      type: Boolean,
      default: false
    },
    colspan:{
      default: 1,
      type: Number
    }
  },
  data:() => ({
    active: false
  }),
  computed:{
    isChecked(){
      try {
        const index = this.$slots.default[0].context.key
        return this.$slots.default[0].context.data[index].check
      } catch (error) {
        return false
      }
    }
  },
  mounted() {
    this.active = true
  }
}
</script>