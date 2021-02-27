import role from '../../api/role'

const FETCHING_KEY = 'FETCHING_KEY'
const SWITCH_KEY_NEW_MODAL = 'SWITCH_KEY_NEW_MODAL'
const SWITCH_KEY_EDIT_MODAL = 'SWITCH_KEY_EDIT_MODAL'
const SWITCH_KEY_SHOW_MODAL = 'SWITCH_KEY_SHOW_MODAL'
const KEY_CREATED = 'KEY_CREATED'
const KEY_EDIT = 'KEY_EDIT'
const KEY_UPDATED = 'KEY_UPDATED'
const KEY_DELETE = 'KEY_DELETE'
const KEY_TABLE_LOADING = 'KEY_TABLE_LOADING'
const FAILED_KEY = 'FAILED_KEY'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const LOAD_KEY_CONST = 'LOAD_KEY_CONST'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  roles: [],
  rolesToSelect: [],
  avatar: '',
  loading: false,
  saved: false,
  keys: [],
  newAccess: {
	key: '',
	name: '',
	accessPin: false,
	accessEmail: false,
	description: '',
	access_permit: []
  },
  editAccess: {
	id: '',
	key: {},
	name: '',
	accessPin: false,
	accessEmail: false,
	description: '',
	access_permit: []
  },
  isAccessLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [LOAD_KEY_CONST] (state) {
	state.keys = []
  },
  [SWITCH_KEY_NEW_MODAL] (state, showModal) {
	state.showNewModal = showModal
  },
  [SWITCH_KEY_EDIT_MODAL] (state, showModal) {
	state.showEditModal = showModal
  },
  [SWITCH_KEY_SHOW_MODAL] (state, showModal) {
	state.showShowModal = showModal
  },
  [KEY_TABLE_LOADING] (state, isLoading) {
	state.isTableLoading = isLoading
	state.isAccessLoading = isLoading
  },
  [FETCHING_KEY] (state, roles) {
	state.roles = roles
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
	state.isActionInProgress = isActionInProgress
  },
  [CANCEL_MODAL] (state) {
	state.newAccess = {
	  key: '',
	  name: '',
	  accessPin: false,
	  accessEmail: false,
	  description: ''
	}
	state.saved = false
  },
  [KEY_CREATED] (state) {
	state.showNewModal = false
	state.newAccess = {
	  key: '',
	  name: '',
	  accessPin: false,
	  accessEmail: false,
	  description: ''
	}
	state.saved = true
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
		this._vm.$language.t('$vuetify.menu.access')
	  ])
	})
  },
  [KEY_EDIT] (state, roleId) {
	state.editAccess = Object.assign(
	  {},
	  state.roles.filter(node => node.id === roleId).shift()
	)
  },
  [KEY_UPDATED] (state) {
	state.showEditModal = false
	state.editAccess = {
	  id: '',
	  key: '',
	  name: '',
	  accessPin: false,
	  accessEmail: false,
	  description: ''
	}
	state.saved = true
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
		this._vm.$language.t('$vuetify.menu.access')
	  ])
	})
  },
  [KEY_DELETE] (state) {
	state.saved = true
	this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
		this._vm.$language.t('$vuetify.menu.access')
	  ])
	})
  },
  [FAILED_KEY] (state, error) {
	state.saved = false
	state.error = error
	state.isAccessLoading = false
	state.isActionInProgress = false
	state.isTableLoading = false
	this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
		this._vm.$language.t('$vuetify.menu.access')
	  ])
	})
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
	commit(LOAD_KEY_CONST)
	commit(SWITCH_KEY_NEW_MODAL, showModal)
	if (!showModal) {
	  commit(CANCEL_MODAL)
	}
  },
  toogleEditModal ({ commit }, showModal) {
	commit(LOAD_KEY_CONST)
	commit(SWITCH_KEY_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
	commit(SWITCH_KEY_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, roleId) {
	commit(SWITCH_KEY_EDIT_MODAL, true)
	commit(KEY_EDIT, roleId)
  },
  openShowModal ({ commit }, roleId) {
	commit(SWITCH_KEY_SHOW_MODAL, true)
	commit(KEY_EDIT, roleId)
  },
  async getRoles ({ commit }) {
	commit(KEY_TABLE_LOADING, true)
	// noinspection JSUnresolvedVariable
	await role
	  .fetchRoles()
	  .then(({ data }) => {
		commit(FETCHING_KEY, data.data)
		commit(KEY_TABLE_LOADING, false)
		this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_KEY, error))
  },
  loadKeysPermitConst ({ commit }) {
	commit(LOAD_KEY_CONST)
  },
  async createRole ({
	commit,
	dispatch
  }, newAccess) {
	commit(ENV_DATA_PROCESS, true)

	await role
	  .sendCreateRequest(newAccess)
	  .then(data => {
		commit(KEY_CREATED)
		commit(ENV_DATA_PROCESS, false)
		dispatch('role/getRoles', null, { root: true })
		this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_KEY, error))
  },
  async updateRole ({
	commit,
	dispatch
  }, editAccess) {
	commit(ENV_DATA_PROCESS, true)

	await role
	  .sendUpdateRequest(editAccess)
	  .then(data => {
		commit(KEY_UPDATED)
		commit(ENV_DATA_PROCESS, false)
		dispatch('role/getRoles', null, { root: true })
		this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_KEY, error))
  },
  async deleteRole ({
	commit,
	dispatch
  }, roleId) {
	await role
	  .sendDeleteRequest(roleId)
	  .then(data => {
		commit(KEY_DELETE)
		dispatch('role/getRoles', null, { root: true })
		this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_KEY, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
