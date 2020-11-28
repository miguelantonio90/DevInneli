import sale from '../../api/sale'

const FETCHING_SALES = 'FETCHING_SALES'
const FETCHING_SALES_BY_CATEGORIES = 'FETCHING_SALES_BY_CATEGORIES'
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
  loading: false,
  saved: false,
  newSale: {
    no_facture: '',
    pay: '',
    discounts: [],
    taxes: [],
    payments: {},
    articles: [],
    shop: null,
    client: null
  },
  editSale: {
    id: '',
    no_facture: '',
    pay: '',
    discounts: [],
    taxes: [],
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
  salesByCategories: []
}

const mutations = {
  [SWITCH_SALE_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
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
    sales.forEach((value) => {
      if (!value.parent_id) {
        state.sales.push(value)
      }
    })
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [SALE_CREATED] (state) {
    state.showNewModal = false
    state.newSale = {
      no_facture: '',
      pay: '',
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
      pay: '',
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
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_SALE_NEW_MODAL, showModal)
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
  async getSales ({ commit }) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await sale
      .fetchSales()
      .then(({ data }) => {
        commit(FETCHING_SALES, data.data)
        commit(SALE_TABLE_LOADING, false)
      }).catch((error) => commit(FAILED_SALE, error))
  },
  async getSalesByCategories ({ commit }, filter) {
    commit(SALE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await sale
      .fetchSaleByCategory(filter)
      .then(({ data }) => {
        commit(FETCHING_SALES_BY_CATEGORIES, data.data)
        commit(SALE_TABLE_LOADING, false)
      }).catch((error) => commit(FAILED_SALE, error))
  },
  async createSale ({ commit, dispatch }, newSale) {
    commit(ENV_DATA_PROCESS, true)
    await sale
      .sendCreateRequest(newSale)
      .then(() => {
        commit(SALE_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('sale/getSales', null, { root: true })
      })
      .catch((error) => commit(FAILED_SALE, error))
  },
  async updateSale ({ commit, dispatch }, saleE) {
    commit(ENV_DATA_PROCESS, true)
    const request = saleE || state.editSale

    // const request = profile || state.editUser
    await sale
      .sendUpdateRequest(request)
      .then(() => {
        commit(SALE_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('sale/getSales', null, { root: true })
      })
      .catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit(FAILED_SALE, error)
      })
  },
  async deleteSale ({ commit, dispatch, state }, saleId) {
    await sale
      .sendDeleteRequest(saleId)
      .then(() => {
        commit(SALE_DELETE)
        dispatch('sale/getSales', null, { root: true })
      })
      .catch((error) => commit(FAILED_SALE, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
