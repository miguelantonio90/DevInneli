import api from '../config/api'

export default {
  fetchModifiers () {
    return api.get('modifiers')
  },
  sendCreateRequest (modifier) {
    return api.post('modifiers', modifier)
  },
  sendUpdateRequest (modifier) {
    return api.put('modifiers/' + modifier.id, modifier)
  },
  sendDeleteRequest (modifierId) {
    return api.remove('modifiers/' + modifierId)
  }
}
