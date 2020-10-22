import exchangeRate from '../../api/exchange_rate'
import currency from '../../api/currency'

const FETCHING_CHANGE = 'FETCHING_CHANGE'
const SWITCH_CHANGE_NEW_MODAL = 'SWITCH_CHANGE_NEW_MODAL'
const SWITCH_CHANGE_EDIT_MODAL = 'SWITCH_CHANGE_EDIT_MODAL'
const SWITCH_CHANGE_SHOW_MODAL = 'SWITCH_CHANGE_SHOW_MODAL'
const CHANGE_CREATED = 'CHANGE_CREATED'
const CHANGE_EDIT = 'CHANGE_EDIT'
const CHANGE_UPDATE = 'CHANGE_UPDATE'
const CHANGE_DELETE = 'CHANGE_DELETE'
const CHANGE_TABLE_LOADING = 'CHANGE_TABLE_LOADING'
const FAILED_CHANGE = 'FAILED_CHANGE'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_CHANGE = 'SET_EDIT_CHANGE'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  changes: [],
  saved: false,
  newChange: {
    country: null
  },
  editChange: {
    id: '',
    country: null
  },
  isChangeLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_CHANGE_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_CHANGE_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_CHANGE_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [CHANGE_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isChangeLoading = isLoading
  },
  [FETCHING_CHANGE] (state, changes) {
    state.changes = changes
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [CHANGE_CREATED] (state) {
    state.showNewModal = false
    state.newChange = {
      country: null,
      change: 0
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.exchange_rate')]
      )
    })
  },
  [CHANGE_EDIT] (state, categoryId) {
    state.editChange = Object.assign({}, state.changes
      .filter(node => node.id === categoryId)
      .shift()
    )
  },
  [CHANGE_UPDATE] (state) {
    state.showEditModal = false
    state.editChange = {
      id: '',
      country: null,
      change: 0
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.exchange_rate')]
      )
    })
  },
  [SET_EDIT_CHANGE] (state, profile) {
    state.editChange.push(profile)
  },
  [CHANGE_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.exchange_rate')]
      )
    })
  },
  [FAILED_CHANGE] (state, error) {
    state.isTableLoading = false
    state.isChangeLoading = false
    state.isActionInProgress = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.exchange_rate')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_CHANGE_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_CHANGE_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_CHANGE_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, categoryId) {
    commit(SWITCH_CHANGE_EDIT_MODAL, true)
    commit(CHANGE_EDIT, categoryId)
  },
  openShowModal ({ commit }, categoryId) {
    commit(SWITCH_CHANGE_SHOW_MODAL, true)
    commit(CHANGE_EDIT, categoryId)
  },
  async getChanges ({ commit, rootState }) {
    const current = rootState.auth.userData.company.currency
    commit(CHANGE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await exchangeRate
      .fetchExchangeRate()
      .then(({ data }) => {
        const changes = data.data
        changes.map((value, key) => {
          currency.getCurrencyRate(1, current, value.currency,
            function (e, amount) {
              data.data[key].change = amount
            })
        })
        commit(FETCHING_CHANGE, data.data)
        commit(CHANGE_TABLE_LOADING, false)
        return data
      }).catch((error) => commit(FAILED_CHANGE, error))
  },
  async createChange ({ commit, dispatch }, newChange) {
    commit(ENV_DATA_PROCESS, true)

    await exchangeRate
      .sendCreateRequest(newChange)
      .then(() => {
        commit(CHANGE_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('exchangeRate/getChanges', null, { root: true })
      })
      .catch((error) => commit(FAILED_CHANGE, error))
  },
  async updateChange ({ commit, dispatch }, editChange) {
    commit(ENV_DATA_PROCESS, true)
    await exchangeRate
      .sendUpdateRequest(editChange)
      .then(() => {
        commit(CHANGE_UPDATE)
        commit(ENV_DATA_PROCESS, false)
        dispatch('exchangeRate/getChanges', null, { root: true })
      })
      .catch((error) => commit(FAILED_CHANGE, error))
  },
  deleteChange: async function ({ commit, dispatch }, categoryId) {
    await exchangeRate
      .sendDeleteRequest(categoryId)
      .then(() => {
        commit(CHANGE_DELETE)
        dispatch('exchangeRate/getChanges', null, { root: true })
      })
      .catch((error) => commit(FAILED_CHANGE, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
