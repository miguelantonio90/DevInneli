import tax from '../../api/tax'

const FETCHING_TAXES = 'FETCHING_TAXESS'
const SWITCH_TAXES_NEW_MODAL = 'SWITCH_TAXES_NEW_MODAL'
const SWITCH_TAXES_EDIT_MODAL = 'SWITCH_TAXES_EDIT_MODAL'
const SWITCH_TAXES_SHOW_MODAL = 'SWITCH_TAXES_SHOW_MODAL'
const TAXES_CREATED = 'TAXES_CREATED'
const TAXES_EDIT = 'TAXES_EDIT'
const TAXES_UPDATED = 'TAXES_UPDATED'
const TAXES_DELETE = 'TAXES_DELETE'
const TAXES_TABLE_LOADING = 'TAXES_TABLE_LOADING'
const FAILED_TAXES = 'FAILED_TAXES'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_TAXES = 'SET_EDIT_TAXES'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  taxes: [],
  loading: false,
  saved: false,
  newTax: {
    name: '',
    value: '',
    type: '',
    existing: false,
    percent: 'true'
  },
  editTax: {
    id: '',
    name: '',
    value: '',
    type: '',
    existing: false,
    percent: true
  },
  isTaxLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_TAXES_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_TAXES_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_TAXES_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [TAXES_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isTaxLoading = isLoading
  },
  [FETCHING_TAXES] (state, taxes) {
    state.taxes = taxes
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [TAXES_CREATED] (state) {
    state.showNewModal = false
    state.newTax = {
      name: '',
      value: '',
      type: '',
      existing: false,
      percent: true
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.tax_list')]
      )
    })
  },
  [TAXES_EDIT] (state, taxId) {
    state.editTax = Object.assign({}, state.taxes
      .filter(node => node.id === taxId)
      .shift()
    )
  },
  [TAXES_UPDATED] (state) {
    state.showEditModal = false
    state.editTax = {
      id: '',
      name: '',
      value: '',
      type: '',
      existing: false,
      percent: true
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.tax_list')]
      )
    })
  },
  [SET_EDIT_TAXES] (state, profile) {
    state.editTax.push(profile)
  },
  [TAXES_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.tax_list')]
      )
    })
  },
  [FAILED_TAXES] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.tax_list')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_TAXES_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_TAXES_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_TAXES_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, taxId) {
    commit(SWITCH_TAXES_EDIT_MODAL, true)
    commit(TAXES_EDIT, taxId)
  },
  openShowModal ({ commit }, taxId) {
    commit(SWITCH_TAXES_SHOW_MODAL, true)
    commit(TAXES_EDIT, taxId)
  },
  async getTaxes ({ commit }) {
    commit(TAXES_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await tax
      .fetchTaxes()
      .then(({ data }) => {
        commit(FETCHING_TAXES, data.data)
        commit(TAXES_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data.access)
        return data
      }).catch((error) => commit(FAILED_TAXES, error))
  },
  async createTax ({ commit, dispatch }, newTax) {
    commit(ENV_DATA_PROCESS, true)

    await tax
      .sendCreateRequest(newTax)
      .then((data) => {
        commit(TAXES_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('tax/getTaxes', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch((error) => commit(FAILED_TAXES, error))
  },
  async updateTax ({ commit, dispatch }, editTax) {
    commit(ENV_DATA_PROCESS, true)
    await tax
      .sendUpdateRequest(editTax)
      .then((data) => {
        commit(TAXES_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('tax/getTaxes', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch((error) => commit(FAILED_TAXES, error))
  },
  async deleteTax ({ commit, dispatch }, taxId) {
    await tax
      .sendDeleteRequest(taxId)
      .then((data) => {
        commit(TAXES_DELETE)
        dispatch('tax/getTaxes', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch((error) => commit(FAILED_TAXES, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
