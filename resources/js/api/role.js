import api from '../config/api'

export default {
	fetchRoles () {
		return api.get('access')
	},
	sendCreateRequest (role) {
		return api.post('access', role)
	},
	sendUpdateRequest (role) {
		return api.put('access/' + role.id, role)
	},
	sendDeleteRequest (roleId) {
		return api.remove('access/' + roleId)
	}
}
