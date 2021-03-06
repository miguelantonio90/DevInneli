import api from '../config/api'

export default {
  fetchBanks () {
    return api.get('bank')
  },
  sendCreateRequest (bank) {
    return api.post('bank', bank)
  },
  sendUpdateRequest (bank) {
    return api.put('bank/' + bank.id, bank)
  },
  sendDeleteRequest (bankId) {
    return api.remove('bank/' + bankId)
  }
}
