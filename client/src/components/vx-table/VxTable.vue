<template>
  <div id="data-list" class="overflow-auto h-full w-full" @scroll.passive="handleScroll">
    <table-custom ref="table" :data="data" :hoverFlat="hover" :noDataText="noDataText" @loadData="loadData">
      <template slot="thead">
        <vx-th></vx-th>
        <vx-th v-if="select" class="td-check" :width="50" align="justify-center" :style="`font-size:${fontSize}px;`">
          <td>
              <vs-checkbox :icon="undetermined?'remove':'done'" size="small" v-model="check_all" @input="checkAll"></vs-checkbox>
          </td>
        </vx-th>
        <vx-th ref="th" v-for="(column, index) in columns" :hidden="column.hidden" :key="column.key" :width="column.width" :sort-key="column.sortable?column.key:''" :align="column.align">
          <span @click="sortValue(index)" :style="`font-size:${fontSize}px;`">{{column.label.toUpperCase()}}</span>
          <vx-dropdown v-if="column.filters" :id="index" :options="column.filters" :onSelect="column.filterMethod" icon="ios-funnel" size="15" style="margin-top:-3px;"></vx-dropdown>
        </vx-th>
      </template>
      <template slot="data">
          <draggable v-if="draggable" v-model="datax" :group="group" tag="tbody">
            <vx-tr draggable :data="tr" v-for="(tr, index) in datax" :key="index" :class="[{'inactive':!tr.active},{'is-selected':tr.check}]" class="row-list" :style="tr.styleTr">
              <vx-td :class="!tr.styleTr?'border-left':''" :style="`font-size:${fontSize}px;`"></vx-td>
              <vx-td v-if="select" class="td-check" :width="50" align="justify-center" :style="`font-size:${fontSize}px;`">
                <vs-checkbox v-model="tr.check" size="small" @input="checkout(tr)"></vs-checkbox>
              </vx-td>
              <vx-td v-for="column in columns" :hidden="column.hidden" :key="column.value" :data="tr[column.key]" :class="column.align ? 'text-'+column.align :  'text-left'" background="red" :style="`font-size:${fontSize}px;`">
                  <custom-render :column="column"
                          :index="index"
                          :render="column.render"
                          :row="tr"
                        v-if="column.render"></custom-render>
                  {{ !column.render ? tr[column.key] : '' }}
                  <template v-if="column.edit" slot="edit">
                    <vs-row class="m-2">
                      <vs-col v-if="!column.type || !regex.test(regex.test(tr[column.key]) + tr[column.key])" vs-type="flex" vs-justify="center" vs-align="center" w="11">
                        <i-input clearable :prefix="column.icon" v-model="tr[column.key]" @input="edit(tr, column.key)"></i-input>
                      </vs-col>
                      <vs-col v-else-if="column.type === 'money'" vs-type="flex" vs-justify="center" vs-align="center" w="11">
                        <div class="ivu-input-wrapper ivu-input-wrapper-default ivu-input-type-text">
                          <i class="ivu-icon ivu-icon-ios-loading ivu-load-loop ivu-input-icon ivu-input-icon-validate"></i> 
                          <money v-model="tr[column.key]" v-money="'money'" @input="edit(tr, column.key, true)" class="ivu-input ivu-input-default" :class="column.icon ? 'ivu-input-with-prefix':''"></money>
                          <span class="ivu-input-prefix">
                            <Icon :type="column.icon" />
                          </span>
                        </div>
                      </vs-col>
                      <vs-col v-if="column.type === 'boolean'" vs-type="flex" vs-justify="center" vs-align="center">
                        <ul class="con-s inline-flex" @click="edit(tr, column.key)">
                          <li class="mr-2">
                            <vs-radio v-model="tr[column.key]" :vs-name="column.key" :vs-value="1">Sim</vs-radio>
                          </li>
                          <li>
                            <vs-radio v-model="tr[column.key]" :vs-name="column.key" :vs-value="0">Não</vs-radio>
                          </li>
                        </ul>
                      </vs-col>
                    </vs-row>
                  </template>
              </vx-td>
              <template slot="expand">
                <slot></slot>
              </template>
            </vx-tr>
          </draggable>
          <tbody v-else>
            <vx-tr :data="tr" v-for="(tr, index) in datax" :key="index" :class="[{'inactive':!tr.active},{'is-selected':tr.check}]" class="row-list" :style="tr.styleTr">
              <vx-td :class="!tr.styleTr?'border-left':''"></vx-td>
              <vx-td v-if="select" class="td-check" :width="50" align="justify-center" :style="`font-size:${fontSize}px;`">
                <vs-checkbox v-model="tr.check" size="small" @input="checkout(tr)"></vs-checkbox>
              </vx-td>
              <vx-td v-for="(column, key) in columns" :hidden="column.hidden" :key="column.value" :data="tr[column.key]" @close="isClose(tr)" :class="column.align ? 'text-'+column.align :  'text-left'" :style-td="getStyleTd(tr, key)" :style="`font-size:${fontSize}px;`">
                    <custom-render :column="column"
                                    :index="index"
                                    :render="column.render"
                                    :row="tr"
                                  v-if="column.render"></custom-render>
                    {{ !column.render ? tr[column.key] : '' }}
                    <template v-if="column.edit" slot="edit">
                      <vs-row class="m-2">
                        <vs-col v-if="!column.type" vs-type="flex" vs-justify="center" vs-align="center" w="11">
                          <i-input clearable :prefix="column.icon" v-model="tr[column.key]" @input="edit(tr, column.key)"></i-input>
                        </vs-col>
                        <vs-col v-if="column.type === 'money'  && !regex.test(regex.test(tr[column.key]) + tr[column.key])" vs-type="flex" vs-justify="center" vs-align="center" w="11">
                          <i-input clearable :prefix="column.icon" v-model="tr[column.key]" @input="edit(tr, column.key)"></i-input>
                        </vs-col>
                        <vs-col v-else-if="column.type === 'money'" vs-type="flex" vs-justify="center" vs-align="center" w="11">
                          <div class="ivu-input-wrapper ivu-input-wrapper-default ivu-input-type-text">
                            <i class="ivu-icon ivu-icon-ios-loading ivu-load-loop ivu-input-icon ivu-input-icon-validate"></i> 
                            <money v-model="tr[column.key]" v-money="'money'" @input="edit(tr, column.key, true)" class="ivu-input ivu-input-default" :class="column.icon ? 'ivu-input-with-prefix':''"></money>
                            <span class="ivu-input-prefix">
                              <Icon :type="column.icon" />
                            </span>
                          </div>
                        </vs-col>
                        <vs-col v-if="column.type === 'boolean'" vs-type="flex" vs-justify="center" vs-align="center">
                          <ul class="con-s inline-flex" @click="edit(tr, column.key)">
                            <li class="mr-2">
                              <vs-radio v-model="tr[column.key]" :vs-name="column.key" :vs-value="1">Sim</vs-radio>
                            </li>
                            <li>
                              <vs-radio v-model="tr[column.key]" :vs-name="column.key" :vs-value="0">Não</vs-radio>
                            </li>
                          </ul>
                        </vs-col>
                        <vs-col v-if="column.type === 'select'" vs-type="flex" vs-justify="center" vs-align="center" w="11">
                          <i-select v-model="tr[column.key]" clearable filterable @input="edit(tr, column.key)">
                            <Option v-for="item in column.items" :value="item.label" :key="`${item.label} ${item.code}`" :label="item.label"></Option>
                          </i-select>
                        </vs-col>
                      </vs-row>
                    </template>
              </vx-td>
              <template slot="expand">
                <slot></slot>
              </template>
            </vx-tr>
          </tbody>
      </template>
    </table-custom>
  </div>    
</template>

<script>
import _ from 'lodash'
export default {
    name:'vx-table',
    props: {
      data:Array,
      columns:Array,
      field:{
        type: Number,
        default: 0
      },
      draggable:{
        type: Boolean,
        default: false
      },
      group:{
        type: String,
        default: null
      },
      select:{
        type: Boolean,
        default: true
      },
      fontSize:{
        type: [Number, String],
        default: 11
      },
      noDataText:{
        type: String,
        default: null
      },
      hover:Boolean,
      loading:Boolean
    },
    components: {
		Draggable: () => import("vuedraggable")
    },
    data:()=>({
      check_all:false,
      undetermined:false,
      datax:[],
      selected:[],
      regex: /[0-9]/,
      check:0
    }),
    methods: {
      loadData(data){
        this.datax = data
      },
      checkout(value){
        this.selected.push(value)
        this.selected = this.selected.filter(item => {
          return item.check
        })
        this.check_all = this.selected.length > 0 ? true : false
        this.undetermined = this.data.length != this.selected.length ? true : false
      },
      checkAll(value){
        this.selected = []
        this.data.map(item =>{
          item.check = value
          this.selected.push(item)
        })
        this.undetermined = false
      },
      isLoading(){
        if(this.loading){
          this.$vx.loading({
            container: '.vx-table',
            background: 'transparent',
            type:'sound'
          })
        }else{
          this.$vx.loading.close('.vx-table > .con-vs-loading')
          this.datax = this.data
        }
      },
      getStyleTd(tr, key){
        return tr.styleTd ? _.includes(tr.styleTd.indexRange, key) ? tr.styleTd.style : '' : '' 
      },
      sortValue(index){
        this.$refs['th'][index].sort()
      },
      isClose(item){
        item.change = 0
      },
      edit: _.debounce(function(item, key, change = false){
        if(change){
          if(item.change === 1){
            this.$emit("edit", item, key)
          }
          item.change = 1
        }else{
          this.$emit("edit", item, key)
        }
      }, 1000),
      handleScroll: _.debounce(async function ({target}) {
        if ((target.scrollHeight - target.offsetHeight) - target.scrollTop <= 2) {
          this.$emit("scroll")
        }
      }, 1000)
    },
    watch:{
      loading(){
        this.isLoading()
        this.check_all = false
      },
      selected(value){
        let ids = []
        value.forEach(item => {
          if(item.check){
            ids.push(item.id)
          }
        })
        this.$emit("selected", ids)
      },
      datax: _.debounce(function (value){
        if(this.check === 1){
          this.$emit("queue", value)
        }
        this.check = 1
      }, 1000)
    },
    created() {
      this.key = this.field
    },
    mounted(){
      this.isLoading()
    },
}
</script>

<style lang="scss">
#data-list {
  
  .vs-con-table {

    .vs-table {
      border-collapse: separate;
      border-spacing: 0 0.5rem;
      padding: 0 1rem;

      tr {
          box-shadow: 0 4px 20px 0 rgba(0,0,0,.05);
          td {
            &:first-child {
              border-top-left-radius: .5rem;
              border-bottom-left-radius: .5rem;
            }
            &:last-child {
              border-top-right-radius: .5rem;
              border-bottom-right-radius: .5rem;
            }
          }
          td.td-check {
            padding-left: 10px !important;
          }
      }

      th {
        padding: 10px 10px 10px 10px;
        .vs-table-text {
          cursor: default;
          text-transform: uppercase;
          font-weight: 600px;
          span{
            cursor: pointer;
          }
        }
      }

      .vs-table--thead {
        tr {
          background: none;
          box-shadow: none;
        }
      }

    }

    .sort-th{
      color: #C5C8CE;
    }

    .ivu-icon-ios-funnel {
      color: #C5C8CE;
    }

  }
  
  .row-list:hover .border-left{
		border-left: solid var(--primary) 2px;
	}
	.border-left{
		border-left:none;
	}

  .inactive {
    background:rgb(223, 223, 223);
  }
  
  .vs-table-primary .is-selected{
    transform: none !important;
  }

}

</style>