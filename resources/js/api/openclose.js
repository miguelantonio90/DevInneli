import api from '../config/api'

export default {
	fetchOpenClose () {
		return api.get('openClose')
	},
	sendCreateRequest (openClose) {
		return api.post('openClose', openClose)
	},
	sendUpdateRequest (openClose) {
		return api.put('openClose/' + openClose.id, openClose)
	},
	sendDeleteRequest (openCloseId) {
		return api.remove('openClose/' + openCloseId)
	},
	sendOpenCloseBox (box) {
		return api.post('openClose/sendOpenCloseBox', box)
	}
}
