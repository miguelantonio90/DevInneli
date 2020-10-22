import data from '../../data'

const state = {
  arrayCountry: data.countries.getCountryToSelect(),
  payments: data.payments.getPaymentToSelect()
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
