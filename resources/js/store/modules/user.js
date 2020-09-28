import user from '../../api/user'

const FETCHING_USERS = 'FETCHING_USERS'
const SWITCH_USER_NEW_MODAL = 'SWITCH_USER_NEW_MODAL'
const SWITCH_USER_EDIT_MODAL = 'SWITCH_USER_EDIT_MODAL'
const SWITCH_USER_SHOW_MODAL = 'SWITCH_USER_SHOW_MODAL'
const USER_CREATED = 'USER_CREATED'
const USER_EDIT = 'USER_EDIT'
const USER_UPDATED = 'USER_UPDATED'
const USER_DELETE = 'USER_DELETE'
const USER_TABLE_LOADING = 'USER_TABLE_LOADING'
const FAILED_USER = 'FAILED_USER'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_USER = 'SET_EDIT_USER'
const SET_USER_AVATAR = 'SET_USER_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  users: [],
  avatar: '',
  loading: false,
  saved: false,
  newUser: {
    firstName: '',
    lastName: '',
    email: '',
    country: '',
    avatar: '',
    pinCode: '',
    phone: '',
    positions: [],
    shops: []
  },
  editUser: {
    id: '',
    firstName: '',
    lastName: '',
    email: '',
    country: '',
    avatar: '',
    pinCode: '',
    phone: '',
    positions: [],
    shops: []
  },
  isUserTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_USER_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_USER_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_USER_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [USER_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_USERS] (state, users) {
    state.users = users
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [USER_CREATED] (state) {
    state.showNewModal = false
    state.newUser = {
      firstName: '',
      lastName: '',
      email: '',
      country: '',
      phone: '',
      password: '',
      avatar: '',
      employer: {},
      position: [],
      position_id: '',
      shops: []
    }
    state.saved = true
  },
  [USER_EDIT] (state, userId) {
    state.editUser = state.users.filter((node) => node.id === userId)[0]
  },
  [USER_UPDATED] (state) {
    state.showEditModal = false
    state.editUser = {
      id: '',
      firstName: '',
      lastName: '',
      email: '',
      password: '',
      phone: '',
      country: '',
      avatar: '',
      position: [],
      positions_id: '',
      shops: []
    }
    state.saved = true
  },
  [SET_EDIT_USER] (state, profile) {
    state.editUser.push(profile)
  },
  [USER_DELETE] (state) {
    state.saved = true
  },
  [SET_USER_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_USER] (state) {
    state.saved = false
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_USER_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_USER_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_USER_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, userId) {
    commit(SWITCH_USER_EDIT_MODAL, true)
    commit(USER_EDIT, userId)
  },
  openShowModal ({ commit }, userId) {
    commit(SWITCH_USER_SHOW_MODAL, true)
    commit(USER_EDIT, userId)
  },
  async getUsers ({ commit }) {
    commit(USER_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await user
      .fetchUsers()
      .then(({ data }) => {
        commit(FETCHING_USERS, data.data)
        commit(USER_TABLE_LOADING, false)
      }).catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit('SET_ERRORS', error, { root: true })
      })
  },
  async createUser ({ commit, dispatch }, newUser) {
    commit(ENV_DATA_PROCESS, true)
    commit('CLEAR_ERRORS', null, { root: true })

    await user
      .sendCreateRequest(newUser)
      .then(() => {
        commit(USER_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('user/getUsers', null, { root: true })
      })
      .catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit('SET_ERRORS', error, { root: true })
      })
  },
  async updateUser ({ commit, dispatch }, profile) {
    commit('CLEAR_ERRORS', null, { root: true })
    const request = profile || state.editUser

    await user
      .sendUpdateRequest(request)
      .then(() => {
        commit(USER_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('user/getUsers', null, { root: true })
      })
      .catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit('SET_ERRORS', error, { root: true })
      })
  },
  async deleteUser ({ commit, dispatch }, userId) {
    commit('CLEAR_ERRORS', null, { root: true })

    await user
      .sendDeleteRequest(userId)
      .then(() => {
        commit(USER_DELETE)
        dispatch('user/getUsers', null, { root: true })
      })
      .catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit('SET_ERRORS', error, { root: true })
      })
  },

  async updateAvatar ({ commit, dispatch }, file) {
    const image = `data:${file.file.type};base64,${file.file.base64}`
    const sendData = {
      id: file.id,
      image: image
    }
    await user.updateAvatar(sendData).then(() => {
      commit(SET_USER_AVATAR, file.file.base64)
      dispatch('auth/getUserData', null, { root: true })
    })
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
