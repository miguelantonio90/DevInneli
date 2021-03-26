import account from '../../api/account'

const FETCHING_ACCOUNTS = 'FETCHING_ACCOUNTS'
const SWITCH_ACCOUNT_NEW_MODAL = 'SWITCH_ACCOUNT_NEW_MODAL'
const SWITCH_ACCOUNT_EDIT_MODAL = 'SWITCH_ACCOUNT_EDIT_MODAL'
const SWITCH_ACCOUNT_SHOW_MODAL = 'SWITCH_ACCOUNT_SHOW_MODAL'
const ACCOUNT_CREATED = 'ACCOUNT_CREATED'
const ACCOUNT_EDIT = 'ACCOUNT_EDIT'
const ACCOUNT_UPDATED = 'ACCOUNT_UPDATED'
const ACCOUNT_DELETE = 'ACCOUNT_DELETE'
const ACCOUNT_TABLE_LOADING = 'ACCOUNT_TABLE_LOADING'
const FAILED_ACCOUNT = 'FAILED_ACCOUNT'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_ACCOUNT = 'SET_EDIT_ACCOUNT'
const SET_ACCOUNT_AVATAR = 'SET_ACCOUNT_AVATAR'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewAccountModal: false,
  showEditAccountModal: false,
  showShowAccountModal: false,
  accounts: [],
  avatar: '',
  loading: false,
  saved: false,
  newAccount: {
    name: '',
    nature: '',
    init_balance: 0.00,
    category_id: '',
    code: '',
    description: ''
  },
  editAccount: {
    id: '',
    name: '',
    code: '',
    category_id: '',
    description: ''
  },
  isAccountLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_ACCOUNT_NEW_MODAL] (state, showAccountsModal) {
    state.showNewAccountModal = showAccountsModal
  },
  [SWITCH_ACCOUNT_EDIT_MODAL] (state, showAccountsModal) {
    state.showEditAccountModal = showAccountsModal
  },
  [SWITCH_ACCOUNT_SHOW_MODAL] (state, showAccountsModal) {
    state.showShowAccountModal = showAccountsModal
  },
  [ACCOUNT_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isAccountLoading = isLoading
  },
  [FETCHING_ACCOUNTS] (state, accounts) {
    state.accounts = accounts
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [ACCOUNT_CREATED] (state) {
    state.showNewAccountModal = false
    state.newAccount = {
	  name: '',
      category_id: '',
      code: '',
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
        this._vm.$language.t('$vuetify.menu.accounting')
	  ])
    })
  },
  [ACCOUNT_EDIT] (state, accountId) {
    state.editAccount = accountId
  },
  [ACCOUNT_UPDATED] (state) {
    state.showEditAccountModal = false
    state.editAccount = {
	  id: '',
	  name: '',
      code: '',
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
        this._vm.$language.t('$vuetify.menu.accounting')
	  ])
    })
  },
  [SET_EDIT_ACCOUNT] (state, profile) {
    state.editAccount.push(profile)
  },
  [CANCEL_MODAL] (state) {
    state.newAccount = {
	  name: '',
      category_id: '',
      description: ''
    }
    state.saved = false
  },
  [ACCOUNT_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
        this._vm.$language.t('$vuetify.menu.accounting')
	  ])
    })
  },
  [SET_ACCOUNT_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_ACCOUNT] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
        this._vm.$language.t('$vuetify.menu.accounting')
	  ])
    })
  }
}

const getters = {}

const actions = {
  toogleNewAccountsModal ({ commit }, showAccountsModal) {
    commit(SWITCH_ACCOUNT_NEW_MODAL, showAccountsModal)
    if (!showAccountsModal) {
	  commit(CANCEL_MODAL)
    }
  },
  toogleEditAccountsModal ({ commit }, showAccountsModal) {
    commit(SWITCH_ACCOUNT_EDIT_MODAL, showAccountsModal)
  },
  toogleShowAccountsModal ({ commit }, showAccountsModal) {
    commit(SWITCH_ACCOUNT_SHOW_MODAL, showAccountsModal)
  },
  getEditAccount ({ commit, state }, accountId) {
    console.log(accountId)
    account
      .fetchAccount(accountId).then(({ data }) => {
        commit(ACCOUNT_EDIT, data.data)
        return data.data
      })
  },
  openEditAccountsModal ({ commit }, accountId) {
    commit(SWITCH_ACCOUNT_EDIT_MODAL, true)
    commit(ACCOUNT_EDIT, accountId)
  },
  openShowAccountsModal ({ commit }, accountId) {
    commit(SWITCH_ACCOUNT_SHOW_MODAL, true)
    commit(ACCOUNT_EDIT, accountId)
  },
  async getAccounts ({ commit }) {
    commit(ACCOUNT_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await account
	  .fetchAccounts()
	  .then(({ data }) => {
        commit(FETCHING_ACCOUNTS, data.data)
        commit(ACCOUNT_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        return data
	  })
	  .catch(error => commit(FAILED_ACCOUNT, error))
  },
  async createAccount ({
    commit,
    dispatch
  }, newAccount) {
    commit(ENV_DATA_PROCESS, true)

    await account
	  .sendCreateRequest(newAccount)
	  .then(data => {
        commit(ACCOUNT_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('account/getAccounts', null, { root: true })
        dispatch('accountCategory/getTree', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_ACCOUNT, error))
  },
  async getAccountsShop ({ commit }, data) {
    commit(ACCOUNT_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await account
	  .getAccountsShop(data)
	  .then(({ data }) => {
        commit(FETCHING_ACCOUNTS, data.data)
        commit(ACCOUNT_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        return data
	  })
	  .catch(error => commit(FAILED_ACCOUNT, error))
  },
  async updateAccount ({
    commit,
    dispatch
  }, editAccount) {
    commit(ENV_DATA_PROCESS, true)
    await account
	  .sendUpdateRequest(editAccount)
	  .then(data => {
        commit(ACCOUNT_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('account/getAccounts', null, { root: true })
        dispatch('accountCategory/getTree', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_ACCOUNT, error))
  },
  async deleteAccount ({
    commit,
    dispatch
  }, accountId) {
    await account
	  .sendDeleteRequest(accountId)
	  .then(data => {
        commit(ACCOUNT_DELETE)
        dispatch('accountCategory/getTree', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_ACCOUNT, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
