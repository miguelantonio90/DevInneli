import expenseCategory from '../../api/expense_category'

const FETCHING_EXPENSE = 'FETCHING_EXPENSE'
const SWITCH_EXPENSE_NEW_MODAL = 'SWITCH_EXPENSE_NEW_MODAL'
const SWITCH_EXPENSE_EDIT_MODAL = 'SWITCH_EXPENSE_EDIT_MODAL'
const SWITCH_EXPENSE_SHOW_MODAL = 'SWITCH_EXPENSE_SHOW_MODAL'
const EXPENSE_CREATED = 'EXPENSE_CREATED'
const EXPENSE_EDIT = 'EXPENSE_EDIT'
const EXPENSE_UPDATE = 'EXPENSE_UPDATE'
const EXPENSE_DELETE = 'EXPENSE_DELETE'
const EXPENSE_TABLE_LOADING = 'EXPENSE_TABLE_LOADING'
const FAILED_EXPENSE = 'FAILED_EXPENSE'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_CATEGORY = 'SET_EDIT_CATEGORY'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  categories: [],
  saved: false,
  newCategory: {
    name: '',
    description: ''
  },
  editCategory: {
    id: '',
    name: '',
    description: ''
  },
  isCategoryLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_EXPENSE_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_EXPENSE_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_EXPENSE_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [EXPENSE_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isCategoryLoading = isLoading
  },
  [FETCHING_EXPENSE] (state, categories) {
    state.categories = categories
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [EXPENSE_CREATED] (state) {
    state.showNewModal = false
    state.newCategory = {
      name: '',
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.expense_category')]
      )
    })
  },
  [EXPENSE_EDIT] (state, categoryId) {
    state.editCategory = Object.assign({}, state.categories
      .filter(node => node.id === categoryId)
      .shift()
    )
  },
  [EXPENSE_UPDATE] (state) {
    state.showEditModal = false
    state.editCategory = {
      id: '',
      name: '',
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.expense_category')]
      )
    })
  },
  [SET_EDIT_CATEGORY] (state, profile) {
    state.editCategory.push(profile)
  },
  [EXPENSE_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.expense_category')]
      )
    })
  },
  [FAILED_EXPENSE] (state, error) {
    state.isTableLoading = false
    state.isCategoryLoading = false
    state.isActionInProgress = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.expense_category')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_EXPENSE_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_EXPENSE_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_EXPENSE_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, categoryId) {
    commit(SWITCH_EXPENSE_EDIT_MODAL, true)
    commit(EXPENSE_EDIT, categoryId)
  },
  openShowModal ({ commit }, categoryId) {
    commit(SWITCH_EXPENSE_SHOW_MODAL, true)
    commit(EXPENSE_EDIT, categoryId)
  },
  async getExpenseCategories ({ commit }) {
    commit(EXPENSE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await expenseCategory
      .fetchCategories()
      .then(({ data }) => {
        commit(FETCHING_EXPENSE, data.data)
        commit(EXPENSE_TABLE_LOADING, false)
        return data
      }).catch((error) => commit(FAILED_EXPENSE, error))
  },
  async createCategory ({ commit, dispatch }, newCategory) {
    commit(ENV_DATA_PROCESS, true)

    await expenseCategory
      .sendCreateRequest(newCategory)
      .then(() => {
        commit(EXPENSE_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('expenseCategory/getExpenseCategories', null, { root: true })
      })
      .catch((error) => commit(FAILED_EXPENSE, error))
  },
  async updateCategory ({ commit, dispatch }, editCategory) {
    commit(ENV_DATA_PROCESS, true)
    await expenseCategory
      .sendUpdateRequest(editCategory)
      .then(() => {
        commit(EXPENSE_UPDATE)
        commit(ENV_DATA_PROCESS, false)
        dispatch('expenseCategory/getExpenseCategories', null, { root: true })
      })
      .catch((error) => commit(FAILED_EXPENSE, error))
  },
  deleteCategory: async function ({ commit, dispatch }, categoryId) {
    await expenseCategory
      .sendDeleteRequest(categoryId)
      .then(() => {
        commit(EXPENSE_DELETE)
        dispatch('expenseCategory/getExpenseCategories', null, { root: true })
      })
      .catch((error) => commit(FAILED_EXPENSE, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
