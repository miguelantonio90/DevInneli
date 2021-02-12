import apiArticle from '../../api/article'
import util from '../../util'

const FETCHING_ARTICLES = 'FETCHING_ARTICLES'
const FETCHING_ARTICLES_MERGE = 'FETCHING_ARTICLES_MERGE'
const FETCHING_ARTICLE_NUMBER = 'FETCHING_ARTICLE_NUMBER'
const SWITCH_ARTICLE_NEW_MODAL = 'SWITCH_ARTICLE_NEW_MODAL'
const SWITCH_ARTICLE_EDIT_MODAL = 'SWITCH_ARTICLE_EDIT_MODAL'
const SWITCH_ARTICLE_SHOW_MODAL = 'SWITCH_ARTICLE_SHOW_MODAL'
const SWITCH_ARTICLE_REFOUND_MODAL = 'SWITCH_ARTICLE_REFOUND_MODAL'
const SWITCH_ARTICLE_IMPORT_MODAL = 'SWITCH_ARTICLE_IMPORT_MODAL'
const ARTICLE_CREATED = 'ARTICLE_CREATED'
const ARTICLE_EDIT = 'ARTICLE_EDIT'
const ARTICLE_REFOUND = 'ARTICLE_REFOUND'
const ARTICLE_UPDATED = 'ARTICLE_UPDATED'
const ARTICLE_DELETE = 'ARTICLE_DELETE'
const ARTICLE_TABLE_LOADING = 'ARTICLE_TABLE_LOADING'
const FAILED_ARTICLE = 'FAILED_ARTICLE'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_ARTICLE = 'SET_EDIT_ARTICLE'
const SET_ARTICLE_AVATAR = 'SET_ARTICLE_AVATAR'
const SWITCH_TRANSFER_MODAL = 'SWITCH_TRANSFER_MODAL'

const state = {
	showNewModal: false,
	showTransfer: false,
	showEditModal: false,
	showShowModal: false,
	showRefoundModal: false,
	articles: [],
	articlesMerge: [],
	avatar: '',
	loading: false,
	saved: false,
	managerArticle: false,
	newArticle: {
		name: '',
		um: '',
		price: 0.00,
		unit: 'unit',
		cost: 0.00,
		ref: '10001',
		barCode: '',
		variants: [],
		tax: [],
		variant_values: [],
		composite: false,
		track_inventory: false,
		category: [],
		color: '',
		articles_shops: [],
		composites: [],
		images: []
	},
	editArticle: {
		id: '',
		name: '',
		um: '',
		price: '',
		unit: '',
		cost: '',
		ref: '',
		barCode: '',
		variants: [],
		tax: [],
		variant_values: [],
		composite: false,
		composites: [],
		track_inventory: false,
		category: [],
		color: '',
		articles_shops: [],
		variants_shops: [],
		images: []
	},
	refound: {
		sale: {},
		article: {},
		cant: 0,
		money: 0
	},
	importArticle: {
		type: 'loyverse',
		file: ''
	},
	showImportModal: false,
	isArticleTableLoading: false,
	isActionInProgress: false,
	isTableLoading: false,
	articleNumber: ''
}

const mutations = {
	[SWITCH_ARTICLE_REFOUND_MODAL] (state, showModal) {
		state.showRefoundModal = showModal
	},
	[SWITCH_TRANSFER_MODAL] (state, showModal) {
		state.showTransfer = showModal
	},
	[SWITCH_ARTICLE_NEW_MODAL] (state, showModal) {
		state.showNewModal = showModal
	},
	[SWITCH_ARTICLE_EDIT_MODAL] (state, showModal) {
		state.showEditModal = showModal
	},
	[SWITCH_ARTICLE_SHOW_MODAL] (state, showModal) {
		state.showShowModal = showModal
	},
	[SWITCH_ARTICLE_IMPORT_MODAL] (state, showModal) {
		state.showImportModal = showModal
	},
	[ARTICLE_TABLE_LOADING] (state, isLoading) {
		state.isTableLoading = isLoading
	},
	[FETCHING_ARTICLES] (state, articles) {
		state.articles = []
		articles.forEach((value) => {
			if (!value.parent_id) {
				if (value.variants.length > 0) {
					value.variants.forEach((k) => {
						k.value = JSON.parse(k.value)
					})
				}
				state.articles.push(value)
			}
		})
	},
	[FETCHING_ARTICLE_NUMBER] (state, number) {
		state.articleNumber = number
	},
	[FETCHING_ARTICLES_MERGE] (state, articles) {
		const articlesMerge = articles.sort(util.compareValues('percent', 'desc')).slice(0, 5)
		state.articlesMerge = articlesMerge.map((value) => {
			return {
				avatar: value.path ? value.path : null,
				name: value.name,
				price: value.price,
				cost: value.cost,
				progress: value.percent
			}
		})
	},
	[ENV_DATA_PROCESS] (state, isActionInProgress) {
		state.isActionInProgress = isActionInProgress
	},
	[ARTICLE_CREATED] (state) {
		state.showNewModal = false
		state.showTransfer = false
		state.newArticle = {
			name: '',
			um: '',
			price: 0.00,
			unit: 'unit',
			cost: '',
			ref: '',
			barCode: '',
			variants: [],
			tax: [],
			variant_values: [],
			composite: false,
			composites: [],
			track_inventory: false,
			category: [],
			articles_shops: [],
			color: '',
			variants_shops: [],
			images: []
		}
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.articles.name')]
			)
		})
	},
	[ARTICLE_EDIT] (state, articleId) {
		state.editArticle = Object.assign({}, state.articles
			.filter(node => node.id === articleId)
			.shift()
		)
	},
	[ARTICLE_REFOUND] (state, { sale, article }) {
		state.refound.sale = sale
		state.refound.article = article
	},
	[ARTICLE_UPDATED] (state) {
		state.showEditModal = false
		state.showTransfer = false
		state.editArticle = {
			id: '',
			name: '',
			um: '',
			price: '',
			unit: '',
			cost: '',
			ref: '',
			barCode: '',
			variants: [],
			tax: [],
			variant_values: [],
			composite: false,
			track_inventory: false,
			category: [],
			articles_shops: [],
			color: '',
			variants_shops: [],
			images: []
		}
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.articles.name')]
			)
		})
	},
	[SET_EDIT_ARTICLE] (state, profile) {
		state.editArticle.push(profile)
	},
	[ARTICLE_DELETE] (state) {
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.articles.name')]
			)
		})
	},
	[SET_ARTICLE_AVATAR] (state, avatar) {
		state.avatar = avatar
		state.saved = true
	},
	[FAILED_ARTICLE] (state, error) {
		state.isActionInProgress = false
		state.isArticleTableLoading = false
		state.isTableLoading = false
		state.saved = false
		state.error = error
		this._vm.$Toast.fire({
			icon: 'error',
			title: this._vm.$language.t(
				'$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.articles.name')]
			)
		})
	}
}

const getters = {
	getNumberArticle: (state) => {
		return state.articleNumber
	}
}

const actions = {
	toogleRefoundModal ({ commit }, showModal) {
		commit(SWITCH_ARTICLE_REFOUND_MODAL, showModal)
	},
	toogleTransferModal ({ commit }, showModal) {
		commit(SWITCH_TRANSFER_MODAL, showModal)
	},
	toogleNewModal ({ commit }, showModal) {
		commit(SWITCH_ARTICLE_NEW_MODAL, showModal)
	},
	toogleEditModal ({ commit }, showModal) {
		commit(SWITCH_ARTICLE_EDIT_MODAL, showModal)
	},
	toogleShowModal ({ commit }, showModal) {
		commit(SWITCH_ARTICLE_SHOW_MODAL, showModal)
	},
	toogleImportModal ({ commit }, showModal) {
		commit(SWITCH_ARTICLE_IMPORT_MODAL, showModal)
	},
	openTransferModal ({ commit }, articleId) {
		commit(ARTICLE_EDIT, articleId)
	},
	openEditModal ({ commit }, articleId) {
		commit(ARTICLE_EDIT, articleId)
	},
	openShowModal ({ commit }, articleId) {
		commit(SWITCH_ARTICLE_SHOW_MODAL, true)
		commit(ARTICLE_EDIT, articleId)
	},
	openRefoundModal ({ commit }, { sale, article }) {
		commit(ARTICLE_REFOUND, { sale, article })
		commit(SWITCH_ARTICLE_REFOUND_MODAL, true)
	},
	async getArticles ({ commit, dispatch }) {
		commit(ARTICLE_TABLE_LOADING, true)
		// noinspection JSUnresolvedVariable
		await apiArticle
			.fetchArticles()
			.then(({ data }) => {
				commit(FETCHING_ARTICLES, data.data)
				commit(ARTICLE_TABLE_LOADING, false)
				this.dispatch('auth/updateAccess', data)
			}).catch((error) => commit(FAILED_ARTICLE, error))
	},
	async getArticlesMerge ({ commit, dispatch }) {
		commit(ARTICLE_TABLE_LOADING, true)
		// noinspection JSUnresolvedVariable
		await apiArticle
			.fetchArticles()
			.then(({ data }) => {
				commit(FETCHING_ARTICLES_MERGE, data.data)
				commit(ARTICLE_TABLE_LOADING, false)
				this.dispatch('auth/updateAccess', data)
			}).catch((error) => commit(FAILED_ARTICLE, error))
	},
	async importArticles ({ commit, dispatch }, articlesData) {
		commit(ENV_DATA_PROCESS, true)
		// noinspection JSUnresolvedVariable
		await apiArticle
			.importArticles(articlesData)
			.then(({ data }) => {
				commit(ENV_DATA_PROCESS, false)
				dispatch('article/toogleImportModal', false, { root: true })
				dispatch('article/getArticles', null, { root: true })
				this.dispatch('auth/updateAccess', data)
			}).catch((error) => commit(FAILED_ARTICLE, error))
	},
	async refoundArticle ({ commit, dispatch }, refound) {
		commit(ENV_DATA_PROCESS, true)
		// noinspection JSUnresolvedVariable
		await apiArticle
			.refoundArticle(refound)
			.then(({ data }) => {
				commit(SWITCH_ARTICLE_REFOUND_MODAL, false)
				this.dispatch('auth/updateAccess', data)
			}).catch((error) => commit(FAILED_ARTICLE, error))
	},
	async createArticle ({ commit, dispatch }, newArticle) {
		commit(ENV_DATA_PROCESS, true)

		await apiArticle
			.sendCreateRequest(newArticle)
			.then((data) => {
				commit(ARTICLE_CREATED)
				commit(ENV_DATA_PROCESS, false)
				dispatch('article/getArticles', null, { root: true })
				this.dispatch('auth/updateAccess', data)
			})
			.catch((error) => commit(FAILED_ARTICLE, error))
	},
	async updateArticle ({ commit, dispatch }, articleE) {
		commit(ENV_DATA_PROCESS, true)
		const request = articleE || state.editArticle

		// const request = profile || state.editUser
		await apiArticle
			.sendUpdateRequest(request)
			.then((response) => {
				commit(ARTICLE_UPDATED)
				commit(ENV_DATA_PROCESS, false)
				dispatch('article/getArticles', null, { root: true })
				this.dispatch('auth/updateAccess', response.access)
			})
			.catch((error) => {
				commit(ENV_DATA_PROCESS, false)
				commit(FAILED_ARTICLE, error)
			})
	},
	async deleteArticle ({ commit, dispatch }, articleId) {
		await apiArticle
			.sendDeleteRequest(articleId)
			.then((response) => {
				commit(ARTICLE_DELETE)
				this.dispatch('article/getArticles')
				this.dispatch('auth/updateAccess', response.access)
			})
			.catch((error) => commit(FAILED_ARTICLE, error))
	},
	async fetchArticleNumber ({
		commit,
		dispatch
	}) {
		await apiArticle
			.fetchArticleNumber()
			.then(({ data }) => {
				commit(FETCHING_ARTICLE_NUMBER, data.data)
				dispatch('auth/updateAccess', data.access, { root: true })
			}).catch((error) => commit(FAILED_ARTICLE, error))
	}
}

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
}
