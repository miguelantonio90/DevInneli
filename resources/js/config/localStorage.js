import Vue from 'vue'
import VueLocalStorage from 'vue-localstorage'

Vue.use(VueLocalStorage)

const saveToken = (authToken) => {
  return Vue.localStorage.set('authToken', authToken)
}

const removeToken = () => {
  return Vue.localStorage.remove('authToken')
}

const getToken = () => {
  return Vue.localStorage.get('authToken')
}

const setLanguage = (item) => {
  return Vue.localStorage.set('language', item)
}
const getLanguage = () => {
  return Vue.localStorage.get('language')
}

const setTheme = (item) => {
  return Vue.localStorage.set('theme', item)
}
const getTheme = () => {
  return Vue.localStorage.get('theme')
}

const saveTokenManager = (item) => {
  return Vue.localStorage.set('managerToken', item)
}
const removeTokenManager = () => {
  return Vue.localStorage.remove('managerToken')
}

const getTokenManager = () => {
  return Vue.localStorage.get('managerToken')
}

export default {
  getToken,
  saveToken,
  setLanguage,
  getLanguage,
  setTheme,
  getTheme,
  removeToken,
  saveTokenManager,
  removeTokenManager,
  getTokenManager
}
