import api from '../../api/company'

const FETCHING_COMPS = 'FETCHING_COMPS'
const COMP_UPDATED = 'COMP_UPDATED'
const COMP_DELETE = 'COMP_DELETE'
const COMP_TABLE_LOADING = 'COMP_TABLE_LOADING'
const FAILED_COMP = 'FAILED_COMP'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_COMP_LOGO = 'SET_COMP_LOGO'

const state = {
  companies: [],
  saved: false,
  isActionInProgress: false,
  editCompany: {
    id: '',
    name: '',
    email: '',
    phone: '',
    country: '',
    address: '',
    logo: ''
  }
}

const mutations = {

  [COMP_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_COMPS] (state, companies) {
    state.companies = companies
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [COMP_UPDATED] (state) {
    state.editCompany = {
      id: '',
      name: '',
      email: '',
      phone: '',
      country: '',
      address: '',
      logo: ''
    }
    state.saved = true
  },
  [COMP_DELETE] (state) {
    state.saved = true
  },
  [FAILED_COMP] (state) {
    state.saved = false
  },
  [SET_COMP_LOGO] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  }
}

const getters = {}

const actions = {
  async getCompanies ({ commit }) {
    commit(COMP_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await api
      .fetchCompanies()
      .then(({ data }) => {
        commit(FETCHING_COMPS, data.data)
        commit(COMP_TABLE_LOADING, false)
      }).catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async updateCompany ({ commit, dispatch }, company) {
    commit('CLEAR_ERRORS', null, { root: true })
    commit(ENV_DATA_PROCESS, true)

    await api
      .sendUpdateRequest(company)
      .then(() => {
        commit(COMP_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('auth/getUserData', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async deleteCompany ({ commit, dispatch }, companyId) {
    commit('CLEAR_ERRORS', null, { root: true })

    await api
      .sendDeleteRequest(companyId)
      .then(() => {
        commit(COMP_DELETE)
        dispatch('auth/getUserData', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },

  async updateLogo ({ commit, dispatch }, file) {
    const image = `data:${file.file.type};base64,${file.file.base64}`
    const sendData = {
      id: file.id,
      image: image
    }
    await api.updateLogo(sendData).then(() => {
      commit(SET_COMP_LOGO, file.file.base64)
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
