import employment from '../../api/employment'

const FETCHING_EMPLOYMENTS = 'FETCHING_EMPLOYMENTS'
const SWITCH_EMPLOYMENT_NEW_MODAL = 'SWITCH_EMPLOYMENT_NEW_MODAL'
const SWITCH_EMPLOYMENT_EDIT_MODAL = 'SWITCH_EMPLOYMENT_EDIT_MODAL'
const SWITCH_EMPLOYMENT_SHOW_MODAL = 'SWITCH_EMPLOYMENT_SHOW_MODAL'
const EMPLOYMENT_CREATED = 'EMPLOYMENT_CREATED'
const EMPLOYMENT_EDIT = 'EMPLOYMENT_EDIT'
const EMPLOYMENT_UPDATED = 'EMPLOYMENT_UPDATED'
const EMPLOYMENT_DELETE = 'EMPLOYMENT_DELETE'
const EMPLOYMENT_TABLE_LOADING = 'EMPLOYMENT_TABLE_LOADING'
const FAILED_EMPLOYMENT = 'FAILED_EMPLOYMENT'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_USER = 'SET_EDIT_USER'
const SET_USER_AVATAR = 'SET_USER_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  employments: [],
  avatar: '',
  loading: false,
  saved: false,
  newEmployment: {
    firstName: '',
    lastName: '',
    email: '',
    username: '',
    password: '',
    codePin: '',
    position: '',
    phone: '',
    company: '',
    avatar: ''
  },
  editEmployment: {
    id: '',
    firstName: '',
    lastName: '',
    email: '',
    username: '',
    password: '',
    codePin: '',
    position: '',
    phone: '',
    company: '',
    avatar: ''
  },
  isUserTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_EMPLOYMENT_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_EMPLOYMENT_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_EMPLOYMENT_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [EMPLOYMENT_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_EMPLOYMENTS] (state, employments) {
    state.employments = employments
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [EMPLOYMENT_CREATED] (state) {
    state.showNewModal = false
    state.newEmployment = {
      firstName: '',
      lastName: '',
      email: '',
      username: '',
      password: '',
      codePin: '',
      position: '',
      phone: '',
      company: '',
      avatar: ''
    }
    state.saved = true
  },
  [EMPLOYMENT_EDIT] (state, userId) {
    state.editEmployment = state.employments.filter((node) => node.id === userId)[0]
  },
  [EMPLOYMENT_UPDATED] (state) {
    state.showEditModal = false
    state.editEmployment = {
      id: '',
      firstName: '',
      lastName: '',
      email: '',
      username: '',
      password: '',
      codePin: '',
      position: '',
      phone: '',
      company: '',
      avatar: ''
    }
    state.saved = true
  },
  [SET_EDIT_USER] (state, profile) {
    state.editEmployment.push(profile)
  },
  [EMPLOYMENT_DELETE] (state) {
    state.saved = true
  },
  [SET_USER_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_EMPLOYMENT] (state) {
    state.saved = false
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_EMPLOYMENT_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_EMPLOYMENT_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_EMPLOYMENT_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, userId) {
    commit(SWITCH_EMPLOYMENT_EDIT_MODAL, true)
    commit(EMPLOYMENT_EDIT, userId)
  },
  openShowModal ({ commit }, userId) {
    commit(SWITCH_EMPLOYMENT_SHOW_MODAL, true)
    commit(EMPLOYMENT_EDIT, userId)
  },
  async getEmployments ({ commit }) {
    commit(EMPLOYMENT_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await employment
      .fetchEmployments()
      .then(({ data }) => {
        commit(FETCHING_EMPLOYMENTS, data.data)
        commit(EMPLOYMENT_TABLE_LOADING, false)
      }).catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async createEmployment ({ commit, dispatch }, newEmployment) {
    commit(ENV_DATA_PROCESS, true)
    commit('CLEAR_ERRORS', null, { root: true })

    await employment
      .sendCreateRequest(newEmployment)
      .then(() => {
        commit(EMPLOYMENT_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('employment/getEmployments', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async updateEmployment ({ commit, dispatch }, profile) {
    commit('CLEAR_ERRORS', null, { root: true })
    const request = profile || state.editEmployment

    await employment
      .sendUpdateRequest(request)
      .then(() => {
        commit(EMPLOYMENT_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('employment/getEmployments', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async deleteEmployment ({ commit, dispatch }, userId) {
    commit('CLEAR_ERRORS', null, { root: true })

    await employment
      .sendDeleteRequest(userId)
      .then(() => {
        commit(EMPLOYMENT_DELETE)
        dispatch('employment/getEmployments', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },

  async updateAvatar ({ commit, dispatch }, file) {
    const image = `data:${file.file.type};base64,${file.file.base64}`
    const sendData = {
      id: file.id,
      image: image
    }
    await employment.updateAvatar(sendData).then(() => {
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
