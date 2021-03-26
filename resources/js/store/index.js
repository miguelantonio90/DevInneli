import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VuexPersistence from 'vuex-persist'
import app from './modules/app'
import auth from './modules/auth'
import user from './modules/user'
import accountCategory from './modules/accountCategory'
import accountMove from './modules/accountMove'
import account from './modules/account'
import assistance from './modules/assistance'
import role from './modules/role'
import keys from './modules/keys'
import shop from './modules/shop'
import payment from './modules/payment'
import client from './modules/client'
import supplier from './modules/supplier'
import company from './modules/company'
import bank from './modules/bank'
import category from './modules/category'
import article from './modules/article'
import statics from './modules/statics'
import refund from './modules/refund'
import settings from './modules/settings'
import products from './modules/products'
import shoppingCart from './modules/shoppingCart'
import expenseCategory from './modules/expense_category'
import exchangeRate from './modules/exchange_rate'
import typeOrder from './modules/type_order'
import tax from './modules/tax'
import discount from './modules/discount'
import inventory from './modules/inventory'
import sale from './modules/sale'
import supply from './modules/supply'
import boxes from './modules/boxes'
import openclose from './modules/openclose'
import modifiers from './modules/modifiers'
import online from './modules/online_config'

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
    boxes,
    openclose,
    settings,
    user,
    accountCategory,
    accountMove,
    account,
    assistance,
    role,
    keys,
    shop,
    bank,
    category,
    client,
    refund,
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
    inventory,
    sale,
    supply,
    online,
    modifiers
  },
  plugins: [vuexLocal.plugin],
  getters: {
    errors: state => state.errors
  },
  mutations: {
    SET_ERRORS (state, response) {
	  if (response) {
        state.errors = response.data
		  ? {
            status: response.status,
            message: response.data.message
		  }
		  : {
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
