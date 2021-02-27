import api from '../config/api'

export default {
  fetchInventories () {
    return api.get('inventory')
  },
  sendCreateRequest (inventory) {
    return api.post('inventory', inventory)
  },
  sendUpdateRequest (inventory) {
    return api.put('inventory/' + inventory.id, inventory)
  },
  sendDeleteRequest (inventoryId) {
    return api.remove('inventory/' + inventoryId)
  }
}
