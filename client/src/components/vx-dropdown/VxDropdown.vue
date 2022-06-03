<template lang="html">
    <vs-dropdown vs-trigger-click v-if="options.length" class="cursor-pointer">
      <div class="con-img ml-2">
            <Icon ref="icon" :type="icon"/>
        </div>
      <vs-dropdown-menu>
            <i-input v-if="isSearch" prefix="ios-search" v-model="search" style="width:94%;" class="m-2"></i-input>
            <div :style="isSearch ? 'max-height:200px;': ''" :class="isSearch ?'overflow-scroll' : ''">
                <vs-dropdown-item v-for="(option, index) in getData" :key="index" @click="onSelect(option)" class="inline">
                    <Icon v-if="option.iconPath === 'iview'" :type="option.icon" class="mb-3"/>
                    <feather-icon v-if="option.iconPath === 'feather'" :icon="option.icon" svgClasses="h-5 w-5" style="top:3px;"/>
                    <i v-if="option.iconPath === 'fontawesome'" :class="option.icon"></i>
                    <span class="px-2 pt-2 pb-1">{{ option.label }}</span>
                </vs-dropdown-item>
            </div>
      </vs-dropdown-menu>
    </vs-dropdown>
</template>

<script>
export default {
    props: {
        id: Number,
        onSelect: Function,
        options: Array,
        icon:{
            type:String,
            default: 'md-more'
        },
        size:{
            type:[Number, String],
            default: 22
        },
        placement:String
    },
    data:()=>({
        search:null
    }),
    computed:{
        isSearch(){
            return this.options.length > 4
        },
        getData(){
            if(this.search){
                return this.options.filter(item=>{
                    return item.label.toLowerCase().includes(this.search.toLowerCase())
                })
            }
            return this.options
        }
    }
}
</script>

<style lang="scss">
    .con-vs-dropdown--menu{
        margin-left: 12.5px;
    }
    .vs-dropdown--item{
        padding: 0.5rem;
        font-size: 14px;
    }
</style>