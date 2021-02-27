import api from '../config/api'

export default {
  fetchTypeOrders () {
	return api.get('type/order')
  },
  sendCreateRequest (typeOrder) {
	return api.post('type/order', typeOrder)
  },
  sendUpdateRequest (typeOrder) {
	return api.put('type/order/' + typeOrder.id, typeOrder)
  },
  sendDeleteRequest (id) {
	return api.remove('type/order/' + id)
  },
  sendSetPrincipal (item) {
	return api.put('type/order/edit/' + item.id, item)
  }
}
