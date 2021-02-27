import data from '../../data'

const FETCH_COUNTRIES = 'FETCH_COUNTRIES'
const FETCH_PAYMENTS = 'FETCH_PAYMENTS'
const PAYMENTS_LOADING = 'PAYMENTS_LOADING'
const COUNTRIES_LOADING = 'COUNTRIES_LOADING'
const FAILED = 'FAILED'

const state = {
  arrayCountry: data.getCountryToSelect(),
  arrayUM: data.getUM(),
  arraySector: data.getSector(),
  arrayCurrency: data.getCurrencyToSelect(),
  payments: data.getPaymentToSelect(),
  isCountryLoading: false,
  isPaymentsLoading: false
}

const mutations = {
  [FETCH_COUNTRIES] (state, arrayCurrency) {
    state.arrayCurrency = arrayCurrency
  },
  [FETCH_PAYMENTS] (state, payments) {
    state.payments = payments
  },
  [COUNTRIES_LOADING] (state, isLoading) {
    state.isCountryLoading = isLoading
  },
  [PAYMENTS_LOADING] (state, isLoading) {
    state.isPaymentsLoading = isLoading
  },
  [FAILED] (state, error) {
    state.error = error
  }
}

const getters = {
  arrayCurrency: state => state.arrayCurrency,
  arrayCountry: state => state.arrayCountry,
  arraySector: state => state.arraySector,
  payments: state => state.payments
}

const actions = {}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
