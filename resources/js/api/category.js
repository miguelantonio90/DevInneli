import api from '../config/api'

export default {
  fetchCategories () {
    return api.get('category')
  },
  sendCreateRequest (category) {
    return api.post('category', category)
  },
  sendUpdateRequest (category) {
    return api.put('category/' + category.id, category)
  },
  sendDeleteRequest (categoryId) {
    return api.remove('category/' + categoryId)
  }
}
