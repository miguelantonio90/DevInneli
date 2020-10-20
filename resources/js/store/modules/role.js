import role from '../../api/role'

const FETCHING_ACCESS = 'FETCHING_ACCESS'
const SWITCH_ACCESS_NEW_MODAL = 'SWITCH_ACCESS_NEW_MODAL'
const SWITCH_ACCESS_EDIT_MODAL = 'SWITCH_ACCESS_EDIT_MODAL'
const SWITCH_ACCESS_SHOW_MODAL = 'SWITCH_ACCESS_SHOW_MODAL'
const ACCESS_CREATED = 'ACCESS_CREATED'
const ACCESS_EDIT = 'ACCESS_EDIT'
const ACCESS_UPDATED = 'ACCESS_UPDATED'
const ACCESS_DELETE = 'ACCESS_DELETE'
const ACCESS_TABLE_LOADING = 'ACCESS_TABLE_LOADING'
const FAILED_ACCESS = 'FAILED_ACCESS'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  roles: [],
  rolesToSelect: [],
  avatar: '',
  loading: false,
  saved: false,
  keys: [
    { name: 'CEO Manager', value: 'super_manager', disabled: true },
    { name: 'Manager', value: 'manager' },
    { name: 'Supervisor (Supervisor)', value: 'supervisor' },
    { name: 'Atm (Cajero)', value: 'atm' },
    { name: 'Waiter (Camarero)', value: 'waiter' },
    { name: 'Seller (Vendedor)', value: 'seller' }
  ],
  newAccess: {
    key: '',
    name: '',
    accessPin: false,
    accessEmail: false,
    description: ''

  },
  editAccess: {
    id: '',
    key: '',
    name: '',
    accessPin: false,
    accessEmail: false,
    description: ''
  },
  isAccessLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_ACCESS_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_ACCESS_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_ACCESS_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [ACCESS_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isAccessLoading = isLoading
  },
  [FETCHING_ACCESS] (state, roles) {
    state.roles = roles
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [ACCESS_CREATED] (state) {
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
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [ACCESS_EDIT] (state, roleId) {
    state.editAccess = Object.assign({}, state.roles
      .filter(node => node.id === roleId)
      .shift()
    )
  },
  [ACCESS_UPDATED] (state) {
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
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [ACCESS_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [FAILED_ACCESS] (state, error) {
    state.saved = false
    state.error = error
    state.isAccessLoading = false
    state.isActionInProgress = false
    state.isTableLoading = false
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_ACCESS_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_ACCESS_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_ACCESS_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, roleId) {
    commit(SWITCH_ACCESS_EDIT_MODAL, true)
    commit(ACCESS_EDIT, roleId)
  },
  openShowModal ({ commit }, roleId) {
    commit(SWITCH_ACCESS_SHOW_MODAL, true)
    commit(ACCESS_EDIT, roleId)
  },
  async getRoles ({ commit }) {
    commit(ACCESS_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await role
      .fetchRoles()
      .then(({ data }) => {
        commit(FETCHING_ACCESS, data.data)
        commit(ACCESS_TABLE_LOADING, false)
      })
      .catch(error => commit(FAILED_ACCESS, error))
  },
  async createRole ({ commit, dispatch }, newAccess) {
    commit(ENV_DATA_PROCESS, true)

    await role
      .sendCreateRequest(newAccess)
      .then(() => {
        commit(ACCESS_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch(error => commit(FAILED_ACCESS, error))
  },
  async updateRole ({ commit, dispatch }, editAccess) {
    commit(ENV_DATA_PROCESS, true)

    await role
      .sendUpdateRequest(editAccess)
      .then(() => {
        commit(ACCESS_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch(error => commit(FAILED_ACCESS, error))
  },
  async deleteRole ({ commit, dispatch }, roleId) {
    await role
      .sendDeleteRequest(roleId)
      .then(() => {
        commit(ACCESS_DELETE)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch(error => commit(FAILED_ACCESS, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
