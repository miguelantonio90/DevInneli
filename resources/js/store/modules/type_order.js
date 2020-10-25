import ordersAPI from '../../api/type_order'

const FETCHING = 'FETCHING'
const SWITCH_NEW_MODAL = 'SWITCH_NEW_MODAL'
const SWITCH_EDIT_MODAL = 'SWITCH_EDIT_MODAL'
const SWITCH_SHOW_MODAL = 'SWITCH_SHOW_MODAL'
const ORDER_CREATED = 'ORDER_CREATED'
const ORDER_EDIT = 'ORDER_EDIT'
const ORDER_UPDATE = 'ORDER_UPDATE'
const ORDER_DELETE = 'ORDER_DELETE'
const ORDER_TABLE_LOADING = 'ORDER_TABLE_LOADING'
const FAILED_ORDER = 'FAILED_ORDER'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  typeOrders: [],
  saved: false,
  newOrder: {
    name: '',
    description: '',
    principal: false,
    shops: []
  },
  editOrder: {
    id: '',
    name: '',
    description: '',
    principal: false,
    shops: []
  },
  isOrderLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [ORDER_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isChangeLoading = isLoading
  },
  [FETCHING] (state, typeOrders) {
    state.typeOrders = typeOrders
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [ORDER_CREATED] (state) {
    state.showNewModal = false
    state.newOrder = {
      name: '',
      description: '',
      principal: false,
      shops: []
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.type_of_order')]
      )
    })
  },
  [ORDER_EDIT] (state, id) {
    state.editOrder = Object.assign({}, state.typeOrders
      .filter(node => node.id === id)
      .shift()
    )
  },
  [ORDER_UPDATE] (state) {
    state.showEditModal = false
    state.editOrder = {
      id: '',
      name: '',
      description: '',
      principal: false,
      shops: []
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.type_of_order')]
      )
    })
  },
  [ORDER_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.type_of_order')]
      )
    })
  },
  [FAILED_ORDER] (state, error) {
    state.isTableLoading = false
    state.isOrderLoading = false
    state.isActionInProgress = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.type_of_order')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, id) {
    commit(SWITCH_EDIT_MODAL, true)
    commit(ORDER_EDIT, id)
  },
  openShowModal ({ commit }, id) {
    commit(SWITCH_SHOW_MODAL, true)
    commit(ORDER_EDIT, id)
  },
  async getTypeOfOrders ({ commit }) {
    commit(ORDER_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await ordersAPI
      .fetchTypeOrders()
      .then(({ data }) => {
        commit(FETCHING, data.data)
        commit(ORDER_TABLE_LOADING, false)
        return data
      }).catch((error) => commit(FAILED_ORDER, error))
  },
  async createTypeOrder ({ commit, dispatch }, newOrder) {
    commit(ENV_DATA_PROCESS, true)

    await ordersAPI
      .sendCreateRequest(newOrder)
      .then(() => {
        commit(ORDER_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('typeOrder/getTypeOfOrders', null, { root: true })
      })
      .catch((error) => commit(FAILED_ORDER, error))
  },
  async updateTypeOrder ({ commit, dispatch }, editOrder) {
    commit(ENV_DATA_PROCESS, true)

    await ordersAPI
      .sendUpdateRequest(editOrder)
      .then(() => {
        commit(ORDER_UPDATE)
        commit(ENV_DATA_PROCESS, false)
        dispatch('typeOrder/getTypeOfOrders', null, { root: true })
      })
      .catch((error) => commit(FAILED_ORDER, error))
  },
  async deleteTypeOrder ({ commit, dispatch }, id) {
    commit(ENV_DATA_PROCESS, true)

    await ordersAPI
      .sendDeleteRequest(id)
      .then(() => {
        commit(ORDER_DELETE)
        commit(ENV_DATA_PROCESS, false)
        dispatch('typeOrder/getTypeOfOrders', null, { root: true })
      })
      .catch((error) => commit(FAILED_ORDER, error))
  },
  async setPrincipalTypeOrder ({ commit, dispatch }, item) {
    commit(ENV_DATA_PROCESS, true)

    await ordersAPI
      .sendSetPrincipal(item)
      .then(() => {
        commit(ENV_DATA_PROCESS, false)
        dispatch('typeOrder/getTypeOfOrders', null, { root: true })
      })
      .catch((error) => commit(FAILED_ORDER, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
