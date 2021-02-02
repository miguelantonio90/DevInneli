import apiSupply from '../../api/supply'
import moment from 'moment'
import { fullName } from '../../util'

const FETCHING_SUPPLIES = 'FETCHING_SUPPLIES'
const FETCHING_SUPPLIES_BY_CATEGORIES = 'FETCHING_SUPPLIES_BY_CATEGORIES'
const FETCHING_SUPPLIES_BY_PAYMENT = 'FETCHING_SUPPLIES_BY_PAYMENT'
const FETCHING_SUPPLIES_BY_PRODUCT = 'FETCHING_SUPPLIES_BY_PRODUCT'
const FETCHING_SUPPLIES_BY_EMPLOYER = 'FETCHING_SUPPLIES_BY_EMPLOYER'
const FETCHING_SUPPLIES_BY_LIMIT = 'FETCHING_SUPPLIES_BY_LIMIT'
const FETCHING_SUPPLIES_NUMBER = 'FETCHING_SUPPLIES_NUMBER'
const FETCHING_SUPPLIES_TOTAL = 'FETCHING_SUPPLIES_TOTAL'
const SWITCH_LOAD_DATA = 'SWITCH_LOAD_DATA'
const SWITCH_SUPPLY_NEW_MODAL = 'SWITCH_SUPPLY_NEW_MODAL'
const SWITCH_SUPPLY_EDIT_MODAL = 'SWITCH_SUPPLY_EDIT_MODAL'
const SWITCH_SUPPLY_SHOW_MODAL = 'SWITCH_SUPPLY_SHOW_MODAL'
const SUPPLY_CREATED = 'SUPPLY_CREATED'
const SUPPLY_EDIT = 'SUPPLY_EDIT'
const SUPPLY_UPDATED = 'SUPPLY_UPDATED'
const SUPPLY_DELETE = 'SUPPLY_DELETE'
const SUPPLY_TABLE_LOADING = 'SUPPLY_TABLE_LOADING'
const FAILED_SUPPLY = 'FAILED_SUPPLY'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_SUPPLY = 'SET_EDIT_SUPPLY'
const SET_SUPPLY_AVATAR = 'SET_SUPPLY_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  supplies: [],
  avatar: '',
  loadData: false,
  loading: false,
  saved: false,
  managerSupply: false,
  newSupply: {
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
  editSupply: {
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
  isSupplyTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false,
  suppliesByCategories: [],
  suppliesByPayments: [],
  suppliesByProducts: [],
  suppliesByEmployer: [],
  suppliesByLimit: [],
  suppliesStatics: [],
  supplyNumber: ''
}

const mutations = {
  [SWITCH_SUPPLY_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_LOAD_DATA] (state, loadData) {
    state.loadData = loadData
  },
  [SWITCH_SUPPLY_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_SUPPLY_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [SUPPLY_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_SUPPLIES] (state, supplies) {
    state.supplies = []
    state.supplies = supplies
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [SUPPLY_CREATED] (state) {
    state.showNewModal = false
    state.newSupply = {
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
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.supply_productS')]
      )
    })
  },
  [SUPPLY_EDIT] (state, supplyId) {
    state.editSupply = Object.assign({}, state.supplies
      .filter(node => node.id === supplyId)
      .shift()
    )
  },
  [SUPPLY_UPDATED] (state) {
    state.showEditModal = false
    state.editSupply = {
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
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.supply_productS')]
      )
    })
  },
  [SET_EDIT_SUPPLY] (state, profile) {
    state.editSupply.push(profile)
  },
  [SUPPLY_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.supply_productS')]
      )
    })
  },
  [SET_SUPPLY_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_SUPPLY] (state, error) {
    state.isActionInProgress = false
    state.isSupplyTableLoading = false
    state.isTableLoading = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.supply_productS')]
      )
    })
  },
  [FETCHING_SUPPLIES_BY_CATEGORIES] (state, supplyByCategory) {
    state.suppliesByCategories = supplyByCategory
  },
  [FETCHING_SUPPLIES_BY_PAYMENT] (state, suppliesByPayment) {
    state.suppliesByPayments = suppliesByPayment
  },
  [FETCHING_SUPPLIES_BY_PRODUCT] (state, suppliesByProduct) {
    state.suppliesByProducts = suppliesByProduct
  },
  [FETCHING_SUPPLIES_BY_EMPLOYER] (state, suppliesByEmployer) {
    state.suppliesByEmployer = suppliesByEmployer
  },
  [FETCHING_SUPPLIES_NUMBER] (state, supplyNumber) {
    state.supplyNumber = supplyNumber
  },
  [FETCHING_SUPPLIES_BY_LIMIT] (state, suppliesByLimit) {
    suppliesByLimit.map((value) => {
      switch (value.state) {
        case 'open':
          value.status = this._vm.$language.t('$vuetify.supply.state.open')
          value.color = 'green'
          break
        case 'accepted':
          value.status = this._vm.$language.t('$vuetify.supply.state.accepted')
          value.color = 'blue'
          break
        case 'cancelled':
          value.status = this._vm.$language.t('$vuetify.supply.state.cancelled')
          value.color = 'red'
          break
      }
      value.timeString = moment(value.created_at).fromNow()
      const createdName = fullName(value.created.firstName, value.created.lastName)
      const clientName = value.client ? fullName(value.client.firstName, value.client.lastName) : ''
      value.text = createdName + ' ' + this._vm.$language.t('$vuetify.dashboard.timeLineText') + ' ' + clientName
    })
    state.suppliesByLimit = suppliesByLimit
  },
  [FETCHING_SUPPLIES_TOTAL] (state, suppliesStatics) {
    state.suppliesStatics = suppliesStatics
  }
}

const getters = {
  getNumberFacture: (state) => {
    return state.supplyNumber
  }
}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_SUPPLY_NEW_MODAL, showModal)
  },
  switchLoadData ({ commit }, loadData) {
    commit(SWITCH_LOAD_DATA, loadData)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_SUPPLY_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_SUPPLY_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, supplyId) {
    commit(SUPPLY_EDIT, supplyId)
  },
  openShowModal ({ commit }, supplyId) {
    commit(SWITCH_SUPPLY_SHOW_MODAL, true)
    commit(SUPPLY_EDIT, supplyId)
  },
  async getSupplies ({
    commit,
    dispatch
  }) {
    commit(SUPPLY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSupply
      .fetchSupplys()
      .then(({ data }) => {
        commit(FETCHING_SUPPLIES, data.data)
        commit(SUPPLY_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SUPPLY, error))
  },
  async getSuppliesByCategories ({
    commit,
    dispatch
  }, filter) {
    commit(SUPPLY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSupply
      .fetchSupplyByCategory(filter)
      .then(({ data }) => {
        commit(FETCHING_SUPPLIES_BY_CATEGORIES, data.data)
        commit(SUPPLY_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SUPPLY, error))
  },
  async getSuppliesByPayment ({
    commit,
    dispatch
  }, filter) {
    commit(SUPPLY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSupply
      .fetchSupplyByPayment(filter)
      .then(({ data }) => {
        commit(FETCHING_SUPPLIES_BY_PAYMENT, data.data)
        commit(SUPPLY_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SUPPLY, error))
  },
  async getSupplyByProduct ({
    commit,
    dispatch
  }, filter) {
    commit(SUPPLY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSupply
      .fetchSupplyByProduct(filter)
      .then(({ data }) => {
        commit(FETCHING_SUPPLIES_BY_PRODUCT, data.data)
        commit(SUPPLY_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SUPPLY, error))
  },
  async getSupplyByEmployer ({
    commit,
    dispatch
  }, filter) {
    commit(SUPPLY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSupply
      .fetchSupplyByEmployer(filter)
      .then(({ data }) => {
        commit(FETCHING_SUPPLIES_BY_EMPLOYER, data.data)
        commit(SUPPLY_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SUPPLY, error))
  },
  async getSupplyByLimit ({
    commit,
    dispatch
  }, filter) {
    commit(SUPPLY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSupply
      .fetchSupplyByLimit(filter)
      .then(({ data }) => {
        commit(FETCHING_SUPPLIES_BY_LIMIT, data.data)
        commit(SUPPLY_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => {
        commit(FAILED_SUPPLY, error)
      })
  },
  async getSupplyStatics ({
    commit,
    dispatch
  }) {
    commit(SUPPLY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiSupply
      .fetchSupplyStatics()
      .then(({ data }) => {
        commit(FETCHING_SUPPLIES_TOTAL, data.data)
        commit(SUPPLY_TABLE_LOADING, false)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SUPPLY, error))
  },
  async createSupply ({
    commit,
    dispatch
  }, newSupply) {
    commit(ENV_DATA_PROCESS, true)
    await apiSupply
      .sendCreateRequest(newSupply)
      .then((data) => {
        commit(SUPPLY_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('supply/getSupplies', null, { root: true })
        dispatch('auth/updateAccess', data.access, { root: true })
      })
      .catch((error) => commit(FAILED_SUPPLY, error))
  },
  async updateSupply ({
    commit,
    dispatch
  }, supplyE) {
    commit(ENV_DATA_PROCESS, true)
    const request = supplyE || state.editSupply

    // const request = profile || state.editUser
    await apiSupply
      .sendUpdateRequest(request)
      .then((data) => {
        commit(SUPPLY_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('supply/getSupplies', null, { root: true })
        dispatch('auth/updateAccess', data.access, { root: true })
      })
      .catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit(FAILED_SUPPLY, error)
      })
  },
  async deleteSupply ({
    commit,
    dispatch,
    state
  }, supplyId) {
    await apiSupply
      .sendDeleteRequest(supplyId)
      .then((data) => {
        commit(SUPPLY_DELETE)
        dispatch('supply/getSupplies', null, { root: true })
        dispatch('auth/updateAccess', data.access, { root: true })
      })
      .catch((error) => commit(FAILED_SUPPLY, error))
  },
  async fetchSupplyNumber ({
    commit,
    dispatch
  }) {
    await apiSupply
      .fetchSupplyNumber()
      .then(({ data }) => {
        commit(FETCHING_SUPPLIES_NUMBER, data.data)
        dispatch('auth/updateAccess', data.access, { root: true })
      }).catch((error) => commit(FAILED_SUPPLY, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
