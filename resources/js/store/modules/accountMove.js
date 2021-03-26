import move from '../../api/accountMove'

const FETCHING_MOVES = 'FETCHING_MOVES'
const SWITCH_MOVE_NEW_MODAL = 'SWITCH_MOVE_NEW_MODAL'
const SWITCH_MOVE_EDIT_MODAL = 'SWITCH_MOVE_EDIT_MODAL'
const SWITCH_MOVE_SHOW_MODAL = 'SWITCH_MOVE_SHOW_MODAL'
const MOVE_CREATED = 'MOVE_CREATED'
const MOVE_EDIT = 'MOVE_EDIT'
const MOVE_UPDATED = 'MOVE_UPDATED'
const MOVE_DELETE = 'MOVE_DELETE'
const MOVE_TABLE_LOADING = 'MOVE_TABLE_LOADING'
const FAILED_MOVE = 'FAILED_MOVE'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_MOVE = 'SET_EDIT_MOVE'
const SET_MOVE_AVATAR = 'SET_MOVE_AVATAR'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewMoveModal: false,
  showEditMoveModal: false,
  showShowMoveModal: false,
  moves: [],
  avatar: '',
  loading: false,
  saved: false,
  newMove: {
    detail: '',
    ref: '',
    debit: 0.00,
    credit: 0.00,
    account_id: '',
    date: ''
  },
  editMove: {
    id: '',
    account_id: '',
    detail: '',
    ref: '',
    debit: '',
    credit: '',
    date: ''
  },
  isMoveLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_MOVE_NEW_MODAL] (state, showMovesModal) {
    state.showNewMoveModal = showMovesModal
  },
  [SWITCH_MOVE_EDIT_MODAL] (state, showMovesModal) {
    state.showEditMoveModal = showMovesModal
  },
  [SWITCH_MOVE_SHOW_MODAL] (state, showMovesModal) {
    state.showShowMoveModal = showMovesModal
  },
  [MOVE_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isMoveLoading = isLoading
  },
  [FETCHING_MOVES] (state, moves) {
    state.moves = moves
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [MOVE_CREATED] (state) {
    state.showNewMoveModal = false
    state.newMove = {
      account_id: '',
      detail: '',
      ref: '',
      debit: '',
      credit: '',
      date: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
        this._vm.$language.t('$vuetify.menu.account_move')
	  ])
    })
  },
  [MOVE_EDIT] (state, moveId) {
    state.editMove = Object.assign(
	  {},
	  state.moves.filter(node => node.id === moveId).shift()
    )
  },
  [MOVE_UPDATED] (state) {
    state.showEditMoveModal = false
    state.editMove = {
	  id: '',
      account_id: '',
      detail: '',
      ref: '',
      debit: '',
      credit: '',
      date: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
        this._vm.$language.t('$vuetify.menu.account_move')
	  ])
    })
  },
  [SET_EDIT_MOVE] (state, profile) {
    state.editMove.push(profile)
  },
  [CANCEL_MODAL] (state) {
    state.newMove = {
      account_id: '',
      detail: '',
      ref: '',
      debit: '',
      credit: '',
      date: ''
    }
    state.saved = false
  },
  [MOVE_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
        this._vm.$language.t('$vuetify.menu.account_move')
	  ])
    })
  },
  [SET_MOVE_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_MOVE] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
        this._vm.$language.t('$vuetify.menu.account_move')
	  ])
    })
  }
}

const getters = {}

const actions = {
  toogleNewMovesModal ({ commit }, showMovesModal) {
    commit(SWITCH_MOVE_NEW_MODAL, showMovesModal)
    if (!showMovesModal) {
	  commit(CANCEL_MODAL)
    }
  },
  toogleEditMovesModal ({ commit }, showMovesModal) {
    commit(SWITCH_MOVE_EDIT_MODAL, showMovesModal)
  },
  toogleShowMovesModal ({ commit }, showMovesModal) {
    commit(SWITCH_MOVE_SHOW_MODAL, showMovesModal)
  },
  openEditMovesModal ({ commit }, moveId) {
    commit(SWITCH_MOVE_EDIT_MODAL, true)
    commit(MOVE_EDIT, moveId)
  },
  openShowMovesModal ({ commit }, moveId) {
    commit(SWITCH_MOVE_SHOW_MODAL, true)
    commit(MOVE_EDIT, moveId)
  },
  async getMoves ({ commit }) {
    commit(MOVE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await move
	  .fetchMoves()
	  .then(({ data }) => {
        commit(FETCHING_MOVES, data.data)
        commit(MOVE_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        return data
	  })
	  .catch(error => commit(FAILED_MOVE, error))
  },
  async createMove ({
    commit,
    dispatch
  }, newMove) {
    commit(ENV_DATA_PROCESS, true)

    await move
	  .sendCreateRequest(newMove)
	  .then(data => {
        commit(MOVE_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('account/getAccounts', null, { root: true })
        dispatch('account/getEditAccount', data.data.data.account_id, { root: true })
        dispatch('accountMove/getMovesByAccount', { account: data.data.data.account_id }, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_MOVE, error))
  },
  async getMovesByAccount ({ commit }, data) {
    commit(MOVE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await move
	  .getMovesAccount(data)
	  .then(({ data }) => {
        commit(FETCHING_MOVES, data.data)
        commit(MOVE_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        return data
	  })
	  .catch(error => commit(FAILED_MOVE, error))
  },
  async updateMove ({
    commit,
    dispatch
  }, editMove) {
    commit(ENV_DATA_PROCESS, true)
    await move
	  .sendUpdateRequest(editMove)
	  .then(data => {
        commit(MOVE_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('account/getEditAccount', data.data.data.account_id, { root: true })
        dispatch('accountMove/getMovesByAccount', { account: data.data.data.account_id }, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_MOVE, error))
  },
  async deleteMove ({
    commit,
    dispatch
  }, moveId) {
    await move
	  .sendDeleteRequest(moveId)
	  .then(data => {
        commit(MOVE_DELETE)
        dispatch('account/getEditAccount', data.data.data.account_id, { root: true })
        dispatch('accountMove/getMovesByAccount', { account: data.data.data.account_id }, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_MOVE, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
