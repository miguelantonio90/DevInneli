import api from '../config/api'

export default {
  fetchSales () {
    return api.get('sale')
  },
  sendCreateRequest (sale) {
    return api.post('sale', sale)
  },
  sendUpdateRequest (sale) {
    return api.put('sale/' + sale.id, sale)
  },
  sendDeleteRequest (saleId) {
    return api.remove('sale/' + saleId)
  },
  fetchSaleByCategory (filter) {
    return api.post('sale/category', filter)
  }
}
