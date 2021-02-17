import api from '../config/api'

export default {
	fetchTaxes () {
		return api.get('tax')
	},
	sendCreateRequest (tax) {
		return api.post('tax', tax)
	},
	sendUpdateRequest (tax) {
		return api.put('tax/' + tax.id, tax)
	},
	sendDeleteRequest (taxId) {
		return api.remove('tax/' + taxId)
	}
}
