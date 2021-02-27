import api from '../config/api'

export default {
  fetchCompanies () {
	return api.get('company')
  },
  fetchCompaniesByEmail (email) {
	return api.get('company/email/' + email)
  },
  sendCreateRequest (company) {
	return api.post('company', company)
  },
  sendUpdateRequest (company) {
	return api.put('company/' + company.id, company)
  },
  sendDeleteRequest (companyId) {
	return api.remove('company/' + companyId)
  },

  updateLogo (avatar) {
	return api.post('company/logo/' + avatar.id, avatar)
  }
}
