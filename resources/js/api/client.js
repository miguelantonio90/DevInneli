import api from '../config/api'

export default {
  fetchUsers () {
    return api.get('client')
  },
  sendCreateRequest (client) {
    return api.post('client', client)
  },
  sendUpdateRequest (client) {
    return api.post('client/' + client.id, client)
  },
  sendDeleteRequest (clientId) {
    return api.remove('client/' + clientId)
  },
  updateAvatar (avatar) {
    return api.post('client/avatar/' + avatar.id, avatar)
  }
}
