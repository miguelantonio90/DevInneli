import api from '../config/api'

export default {
  fetchDiscounts () {
    return api.get('discount')
  },
  sendCreateRequest (discount) {
    return api.post('discount', discount)
  },
  sendUpdateRequest (discount) {
    return api.put('discount/' + discount.id, discount)
  },
  sendDeleteRequest (discountId) {
    return api.remove('discount/' + discountId)
  }
}
