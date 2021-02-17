import api from '../config/api'

export default {
	fetchExchangeRate () {
		return api.get('exchange/rate')
	},
	sendCreateRequest (exchange) {
		return api.post('exchange/rate', exchange)
	},
	sendUpdateRequest (exchange) {
		return api.put('exchange/rate/' + exchange.id, exchange)
	},
	sendDeleteRequest (exchangeId) {
		return api.remove('exchange/rate/' + exchangeId)
	}
}
