import api from '../config/api'

export default {
	fetchBoxes () {
		return api.get('boxes')
	},
	sendCreateRequest (boxes) {
		return api.post('boxes', boxes)
	},
	fetchOpenClose (boxId) {
		return api.get('boxes/' + boxId)
	},
	sendUpdateRequest (boxes) {
		return api.put('boxes/' + boxes.id, boxes)
	},
	sendDeleteRequest (boxesId) {
		return api.remove('boxes/' + boxesId)
	},
	sendOpenCloseBox (box) {
		return api.post('boxes/sendOpenCloseBox', box)
	}
}
