import api from '../config/api'

export default {
  fetchAccounts () {
    return api.get('accounting/account')
  },
  fetchAccount (accountingId) {
    return api.get('accounting/account/' + accountingId)
  },
  sendCreateRequest (accounting) {
    return api.post('accounting/account', accounting)
  },
  sendUpdateRequest (accounting) {
    return api.put('accounting/account/' + accounting.id, accounting)
  },
  sendDeleteRequest (accountingId) {
    return api.remove('accounting/account/' + accountingId)
  }
}
