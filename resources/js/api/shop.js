import api from '../config/api'

export default {
  fetchShops () {
    return api.get('shop')
  },
  sendCreateRequest (shop) {
    return api.post('shop', shop)
  },
  sendUpdateRequest (shop) {
    return api.put('shop/' + shop.id, shop)
  },
  sendDeleteRequest (shopId) {
    return api.remove('shop/' + shopId)
  }
}
