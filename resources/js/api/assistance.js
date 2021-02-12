import api from '../config/api'

export default {
	fetchAssistances () {
		return api.get('assistance')
	},
	sendCreateRequest (assistance) {
		assistance.datetimeEntry = assistance.datetimeEntry.toString()
		assistance.datetimeExit = assistance.datetimeExit.toString()

		return api.post('assistance', assistance)
	},
	sendUpdateRequest (assistance) {
		assistance.datetimeEntry = assistance.datetimeEntry.toString()
		assistance.datetimeExit = assistance.datetimeExit.toString()

		return api.put('assistance/' + assistance.id, assistance)
	},
	sendDeleteRequest (assistanceId) {
		return api.remove('assistance/' + assistanceId)
	}
}
