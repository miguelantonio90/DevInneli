import api from '../config/api'

export default {
  fetchClients () {
	return api.get('client')
  },
  sendCreateRequest (client) {
	return api.post('client', client)
  },
  sendUpdateRequest (client) {
	return api.put('client/' + client.id, client)
  },
  sendDeleteRequest (clientId) {
	return api.remove('client/' + clientId)
  },
  updateAvatar (avatar) {
	return api.post('client/avatar/' + avatar.id, avatar)
  }
}
