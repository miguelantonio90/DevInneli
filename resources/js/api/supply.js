import api from '../config/api'

export default {
  fetchSupplys () {
	return api.get('supply')
  },
  sendCreateRequest (supply) {
	return api.post('supply', supply)
  },
  sendUpdateRequest (supply) {
	return api.put('supply/' + supply.id, supply)
  },
  sendDeleteRequest (supplyId) {
	return api.remove('supply/' + supplyId)
  },
  fetchSupplyByCategory (filter) {
	return api.post('supply/category', filter)
  },
  fetchSupplyByPayment (filter) {
	return api.post('supply/payment', filter)
  },
  fetchSupplyByProduct (filter) {
	return api.post('supply/product', filter)
  },
  fetchSupplyByEmployer (filter) {
	return api.post('supply/employer', filter)
  },
  fetchSupplyByLimit (filter) {
	return api.get('supply/by_limit/' + filter)
  },
  fetchSupplyStatics () {
	return api.get('supply/supplys/static')
  },
  fetchSupplyNumber () {
	return api.post('supply/number/facture')
  }
}
