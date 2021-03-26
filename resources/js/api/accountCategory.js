import api from '../config/api'

export default {
  fetchCategories () {
    return api.get('accounting/category')
  },
  sendCreateRequest (accounting) {
    return api.post('accounting/category', accounting)
  },
  fetchCategoriesTree (accounting) {
    return api.post('accounting/category/tree')
  },
  sendUpdateRequest (accounting) {
    return api.put('accounting/category/' + accounting.id, accounting)
  },
  sendDeleteRequest (accountingId) {
    return api.remove('accounting/category/' + accountingId)
  }
}
