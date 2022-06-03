import Vue from 'vue';
import VueRouter from 'vue-router'
import store from './store/store'
import ViewUI from 'view-design'
import {
  Login,
  Investment,
  Register
} from "./all-components"
ViewUI.LoadingBar.config({
  color: 'var(--primary)',
  failedColor: 'var(--danger)',
})
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
            path: '/investments',
            meta:{
              name:'Investimentos',
              breadcrumb:[
                {
                    label:'Investimentos',
                    url:'',
                    icon:'TrendingUpIcon',
                    children:false
                },
              ],
            },
            component: Investment,
          },
        ]
      },
      {
        path: "/login",
        name: 'login',
        component: Login
      },
      {
        path: "/register",
        name: 'register',
        component: Register
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