export const mutations = {
    // ////////////////////////////////////////////
    // SIDEBAR & UI UX
    // ////////////////////////////////////////////

    UPDATE_SIDEBAR_WIDTH(state, width) {
        state.sidebarWidth = width
    },
    UPDATE_SIDEBAR_ITEMS_MIN(state, value) {
        state.sidebarItemsMin = value
    },
    TOGGLE_REDUCE_BUTTON(state, value) {
        state.reduceButton = value
    },
    TOGGLE_CONTENT_OVERLAY(state, value) {
        state.bodyOverlay = value
    },
    TOGGLE_IS_SIDEBAR_ACTIVE(state, value) {
        state.isSidebarActive = value
    },
    UPDATE_THEME(state, value) {
        state.theme = value
    },
    UPDATE_PRIMARY_COLOR(state, value) {
        state.themePrimaryColor = value
    },
    UPDATE_NAVBAR(state, value){
        state.navbarType = value
    },
    UPDATE_NAVBAR_COLOR(state, value){
        state.navbarColor = value
    },
    UPDATE_ROUTER_TRANSITION(state, value){
        state.routerTransition = value
    },
    UPDATE_FOOTER(state, value){
        state.footerType = value
    },
    UPDATE_WINDOW_BREAKPOINT(state, value) {
        state.breakpoint = value
    },
    UPDATE_STATUS_CHAT(state, value) {
        state.AppActiveUser.status = value
    },


    // ////////////////////////////////////////////
    // COMPONENT
    // ////////////////////////////////////////////

    // VxAutoSuggest
    UPDATE_STARRED_PAGE(state, payload) {
        // find item index in search list state
        const index = state.navbarSearchAndPinList.data.findIndex((item) => item.index == payload.index)
        // update the main list
        state.navbarSearchAndPinList.data[index].highlightAction = payload.val

        // if val is true add it to starred else remove
        if(payload.val) {
            state.starredPages.push(state.navbarSearchAndPinList.data[index])
        }else {
            // find item index from starred pages
            const index = state.starredPages.findIndex((item) => item.index == payload.index)
            // remove item using index
            state.starredPages.splice(index, 1)
        }
    },

    // The Navbar
    ARRANGE_STARRED_PAGES_LIMITED(state, list) {
        const starredPagesMore = state.starredPages.slice(10)
        state.starredPages = list.concat(starredPagesMore)
    },
    ARRANGE_STARRED_PAGES_MORE(state, list) {
        let downToUp = false
        let lastItemInStarredLimited = state.starredPages[10]
        const starredPagesLimited = state.starredPages.slice(0, 10)
        state.starredPages = starredPagesLimited.concat(list)

        state.starredPages.slice(0,10).map((i) => {
            if(list.indexOf(i) > -1) downToUp = true
        })
        if(!downToUp) {
            state.starredPages.splice(10, 0, lastItemInStarredLimited)
        }
    },

    UPDATE_USER(state, user) {
        state.currentUser = user
    },

    ADD_TOKEN(state, { token, source }) {
        Object.assign(state.axios_req, {[token]: source})
    },
    UPDATE_TOKEN(state, token) {
        state.authToken = token
    },
    REMOVE_TOKEN(state, key) {
        delete state.axios_req[key]
    },

    // ////////////////////////////////////////////
    // System
    // ////////////////////////////////////////////

    UPDATE_ROLES(state, roles) {
        state.roles = roles
    },

    UPDATE_USERS(state, users) {
        state.users = users
    }

}
