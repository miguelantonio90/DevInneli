import inventory from '../../api/inventory'

const FETCHING_INVENTORIES = 'FETCHING_INVENTORIES'
const SWITCH_INVENTORY_NEW_MODAL = 'SWITCH_INVENTORY_NEW_MODAL'
const SWITCH_INVENTORY_EDIT_MODAL = 'SWITCH_INVENTORY_EDIT_MODAL'
const SWITCH_INVENTORY_SHOW_MODAL = 'SWITCH_INVENTORY_SHOW_MODAL'
const INVENTORY_CREATED = 'INVENTORY_CREATED'
const INVENTORY_EDIT = 'INVENTORY_EDIT'
const INVENTORY_UPDATED = 'INVENTORY_UPDATED'
const INVENTORY_DELETE = 'INVENTORY_DELETE'
const INVENTORY_TABLE_LOADING = 'INVENTORY_TABLE_LOADING'
const FAILED_INVENTORY = 'FAILED_INVENTORY'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_INVENTORY = 'SET_EDIT_INVENTORY'
const SET_INVENTORY_AVATAR = 'SET_INVENTORY_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  inventories: [],
  avatar: '',
  loading: false,
  saved: false,
  newInventory: {
    no_facture: '',
    pay: '',
    taxes: [],
    payments: {},
    articles: [],
    shop: null,
    supplier: null
  },
  editInventory: {
    id: '',
    no_facture: '',
    pay: '',
    taxes: [],
    payments: {},
    articles: [],
    articles_shops: [],
    shop: {},
    supplier: {},
    supplier_id: ''
  },
  isInventoryTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_INVENTORY_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_INVENTORY_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_INVENTORY_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [INVENTORY_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_INVENTORIES] (state, inventories) {
    state.inventories = []
    inventories.forEach((value) => {
      if (!value.parent_id) {
        state.inventories.push(value)
      }
    })
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [INVENTORY_CREATED] (state) {
    state.showNewModal = false
    state.newInventory = {
      no_facture: '',
      pay: '',
      taxes: [],
      payments: {},
      articles: [],
      shop: {},
      supplier: {}
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.inventories.name')]
      )
    })
  },
  [INVENTORY_EDIT] (state, inventoryId) {
    state.editInventory = Object.assign({}, state.inventories
      .filter(node => node.id === inventoryId)
      .shift()
    )
  },
  [INVENTORY_UPDATED] (state) {
    state.showEditModal = false
    state.editInventory = {
      id: '',
      no_facture: '',
      pay: '',
      taxes: [],
      payments: {},
      articles: [],
      shop: {},
      supplier: {}
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.inventories.name')]
      )
    })
  },
  [SET_EDIT_INVENTORY] (state, profile) {
    state.editInventory.push(profile)
  },
  [INVENTORY_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.inventories.name')]
      )
    })
  },
  [SET_INVENTORY_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_INVENTORY] (state, error) {
    state.isActionInProgress = false
    state.isInventoryTableLoading = false
    state.isTableLoading = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.inventories.name')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_INVENTORY_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_INVENTORY_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_INVENTORY_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, inventoryId) {
    commit(INVENTORY_EDIT, inventoryId)
  },
  openShowModal ({ commit }, inventoryId) {
    commit(SWITCH_INVENTORY_SHOW_MODAL, true)
    commit(INVENTORY_EDIT, inventoryId)
  },
  async getInventories ({ commit }) {
    commit(INVENTORY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await inventory
      .fetchInventories()
      .then(({ data }) => {
        commit(FETCHING_INVENTORIES, data.data)
        commit(INVENTORY_TABLE_LOADING, false)
      }).catch((error) => commit(FAILED_INVENTORY, error))
  },
  async createInventory ({ commit, dispatch }, newInventory) {
    commit(ENV_DATA_PROCESS, true)

    await inventory
      .sendCreateRequest(newInventory)
      .then(() => {
        commit(INVENTORY_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('inventory/getInventories', null, { root: true })
      })
      .catch((error) => commit(FAILED_INVENTORY, error))
  },
  async updateInventory ({ commit, dispatch }, inventoryE) {
    commit(ENV_DATA_PROCESS, true)
    const request = inventoryE || state.editInventory

    // const request = profile || state.editUser
    await inventory
      .sendUpdateRequest(request)
      .then(() => {
        commit(INVENTORY_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('inventory/getInventories', null, { root: true })
      })
      .catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit(FAILED_INVENTORY, error)
      })
  },
  async deleteInventory ({ commit, dispatch }, inventoryId) {
    await inventory
      .sendDeleteRequest(inventoryId)
      .then(() => {
        commit(INVENTORY_DELETE)
        dispatch('inventory/getInventories', null, { root: true })
      })
      .catch((error) => commit(FAILED_INVENTORY, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
