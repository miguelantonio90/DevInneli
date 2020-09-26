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
  avatar: '',
  loading: false,
  saved: false,
  keys: ['supervisor', 'atm', 'waiter', 'seller'],
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
  },
  [ACCESS_EDIT] (state, roleId) {
    state.editAccess = state.roles.filter((node) => node.id === roleId)[0]
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
  },
  [ACCESS_DELETE] (state) {
    state.saved = true
  },
  [FAILED_ACCESS] (state) {
    state.saved = false
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
      }).catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async createRole ({ commit, dispatch }, newAccess) {
    commit(ENV_DATA_PROCESS, true)
    commit('CLEAR_ERRORS', null, { root: true })

    await role
      .sendCreateRequest(newAccess)
      .then(() => {
        commit(ACCESS_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async updateRole ({ commit, dispatch }, role) {
    commit('CLEAR_ERRORS', null, { root: true })
    commit(ENV_DATA_PROCESS, true)

    await role
      .sendUpdateRequest(role)
      .then(() => {
        commit(ACCESS_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async deleteRole ({ commit, dispatch }, roleId) {
    commit('CLEAR_ERRORS', null, { root: true })

    await role
      .sendDeleteRequest(roleId)
      .then(() => {
        commit(ACCESS_DELETE)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
