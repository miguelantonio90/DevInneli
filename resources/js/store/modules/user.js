import user from '../../api/user'

const FETCHING_USERS = 'FETCHING_USERS'
const FETCHING_USER = 'FETCHING_USER'
const SWITCH_USER_NEW_MODAL = 'SWITCH_USER_NEW_MODAL'
const SWITCH_USER_EDIT_MODAL = 'SWITCH_USER_EDIT_MODAL'
const SWITCH_USER_SHOW_MODAL = 'SWITCH_USER_SHOW_MODAL'
const USER_CREATED = 'USER_CREATED'
const USER_EDIT = 'USER_EDIT'
const USER_UPDATED = 'USER_UPDATED'
const USER_DELETE = 'USER_DELETE'
const USER_TABLE_LOADING = 'USER_TABLE_LOADING'
const FAILED_USER = 'FAILED_USER'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_USER = 'SET_EDIT_USER'
const SET_USER_AVATAR = 'SET_USER_AVATAR'

const state = {
	showNewModal: false,
	showEditModal: false,
	showShowModal: false,
	users: [],
	userLogin: {},
	avatar: '',
	loading: false,
	saved: false,
	newUser: {
		firstName: '',
		lastName: '',
		email: '',
		country: '',
		avatar: '',
		pinCode: '',
		phone: '',
		position: [],
		shops: []
	},
	editUser: {
		id: '',
		firstName: '',
		lastName: '',
		email: '',
		country: '',
		avatar: '',
		pinCode: '',
		phone: '',
		position: [],
		shops: []
	},
	isUserTableLoading: false,
	isActionInProgress: false,
	isTableLoading: false
}

const mutations = {
	[SWITCH_USER_NEW_MODAL] (state, showModal) {
		state.showNewModal = showModal
	},
	[SWITCH_USER_EDIT_MODAL] (state, showModal) {
		state.showEditModal = showModal
	},
	[SWITCH_USER_SHOW_MODAL] (state, showModal) {
		state.showShowModal = showModal
	},
	[USER_TABLE_LOADING] (state, isLoading) {
		state.isTableLoading = isLoading
	},
	[FETCHING_USERS] (state, users) {
		state.users = users
	},
	[FETCHING_USER] (state, user) {
		state.userLogin = user
	},
	[ENV_DATA_PROCESS] (state, isActionInProgress) {
		state.isActionInProgress = isActionInProgress
	},
	[USER_CREATED] (state) {
		state.showNewModal = false
		state.newUser = {
			firstName: '',
			lastName: '',
			email: '',
			country: '',
			phone: '',
			password: '',
			avatar: '',
			position: [],
			position_id: '',
			shops: []
		}
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.user')]
			)
		})
	},
	[USER_EDIT] (state, userId) {
		state.editUser = Object.assign({}, state.users
			.filter(node => node.id === userId)
			.shift()
		)
	},
	[USER_UPDATED] (state) {
		state.showEditModal = false
		state.editUser = {
			id: '',
			firstName: '',
			lastName: '',
			email: '',
			password: '',
			phone: '',
			country: '',
			avatar: '',
			position: [],
			positions_id: '',
			shops: []
		}
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.user')]
			)
		})
	},
	[SET_EDIT_USER] (state, profile) {
		state.editUser.push(profile)
	},
	[USER_DELETE] (state) {
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.user')]
			)
		})
	},
	[SET_USER_AVATAR] (state, avatar) {
		state.avatar = avatar
		state.saved = true
	},
	[FAILED_USER] (state, error) {
		state.saved = false
		state.error = error
		state.isUserTableLoading = false
		state.isActionInProgress = false
		state.isTableLoading = false
		this._vm.$Toast.fire({
			icon: 'error',
			title: this._vm.$language.t(
				'$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.user')]
			)
		})
	}
}
const getters = {}
const actions = {
	toogleNewModal ({ commit }, showModal) {
		commit(SWITCH_USER_NEW_MODAL, showModal)
	},
	toogleEditModal ({ commit }, showModal) {
		commit(SWITCH_USER_EDIT_MODAL, showModal)
	},
	toogleShowModal ({ commit }, showModal) {
		commit(SWITCH_USER_SHOW_MODAL, showModal)
	},
	openEditModal ({ commit }, userId) {
		commit(SWITCH_USER_EDIT_MODAL, true)
		commit(USER_EDIT, userId)
	},
	openShowModal ({ commit }, userId) {
		commit(SWITCH_USER_SHOW_MODAL, true)
		commit(USER_EDIT, userId)
	},
	async getUsers ({ commit }) {
		commit(USER_TABLE_LOADING, true)
		// noinspection JSUnresolvedVariable
		await user
			.fetchUsers()
			.then(({ data }) => {
				commit(FETCHING_USERS, data.data)
				commit(USER_TABLE_LOADING, false)
				this.dispatch('auth/updateAccess', data.access)
			}).catch((error) => {
				commit(ENV_DATA_PROCESS, false)
				commit(FAILED_USER, error)
			})
	},
	async getUserLogin ({ commit }) {
		commit(USER_TABLE_LOADING, true)
		// noinspection JSUnresolvedVariable
		await user
			.fetchUser()
			.then(({ data }) => {
				commit(FETCHING_USER, data.data)
				commit(USER_TABLE_LOADING, false)
				this.dispatch('auth/updateAccess', data)
			}).catch((error) => {
				commit(ENV_DATA_PROCESS, false)
				commit(FAILED_USER, error)
			})
	},
	async createUser ({ commit, dispatch }, newUser) {
		commit(ENV_DATA_PROCESS, true)

		await user
			.sendCreateRequest(newUser)
			.then((data) => {
				commit(USER_CREATED)
				commit(ENV_DATA_PROCESS, false)
				dispatch('user/getUsers', null, { root: true })
				this.dispatch('auth/updateAccess', data)
			})
			.catch((error) => {
				commit(ENV_DATA_PROCESS, false)
				commit(FAILED_USER, error)
			})
	},
	async updateUser ({ commit, dispatch }, profile) {
		commit(ENV_DATA_PROCESS, true)

		// const request = profile || state.editUser
		await user
			.sendUpdateRequest(profile)
			.then((data) => {
				commit(USER_UPDATED)
				commit(ENV_DATA_PROCESS, false)
				dispatch('user/getUsers', null, { root: true })
				this.dispatch('auth/updateAccess', data)
			})
			.catch((error) => {
				commit(ENV_DATA_PROCESS, false)
				commit(FAILED_USER, error)
			})
	},
	async deleteUser ({ commit, dispatch }, userId) {
		await user
			.sendDeleteRequest(userId)
			.then((data) => {
				commit(USER_DELETE)
				dispatch('user/getUsers', null, { root: true })
				this.dispatch('auth/updateAccess', data)
			})
			.catch((error) => commit(FAILED_USER, error))
	},

	async updateAvatar ({ commit, dispatch }, file) {
		const image = `data:${file.file.type};base64,${file.file.base64}`
		const sendData = {
			id: file.id,
			image: image
		}
		await user.updateAvatar(sendData).then((data) => {
			commit(SET_USER_AVATAR, file.file.base64)
			dispatch('auth/getUserData', null, { root: true })
			this.dispatch('auth/updateAccess', data.access)
		})
	}
}

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
}
