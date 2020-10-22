import api from '../config/api'

export default {
  fetchSuppliers () {
    return api.get('supplier')
  },
  sendCreateRequest (supplier) {
    return api.post('supplier', supplier)
  },
  sendUpdateRequest (supplier) {
    return api.put('supplier/' + supplier.id, supplier)
  },
  sendDeleteRequest (supplierId) {
    return api.remove('supplier/' + supplierId)
  },
  updateAvatar (avatar) {
    return api.post('supplier/avatar/' + avatar.id, avatar)
  }
}
