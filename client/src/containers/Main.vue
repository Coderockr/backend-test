<template>
    <div class="layout--main" :class="[navbarClasses, footerClasses]">
        <vx-sidebar :sidebarItems="sidebarItems" :logo="require('@/assets/images/logo/logo.png')" :logo-text="require('@/assets/images/logo/logotext.png')" parent=".layout--main" />
        <div id="content-area" :class="[contentAreaClass, {'show-overlay': bodyOverlay}]">
            <div id="content-overlay">
            </div>
            <div class="content-wrapper" :style="!isThemeDark?'background-color:rgb(248, 248, 248);':'background-color:rgb(33, 39, 66);'">
                <Navbar :navbarColor="navbarColor" :class="[{'text-white': isNavbarDark && !isThemeDark}, {'text-base': !isNavbarDark && isThemeDark}]" />
                <div class="vx-navbar-wrapper h-16 p-4" :class="[{'mt-0':navbarType === 'static'}, {'mt-24':navbarType === 'floating' ||  navbarType === 'sticky'},]" style="z-index:998;" :style="!isThemeDark?'background-color:rgb(248, 248, 248);':'background-color:rgb(33, 39, 66);'">
                    <transition :name="routerTransition">
                        <div class="mt-2">
                            <div class="w-full flex justify-between h-auto">
                                <div class="w-full h-full flex">
                                    <div class="router-header flex flex-wrap items-center mb-6"
                                         v-if="$route.meta.breadcrumb || $route.meta.name">
                                        <div class="m-4"
                                             :class="{'pr-4 border-0 md:border-r border-t-0 border-b-0 border-l-0 border-solid border-grey-light' : $route.meta.breadcrumb}">
                                            <h2>{{ routeTitle }}</h2>
                                        </div>
                                        <vx-breadcrumb :itens="$route.meta.breadcrumb" v-if="$route.meta.breadcrumb"></vx-breadcrumb>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                </div>
                <div class="router-view">
                    <div class="router-content" :class="{'mt-0': navbarType == 'hidden'}">
                        <div class="content-area__content m-4" :class="[{'pt-24':navbarType === 'floating' ||  navbarType === 'sticky'}]">
                            <transition :name="routerTransition" mode="out-in">
                                <router-view @changeRouteTitle="changeRouteTitle" style="padding: 0 15px;"></router-view>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
            <Footer/>
        </div>        
    </div>
</template>

<script>
import themeConfig from '@/../themeConfig'
import sidebarItems from './components/vx-sidebar/sidebarItems'
import {mapActions, mapGetters} from 'vuex'
export default {
    components: {
        VxSidebar: () => import('@/containers/components/vx-sidebar/VxSidebar'),
        Navbar: () => import('./components/Navbar'),
        Footer: () => import('./components/Footer')
    },
    data() {
        return {
            isNavbarDark:false,
            routeTitle:this.$route.meta.name,
            sidebarItems:sidebarItems,
            windowWidth:window.innerWidth, //width of windows
            hideScrollToTop:themeConfig.hideScrollToTop,
            disableThemeTour:themeConfig.disableThemeTour,
            loading:false
        }
    },
    computed: {
        ...mapGetters(['isThemeDark', 'sidebarWidth', 'bodyOverlay', 'currentUser', 'isLoggedIn', 'themePrimaryColor', 'navbarType', 'navbarColor', 'routerTransition', 'footerType']),
        contentAreaClass() {
            if(this.sidebarWidth == "default") return "content-area-default"
            else if(this.sidebarWidth == "reduced") return "content-area-reduced"
            else if(this.sidebarWidth) return "content-area-full"
            return ""
        },
        navbarClasses() {
            return {
                'navbar-hidden': this.navbarType == 'hidden',
                'navbar-sticky': this.navbarType == 'sticky',
                'navbar-static': this.navbarType == 'static',
                'navbar-floating': this.navbarType == 'floating',
            }
        },
        footerClasses() {
            return {
                'footer-hidden': this.footerType == 'hidden',
                'footer-sticky': this.footerType == 'sticky',
                'footer-static': this.footerType == 'static',
            }
        },
    },
    methods: {
        ...mapActions(['toggleIsSidebarActive', 'updateSidebarWidth','updateNavbarColor']),
        changeRouteTitle(title) {
            this.routeTitle = title
        },
        updateNavbarColorLocal(value) {
            this.updateNavbarColor(value)
            if(value == "#fff") this.isNavbarDark = false
            else this.isNavbarDark = true
        },
        handleWindowResize(event) {
            this.windowWidth = event.currentTarget.innerWidth
            this.setSidebarWidth()
        },
        async setSidebarWidth() {
            if (this.windowWidth < 1200) {
                await this.toggleIsSidebarActive(false)
                await this.updateSidebarWidth('no-sidebar')
            } else if (this.windowWidth < 1200) {
                await this.updateSidebarWidth('reduced')
            } else {
                await this.toggleIsSidebarActive(true)
            }
        }
    },
    watch: {
        '$route'() {
            this.routeTitle = this.$route.meta.name
        },
        isThemeDark(value) {
            if(this.navbarColor == "#fff" && value) {
                this.updateNavbarColorLocal("#10163a")
            }else {
                this.updateNavbarColorLocal("#fff")
            }
        }
    },
    created() {
        this.$vs.theme({ primary: this.themePrimaryColor })
        this.setSidebarWidth()
        if(this.navbarColor == "#fff" && this.isThemeDark) {
            this.updateNavbarColorLocal("#10163a")
        }else {
            this.updateNavbarColorLocal(this.navbarColor)
        }
    }
}
</script>
<style>
    .spin-icon-load{
        animation: ani-spin 1s linear infinite;
    }
    @keyframes ani-spin {
        from { transform: rotate(0deg);}
        50%  { transform: rotate(180deg);}
        to   { transform: rotate(360deg);}
    }
    .spin-col{
        background: transparent;
        color:var(--primary);
        position: relative;
        top:-5px;
    }
</style>