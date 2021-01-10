import discount from '../../api/discount'

const FETCHING_DISCOUNTS = 'FETCHING_DISCOUNTS'
const SWITCH_DISCOUNTS_NEW_MODAL = 'SWITCH_DISCOUNTS_NEW_MODAL'
const SWITCH_DISCOUNTS_EDIT_MODAL = 'SWITCH_DISCOUNTS_EDIT_MODAL'
const SWITCH_DISCOUNTS_SHOW_MODAL = 'SWITCH_DISCOUNTS_SHOW_MODAL'
const DISCOUNTS_CREATED = 'DISCOUNTS_CREATED'
const DISCOUNTS_EDIT = 'DISCOUNTS_EDIT'
const DISCOUNTS_UPDATED = 'DISCOUNTS_UPDATED'
const DISCOUNTS_DELETE = 'DISCOUNTS_DELETE'
const DISCOUNTS_TABLE_LOADING = 'DISCOUNTS_TABLE_LOADING'
const FAILED_DISCOUNTS = 'FAILED_DISCOUNTS'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_DISCOUNTS = 'SET_EDIT_DISCOUNTS'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  discounts: [],
  loading: false,
  saved: false,
  newDiscount: {
    name: '',
    value: '',
    percent: 'true'
  },
  editDiscount: {
    id: '',
    name: '',
    value: '',
    percent: true
  },
  isDiscountLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_DISCOUNTS_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_DISCOUNTS_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_DISCOUNTS_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [DISCOUNTS_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isDiscountLoading = isLoading
  },
  [FETCHING_DISCOUNTS] (state, discounts) {
    state.discounts = discounts
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [DISCOUNTS_CREATED] (state) {
    state.showNewModal = false
    state.newDiscount = {
      name: '',
      value: '',
      percent: true
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.discount')]
      )
    })
  },
  [DISCOUNTS_EDIT] (state, discountId) {
    state.editDiscount = Object.assign({}, state.discounts
      .filter(node => node.id === discountId)
      .shift()
    )
  },
  [DISCOUNTS_UPDATED] (state) {
    state.showEditModal = false
    state.editDiscount = {
      id: '',
      name: '',
      value: '',
      percent: true
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.discount')]
      )
    })
  },
  [SET_EDIT_DISCOUNTS] (state, profile) {
    state.editDiscount.push(profile)
  },
  [DISCOUNTS_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.discount')]
      )
    })
  },
  [FAILED_DISCOUNTS] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.discount')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_DISCOUNTS_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_DISCOUNTS_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_DISCOUNTS_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, discountId) {
    commit(SWITCH_DISCOUNTS_EDIT_MODAL, true)
    commit(DISCOUNTS_EDIT, discountId)
  },
  openShowModal ({ commit }, discountId) {
    commit(SWITCH_DISCOUNTS_SHOW_MODAL, true)
    commit(DISCOUNTS_EDIT, discountId)
  },
  async getDiscounts ({ commit }) {
    commit(DISCOUNTS_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await discount
      .fetchDiscounts()
      .then(({ data }) => {
        commit(FETCHING_DISCOUNTS, data.data)
        commit(DISCOUNTS_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data.access)
        return data
      }).catch((error) => commit(FAILED_DISCOUNTS, error))
  },
  async createDiscount ({ commit, dispatch }, newDiscount) {
    commit(ENV_DATA_PROCESS, true)

    await discount
      .sendCreateRequest(newDiscount)
      .then((data) => {
        commit(DISCOUNTS_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('discount/getDiscounts', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch((error) => commit(FAILED_DISCOUNTS, error))
  },
  async updateDiscount ({ commit, dispatch }, editDiscount) {
    commit(ENV_DATA_PROCESS, true)
    await discount
      .sendUpdateRequest(editDiscount)
      .then((data) => {
        commit(DISCOUNTS_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('discount/getDiscounts', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch((error) => commit(FAILED_DISCOUNTS, error))
  },
  async deleteDiscount ({ commit, dispatch }, discountId) {
    await discount
      .sendDeleteRequest(discountId)
      .then((data) => {
        commit(DISCOUNTS_DELETE)
        dispatch('discount/getDiscounts', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch((error) => commit(FAILED_DISCOUNTS, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
