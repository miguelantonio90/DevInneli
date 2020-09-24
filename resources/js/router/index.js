import Vue from 'vue'
import Router from 'vue-router'
import { protectedRoute, publicRoute } from './config'
import NProgress from 'nprogress'
import localStorage from '../config/localStorage'
import 'nprogress/nprogress.css'
import auth from '../store/modules/auth'

const routes = publicRoute.concat(protectedRoute)

Vue.use(Router)
const router = new Router({
  mode: 'history',
  linkActiveClass: 'active',
  routes: routes
})
// router gards
router.beforeEach((to, from, next) => {
  NProgress.start()
  // auth route is authenticated
  if (to.matched.some((route) => route.meta.requiresAuth)) {
    if (localStorage.getToken() && auth.getters.isLoggedIn) {
      next()
    } else {
      next({ path: '/auth/login' })
    }
  }
  next()
})

router.afterEach(() => {
  NProgress.done()
})

export default router
