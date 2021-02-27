import api from '../config/api'

export default {
  fetchKeys () {
    return api.get('keys')
  },
  sendCreateRequest (role) {
    return api.post('keys', role)
  },
  sendUpdateRequest (role) {
    return api.put('keys/' + role.id, role)
  },
  sendDeleteRequest (keyId) {
    return api.remove('keys/' + keyId)
  }
}
