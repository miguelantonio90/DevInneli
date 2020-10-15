import api from '../config/api'

export default {
  fetchAssistances () {
    return api.get('assistance')
  },
  sendCreateRequest (assistance) {
    return api.post('assistance', assistance)
  },
  sendUpdateRequest (assistance) {
    return api.put('assistance/' + assistance.id, assistance)
  },
  sendDeleteRequest (assistanceId) {
    return api.remove('assistance/' + assistanceId)
  }
}
