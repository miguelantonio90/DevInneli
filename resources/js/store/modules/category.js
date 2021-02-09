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
  isCategoryLoading: false,
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
    state.isCategoryLoading = isLoading
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
      name: '',
      color: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.category')]
      )
    })
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
      name: '',
      color: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.category')]
      )
    })
  },
  [SET_EDIT_CATEGORY] (state, profile) {
    state.editCategory.push(profile)
  },
  [CATEGORY_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.category')]
      )
    })
  },
  [SET_CATEGORY_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_CATEGORY] (state, error) {
    state.saved = false
    state.error = error
    state.isActionInProgress = false
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.category')]
      )
    })
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
        this.dispatch('auth/updateAccess', data)
        return data
      }).catch((error) => commit(FAILED_CATEGORY, error))
  },
  async createCategory ({ commit, dispatch }, newCategory) {
    commit(ENV_DATA_PROCESS, true)

    await category
      .sendCreateRequest(newCategory)
      .then((data) => {
        commit(CATEGORY_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('category/getCategories', null, { root: true })
        this.dispatch('auth/updateAccess', data)
      })
      .catch((error) => commit(FAILED_CATEGORY, error))
  },
  async getCategoriesShop ({ commit }, data) {
    commit(CATEGORY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await category
      .getCategoriesShop(data)
      .then(({ data }) => {
        commit(FETCHING_CATEGORIES, data.data)
        commit(CATEGORY_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        return data
      }).catch((error) => commit(FAILED_CATEGORY, error))
  },
  async updateCategory ({ commit, dispatch }, editCategory) {
    commit(ENV_DATA_PROCESS, true)
    await category
      .sendUpdateRequest(editCategory)
      .then((data) => {
        commit(CATEGORY_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('category/getCategories', null, { root: true })
        this.dispatch('auth/updateAccess', data)
      })
      .catch((error) => commit(FAILED_CATEGORY, error))
  },
  async deleteCategory ({ commit, dispatch }, categoryId) {
    await category
      .sendDeleteRequest(categoryId)
      .then((data) => {
        commit(CATEGORY_DELETE)
        dispatch('category/getCategories', null, { root: true })
        this.dispatch('auth/updateAccess', data)
      })
      .catch((error) => commit(FAILED_CATEGORY, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
