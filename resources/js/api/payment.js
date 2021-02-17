import api from '../config/api'

export default {
	fetchPayments () {
		return api.get('payment')
	},
	sendCreateRequest (payment) {
		return api.post('payment', payment)
	},
	sendUpdateRequest (payment) {
		return api.put('payment/' + payment.id, payment)
	},
	sendDeleteRequest (paymentId) {
		return api.remove('payment/' + paymentId)
	}
}
