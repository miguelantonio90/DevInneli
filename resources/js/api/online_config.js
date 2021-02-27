import api from '../config/api'

export default {
  fetchConfigs () {
	return api.get('online')
  },
  sendCreateRequest (online) {
	return api.post('online', online)
  },
  findConfigShop (shopId) {
	return api.post('online/shop', shopId)
  },
  sendUpdateRequest (online) {
	return api.put('online/' + online.id, online)
  },
  sendDeleteRequest (onlineId) {
	return api.remove('online/' + onlineId)
  }
}
