import api from '../config/api'

export default {
  fetchMoves () {
    return api.get('accounting/move')
  },
  sendCreateRequest (move) {
    return api.post('accounting/move', move)
  },
  getMovesAccount (move) {
    return api.post('accounting/move/account', move)
  },
  sendUpdateRequest (move) {
    return api.put('accounting/move/' + move.id, move)
  },
  sendDeleteRequest (moveId) {
    return api.remove('accounting/move/' + moveId)
  }
}
