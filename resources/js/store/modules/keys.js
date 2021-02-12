import keys from '../../api/keys'

const FETCHING_KEY = 'FETCHING_KEY'
const SWITCH_KEY_NEW_MODAL = 'SWITCH_KEY_NEW_MODAL'
const SWITCH_KEY_EDIT_MODAL = 'SWITCH_KEY_EDIT_MODAL'
const SWITCH_KEY_SHOW_MODAL = 'SWITCH_KEY_SHOW_MODAL'
const KEY_CREATED = 'KEY_CREATED'
const KEY_EDIT = 'KEY_EDIT'
const KEY_UPDATED = 'KEY_UPDATED'
const KEY_DELETE = 'KEY_DELETE'
const KEY_TABLE_LOADING = 'KEY_TABLE_LOADING'
const FAILED_KEY = 'FAILED_KEY'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const LOAD_KEY_CONST = 'LOAD_KEY_CONST'

const state = {
	showNewModal: false,
	showEditModal: false,
	showShowModal: false,
	keys: [],
	keysToSelect: [],
	avatar: '',
	loading: false,
	saved: false,
	newKey: {
		key: '',
		access_permit: [
			{
				title: {
					name: 'manager_article',
					value: false
				},
				actions: [
					{
						article_list: false
					},
					{
						article_add: false
					},
					{
						article_edit: false
					},
					{ article_delete: false }
				]

			},
			{
				title: { name: 'manager_vending', value: false },
				actions: [{ vending_list: false },
					{ vending_add: false }, { vending_edit: false }, { vending_delete: false }
				]
			},
			{
				title: {
					name: 'manager_category',
					value: false
				},
				actions: [{ category_list: false }, { category_add: false }, { category_edit: false }, { category_delete: false }
				]
			},
			{
				title: {
					name: 'manager_mod',
					value: false
				},
				actions: [{ mod_list: false }, { mod_add: false }, { mod_edit: false }, { mod_delete: false }]
			},
			{
				title: {
					name: 'manager_supplier',
					value: false
				},
				actions: [{ supplier_list: false }, { supplier_add: false }, { supplier_edit: false }, { supplier_delete: false }]
			},
			{
				title: {
					name: 'manager_buy',
					value: false
				},
				actions: [{ buy_list: false }, { buy_add: false }, { buy_edit: false }, { buy_delete: false }]
			},
			{
				title: {
					name: 'manager_sell',
					value: false
				},
				actions: [{ sell_by_product: false }, { sell_by_category: false }, { sell_by_employer: false }, { sell_by_payments: false }]
			},
			{
				title: {
					name: 'manager_employer',
					value: false
				},
				actions: [{ employer_list: false }, { employer_add: false }, { employer_edit: false }, { employer_delete: false }]
			},
			{
				title: {
					name: 'manager_assistance',
					value: false
				},
				actions: [{ assistance_list: false }, { assistance_add: false }, { assistance_edit: false }, { assistance_delete: false }]
			},
			{
				title: {
					name: 'manager_client',
					value: false
				},
				actions: [{ client_list: false }, { client_add: false }, { client_edit: false }, { client_delete: false }]
			},
			{
				title: {
					name: 'manager_shop',
					value: false
				},
				actions: [{ shop_list: false }, { shop_add: false }, { shop_edit: false }, { shop_delete: false }]
			},
			{
				title: {
					name: 'manager_access',
					value: false
				},
				actions: [{ access_list: false }, { access_add: false }, { access_edit: false }, { access_delete: false }]
			},
			{
				title: {
					name: 'manager_payment',
					value: false
				},
				actions: [{ payment_list: false }, { payment_add: false }, { payment_edit: false }, { payment_delete: false }]
			},
			{
				title: {
					name: 'manager_expense_category',
					value: false
				},
				actions: [{ expense_category_list: false }, { expense_category_add: false }, { expense_category_edit: false }, { expense_category_delete: false }]
			},
			{
				title: {
					name: 'manager_exchange_rate',
					value: false
				},
				actions: [{ exchange_rate_list: false }, { exchange_rate_add: false }, { exchange_rate_edit: false }, { exchange_rate_delete: false }]
			},
			{
				title: {
					name: 'manager_type_of_order',
					value: false
				},
				actions: [{ type_of_order_list: false }, { type_of_order_add: false }, { type_of_order_edit: false }, { type_of_order_delete: false }]
			},
			{
				title: {
					name: 'manager_tax',
					value: false
				},
				actions: [{ tax_list: false }, { tax_add: false }, { tax_edit: false }, { tax_delete: false }]
			},
			{
				title: {
					name: 'manager_discount',
					value: false
				},
				actions: [{ discount_list: false }, { discount_add: false }, { discount_edit: false }, { discount_delete: false }]
			}
		]
	},
	editKey: {
		id: '',
		key: '',
		access_permit: []
	},
	isKeyLoading: false,
	isActionInProgress: false,
	isTableLoading: false
}

const mutations = {
	[LOAD_KEY_CONST] (state) {
	},
	[SWITCH_KEY_NEW_MODAL] (state, showModal) {
		state.showNewModal = showModal
	},
	[SWITCH_KEY_EDIT_MODAL] (state, showModal) {
		state.showEditModal = showModal
	},
	[SWITCH_KEY_SHOW_MODAL] (state, showModal) {
		state.showShowModal = showModal
	},
	[KEY_TABLE_LOADING] (state, isLoading) {
		state.isTableLoading = isLoading
		state.isKeyLoading = isLoading
	},
	[FETCHING_KEY] (state, keys) {
		state.keys = keys
	},
	[ENV_DATA_PROCESS] (state, isActionInProgress) {
		state.isActionInProgress = isActionInProgress
	},
	[KEY_CREATED] (state) {
		state.showNewModal = false
		state.newKey = {
			key: '',
			access_permit: [
				{
					title: {
						name: 'manager_article',
						value: false
					},
					actions: [
						{
							article_list: false
						},
						{
							article_add: false
						},
						{
							article_edit: false
						},
						{ article_delete: false }
					]

				},
				{
					title: { name: 'manager_vending', value: false },
					actions: [{ vending_list: false },
						{ vending_add: false }, { vending_edit: false }, { vending_delete: false }
					]
				},
				{
					title: {
						name: 'manager_category',
						value: false
					},
					actions: [{ category_list: false }, { category_add: false }, { category_edit: false }, { category_delete: false }
					]
				},
				{
					title: {
						name: 'manager_mod',
						value: false
					},
					actions: [{ mod_list: false }, { mod_add: false }, { mod_edit: false }, { mod_delete: false }]
				},
				{
					title: {
						name: 'manager_supplier',
						value: false
					},
					actions: [{ supplier_list: false }, { supplier_add: false }, { supplier_edit: false }, { supplier_delete: false }]
				},
				{
					title: {
						name: 'manager_buy',
						value: false
					},
					actions: [{ buy_list: false }, { buy_add: false }, { buy_edit: false }, { buy_delete: false }]
				},
				{
					title: {
						name: 'manager_sell',
						value: false
					},
					actions: [{ sell_by_product: false }, { sell_by_category: false }, { sell_by_employer: false }, { sell_by_payments: false }]
				},
				{
					title: {
						name: 'manager_employer',
						value: false
					},
					actions: [{ employer_list: false }, { employer_add: false }, { employer_edit: false }, { employer_delete: false }]
				},
				{
					title: {
						name: 'manager_assistance',
						value: false
					},
					actions: [{ assistance_list: false }, { assistance_add: false }, { assistance_edit: false }, { assistance_delete: false }]
				},
				{
					title: {
						name: 'manager_client',
						value: false
					},
					actions: [{ client_list: false }, { client_add: false }, { client_edit: false }, { client_delete: false }]
				},
				{
					title: {
						name: 'manager_shop',
						value: false
					},
					actions: [{ shop_list: false }, { shop_add: false }, { shop_edit: false }, { shop_delete: false }]
				},
				{
					title: {
						name: 'manager_access',
						value: false
					},
					actions: [{ access_list: false }, { access_add: false }, { access_edit: false }, { access_delete: false }]
				},
				{
					title: {
						name: 'manager_payment',
						value: false
					},
					actions: [{ payment_list: false }, { payment_add: false }, { payment_edit: false }, { payment_delete: false }]
				},
				{
					title: {
						name: 'manager_expense_category',
						value: false
					},
					actions: [{ expense_category_list: false }, { expense_category_add: false }, { expense_category_edit: false }, { expense_category_delete: false }]
				},
				{
					title: {
						name: 'manager_exchange_rate',
						value: false
					},
					actions: [{ exchange_rate_list: false }, { exchange_rate_add: false }, { exchange_rate_edit: false }, { exchange_rate_delete: false }]
				},
				{
					title: {
						name: 'manager_type_of_order',
						value: false
					},
					actions: [{ type_of_order_list: false }, { type_of_order_add: false }, { type_of_order_edit: false }, { type_of_order_delete: false }]
				},
				{
					title: {
						name: 'manager_tax',
						value: false
					},
					actions: [{ tax_list: false }, { tax_add: false }, { tax_edit: false }, { tax_delete: false }]
				},
				{
					title: {
						name: 'manager_discount',
						value: false
					},
					actions: [{ discount_list: false }, { discount_add: false }, { discount_edit: false }, { discount_delete: false }]
				}
			]
		}
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.access')]
			)
		})
	},
	[KEY_EDIT] (state, keyId) {
		state.editKey = Object.assign({}, state.keys
			.filter(node => node.id === keyId)
			.shift()
		)
		state.editKey.access_permit = JSON.parse(state.editKey.access_permit)
	},
	[KEY_UPDATED] (state) {
		state.showEditModal = false
		state.editKey = {
			id: '',
			name: '',
			accessPin: false,
			accessEmail: false,
			description: ''
		}
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.access')]
			)
		})
	},
	[KEY_DELETE] (state) {
		state.saved = true
		this._vm.$Toast.fire({
			icon: 'success',
			title: this._vm.$language.t(
				'$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.access')]
			)
		})
	},
	[FAILED_KEY] (state, error) {
		state.saved = false
		state.error = error
		state.isKeyLoading = false
		state.isActionInProgress = false
		state.isTableLoading = false
		this._vm.$Toast.fire({
			icon: 'error',
			title: this._vm.$language.t(
				'$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.access')]
			)
		})
	}
}

const getters = {}

const actions = {
	toogleNewModal ({ commit }, showModal) {
		commit(LOAD_KEY_CONST)
		commit(SWITCH_KEY_NEW_MODAL, showModal)
	},
	toogleEditModal ({ commit }, showModal) {
		commit(LOAD_KEY_CONST)
		commit(SWITCH_KEY_EDIT_MODAL, showModal)
	},
	toogleShowModal ({ commit }, showModal) {
		commit(SWITCH_KEY_SHOW_MODAL, showModal)
	},
	openEditModal ({ commit }, keyId) {
		commit(SWITCH_KEY_EDIT_MODAL, true)
		commit(KEY_EDIT, keyId)
	},
	openShowModal ({ commit }, keyId) {
		commit(SWITCH_KEY_SHOW_MODAL, true)
		commit(KEY_EDIT, keyId)
	},
	async getKeys ({ commit }) {
		commit(KEY_TABLE_LOADING, true)
		// noinspection JSUnresolvedVariable
		await keys
			.fetchKeys()
			.then(({ data }) => {
				commit(FETCHING_KEY, data.data)
				commit(KEY_TABLE_LOADING, false)
				this.dispatch('auth/updateAccess', data)
			})
			.catch(error => commit(FAILED_KEY, error))
	},
	loadKeysPermitConst ({ commit }) {
		commit(LOAD_KEY_CONST)
	},
	async createKey ({ commit, dispatch }, newKey) {
		commit(ENV_DATA_PROCESS, true)

		await keys
			.sendCreateRequest(newKey)
			.then((data) => {
				commit(KEY_CREATED)
				commit(ENV_DATA_PROCESS, false)
				dispatch('keys/getKeys', null, { root: true })
				this.dispatch('auth/updateAccess', data)
			})
			.catch(error => commit(FAILED_KEY, error))
	},
	async updateKey ({ commit, dispatch }, editKey) {
		commit(ENV_DATA_PROCESS, true)

		await keys
			.sendUpdateRequest(editKey)
			.then((data) => {
				commit(KEY_UPDATED)
				commit(ENV_DATA_PROCESS, false)
				dispatch('keys/getKeys', null, { root: true })
				this.dispatch('auth/updateAccess', data)
			})
			.catch(error => commit(FAILED_KEY, error))
	},
	async deleteKey ({ commit, dispatch }, keyId) {
		await keys
			.sendDeleteRequest(keyId)
			.then((data) => {
				commit(KEY_DELETE)
				this.dispatch('keys/getKeys')
				this.dispatch('auth/updateAccess', data)
			})
			.catch(error => commit(FAILED_KEY, error))
	}
}

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
}
