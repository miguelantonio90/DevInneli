import api from '../../api/modifiers'

const FETCHING_MODIFIERS = 'FETCHING_MODIFIERS'
const SWITCH_MODIFIERS_NEW_MODAL = 'SWITCH_MODIFIERS_NEW_MODAL'
const SWITCH_MODIFIERS_EDIT_MODAL = 'SWITCH_MODIFIERS_EDIT_MODAL'
const SWITCH_MODIFIERS_SHOW_MODAL = 'SWITCH_MODIFIERS_SHOW_MODAL'
const MODIFIERS_CREATED = 'MODIFIERS_CREATED'
const MODIFIERS_EDIT = 'MODIFIERS_EDIT'
const MODIFIERS_UPDATED = 'MODIFIERS_UPDATED'
const MODIFIERS_DELETE = 'MODIFIERS_DELETE'
const MODIFIERS_TABLE_LOADING = 'MODIFIERS_TABLE_LOADING'
const FAILED_MODIFIERS = 'FAILED_MODIFIERS'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  modifiers: [],
  loading: false,
  saved: false,
  newModifier: {
	name: '',
	value: '',
	shops: [],
	percent: false
  },
  editModifier: {
	id: '',
	name: '',
	value: '',
	shops: [],
	percent: false
  },
  isModifiersLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_MODIFIERS_NEW_MODAL] (state, showModal) {
	state.showNewModal = showModal
  },
  [SWITCH_MODIFIERS_EDIT_MODAL] (state, showModal) {
	state.showEditModal = showModal
  },
  [SWITCH_MODIFIERS_SHOW_MODAL] (state, showModal) {
	state.showShowModal = showModal
  },
  [MODIFIERS_TABLE_LOADING] (state, isLoading) {
	state.isTableLoading = isLoading
	state.isModifiersLoading = isLoading
  },
  [FETCHING_MODIFIERS] (state, modifiers) {
	state.modifiers = modifiers
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
	state.isActionInProgress = isActionInProgress
  },
  [CANCEL_MODAL] (state) {
	state.newModifier = {
	  name: '',
	  value: '',
	  shops: [],
	  percent: false
	}
	state.saved = false
  },
  [MODIFIERS_CREATED] (state) {
	state.showNewModal = false
	state.newModifier = {
	  name: '',
	  value: '',
	  shops: [],
	  percent: false
	}
	state.saved = true
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
		this._vm.$language.t('$vuetify.menu.modifiers')
	  ])
	})
  },
  [MODIFIERS_EDIT] (state, modifierId) {
	state.editModifier = Object.assign(
	  {},
	  state.modifiers.filter(node => node.id === modifierId).shift()
	)
  },
  [MODIFIERS_UPDATED] (state) {
	state.showEditModal = false
	state.editModifier = {
	  id: '',
	  name: '',
	  value: '',
	  shops: [],
	  percent: false
	}
	state.saved = true
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
		this._vm.$language.t('$vuetify.menu.modifiers')
	  ])
	})
  },
  [MODIFIERS_DELETE] (state, error) {
	state.saved = true
	state.error = error
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
		this._vm.$language.t('$vuetify.menu.modifiers')
	  ])
	})
  },
  [FAILED_MODIFIERS] (state, error) {
	state.saved = false
	state.error = error
	state.isActionInProgress = false
	this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
		this._vm.$language.t('$vuetify.menu.modifiers')
	  ])
	})
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
	commit(SWITCH_MODIFIERS_NEW_MODAL, showModal)
	if (!showModal) {
	  commit(CANCEL_MODAL)
	}
  },
  toogleEditModal ({ commit }, showModal) {
	commit(SWITCH_MODIFIERS_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
	commit(SWITCH_MODIFIERS_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, modifierId) {
	commit(SWITCH_MODIFIERS_EDIT_MODAL, true)
	commit(MODIFIERS_EDIT, modifierId)
  },
  openShowModal ({ commit }, modifierId) {
	commit(SWITCH_MODIFIERS_SHOW_MODAL, true)
	commit(MODIFIERS_EDIT, modifierId)
  },
  async getModifiers ({
	commit,
	dispatch
  }) {
	commit(MODIFIERS_TABLE_LOADING, true)
	// noinspection JSUnresolvedVariable
	await api
	  .fetchModifiers()
	  .then(({ data }) => {
		commit(FETCHING_MODIFIERS, data.data)
		commit(MODIFIERS_TABLE_LOADING, false)
		dispatch('auth/updateAccess', data.access, { root: true })
	  })
	  .catch(error => commit(FAILED_MODIFIERS, error))
  },
  async createModifiers ({
	commit,
	dispatch
  }, newModifier) {
	commit(ENV_DATA_PROCESS, true)

	await api
	  .sendCreateRequest(newModifier)
	  .then(data => {
		commit(MODIFIERS_CREATED)
		commit(ENV_DATA_PROCESS, false)
		dispatch('modifiers/getModifiers', null, { root: true })
		dispatch('auth/updateAccess', data.access, { root: true })
	  })
	  .catch(error => commit(FAILED_MODIFIERS, error))
  },
  async updateModifiers ({
	commit,
	dispatch
  }, editModifier) {
	commit(ENV_DATA_PROCESS, true)
	await api
	  .sendUpdateRequest(editModifier)
	  .then(data => {
		commit(MODIFIERS_UPDATED)
		commit(ENV_DATA_PROCESS, false)
		dispatch('modifiers/getModifiers', null, { root: true })
		dispatch('auth/updateAccess', data.access, { root: true })
	  })
	  .catch(error => commit(FAILED_MODIFIERS, error))
  },
  async deleteModifiers ({
	commit,
	dispatch
  }, modifierId) {
	await api
	  .sendDeleteRequest(modifierId)
	  .then(data => {
		commit(MODIFIERS_DELETE)
		dispatch('modifiers/getModifiers', null, { root: true })
		dispatch('auth/updateAccess', data.access, { root: true })
	  })
	  .catch(error => commit(FAILED_MODIFIERS, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
