import countries from '../../data'

const state = {
  arrayCountry: countries.getCountryToSelect()
}

const mutations = {}

const getters = {}

const actions = {}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
