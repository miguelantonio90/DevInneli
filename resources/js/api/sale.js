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
  },
  fetchSaleByPayment (filter) {
    return api.post('sale/payment', filter)
  },
  fetchSaleByProduct (filter) {
    return api.post('sale/product', filter)
  },
  fetchSaleByEmployer (filter) {
    return api.post('sale/employer', filter)
  },
  fetchSaleByLimit (filter) {
    return api.get('sale/by_limit/' + filter)
  },
  fetchSaleStatics () {
    return api.get('sale/sales/static')
  }
}
