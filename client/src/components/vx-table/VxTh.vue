<template>
  <th colspan="1" rowspan="1">
    <div class="vs-table-text flex" :class="justify">
      <slot></slot>
      <span
        v-if="isColumnSelectedForSort && currentSort != 0"
        class="sort-th">
        <vs-icon
          :icon="currentSort == 1 ? 'expand_more' : 'expand_less'"
          class="th-sort-icon">
        </vs-icon>
      </span>
    </div>
  </th>
</template>
<script>
export default {
  name: 'vx-th',
  props:{
    sortKey:{
      type:String,
      default:null
    },
    align:{
      type:String,
      default:'start'
    }
  },
  data: () => ({
    thwidth: '100%',
    currentSort: 0,
    sortStatuses: [
      null,
      'asc',
      'desc'
    ],
    justify:''
  }),
  computed: {
    styleth () {
      return {
        width: this.thwidth
      }
    },
    isColumnSelectedForSort() {
      if(!this.sortKey) {
        return false
      }
      if(this.$parent.currentSortKey != this.sortKey) {
        this.resetSort()
      }
      return this.$parent.currentSortKey == this.sortKey
    },
    parentSortStatus() {
      return this.$parent.currentSortType
    }
  },
  methods:{
    sort() {
      this.currentSort = this.currentSort !== 2 ? this.currentSort + 1 : 0
      this.$parent.sort(this.sortKey, this.sortStatuses[this.currentSort])
    },
    resetSort() {
      this.currentSort = 0
    }
  },
  created(){
    this.justify = this.align
    if(this.align == 'left'){
      this.justify = 'start'
    }
    if(this.align == 'right'){
      this.justify = 'end'
    }
    this.justify = 'justify-'+this.justify
  }
}
</script>