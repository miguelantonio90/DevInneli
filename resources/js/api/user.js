import api from '../config/api'

export default {
  fetchUsers () {
    return api.get('user')
  },
  sendCreateRequest (user) {
    return api.post('user', user)
  },
  sendUpdateRequest (user) {
    return api.post('user/' + user.id, user)
  },
  sendDeleteRequest (userId) {
    return api.remove('user/' + userId)
  },
  updateAvatar (avatar) {
    return api.post('user/avatar/' + avatar.id, avatar)
  }
}
