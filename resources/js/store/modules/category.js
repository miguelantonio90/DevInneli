import category from '../../api/category'

const FETCHING_CATEGORIES = 'FETCHING_CATEGORIES'
const SWITCH_CATEGORY_NEW_MODAL = 'SWITCH_CATEGORY_NEW_MODAL'
const SWITCH_CATEGORY_EDIT_MODAL = 'SWITCH_CATEGORY_EDIT_MODAL'
const SWITCH_CATEGORY_SHOW_MODAL = 'SWITCH_CATEGORY_SHOW_MODAL'
const CATEGORY_CREATED = 'CATEGORY_CREATED'
const CATEGORY_EDIT = 'CATEGORY_EDIT'
const CATEGORY_UPDATED = 'CATEGORY_UPDATED'
const CATEGORY_DELETE = 'CATEGORY_DELETE'
const CATEGORY_TABLE_LOADING = 'CATEGORY_TABLE_LOADING'
const FAILED_CATEGORY = 'FAILED_CATEGORY'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_CATEGORY = 'SET_EDIT_CATEGORY'
const SET_CATEGORY_AVATAR = 'SET_CATEGORY_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  categories: [],
  avatar: '',
  loading: false,
  saved: false,
  newCategory: {
    name: '',
    color: ''
  },
  editCategory: {
    id: '',
    name: '',
    color: ''
  },
  isCategoryTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_CATEGORY_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_CATEGORY_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_CATEGORY_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [CATEGORY_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_CATEGORIES] (state, categories) {
    state.categories = categories
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [CATEGORY_CREATED] (state) {
    state.showNewModal = false
    state.newCategory = {
      name: ''
    }
    state.saved = true
  },
  [CATEGORY_EDIT] (state, categoryId) {
    state.editCategory = Object.assign({}, state.categories
      .filter(node => node.id === categoryId)
      .shift()
    )
  },
  [CATEGORY_UPDATED] (state) {
    state.showEditModal = false
    state.editCategory = {
      id: '',
      name: ''
    }
    state.saved = true
  },
  [SET_EDIT_CATEGORY] (state, profile) {
    state.editCategory.push(profile)
  },
  [CATEGORY_DELETE] (state) {
    state.saved = true
  },
  [SET_CATEGORY_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_CATEGORY] (state) {
    state.saved = false
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_CATEGORY_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_CATEGORY_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_CATEGORY_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, categoryId) {
    commit(SWITCH_CATEGORY_EDIT_MODAL, true)
    commit(CATEGORY_EDIT, categoryId)
  },
  openShowModal ({ commit }, categoryId) {
    commit(SWITCH_CATEGORY_SHOW_MODAL, true)
    commit(CATEGORY_EDIT, categoryId)
  },
  async getCategories ({ commit }) {
    commit(CATEGORY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await category
      .fetchCategories()
      .then(({ data }) => {
        commit(FETCHING_CATEGORIES, data.data)
        commit(CATEGORY_TABLE_LOADING, false)
        return data
      }).catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async createCategory ({ commit, dispatch }, newCategory) {
    commit(ENV_DATA_PROCESS, true)
    commit('CLEAR_ERRORS', null, { root: true })

    await category
      .sendCreateRequest(newCategory)
      .then(() => {
        commit(CATEGORY_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('category/getCategories', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async updateCategory ({ commit, dispatch }, editCategory) {
    commit('CLEAR_ERRORS', null, { root: true })
    commit(ENV_DATA_PROCESS, true)
    await category
      .sendUpdateRequest(editCategory)
      .then(() => {
        commit(CATEGORY_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('category/getCategories', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async deleteCategory ({ commit, dispatch }, categoryId) {
    commit('CLEAR_ERRORS', null, { root: true })

    await category
      .sendDeleteRequest(categoryId)
      .then(() => {
        commit(CATEGORY_DELETE)
        dispatch('category/getCategories', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
