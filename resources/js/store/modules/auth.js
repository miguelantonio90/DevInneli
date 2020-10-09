import auth from '../../api/auth'
import localStorage from '../../config/localStorage'

const SET_USER_DATA = 'SET_USER_DATA'
const LOGIN_SUCCESS = 'LOGIN_SUCCESS'
const PIN_SUCCESS = 'PIN_SUCCESS'
const LOGIN_FAILED = 'LOGIN_FAILED'
const PIN_FAILED = 'PIN_FAILED'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const LOGOUT = 'LOGOUT'
const LOGIN = 'LOGIN'
const FORGOT_PASSWORD = 'FORGOT_PASSWORD'
const SET_RESET = 'SET_RESET'
const IN_PROCESS_RESET = 'IN_PROCESS_RESET'
const IS_MANAGER = 'IS_MANAGER'

const state = {
  isLoggedIn: !!localStorage.getToken(),
  isManager: !!localStorage.getTokenManager(),
  userData: [],
  pending: false,
  pinSuccess: false,
  loadingReset: false,
  successForgot: false,
  successReset: false,
  fromModel: {
    email: '',
    password: ''
  },
  formRegister: {
    shopName: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
    country: ''
  },
  formReset: {
    email: '',
    password: '',
    password_confirmation: ''
  },
  socialIcons: [
    {
      text: 'Google',
      icon: 'mdi-google'
    },
    {
      text: 'Facebook',
      icon: 'mdi-facebook'
    },
    {
      text: 'Twitter',
      icon: 'mdi-twitter'
    }
  ]
}

// getters
const getters = {
  user: (state) => state.userData,
  isLoggedIn: (state) => state.isLoggedIn,
  isManagerIn: (state) => state.isManager,
  pinSuccess: (state) => state.pinSuccess
}

// actions
const actions = {
  async getUserData ({ commit }) {
    commit('CLEAR_ERRORS', null, { root: true })

    await auth
      .getUserData()
      .then(({ data }) => {
        commit(SET_USER_DATA, data)
      })
      .catch(({ response }) => {
        commit('SET_ERRORS', response, { root: true })
        localStorage.removeToken()
      })
  },
  async sendLoginRequest ({ commit }, login) {
    commit('CLEAR_ERRORS', null, { root: true })
    commit(LOGIN)

    return await auth
      .loginRequest(login)
      .then(({ data }) => {
        commit(LOGIN_SUCCESS)
        localStorage.saveToken(
          data.token_type + ' ' + data.access_token
        )
      })
      .catch(({ response }) => {
        commit(LOGIN_FAILED)
        commit('SET_ERRORS', response, { root: true })
      })
  },
  async sendLoginPincode ({ commit, dispatch }, login) {
    commit('CLEAR_ERRORS', null, { root: true })
    commit(LOGIN)
    return await auth
      .loginPincodeRequest(login)
      .then(({ data }) => {
        commit(PIN_SUCCESS)
        if (data.success && data.data.isManager) {
          commit(IS_MANAGER, true)
          localStorage.saveTokenManager(data.data.access_token)
        }
      })
      .catch(({ response }) => {
        commit(PIN_FAILED)
        commit(IS_MANAGER, false)
        commit('SET_ERRORS', response, { root: true })
        localStorage.removeTokenManager()
      })
  },
  async sendRegisterRequest ({ commit, dispatch }, register) {
    commit('CLEAR_ERRORS', null, { root: true })
    return await auth
      .registerRequest(register)
      .then(({ data }) => {
        commit(LOGIN_SUCCESS)
        localStorage.saveToken(
          data.token_type + ' ' + data.access_token
        )
        dispatch('getUserData')
      })
      .catch(({ response }) => {
        commit('SET_ERRORS', response, { root: true })
      })
  },
  async sendLogoutRequest ({ commit }) {
    commit(LOGOUT)
    await auth
      .logoutRequest()
      .then(() => commit(SET_USER_DATA, null))
      .then(() => {
        localStorage.removeToken()
        localStorage.removeTokenManager()
      })
  },
  async sendVerifyResendRequest ({ commit }) {
    return await auth.verifyResendRequest().catch(({ response }) => {
      commit('SET_ERRORS', response, { root: true })
    })
  },
  async sendVerifyRequest ({ dispatch }, hash) {
    return await auth
      .verifyRequest(hash)
      .then(() => dispatch('getUserData'))
  },
  async sendEmailForgot ({ commit }, email) {
    commit('CLEAR_ERRORS', null, { root: true })
    return await auth
      .verifyMailForgot(email)
      .then((response) => {
        if (response.status === 200 && response.data.success) {
          commit(FORGOT_PASSWORD, true)
        } else {
          commit(FORGOT_PASSWORD, false)
        }
      })
      .catch((response) => {
        commit('SET_ERRORS', response, { root: true })
      })
  },
  async sendResetPassword ({ commit }, newData) {
    commit(IN_PROCESS_RESET, true)
    commit('CLEAR_ERRORS', null, { root: true })
    return await auth
      .resetPassword(newData.token, newData)
      .then((response) => {
        if (response.status === 200 && response.data.success) {
          commit(IN_PROCESS_RESET, false)
          commit(SET_RESET, true)
        } else {
          commit(IN_PROCESS_RESET, false)
          commit(SET_RESET, false)
        }
      })
  }
}

// mutations
const mutations = {
  [IS_MANAGER] (state, isManager) {
    state.isManager = isManager
  },
  [SET_USER_DATA] (state, user) {
    state.userData = user
  },
  [IN_PROCESS_RESET] (state, process) {
    state.loadingReset = process
  },

  [LOGIN] (state) {
    state.pending = true
  },
  [LOGIN_SUCCESS] (state) {
    state.isLoggedIn = true
    state.pending = false
  },
  [PIN_SUCCESS] (state) {
    state.pinSuccess = true
    state.pending = false
  },
  [LOGOUT] (state) {
    state.isLoggedIn = false
    state.isManagerIn = false
  },
  [FORGOT_PASSWORD] (state, status) {
    state.successForgot = status
  },
  [LOGIN_FAILED] (state) {
    state.isLoggedIn = false
  },
  [PIN_FAILED] (state) {
    state.pinSuccess = false
    state.isManager = false
  },
  [ENV_DATA_PROCESS] (state, pending) {
    state.pending = pending
  },
  [SET_RESET] (state, reset) {
    state.formReset = {
      username: '',
      email: '',
      password: '',
      password_confirmation: ''
    }
    state.successReset = reset
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
