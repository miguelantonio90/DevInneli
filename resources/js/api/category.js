import api from '../config/api'

export default {
  fetchCategories () {
    return api.get('category')
  },
  getCategoriesShop (data) {
    return api.post('category/shops', data)
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
