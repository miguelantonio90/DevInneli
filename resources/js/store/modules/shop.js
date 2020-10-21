import shop from '../../api/shop'
import data from '../../data'

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
    primary: '',
    country: ''

  },
  editShop: {
    id: '',
    name: '',
    phone: '',
    description: '',
    address: '',
    primary: '',
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
    shops.map((value) => {
      Object.keys(data.countries).map((key) => {
        if (key === value.country) {
          value.nameCountry = data.countries[key].name + '(' + data.countries[key].native + ')'
          value.countryFlag = data.countries[key].emoji
        }
      })
    })

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
      description: '',
      address: '',
      primary: '',
      country: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.shop')]
      )
    })
  },
  [SHOP_EDIT] (state, shopId) {
    state.editShop = Object.assign({}, state.shops
      .filter(node => node.id === shopId)
      .shift()
    )
  },
  [SHOP_UPDATED] (state) {
    state.showEditModal = false
    state.editShop = {
      id: '',
      name: '',
      phone: '',
      description: '',
      address: '',
      primary: '',
      country: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.shop')]
      )
    })
  },
  [SHOP_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.shop')]
      )
    })
  },
  [FAILED_SHOP] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    state.isTableLoading = false
    state.isShopLoading = false
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.shop')]
      )
    })
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
      }).catch((error) => commit(FAILED_SHOP, error))
  },
  async createShop ({ commit, dispatch }, newShop) {
    commit(ENV_DATA_PROCESS, true)

    await shop
      .sendCreateRequest(newShop)
      .then(() => {
        commit(SHOP_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('shop/getShops', null, { root: true })
      })
      .catch((error) => commit(FAILED_SHOP, error))
  },
  async updateShop ({ commit, dispatch }, shopEdited) {
    commit(ENV_DATA_PROCESS, true)

    await shop
      .sendUpdateRequest(shopEdited)
      .then(() => {
        commit(SHOP_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('shop/getShops', null, { root: true })
      })
      .catch((error) => commit(FAILED_SHOP, error))
  },
  async deleteShop ({ commit, dispatch }, shopId) {
    await shop
      .sendDeleteRequest(shopId)
      .then(() => {
        commit(SHOP_DELETE)
        dispatch('shop/getShops', null, { root: true })
      })
      .catch((error) => commit(FAILED_SHOP, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
