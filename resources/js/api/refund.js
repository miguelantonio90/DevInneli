import api from '../config/api'

export default {
  fetchRefunds () {
	return api.get('refund')
  },
  sendCreateRequest (refund) {
	return api.post('refund', refund)
  },
  sendUpdateRequest (refund) {
	return api.put('refund/' + refund.id, refund)
  },
  sendDeleteRequest (refundId) {
	return api.remove('refund/' + refundId)
  }
}
