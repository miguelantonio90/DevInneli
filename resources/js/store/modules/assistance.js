import assistance from '../../api/assistance'

const FETCHING_ASSIST = 'FETCHING_ASSIST'
const SWITCH_ASSIST_NEW_MODAL = 'SWITCH_ASSIST_NEW_MODAL'
const SWITCH_ASSIST_EDIT_MODAL = 'SWITCH_ASSIST_EDIT_MODAL'
const SWITCH_ASSIST_SHOW_MODAL = 'SWITCH_ASSIST_SHOW_MODAL'
const ASSIST_CREATED = 'ASSIST_CREATED'
const ASSIST_EDIT = 'ASSIST_EDIT'
const ASSIST_UPDATED = 'ASSIST_UPDATED'
const ASSIST_DELETE = 'ASSIST_DELETE'
const TABLE_LOADING = 'TABLE_LOADING'
const FAILED_ASSIST = 'FAILED_ASSIST'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  assistances: [],
  saved: false,
  newAssistance: {
    datetimeEntry: '',
    datetimeExit: '',
    totalHours: '',
    user: '',
    shop: ''

  },
  editAssistance: {
    id: '',
    datetimeEntry: '',
    datetimeExit: '',
    totalHours: '',
    user: '',
    shop: ''
  },
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_ASSIST_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_ASSIST_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_ASSIST_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isAccessLoading = isLoading
  },
  [FETCHING_ASSIST] (state, assistances) {
    state.assistances = assistances
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [ASSIST_CREATED] (state) {
    state.showNewModal = false
    state.newAssistance = {
      datetimeEntry: '',
      datetimeExit: '',
      totalHours: '',
      user: '',
      shop: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.assistance')]
      )
    })
  },
  [ASSIST_EDIT] (state, assistanceId) {
    state.editAssistance = Object.assign({}, state.assistances
      .filter(node => node.id === assistanceId)
      .shift()
    )
  },
  [ASSIST_UPDATED] (state) {
    state.showEditModal = false
    state.editAssistance = {
      id: '',
      datetimeEntry: '',
      datetimeExit: '',
      totalHours: '',
      user: '',
      shop: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.assistance')]
      )
    })
  },
  [ASSIST_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.assistance')]
      )
    })
  },
  [FAILED_ASSIST] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    state.isTableLoading = false
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.assistance')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_ASSIST_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_ASSIST_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_ASSIST_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, assistanceId) {
    commit(SWITCH_ASSIST_EDIT_MODAL, true)
    commit(ASSIST_EDIT, assistanceId)
  },
  openShowModal ({ commit }, assistanceId) {
    commit(SWITCH_ASSIST_SHOW_MODAL, true)
    commit(ASSIST_EDIT, assistanceId)
  },
  async getAssistances ({ commit }) {
    commit(TABLE_LOADING, true)

    await assistance
      .fetchAssistances()
      .then(({ data }) => {
        commit(FETCHING_ASSIST, data.data)
        commit(TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
      })
      .catch(error => commit(FAILED_ASSIST, error))
  },
  async createAssistance ({ commit, dispatch }, newAssistance) {
    commit(ENV_DATA_PROCESS, true)

    await assistance
      .sendCreateRequest(newAssistance)
      .then((data) => {
        commit(ASSIST_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('assistance/getAssistances', null, { root: true })
        this.dispatch('auth/updateAccess', data)
      })
      .catch(error => commit(FAILED_ASSIST, error))
  },
  async updateAssistance ({ commit, dispatch }, editAssistance) {
    commit(ENV_DATA_PROCESS, true)

    await assistance
      .sendUpdateRequest(editAssistance)
      .then((data) => {
        commit(ASSIST_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('assistance/getAssistances', null, { root: true })
        this.dispatch('auth/updateAccess', data)
      })
      .catch(error => commit(FAILED_ASSIST, error))
  },
  async deleteAssistance ({ commit, dispatch }, assistanceId) {
    await assistance
      .sendDeleteRequest(assistanceId)
      .then((data) => {
        commit(ASSIST_DELETE)
        dispatch('assistance/getAssistances', null, { root: true })
        this.dispatch('auth/updateAccess', data)
      })
      .catch(error => commit(FAILED_ASSIST, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
