import bank from '../../api/bank'

const FETCHING_BANKS = 'FETCHING_BANKS'
const SWITCH_BANK_NEW_MODAL = 'SWITCH_BANK_NEW_MODAL'
const SWITCH_BANK_EDIT_MODAL = 'SWITCH_BANK_EDIT_MODAL'
const SWITCH_BANK_SHOW_MODAL = 'SWITCH_BANK_SHOW_MODAL'
const BANK_CREATED = 'BANK_CREATED'
const BANK_EDIT = 'BANK_EDIT'
const BANK_UPDATED = 'BANK_UPDATED'
const BANK_DELETE = 'BANK_DELETE'
const BANK_TABLE_LOADING = 'BANK_TABLE_LOADING'
const FAILED_BANK = 'FAILED_BANK'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_BANK = 'SET_EDIT_BANK'
const SET_BANK_AVATAR = 'SET_BANK_AVATAR'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  banks: [],
  avatar: '',
  loading: false,
  saved: false,
  newBank: {
    count_type: '',
    name: '',
    count_number: '',
    currency: {},
    init_balance: 0.00,
    date: '',
    description: ''
  },
  editBank: {
    id: '',
    count_type: '',
    name: '',
    currency: {},
    count_number: '',
    init_balance: 0.00,
    date: '',
    description: ''
  },
  isBankLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_BANK_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_BANK_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_BANK_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [BANK_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isBankLoading = isLoading
  },
  [FETCHING_BANKS] (state, banks) {
    state.banks = banks
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [BANK_CREATED] (state) {
    state.showNewModal = false
    state.newBank = {
      count_type: '',
      name: '',
      currency: {},
      count_number: '',
      init_balance: 0.00,
      date: '',
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
        this._vm.$language.t('$vuetify.menu.bank')
	  ])
    })
  },
  [BANK_EDIT] (state, bankId) {
    state.editBank = Object.assign(
	  {},
	  state.banks.filter(node => node.id === bankId).shift()
    )
    console.log(state.editBank)
  },
  [BANK_UPDATED] (state) {
    state.showEditModal = false
    state.editBank = {
	  id: '',
      count_type: '',
      name: '',
      currency: {},
      count_number: '',
      init_balance: 0.00,
      date: '',
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
        this._vm.$language.t('$vuetify.menu.bank')
	  ])
    })
  },
  [SET_EDIT_BANK] (state, profile) {
    state.editBank.push(profile)
  },
  [CANCEL_MODAL] (state) {
    state.newBank = {
      type: '',
	  name: '',
      init_balance: 0.00,
      currency: {},
      count_number: '',
      description: '',
	  color: ''
    }
    state.saved = false
  },
  [BANK_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
        this._vm.$language.t('$vuetify.menu.bank')
	  ])
    })
  },
  [SET_BANK_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_BANK] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
        this._vm.$language.t('$vuetify.menu.bank')
	  ])
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_BANK_NEW_MODAL, showModal)
    if (!showModal) {
	  commit(CANCEL_MODAL)
    }
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_BANK_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_BANK_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, bankId) {
    commit(SWITCH_BANK_EDIT_MODAL, true)
    commit(BANK_EDIT, bankId)
  },
  openShowModal ({ commit }, bankId) {
    commit(SWITCH_BANK_SHOW_MODAL, true)
    commit(BANK_EDIT, bankId)
  },
  async getBanks ({ commit }) {
    commit(BANK_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await bank
	  .fetchBanks()
	  .then(({ data }) => {
        commit(FETCHING_BANKS, data.data)
        commit(BANK_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        return data
	  })
	  .catch(error => commit(FAILED_BANK, error))
  },
  async createBank ({
    commit,
    dispatch
  }, newBank) {
    commit(ENV_DATA_PROCESS, true)

    await bank
	  .sendCreateRequest(newBank)
	  .then(data => {
        commit(BANK_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('bank/getBanks', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_BANK, error))
  },
  async getBanksShop ({ commit }, data) {
    commit(BANK_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await bank
	  .getBanksShop(data)
	  .then(({ data }) => {
        commit(FETCHING_BANKS, data.data)
        commit(BANK_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        return data
	  })
	  .catch(error => commit(FAILED_BANK, error))
  },
  async updateBank ({
    commit,
    dispatch
  }, editBank) {
    commit(ENV_DATA_PROCESS, true)
    await bank
	  .sendUpdateRequest(editBank)
	  .then(data => {
        commit(BANK_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('bank/getBanks', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_BANK, error))
  },
  async deleteBank ({
    commit,
    dispatch
  }, bankId) {
    await bank
	  .sendDeleteRequest(bankId)
	  .then(data => {
        commit(BANK_DELETE)
        dispatch('bank/getBanks', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_BANK, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
