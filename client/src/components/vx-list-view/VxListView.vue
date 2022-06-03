<template>
  <div id="list-view" class="border relative" style="z-index:997">

    <div id="list-view-container" class="relative clearfix" style="max-height: calc(100vh - 16.4rem);">
      <div class="flex justify-between" :class="{'bg-dark':theme=='dark','bg-white':theme!='dark'}">
        <div :class="{'sidebar-spacer-with-margin': isFilterSidebarActive && showSideBar}" class="w-2/3">
          <slot name="container-header">
              <div class="flex justify-start mt-6">
                <feather-icon
                  class="inline-flex cursor-pointer ml-4 mr-2" style="margin-top:-2px;"
                  icon="MenuIcon"
                  @click.stop="openSideBar"/>
                <slot name="totalizer"></slot>
              </div>
          </slot>
        </div>
        <div class="flex justify-end pt-5 mt-1 w-2/3">
          <slot name="actions"></slot>
        </div>
        <div class="flex justify-end p-4 w-2/3">
          <i-input clearable prefix="ios-search" v-model="search" class="ml-2"></i-input>
        </div>
      </div>
      <vs-sidebar
        class="items-no-padding vs-sidebar-rounded background-absolute"
        parent="#list-view-container"
        :click-not-close="true"
        :hidden-background="true"
        v-model="isFilterSidebarActive">
        <VuePerfectScrollbar class="scroll-area" :settings="settings">
          <div class="p-6 filter-container app-fixed-height">
            <div class="flex justify-center">
              <slot name="sidebar-header">
                <!-- Sidebar Header !-->
              </slot>
            </div>
            <vs-divider v-show="!!$slots['sidebar-header']" />
            <div>
              <slot name="sidebar-body">
                <!-- Sidebar body !-->
              </slot>
            </div>
            <vs-divider v-show="!!$slots['sidebar-body']" />
            <div class="flex justify-center" v-show="!!$slots['sidebar-body']">
              <slot name="sidebar-footer">
                <!-- Sideebar Footer !-->
              </slot>
            </div>
          </div>
        </VuePerfectScrollbar>
      </vs-sidebar>
      <div
        :class="{'sidebar-spacer-with-margin': isFilterSidebarActive && showSideBar}">
        <div class="container-list-hight overflow-auto relative vx-table vs-con-loading__container">
          <slot name="container-body"></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import VuePerfectScrollbar from "vue-perfect-scrollbar"
import {mapGetters} from "vuex"
import _ from "lodash"
export default {
  components: {
    VuePerfectScrollbar
  },
  props: {
    showSideBar: {
      type: Boolean,
      default: true
    },
  },
  data:()=>({
      isFilterSidebarActive: false,
      onSearch: false,
      search: "",
      settings: {
        maxScrollbarLength: 60,
        wheelSpeed: 0.3
      }
  }),
  computed: {
    ...mapGetters(['theme'])
  },
  methods: {
    handleWindowResize() {
      if (this.windowWidth < 992) {
        this.isFilterSidebarActive = false;
      } else {
        this.isFilterSidebarActive = true && this.showSideBar;
      }
    },
    searching(value) {
      this.$emit("searching", value);
    },
    openSideBar() {
      this.isFilterSidebarActive =
        !this.isFilterSidebarActive && this.showSideBar;
    }
  },
  watch: {
    search: _.debounce(function(value) {
      this.searching(value);
    }, 1000)
  },
  mounted() {
    this.isFilterSidebarActive = this.showSideBar;
    window.addEventListener("resize", evt => {
      this.windowWidth = evt.currentTarget.innerWidth;
      this.handleWindowResize();
    })
  }
};
</script>

<style lang="scss" scoped>
  .sidebar-spacer-with-margin {
    width: calc(100% - 260px - 2.2rem);
    margin-left: calc(260px + 2.2rem);
  }

  .container-list-hight {
    height: calc(100vh - 22.97rem);
  }
</style>