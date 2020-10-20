import api from '../config/api'

export default {
  fetchCategories () {
    return api.get('expense/category')
  },
  sendCreateRequest (category) {
    return api.post('expense/category', category)
  },
  sendUpdateRequest (category) {
    return api.put('expense/category/' + category.id, category)
  },
  sendDeleteRequest (categoryId) {
    return api.remove('expense/category/' + categoryId)
  }
}
