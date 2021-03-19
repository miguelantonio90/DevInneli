import refund from '../../api/refund'

const FETCHING_REFUNDS = 'FETCHING_REFUNDS'
const SWITCH_REFUND_NEW_MODAL = 'SWITCH_REFUND_NEW_MODAL'
const SWITCH_REFUND_EDIT_MODAL = 'SWITCH_REFUND_EDIT_MODAL'
const REFUND_CREATED = 'REFUND_CREATED'
const REFUND_EDIT = 'REFUND_EDIT'
const REFUND_NEW = 'REFUND_NEW'
const REFUND_UPDATED = 'REFUND_UPDATED'
const REFUND_DELETE = 'REFUND_DELETE'
const REFUND_TABLE_LOADING = 'REFUND_TABLE_LOADING'
const FAILED_REFUND = 'FAILED_REFUND'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_REFUND = 'SET_EDIT_REFUND'
const SET_REFUND_AVATAR = 'SET_REFUND_AVATAR'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewModal: false,
  showTransfer: false,
  showEditModal: false,
  showShowModal: false,
  refunds: [],
  loading: false,
  saved: false,
  newRefund: {
    sale: {},
    article: {},
    box: null,
    bank: null,
    cant: 0,
    money: 0
  },
  editRefund: {
    id: '',
    sale: {},
    article: {},
    box: {},
    bank: {},
    cant: 0,
    money: 0
  },
  isArticleTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_REFUND_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_REFUND_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [REFUND_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_REFUNDS] (state, refunds) {
    state.refunds = refunds
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [CANCEL_MODAL] (state) {
    state.newRefund = {
	  sale: {},
	  article: {},
	  cant: 0,
	  money: 0
    }
    state.saved = false
  },
  [REFUND_CREATED] (state) {
    state.showNewModal = false
    state.showTransfer = false
    state.newRefund = {
	  sale: {},
	  article: {},
	  cant: 0,
	  money: 0
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
        this._vm.$language.t('$vuetify.menu.refund')
	  ])
    })
  },
  [REFUND_NEW] (state, {
    sale,
    article
  }) {
    state.newRefund.sale = sale
    state.newRefund.article = article
  },
  [REFUND_EDIT] (state, refundId) {
    state.editRefund = Object.assign(
	  {},
	  state.refunds.filter(node => node.id === refundId).shift()
    )
  },
  [REFUND_UPDATED] (state) {
    state.showEditModal = false
    state.showTransfer = false
    state.editRefund = {
	  id: '',
	  sale: {},
	  article: {},
	  cant: 0,
	  money: 0
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
        this._vm.$language.t('$vuetify.menu.refund')
	  ])
    })
  },
  [SET_EDIT_REFUND] (state, profile) {
    state.editRefund.push(profile)
  },
  [REFUND_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
        this._vm.$language.t('$vuetify.menu.refund')
	  ])
    })
  },
  [SET_REFUND_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_REFUND] (state, error) {
    state.isActionInProgress = false
    state.isArticleTableLoading = false
    state.isTableLoading = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
        this._vm.$language.t('$vuetify.menu.refund')
	  ])
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_REFUND_NEW_MODAL, showModal)
    if (!showModal) {
	  commit(CANCEL_MODAL)
    }
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_REFUND_EDIT_MODAL, showModal)
  },
  openTransferModal ({ commit }, refundId) {
    commit(REFUND_EDIT, refundId)
  },
  openEditModal ({ commit }, refundId) {
    commit(REFUND_EDIT, refundId)
  },
  openNewModal ({ commit }, {
    sale,
    article
  }) {
    commit(REFUND_NEW, {
	  sale,
	  article
    })
    commit(SWITCH_REFUND_NEW_MODAL, true)
  },
  async getRefunds ({
    commit,
    dispatch
  }) {
    commit(REFUND_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await refund
	  .fetchRefunds()
	  .then(({ data }) => {
        commit(FETCHING_REFUNDS, data.data)
        commit(REFUND_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        this.dispatch('sale/switchLoadData', true)
	  })
	  .catch(error => commit(FAILED_REFUND, error))
  },
  async createRefund ({
    commit,
    dispatch
  }, newRefund) {
    commit(ENV_DATA_PROCESS, true)

    await refund
	  .sendCreateRequest(newRefund)
	  .then(data => {
        commit(REFUND_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('refund/getRefunds', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_REFUND, error))
  },
  async updateRefund ({
    commit,
    dispatch
  }, refundE) {
    commit(ENV_DATA_PROCESS, true)
    const request = refundE || state.editRefund

    // const request = profile || state.editUser
    await refund
	  .sendUpdateRequest(request)
	  .then(response => {
        commit(REFUND_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('refund/getRefunds', null, { root: true })
        this.dispatch('auth/updateAccess', response.access)
	  })
	  .catch(error => {
        commit(ENV_DATA_PROCESS, false)
        commit(FAILED_REFUND, error)
	  })
  },
  async deleteRefund ({
    commit,
    dispatch
  }, refundId) {
    await refund
	  .sendDeleteRequest(refundId)
	  .then(response => {
        commit(REFUND_DELETE)
        this.dispatch('refund/getRefunds')
        this.dispatch('auth/updateAccess', response.access)
	  })
	  .catch(error => commit(FAILED_REFUND, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
