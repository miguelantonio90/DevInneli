import supplier from '../../api/supplier'
import data from '../../data'

const FETCHING_SUPPLIERS = 'FETCHING_SUPPLIERS'
const FETCHING_CLIENT_SUPPLIERS = 'FETCHING_CLIENT_SUPPLIERS'
const SWITCH_SUPPLIER_NEW_MODAL = 'SWITCH_SUPPLIER_NEW_MODAL'
const SWITCH_SUPPLIER_EDIT_MODAL = 'SWITCH_SUPPLIER_EDIT_MODAL'
const SWITCH_SUPPLIER_SHOW_MODAL = 'SWITCH_SUPPLIER_SHOW_MODAL'
const SUPPLIER_CREATED = 'SUPPLIER_CREATED'
const SUPPLIER_EDIT = 'SUPPLIER_EDIT'
const SUPPLIER_UPDATED = 'SUPPLIER_UPDATED'
const SUPPLIER_DELETE = 'SUPPLIER_DELETE'
const SUPPLIER_TABLE_LOADING = 'SUPPLIER_TABLE_LOADING'
const FAILED_SUPPLIER = 'FAILED_SUPPLIER'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_SUPPLIER = 'SET_EDIT_SUPPLIER'
const SET_SUPPLIER_AVATAR = 'SET_SUPPLIER_AVATAR'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  suppliers: [],
  clientSuppliers: [],
  loading: false,
  saved: false,
  newSupplier: {
    name: '',
    identity: '',
    email: '',
    contract: '',
    phone: '',
    country: '',
    address: '',
    note: '',
    expanse: ''
  },
  editSupplier: {
    id: '',
    name: '',
    identity: '',
    email: '',
    contract: '',
    phone: '',
    country: '',
    address: '',
    note: '',
    expanse: ''
  },
  isSupplierTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_SUPPLIER_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_SUPPLIER_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_SUPPLIER_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [SUPPLIER_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_SUPPLIERS] (state, suppliers) {
    suppliers.map(value => {
	  Object.keys(data.countries).map(key => {
        if (key === value.country) {
		  value.nameCountry =
			data.countries[key].name +
			'(' +
			data.countries[key].native +
			')'
		  value.countryFlag = data.countries[key].emoji
        }
	  })
    })

    state.suppliers = suppliers
  },
  [FETCHING_CLIENT_SUPPLIERS] (state, suppliers) {
    suppliers.map(value => {
	  Object.keys(data.countries).map(key => {
        if (key === value.country) {
		  value.nameCountry =
			data.countries[key].name +
			'(' +
			data.countries[key].native +
			')'
		  value.countryFlag = data.countries[key].emoji
        }
	  })
    })

    state.clientSuppliers = suppliers
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [CANCEL_MODAL] (state) {
    state.newSupplier = {
	  name: '',
	  identity: '',
	  email: '',
	  contract: '',
	  phone: '',
	  country: '',
	  address: '',
	  note: '',
	  expanse: ''
    }
    state.saved = false
  },
  [SUPPLIER_CREATED] (state) {
    state.showNewModal = false
    state.newSupplier = {
	  name: '',
	  identity: '',
	  email: '',
	  contract: '',
	  phone: '',
	  country: '',
	  address: '',
	  note: '',
	  expanse: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
        this._vm.$language.t('$vuetify.menu.supplier')
	  ])
    })
  },
  [SUPPLIER_EDIT] (state, supplierId) {
    state.editSupplier = Object.assign(
	  {},
	  state.suppliers.filter(node => node.id === supplierId).shift()
    )
  },
  [SUPPLIER_UPDATED] (state) {
    state.showEditModal = false
    state.editSupplier = {
	  id: '',
	  name: '',
	  identity: '',
	  email: '',
	  contract: '',
	  phone: '',
	  country: '',
	  address: '',
	  note: '',
	  expanse: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
        this._vm.$language.t('$vuetify.menu.supplier')
	  ])
    })
  },
  [SET_EDIT_SUPPLIER] (state, profile) {
    state.editSupplier.push(profile)
  },
  [SUPPLIER_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
        this._vm.$language.t('$vuetify.menu.supplier')
	  ])
    })
  },
  [FAILED_SUPPLIER] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
        this._vm.$language.t('$vuetify.menu.supplier')
	  ])
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_SUPPLIER_NEW_MODAL, showModal)
    if (!showModal) {
	  commit(CANCEL_MODAL)
    }
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_SUPPLIER_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_SUPPLIER_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, supplierId) {
    commit(SWITCH_SUPPLIER_EDIT_MODAL, true)
    commit(SUPPLIER_EDIT, supplierId)
  },
  openShowModal ({ commit }, supplierId) {
    commit(SWITCH_SUPPLIER_SHOW_MODAL, true)
    commit(SUPPLIER_EDIT, supplierId)
  },
  async getSuppliers ({ commit }) {
    commit(SUPPLIER_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await supplier
	  .fetchSuppliers()
	  .then(({ data }) => {
        commit(FETCHING_SUPPLIERS, data.data)
        commit(SUPPLIER_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_SUPPLIER, error))
  },
  async getClientSupplier ({ commit }) {
    commit(SUPPLIER_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await supplier
	  .fetchClientSupplier()
	  .then(({ data }) => {
        commit(FETCHING_CLIENT_SUPPLIERS, data.data)
        commit(SUPPLIER_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_SUPPLIER, error))
  },
  async createSupplier ({
    commit,
    dispatch
  }, newSupplier) {
    commit(ENV_DATA_PROCESS, true)

    await supplier
	  .sendCreateRequest(newSupplier)
	  .then(data => {
        commit(SUPPLIER_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('supplier/getSuppliers', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_SUPPLIER, error))
  },
  async updateSupplier ({
    commit,
    dispatch
  }, editSupplier) {
    commit(ENV_DATA_PROCESS, true)

    await supplier
	  .sendUpdateRequest(editSupplier)
	  .then(data => {
        commit(SUPPLIER_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('supplier/getSuppliers', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_SUPPLIER, error))
  },
  async deleteSupplier ({
    commit,
    dispatch
  }, supplierId) {
    commit(ENV_DATA_PROCESS, true)

    await supplier
	  .sendDeleteRequest(supplierId)
	  .then(data => {
        commit(ENV_DATA_PROCESS, false)
        commit(SUPPLIER_DELETE)
        dispatch('supplier/getSuppliers', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_SUPPLIER, error))
  },

  async updateAvatar ({
    commit,
    dispatch
  }, file) {
    const image = `data:${file.file.type};base64,${file.file.base64}`
    const sendData = {
	  id: file.id,
	  image: image
    }
    await supplier.updateAvatar(sendData).then(data => {
	  commit(SET_SUPPLIER_AVATAR, file.file.base64)
	  dispatch('auth/getSupplierData', null, { root: true })
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
