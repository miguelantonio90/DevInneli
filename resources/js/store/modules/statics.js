import currency from '../../api/currency'
import data from '../../data'

const FETCH_COUNTRIES = 'FETCH_COUNTRIES'
const FETCH_PAYMENTS = 'FETCH_PAYMENTS'
const PAYMENTS_LOADING = 'PAYMENTS_LOADING'
const COUNTRIES_LOADING = 'COUNTRIES_LOADING'
const FAILED = 'FAILED'

const state = {
  arrayCountry: data.getCountryToSelect(),
  arrayCurrency: null,
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
  payments: state => state.payments
}

const actions = {
  async getArrayCurrency ({ commit }) {
    commit(COUNTRIES_LOADING, true)
    const countries = []
    await currency
      .getCountries()
      .then(({ status, data }) => {
        if (status === 200) {
          const currencies = data.results
          Object.keys(currencies).map((key) => {
            countries.push({
              id: currencies[key].id,
              currencyId: currencies[key].currencyId,
              name: currencies[key].name + '(' + data.results[key].currencyId + ')',
              symbol: currencies[key].currencySymbol
            })
          })
          commit(FETCH_COUNTRIES, countries)
          commit(COUNTRIES_LOADING, false)

          return countries
        }
      }).catch((error) => commit(FAILED, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
