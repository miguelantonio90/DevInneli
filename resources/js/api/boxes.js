import api from '../config/api'

export default {
  fetchBoxes () {
    return api.get('boxes')
  },
  sendCreateRequest (boxes) {
    return api.post('boxes', boxes)
  },
  sendUpdateRequest (boxes) {
    return api.put('boxes/' + boxes.id, boxes)
  },
  sendDeleteRequest (boxesId) {
    return api.remove('boxes/' + boxesId)
  }
}
