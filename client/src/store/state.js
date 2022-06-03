import navbarSearchAndPinList from '@/containers/components/navbarSearchAndPinList'
import themeConfig from '@/../themeConfig.js'
export const state = {
    isSidebarActive: true,
    breakpoint: null,
    sidebarWidth: "default",
    reduceButton: themeConfig.sidebarCollapsed,
    bodyOverlay: false,
    sidebarItemsMin: false,
    theme: themeConfig.theme,
    themePrimaryColor: themeConfig.colors.primary,
    navbarType: themeConfig.navbarType || 'floating',
    navbarColor: themeConfig.navbarColor || '#fff',
    routerTransition: themeConfig.routerTransition || 'none',
    footerType: themeConfig.footerType || 'static',
    navbarSearchAndPinList: navbarSearchAndPinList,
    currentUser: {},
    authToken: null,
    starredPages: navbarSearchAndPinList.data.filter((page) => page.highlightAction)
}