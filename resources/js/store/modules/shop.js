import shop from '../../api/shop'

const FETCHING_SHOPS = 'FETCHING_SHOPS'
const SWITCH_SHOP_NEW_MODAL = 'SWITCH_SHOP_NEW_MODAL'
const SWITCH_SHOP_EDIT_MODAL = 'SWITCH_SHOP_EDIT_MODAL'
const SWITCH_SHOP_SHOW_MODAL = 'SWITCH_SHOP_SHOW_MODAL'
const SHOP_CREATED = 'SHOP_CREATED'
const SHOP_EDIT = 'SHOP_EDIT'
const SHOP_UPDATED = 'SHOP_UPDATED'
const SHOP_DELETE = 'SHOP_DELETE'
const SHOP_TABLE_LOADING = 'SHOP_TABLE_LOADING'
const FAILED_SHOP = 'FAILED_SHOP'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  shops: [],
  saved: false,
  newShop: {
    name: '',
    phone: '',
    description: '',
    address: '',
    country: ''

  },
  editShop: {
    id: '',
    name: '',
    phone: '',
    description: '',
    address: '',
    country: ''
  },
  isActionInProgress: false,
  isTableLoading: false,
  isShopLoading: false
}

const mutations = {
  [SWITCH_SHOP_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_SHOP_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_SHOP_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [SHOP_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isShopLoading = isLoading
  },
  [FETCHING_SHOPS] (state, shops) {
    state.shops = shops
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [SHOP_CREATED] (state) {
    state.showNewModal = false
    state.newShop = {
      name: '',
      phone: '',
      description: ''
    }
    state.saved = true
  },
  [SHOP_EDIT] (state, shopId) {
    state.editShop = state.shops.filter((node) => node.id === shopId)[0]
  },
  [SHOP_UPDATED] (state) {
    state.showEditModal = false
    state.editShop = {
      id: '',
      name: '',
      phone: '',
      description: ''
    }
    state.saved = true
  },
  [SHOP_DELETE] (state) {
    state.saved = true
  },
  [FAILED_SHOP] (state) {
    state.saved = false
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_SHOP_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_SHOP_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_SHOP_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, shopId) {
    commit(SWITCH_SHOP_EDIT_MODAL, true)
    commit(SHOP_EDIT, shopId)
  },
  openShowModal ({ commit }, shopId) {
    commit(SWITCH_SHOP_SHOW_MODAL, true)
    commit(SHOP_EDIT, shopId)
  },
  async getShops ({ commit }) {
    commit(SHOP_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await shop
      .fetchShops()
      .then(({ data }) => {
        commit(FETCHING_SHOPS, data.data)
        commit(SHOP_TABLE_LOADING, false)
      }).catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async createShop ({ commit, dispatch }, newShop) {
    commit(ENV_DATA_PROCESS, true)
    commit('CLEAR_ERRORS', null, { root: true })

    await shop
      .sendCreateRequest(newShop)
      .then(() => {
        commit(SHOP_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('shop/getShops', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async updateShop ({ commit, dispatch }, shopEdited) {
    commit('CLEAR_ERRORS', null, { root: true })
    commit(ENV_DATA_PROCESS, true)

    await shop
      .sendUpdateRequest(shopEdited)
      .then(() => {
        commit(SHOP_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('shop/getShops', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async deleteShop ({ commit, dispatch }, shopId) {
    commit('CLEAR_ERRORS', null, { root: true })

    await shop
      .sendDeleteRequest(shopId)
      .then(() => {
        commit(SHOP_DELETE)
        dispatch('shop/getShops', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
