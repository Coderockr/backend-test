import Vue from 'vue'

/**
 * Páginas
 */

const Login = () => import("./containers/pages/Login")
const Investment = () => import("./views/investments/Investment")
const Register = () => import("./containers/pages/Register")

export {
    Login,
    Investment,
    Register
}

/**
 * Componentes
 */
const VxCard = () => import("./components/vx-card/VxCard")
const VxTable = () => import("./components/vx-table/VxTable")
const TableCustom = () => import("./components/vx-table/TableCustom")
const VxTh = () => import("./components/vx-table/VxTh")
const VxTr = () => import("./components/vx-table/VxTr")
const VxTd = () => import("./components/vx-table/VxTd")
const VxDropdown = () => import("./components/vx-dropdown/VxDropdown")
const VxDrawer = () => import("./components/vx-drawer/VxDrawer")
const VxUpload = () => import("./components/vx-upload/VxUpload")
const VxView = () => import("./components/vx-view/VxView")
const VxBreadcrumb = () => import("./components/vx-breadcrumb/VxBreadcrumb")
const FeatherIcon = () => import("./components/FeatherIcon")
const VxColor = () => import("./components/vx-color/VxColor")
const VxPopup = () => import("./components/vx-popup/VxPopup")
const VxListView = () => import("./components/vx-list-view/VxListView")
const CustomRender = () => import("./helpers/utils").then(({CustomRender}) => CustomRender)
const VueEditor = () => import("vue2-editor").then(({VueEditor}) => VueEditor)

const components = {
    VxCard,
    VxTable,
    TableCustom,
    VxTh,
    VxTr,
    VxTd,
    VxDropdown,
    VxDrawer,
    VxUpload,
    VxView,
    VxBreadcrumb,
    FeatherIcon,
    VxColor,
    VxPopup,
    VxListView,
    CustomRender,
    VueEditor
}

Object.keys(components).forEach(key => {
    Vue.component(key, components[key])
})
