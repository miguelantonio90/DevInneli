import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'
import app from './modules/app'
import auth from './modules/auth'
import user from './modules/user'
import role from './modules/role'
import shop from './modules/shop'
import client from './modules/client'
import company from './modules/company'
import statics from './modules/statics'
import settings from './modules/settings'
import products from './modules/products'
import shoppingCart from './modules/shoppingCart'
import VuexPersistence from 'vuex-persist'
import router from '../router'

Vue.use(Vuex, VueAxios, axios)

const vuexLocal = new VuexPersistence({
  key: 'inneli',
  storage: window.localStorage,
  modules: ['app']
})

Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    errors: {}
  },
  modules: {
    app,
    auth,
    settings,
    user,
    role,
    shop,
    client,
    company,
    products,
    shoppingCart,
    statics
  },
  plugins: [vuexLocal.plugin],
  getters: {
    errors: (state) => state.errors
  },
  mutations: {
    SET_ERRORS (state, response) {
      if (response) {
        state.errors = response.data ? {
          status: response.status,
          message: response.data.message
        } : {
          status: false,
          message: response.message
        }

        this._vm.$Toast.fire({
          icon: 'error',
          title: state.errors.message
        })
        if (response.data) {
          if (response.data.message === 'Unauthenticated') {
            router.push({ name: 'Forbidden' })
          }
        } else if (response.message === 'Unauthenticated') {
          router.push({ name: 'Forbidden' })
        }
      } else {
        state.errors = {
          status: 'failed',
          message: 'Failed: Connections refused.'
        }
        this._vm.$Toast.fire({
          icon: 'error',
          title: state.errors.message
        })
      }
    },
    CLEAR_ERRORS (state) {
      state.errors = []
    }
  }
})

export default store
