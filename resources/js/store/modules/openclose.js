import openclose from '../../api/openclose'

const FETCHING_OPEN_CLOSES = 'FETCHING_OPEN_CLOSES'
const SWITCH_OPEN_CLOSE_NEW_MODAL = 'SWITCH_OPEN_CLOSE_NEW_MODAL'
const SWITCH_OPEN_CLOSE_EDIT_MODAL = 'SWITCH_OPEN_CLOSE_EDIT_MODAL'
const SWITCH_OPEN_CLOSE_SHOW_MODAL = 'SWITCH_OPEN_CLOSE_SHOW_MODAL'
const OPEN_CLOSE_CREATED = 'OPEN_CLOSE_CREATED'
const OPEN_CLOSE_EDIT = 'OPEN_CLOSE_EDIT'
const OPEN_CLOSE_UPDATED = 'OPEN_CLOSE_UPDATED'
const OPEN_CLOSE_DELETE = 'OPEN_CLOSE_DELETE'
const OPEN_CLOSE_TABLE_LOADING = 'OPEN_CLOSE_TABLE_LOADING'
const FAILED_OPEN_CLOSE = 'FAILED_OPEN_CLOSE'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_OPEN_CLOSE = 'SET_EDIT_OPEN_CLOSE'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showOpenModal: false,
  showCloseModal: false,
  saved: false,
  newOpenClose: {
	box: {},
	cashOpen: 0.0,
	openTo: {}
  },
  editOpenClose: {
	id: '',
	box: {},
	cashOpen: 0.0,
	cashClose: 0.0,
	openTo: {},
	closeBy: {}
  }
}

const mutations = {
  [SWITCH_OPEN_CLOSE_NEW_MODAL] (state, showModal) {
	state.showNewModal = showModal
  },
  [SWITCH_OPEN_CLOSE_EDIT_MODAL] (state, showModal) {
	state.showEditModal = showModal
  },
  [SWITCH_OPEN_CLOSE_SHOW_MODAL] (state, showModal) {
	state.showShowModal = showModal
  },
  [OPEN_CLOSE_TABLE_LOADING] (state, isLoading) {
	state.isTableLoading = isLoading
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
	state.isActionInProgress = isActionInProgress
  },
  [CANCEL_MODAL] (state) {
	state.newOpenClose = {
	  box: {},
	  cashOpen: 0,
	  openTo: {}
	}
	state.saved = false
  },
  [OPEN_CLOSE_CREATED] (state) {
	state.showNewModal = false
	state.newOpenClose = {
	  box: {},
	  cashOpen: 0.0,
	  openTo: {}
	}
	state.saved = true
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
		this._vm.$language.t('$vuetify.menu.openclose')
	  ])
	})
  },
  [OPEN_CLOSE_EDIT] (state, opencloseId) {
	state.editOpenClose = Object.assign(
	  {},
	  state.opencloses.filter(node => node.id === opencloseId).shift()
	)
  },
  [OPEN_CLOSE_UPDATED] (state) {
	state.showEditModal = false
	state.editOpenClose = {
	  id: '',
	  box: {},
	  cashOpen: 0.0,
	  cashClose: 0.0,
	  openTo: {},
	  closeBy: {}
	}
	state.saved = true
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
		this._vm.$language.t('$vuetify.menu.openclose')
	  ])
	})
  },
  [SET_EDIT_OPEN_CLOSE] (state, profile) {
	state.editOpenClose.push(profile)
  },
  [OPEN_CLOSE_DELETE] (state) {
	state.saved = true
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
		this._vm.$language.t('$vuetify.menu.openclose')
	  ])
	})
  },
  [FAILED_OPEN_CLOSE] (state, error) {
	state.saved = false
	state.error = error
	state.isOpenCloseTableLoading = false
	state.isActionInProgress = false
	state.isTableLoading = false
	this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
		this._vm.$language.t('$vuetify.menu.openclose')
	  ])
	})
  }
}

const getters = {}

const actions = {
  toogleOpenModal ({ commit }, showModal) {
	commit(SWITCH_OPEN_CLOSE_NEW_MODAL, showModal)
  },
  toogleCloseModal ({ commit }, showModal) {
	commit(SWITCH_OPEN_CLOSE_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
	commit(SWITCH_OPEN_CLOSE_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, opencloseId) {
	commit(SWITCH_OPEN_CLOSE_EDIT_MODAL, true)
	commit(OPEN_CLOSE_EDIT, opencloseId)
  },
  openShowModal ({ commit }, opencloseId) {
	commit(SWITCH_OPEN_CLOSE_SHOW_MODAL, true)
	commit(OPEN_CLOSE_EDIT, opencloseId)
  },
  async getOpenCloses ({ commit }) {
	commit(OPEN_CLOSE_TABLE_LOADING, true)
	// noinspection JSUnresolvedVariable
	await openclose
	  .fetchOpenClose()
	  .then(({ data }) => {
		commit(FETCHING_OPEN_CLOSES, data.data)
		commit(OPEN_CLOSE_TABLE_LOADING, false)
		this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_OPEN_CLOSE, error))
  },
  async createOpenClose ({
	commit,
	dispatch
  }, newOpenClose) {
	commit(ENV_DATA_PROCESS, true)

	await openclose
	  .sendCreateRequest(newOpenClose)
	  .then(data => {
		commit(OPEN_CLOSE_CREATED)
		commit(ENV_DATA_PROCESS, false)
		dispatch('openclose/getOpenCloses', null, { root: true })
		this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_OPEN_CLOSE, error))
  },
  async updateOpenClose ({
	commit,
	dispatch
  }, editOpenClose) {
	await openclose
	  .sendUpdateRequest(editOpenClose)
	  .then(data => {
		commit(OPEN_CLOSE_UPDATED)
		commit(ENV_DATA_PROCESS, false)
		dispatch('openclose/getOpenCloses', null, { root: true })
		this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_OPEN_CLOSE, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
