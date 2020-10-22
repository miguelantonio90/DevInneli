import axios from 'axios'
const baseUrl = process.env.MIX_API_RATE
const apiKey = process.env.MIX_KEY_RATE

const getCountries = () => {
  // return axios.get(baseUrl + 'countries?apiKey=' + apiKey)
  return axios.get(baseUrl + 'countries', {
    params: {
      apiKey: apiKey
    }
  })
}

const getCurrencies = () => {
  return axios.get(baseUrl + 'currencies?apiKey=' + apiKey)
}

const getCurrencyRate = (amount, from, to, cb) => {
  const fromCurrency = encodeURIComponent(from)
  const toCurrency = encodeURIComponent(to)
  const query = fromCurrency + '_' + toCurrency
  const url = baseUrl + 'convert?q=' + query + '&compact=ultra&apiKey=' + apiKey

  axios.get(url).then(({ status, data }) => {
    try {
      if (status === 200) {
        const val = data[query]
        if (val) {
          const total = val * amount

          cb(null, Math.round(total * 100) / 100)
        } else {
          const err = new Error('Value not found for ' + query)
          cb(err)
        }
      }
    } catch (e) {
      cb(e)
    }
  }).catch(e => {
    cb(e)
  })
}

export default {
  getCountries: getCountries,
  getCurrencies: getCurrencies,
  getCurrencyRate: getCurrencyRate
}
