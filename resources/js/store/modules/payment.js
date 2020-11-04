import payment from '../../api/payment'
import data from '../../data'

const FETCHING_PAYMENTS = 'FETCHING_PAYMENTS'
const SWITCH_PAYMENT_NEW_MODAL = 'SWITCH_PAYMENT_NEW_MODAL'
const SWITCH_PAYMENT_EDIT_MODAL = 'SWITCH_PAYMENT_EDIT_MODAL'
const SWITCH_PAYMENT_SHOW_MODAL = 'SWITCH_PAYMENT_SHOW_MODAL'
const PAYMENT_CREATED = 'PAYMENT_CREATED'
const PAYMENT_EDIT = 'PAYMENT_EDIT'
const PAYMENT_UPDATED = 'PAYMENT_UPDATED'
const PAYMENT_DELETE = 'PAYMENT_DELETE'
const PAYMENT_TABLE_LOADING = 'PAYMENT_TABLE_LOADING'
const FAILED_PAYMENT = 'FAILED_PAYMENT'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_PAYMENT = 'SET_EDIT_PAYMENT'
const SET_PAYMENT_AVATAR = 'SET_PAYMENT_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  payments: [],
  avatar: '',
  loading: false,
  saved: false,
  newPayment: {
    name: '',
    method: ''
  },
  editPayment: {
    id: '',
    name: '',
    method: ''
  },
  isPaymentLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_PAYMENT_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_PAYMENT_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_PAYMENT_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [PAYMENT_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isPaymentLoading = isLoading
  },
  [FETCHING_PAYMENTS] (state, paymentsData) {
    paymentsData.map((value) => {
      Object.keys(data.payments).map((key) => {
        if (key === value.method) {
          value.enEs = data.payments[key].en + '(' + data.payments[key].es + ')'
          value.method = data.payments[key]
        }
      })
    })
    state.payments = paymentsData
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [PAYMENT_CREATED] (state) {
    state.showNewModal = false
    state.newPayment = {
      name: '',
      method: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.pay')]
      )
    })
  },
  [PAYMENT_EDIT] (state, paymentId) {
    state.editPayment = Object.assign({}, state.payments
      .filter(node => node.id === paymentId)
      .shift()
    )
  },
  [PAYMENT_UPDATED] (state) {
    state.showEditModal = false
    state.editPayment = {
      id: '',
      name: '',
      method: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.pay')]
      )
    })
  },
  [SET_EDIT_PAYMENT] (state, profile) {
    state.editPayment.push(profile)
  },
  [PAYMENT_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.pay')]
      )
    })
  },
  [SET_PAYMENT_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_PAYMENT] (state, error) {
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.pay')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_PAYMENT_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_PAYMENT_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_PAYMENT_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, paymentId) {
    commit(SWITCH_PAYMENT_EDIT_MODAL, true)
    commit(PAYMENT_EDIT, paymentId)
  },
  openShowModal ({ commit }, paymentId) {
    commit(SWITCH_PAYMENT_SHOW_MODAL, true)
    commit(PAYMENT_EDIT, paymentId)
  },
  async getPayments ({ commit }) {
    commit(PAYMENT_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await payment
      .fetchPayments()
      .then(({ data }) => {
        commit(FETCHING_PAYMENTS, data.data)
        commit(PAYMENT_TABLE_LOADING, false)
        return data
      }).catch((error) => commit(FAILED_PAYMENT, error))
  },
  async createPayment ({ commit, dispatch }, newPayment) {
    commit(ENV_DATA_PROCESS, true)

    await payment
      .sendCreateRequest(newPayment)
      .then(() => {
        commit(PAYMENT_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('payment/getPayments', null, { root: true })
      })
      .catch((error) => commit(FAILED_PAYMENT, error))
  },
  async updatePayment ({ commit, dispatch }, editPayment) {
    commit(ENV_DATA_PROCESS, true)
    await payment
      .sendUpdateRequest(editPayment)
      .then(() => {
        commit(PAYMENT_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('payment/getPayments', null, { root: true })
      })
      .catch((error) => commit(FAILED_PAYMENT, error))
  },
  async deletePayment ({ commit, dispatch }, paymentId) {
    await payment
      .sendDeleteRequest(paymentId)
      .then(() => {
        commit(PAYMENT_DELETE)
        dispatch('payment/getPayments', null, { root: true })
      })
      .catch((error) => commit(FAILED_PAYMENT, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
