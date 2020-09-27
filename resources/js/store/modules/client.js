import client from '../../api/client'

const FETCHING_CLIENTS = 'FETCHING_CLIENTS'
const SWITCH_CLIENT_NEW_MODAL = 'SWITCH_CLIENT_NEW_MODAL'
const SWITCH_CLIENT_EDIT_MODAL = 'SWITCH_CLIENT_EDIT_MODAL'
const SWITCH_CLIENT_SHOW_MODAL = 'SWITCH_CLIENT_SHOW_MODAL'
const CLIENT_CREATED = 'CLIENT_CREATED'
const CLIENT_EDIT = 'CLIENT_EDIT'
const CLIENT_UPDATED = 'CLIENT_UPDATED'
const CLIENT_DELETE = 'CLIENT_DELETE'
const CLIENT_TABLE_LOADING = 'CLIENT_TABLE_LOADING'
const FAILED_CLIENT = 'FAILED_CLIENT'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_CLIENT = 'SET_EDIT_CLIENT'
const SET_CLIENT_AVATAR = 'SET_CLIENT_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  clients: [],
  avatar: '',
  loading: false,
  saved: false,
  newClient: {
    firstName: '',
    lastName: '',
    email: '',
    city: '',
    phone: '',
    avatar: '',
    country: '',
    province: '',
    barCode: '',
    description: ''
  },
  editClient: {
    id: '',
    firstName: '',
    lastName: '',
    email: '',
    city: '',
    phone: '',
    avatar: '',
    country: '',
    province: '',
    barCode: '',
    description: ''
  },
  isClientTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_CLIENT_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_CLIENT_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_CLIENT_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [CLIENT_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_CLIENTS] (state, clients) {
    state.clients = clients
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [CLIENT_CREATED] (state) {
    state.showNewModal = false
    state.newClient = {
      firstName: '',
      lastName: '',
      email: '',
      city: '',
      phone: '',
      avatar: '',
      country: '',
      province: '',
      barCode: '',
      description: ''
    }
    state.saved = true
  },
  [CLIENT_EDIT] (state, clientId) {
    state.editClient = state.clients.filter(cl => cl.id === clientId)[0]
  },
  [CLIENT_UPDATED] (state) {
    state.showEditModal = false
    state.editClient = {
      id: '',
      firstName: '',
      lastName: '',
      email: '',
      city: '',
      phone: '',
      avatar: '',
      country: '',
      province: '',
      barCode: '',
      description: ''
    }
    state.saved = true
  },
  [SET_EDIT_CLIENT] (state, profile) {
    state.editClient.push(profile)
  },
  [CLIENT_DELETE] (state) {
    state.saved = true
  },
  [SET_CLIENT_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_CLIENT] (state) {
    state.saved = false
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_CLIENT_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_CLIENT_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_CLIENT_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, clientId) {
    commit(SWITCH_CLIENT_EDIT_MODAL, true)
    commit(CLIENT_EDIT, clientId)
  },
  openShowModal ({ commit }, clientId) {
    commit(SWITCH_CLIENT_SHOW_MODAL, true)
    commit(CLIENT_EDIT, clientId)
  },
  async getClients ({ commit }) {
    commit(CLIENT_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await client
      .fetchClients()
      .then(({ data }) => {
        commit(FETCHING_CLIENTS, data.data)
        commit(CLIENT_TABLE_LOADING, false)
      }).catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async createClient ({ commit, dispatch }, newClient) {
    commit(ENV_DATA_PROCESS, true)
    commit('CLEAR_ERRORS', null, { root: true })

    await client
      .sendCreateRequest(newClient)
      .then(() => {
        commit(CLIENT_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('client/getClients', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async updateClient ({ commit, dispatch }, editClient) {
    commit('CLEAR_ERRORS', null, { root: true })

    await client
      .sendUpdateRequest(editClient)
      .then(() => {
        commit(CLIENT_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('client/getClients', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async deleteClient ({ commit, dispatch }, clientId) {
    commit('CLEAR_ERRORS', null, { root: true })

    await client
      .sendDeleteRequest(clientId)
      .then(() => {
        commit(CLIENT_DELETE)
        dispatch('client/getClients', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },

  async updateAvatar ({ commit, dispatch }, file) {
    const image = `data:${file.file.type};base64,${file.file.base64}`
    const sendData = {
      id: file.id,
      image: image
    }
    await client.updateAvatar(sendData).then(() => {
      commit(SET_CLIENT_AVATAR, file.file.base64)
      dispatch('auth/getClientData', null, { root: true })
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
