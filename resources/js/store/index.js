import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VuexPersistence from 'vuex-persist'
import app from './modules/app'
import auth from './modules/auth'
import user from './modules/user'
import assistance from './modules/assistance'
import role from './modules/role'
import shop from './modules/shop'
import payment from './modules/payment'
import client from './modules/client'
import supplier from './modules/supplier'
import company from './modules/company'
import category from './modules/category'
import article from './modules/article'
import statics from './modules/statics'
import settings from './modules/settings'
import products from './modules/products'
import shoppingCart from './modules/shoppingCart'
import expenseCategory from './modules/expense_category'
import exchangeRate from './modules/exchange_rate'
import typeOrder from './modules/type_order'
import tax from './modules/tax'
import discount from './modules/discount'
import inventory from './modules/inventory'

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
    assistance,
    role,
    shop,
    category,
    client,
    supplier,
    article,
    company,
    products,
    shoppingCart,
    expenseCategory,
    exchangeRate,
    statics,
    payment,
    typeOrder,
    tax,
    discount,
    inventory
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
