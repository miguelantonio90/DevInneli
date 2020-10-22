import paises from '../../data'
import payments from'../../data'

const state = {
  arrayCountry: paises.getCountryToSelect(),
  payments:payments.getPaymentToSelect()
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
