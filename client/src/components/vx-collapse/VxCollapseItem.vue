<template>
  <div
    :class="{'open-item': maxHeight != '0px', 'disabledx': disabled}"
    class="vs-collapse-item"
    @mouseover="mouseover"
    @mouseout="mouseout">
    <header
      class="vs-collapse-item--header"
      @click="toggleContent">
      <slot name="header"></slot>

      <span
        v-if="!notArrow"
        class="icon-header vs-collapse-item--icon-header">
        <vs-icon 
          :icon-pack="iconPack" 
          :icon="maxHeight != '0px' ? iconOpen : iconClose" >
        </vs-icon>
      </span>
    </header>
    <div
      ref="content"
      :style="styleContent"
      class="vs-collapse-item--content">
      <div class="con-content--item">
        <slot></slot>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name:'vx-collapse-item',
  props:{
    open: {
      default: false,
      type: Boolean
    },
    disabled:{
      default:false,
      type: Boolean
    },
    notArrow:{
      default: false,
      type: Boolean
    },
    iconOpen:{
      default: 'keyboard_arrow_down',
      type: String
    },
    iconClose:{
      default: 'keyboard_arrow_up',
      type: String
    },
    iconPack:{
      default: 'material-icons',
      type: String
    },
    sst: {
      default: false,
      type: Boolean
    }
  },
  data:() => ({
    maxHeight: '0px',
    // only used for sst
    dataReady: false
  }),
  computed:{
    accordion() {
      return this.$parent.accordion
    },
    openHover() {
      return this.$parent.openHover
    },
    styleContent() {
      return {
        maxHeight: this.maxHeight
      }
    }
  },
  watch:{
    maxHeight() {
        this.$parent.emitChange()
    },
    ready(newVal, oldVal) {
      if (oldVal != newVal && newVal) {
        this.initMaxHeight()
      }
    }
  },
  mounted () {
    window.addEventListener('resize', this.changeHeight)
    const maxHeightx = this.$refs.content.scrollHeight
    if(this.open) {
      this.maxHeight = `${maxHeightx}px`
    }
  },
  methods:{
    changeHeight () {
      const maxHeightx = this.$refs.content.scrollHeight
      if(this.maxHeight != '0px') {
        this.maxHeight = `100%`
      }
    },
    toggleContent() {
      if(this.openHover || this.disabled) {
        return
      }
      if(this.accordion) {
        this.$parent.closeAllItems(this.$el)
      }
      if (this.sst && !this.dataReady) {
        this.$emit('fetch', {
          done: () => {
            this.initMaxHeight();
            this.dataReady = true
          }
        })
      } else {
        this.initMaxHeight()
      }
    },
    initMaxHeight() {
      const maxHeightx = this.$refs.content.scrollHeight
      if(this.maxHeight == '0px') {
        this.maxHeight = `${maxHeightx}px`
      } else {
        this.maxHeight = `0px`
      }
    },
    mouseover() {
      if(this.disabled) {
        return
      }
      let maxHeightx = this.$refs.content.scrollHeight
      if(this.openHover) {
        this.maxHeight = `${maxHeightx}px`
      }
    },
    mouseout() {
      if(this.openHover) {
        this.maxHeight = `0px`
      }
    }
  }
}
</script>