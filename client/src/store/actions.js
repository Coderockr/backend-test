export const actions = {
    // ////////////////////////////////////////////
    // SIDEBAR & UI UX
    // ////////////////////////////////////////////

    updateSidebarItemsMin({commit}, value) {
        commit('UPDATE_SIDEBAR_ITEMS_MIN', value)
    },
    updateSidebarWidth({ commit }, value) {
        commit('UPDATE_SIDEBAR_WIDTH', value)
    },
    toggleContentOverlay({ commit }) {
        commit('TOGGLE_CONTENT_OVERLAY')
    },
    toggleIsSidebarActive({ commit }) {
        commit('TOGGLE_IS_SIDEBAR_ACTIVE')
    },
    updateWindowBreakpoint({commit}, value) {
        commit('UPDATE_WINDOW_BREAKPOINT', value)
    },
    updateTheme({ commit }, value) {
        commit('UPDATE_THEME', value)
    },
    updatePrimaryColor({ commit }, value) {
        commit('UPDATE_PRIMARY_COLOR', value)
    },
    updateNavbar({ commit }, value) {
        commit('UPDATE_NAVBAR', value)
    },
    updateNavbarColor({ commit }, value) {
        commit('UPDATE_NAVBAR_COLOR', value)
    },
    updateRouterTransition({ commit }, value) {
        commit('UPDATE_ROUTER_TRANSITION', value)
    },
    updateFooter({ commit }, value) {
        commit('UPDATE_FOOTER', value)
    },

    // ////////////////////////////////////////////
    // COMPONENT
    // //////////////////////////////////////////// 
    
    // VxAutoSuggest
    updateStarredPage({ commit }, payload) {
        commit('UPDATE_STARRED_PAGE', payload)
    },

    //  The Navbar
    arrangeStarredPagesLimited({ commit }, list) {
        commit('ARRANGE_STARRED_PAGES_LIMITED', list)
    },
    arrangeStarredPagesMore({ commit }, list) {
        commit('ARRANGE_STARRED_PAGES_MORE', list)
    },

    // ////////////////////////////////////////////
    // Auth
    // //////////////////////////////////////////// 
    
    login({commit}, {authToken,user}) {
        commit('UPDATE_TOKEN', authToken)
        commit('UPDATE_USER', user)
    },
    logout({commit}) {
        commit('UPDATE_TOKEN', null)
        commit('UPDATE_USER', {})
    },
    addReq({commit}, payload) {
        commit('ADD_TOKEN', payload)
    },
    removeReq({commit}, key) {
        commit('REM_TOKEN', key)
    },

    // ////////////////////////////////////////////
    // System
    // //////////////////////////////////////////// 

    updateRoles({ commit }, value) {
        commit('UPDATE_ROLES', value)
    },

    updateUsers({ commit }, value) {
        commit('UPDATE_USERS', value)
    }

}
