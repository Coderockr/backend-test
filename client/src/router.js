import Vue from 'vue';
import VueRouter from 'vue-router'
import store from './store/store'
import ViewUI from 'view-design'
import {
  Dashboard,
  User,
  Customizer
} from "./all-components"
ViewUI.LoadingBar.config({
  color: 'var(--primary)',
  failedColor: 'var(--danger)',
})
const panel = {
  label:'Painel',
  url:'/',
  icon:'HomeIcon',
  index:0,
  children:false
}
const records = {
  label:'Cadastros',
  url:'',
  icon:'FolderIcon',
  index:1,
  children:false
}
const setting = {
  label:'Configurações',
  url:'',
  icon:'SettingsIcon',
  index:1,
  children:true,
  options:[
      {
        icon:'SettingsIcon',
        iconPath:'feather',
        label:'Personalizar',
        url:'/customize',
      }
  ]
}
Vue.use(VueRouter)
const router = new VueRouter({
    mode: 'history',
    routes: [
      { 
        path: '',
        component: () => import('./containers/Main'),
        meta: {
          requiresAuth: true
        },
        children: [
          {
            path: '/',
            meta:{
              name: 'Painel',
              breadcrumb:[
                {
                  label:'Painel',
                  url:'',
                  icon:'HomeIcon',
                  index:0,
                  children:false
                }
              ]
            },
            component: Dashboard,
          },
          {
            path: '/users',
            meta:{
              name:'Usuários',
              breadcrumb:[
                panel,
                records,
                {
                    label:'Usuários',
                    url:'',
                    icon:'UsersIcon',
                    children:false
                },
              ],
            },
            component: User,
          },
          {
            path: '/customize',
            meta:{
              name:'Personalizar',
              breadcrumb:[
                panel,
                setting,
                {
                    label:'Personalizar',
                    url:'',
                    icon:'SettingsIcon',
                    children:false
                },
              ],
            },
            component: Customizer,
          },
        ]
      },
      {
        path: "/login",
        name: 'login',
        component: () => import("./containers/pages/Login.vue")
      },
      {
        path: '/error-404',
        name: 'error404',
        component: () => import('./containers/pages/Error404.vue')
      },
      {
        path: '/error-500',
        name: 'error500',
        component: () => import('./containers/pages/Error500.vue')
      },
      {
        path: '*',
        redirect: '/error-404'
      }
    ]
})

router.beforeEach((to, from, next) => {
  ViewUI.LoadingBar.start();
  if (to.matched.some(record => record.meta.requiresAuth)) {
      if (store.getters.isLoggedIn) {
          return next()
      }
      return next({path: '/login', query: {redirect: to.path}})
  } else if (to.fullPath == '/login' && store.getters.isLoggedIn) {
      return next({path: '/'})
  }
  next()
})

router.afterEach(() => {
  ViewUI.LoadingBar.finish();
})

export default router