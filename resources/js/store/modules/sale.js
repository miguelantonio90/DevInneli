import apiSale from '../../api/sale'
import moment from 'moment'
import { fullName } from '../../util'

const FETCHING_SALES = 'FETCHING_SALES'
const FETCHING_SALES_BY_CATEGORIES = 'FETCHING_SALES_BY_CATEGORIES'
const FETCHING_SALES_BY_PAYMENT = 'FETCHING_SALES_BY_PAYMENT'
const FETCHING_SALES_BY_PRODUCT = 'FETCHING_SALES_BY_PRODUCT'
const FETCHING_SALES_BY_EMPLOYER = 'FETCHING_SALES_BY_EMPLOYER'
const FETCHING_SALES_BY_LIMIT = 'FETCHING_SALES_BY_LIMIT'
const FETCHING_SALES_NUMBER = 'FETCHING_SALES_NUMBER'
const FETCHING_SALES_TOTAL = 'FETCHING_SALES_TOTAL'
const SWITCH_LOAD_DATA = 'SWITCH_LOAD_DATA'
const SWITCH_SALE_NEW_MODAL = 'SWITCH_SALE_NEW_MODAL'
const SWITCH_SALE_EDIT_MODAL = 'SWITCH_SALE_EDIT_MODAL'
const SWITCH_SALE_SHOW_MODAL = 'SWITCH_SALE_SHOW_MODAL'
const SALE_CREATED = 'SALE_CREATED'
const SALE_EDIT = 'SALE_EDIT'
const SALE_UPDATED = 'SALE_UPDATED'
const SALE_DELETE = 'SALE_DELETE'
const SALE_TABLE_LOADING = 'SALE_TABLE_LOADING'
const FAILED_SALE = 'FAILED_SALE'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_SALE = 'SET_EDIT_SALE'
const SET_SALE_AVATAR = 'SET_SALE_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  sales: [],
  avatar: '',
  loadData: false,
  loading: false,
  saved: false,
  managerSale: false,
  newSale: {
    no_facture: '',
    pay: '',
    pays: [],
    box: null,
    state: 'open',
    discounts: [],
    taxes: [],
    payments: null,
    articles: [],
    shop: null,
    client: null
  },
  editSale: {
    id: '',
    no_facture: '',
    box: {},
    discounts: [],
    taxes: [],
    pays: [],
    payments: {},
    articles: [],
    articles_shops: [],
    shop: {},
    client: {},
    client_id: ''
  },
  isSaleTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false,
  salesByCategories: [],
  salesByPayments: [],
  salesByProducts: [],
  salesByEmployer: [],
  salesByLimit: [],
  salesStatics: [],
  saleNumber: ''
}

const mutations = {
  [SWITCH_SALE_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_LOAD_DATA] (state, loadData) {
    state.loadData = loadData
  },
  [SWITCH_SALE_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_SALE_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [SALE_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_SALES] (state, sales) {
    state.sales = []
    state.sales = sales
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [SALE_CREATED] (state) {
    state.showNewModal = false
    state.newSale = {
      no_facture: '',
      pays: [],
      box: null,
      state: 'open',
      discounts: [],
      taxes: [],
      payments: {},
      articles: [],
      shop: {},
      client: {}
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.sale.sale')]
      )
    })
  },
  [SALE_EDIT] (state, saleId) {
    state.editSale = Object.assign({}, state.sales
      .filter(node => node.id === saleId)
      .shift()
    )
  },
  [SALE_UPDATED] (state) {
    state.showEditModal = false
    state.editSale = {
      id: '',
      no_facture: '',
      pays: [],
      box: {},
      discounts: [],
      taxes: [],
      payments: {},
      articles: [],
      shop: {},
      client: {}
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.sale.sale')]
      )
    })
  },
  [SET_EDIT_SALE] (state, profile) {
    state.editSale.push(profile)
  },
  [SALE_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.sale.sale')]
      )
    })
  },
  [SET_SALE_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_SALE] (state, error) {
    state.isActionInProgress = false
    state.isSaleTableLoading = false
    state.isTableLoading = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.sale.sale')]
      )
    })
  },
  [FETCHING_SALES_BY_CATEGORIES] (state, saleByCategory) {
    state.salesByCategories = saleByCategory
  },
  [FETCHING_SALES_BY_PAYMENT] (state, salesByPayment) {
    state.salesByPayments = salesByPayment
  },
  [FETCHING_SALES_BY_PRODUCT] (state, salesByProduct) {
    state.salesByProducts = salesByProduct
  },
  [FETCHING_SALES_BY_EMPLOYER] (state, salesByEmployer) {
    state.salesByEmployer = salesByEmployer
  },
  [FETCHING_SALES_NUMBER] (state, saleNumber) {
    state.saleNumber = saleNumber
  },
  [FETCHING_SALES_BY_LIMIT] (state, salesByLimit) {
    salesByLimit.map((value) => {
      switch (value.state) {
        case 'open':
          value.status = this._vm.$language.t('$vuetify.sale.state.open')
          value.color = 'green'
          break
        case 'accepted':
          value.status = this._vm.$language.t('$vuetify.sale.state.accepted')
          value.color = 'blue'
          break
        case 'cancelled':
          value.status = this._vm.$language.t('$vuetify.sale.state.cancelled')
          value.color = 'red'
          break
      }
      value.timeString = moment(value.created_at).fromNow()
      const createdName = fullName(value.created.firstName, value.created.lastName)
      const clientName = value.client ? fullName(value.client.firstName, value.client.lastName) : ''
      value.text = createdName + ' ' + this._vm.$language.t('$vuetify.dashboard.timeLineText') + ' ' + clientName
    })
    state.salesByLimit = salesByLimit
  },
  [FETCHING_SALES_TOTAL] (state, salesStatics) {
    state.salesStatics = salesStatics
  }
}

const getters = {
  getNumberFacture: (state) => {
    return state.saleNumber
  }
}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_SALE_NEW_MODAL, showModal)
  },
  switchLoadData ({ commit }, loadData) {
    commit(SWITCH_LOAD_DATA, loadData)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_SALE_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_SALE_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, saleId) {
    commit(SALE_EDIT, saleId)
  },
  openShowModal ({ commit }, saleId) {
    commit(SWITCH_SALE_SHOW_MODAL, true)
    commit(SALE_EDIT, saleId)
  },
  async getSales ({
    commit,
    dispatch
  }) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSale
      .fetchSales()
      .then(({ data }) => {
        commit(FETCHING_SALES, data.data)
        commit(SALE_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SALE, error))
  },
  async getSalesByCategories ({
    commit,
    dispatch
  }, filter) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSale
      .fetchSaleByCategory(filter)
      .then(({ data }) => {
        commit(FETCHING_SALES_BY_CATEGORIES, data.data)
        commit(SALE_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SALE, error))
  },
  async getSalesByPayment ({
    commit,
    dispatch
  }, filter) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSale
      .fetchSaleByPayment(filter)
      .then(({ data }) => {
        commit(FETCHING_SALES_BY_PAYMENT, data.data)
        commit(SALE_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SALE, error))
  },
  async getSaleByProduct ({
    commit,
    dispatch
  }, filter) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSale
      .fetchSaleByProduct(filter)
      .then(({ data }) => {
        commit(FETCHING_SALES_BY_PRODUCT, data.data)
        commit(SALE_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SALE, error))
  },
  async getSaleByEmployer ({
    commit,
    dispatch
  }, filter) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSale
      .fetchSaleByEmployer(filter)
      .then(({ data }) => {
        commit(FETCHING_SALES_BY_EMPLOYER, data.data)
        commit(SALE_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SALE, error))
  },
  async getSaleByLimit ({
    commit,
    dispatch
  }, filter) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSale
      .fetchSaleByLimit(filter)
      .then(({ data }) => {
        commit(FETCHING_SALES_BY_LIMIT, data.data)
        commit(SALE_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => {
        commit(FAILED_SALE, error)
      })
  },
  async getSaleStatics ({
    commit,
    dispatch
  }) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSale
      .fetchSaleStatics()
      .then(({ data }) => {
        commit(FETCHING_SALES_TOTAL, data.data)
        commit(SALE_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SALE, error))
  },
  async createSale ({
    commit,
    dispatch
  }, newSale) {
    commit(ENV_DATA_PROCESS, true)
    await apiSale
      .sendCreateRequest(newSale)
      .then((data) => {
        commit(SALE_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('sale/getSales', null, { root: true })
        dispatch('auth/updateAccess', data.access, { root: true })
      })
      .catch((error) => commit(FAILED_SALE, error))
  },
  async updateSale ({
    commit,
    dispatch
  }, saleE) {
    commit(ENV_DATA_PROCESS, true)
    const request = saleE || state.editSale

    // const request = profile || state.editUser
    await apiSale
      .sendUpdateRequest(request)
      .then((data) => {
        commit(SALE_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('sale/getSales', null, { root: true })
        dispatch('auth/updateAccess', data.access, { root: true })
      })
      .catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit(FAILED_SALE, error)
      })
  },
  async deleteSale ({
    commit,
    dispatch,
    state
  }, saleId) {
    await apiSale
      .sendDeleteRequest(saleId)
      .then((data) => {
        commit(SALE_DELETE)
        dispatch('sale/getSales', null, { root: true })
        dispatch('auth/updateAccess', data.access, { root: true })
      })
      .catch((error) => commit(FAILED_SALE, error))
  },
  async fetchSaleNumber ({
    commit,
    dispatch
  }) {
    await apiSale
      .fetchSaleNumber()
      .then(({ data }) => {
        commit(FETCHING_SALES_NUMBER, data.data)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SALE, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
