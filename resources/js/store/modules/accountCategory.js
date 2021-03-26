import account from '../../api/accountCategory'

const FETCHING_ACCOUNT_CATEGORIES_CATEGORY = 'FETCHING_ACCOUNT_CATEGORIES_CATEGORY'
const FETCHING_ACCOUNT_TREE_CATEGORY = 'FETCHING_ACCOUNT_TREE_CATEGORY'
const SWITCH_ACCOUNT_CATEGORY_NEW_MODAL = 'SWITCH_ACCOUNT_CATEGORY_NEW_MODAL'
const SWITCH_ACCOUNT_CATEGORY_EDIT_MODAL = 'SWITCH_ACCOUNT_CATEGORY_EDIT_MODAL'
const SWITCH_ACCOUNT_CATEGORY_SHOW_MODAL = 'SWITCH_ACCOUNT_CATEGORY_SHOW_MODAL'
const ACCOUNT_CATEGORY_CREATED = 'ACCOUNT_CATEGORY_CREATED'
const ACCOUNT_CATEGORY_EDIT = 'ACCOUNT_CATEGORY_EDIT'
const ACCOUNT_CATEGORY_UPDATED = 'ACCOUNT_CATEGORY_UPDATED'
const ACCOUNT_CATEGORY_DELETE = 'ACCOUNT_CATEGORY_DELETE'
const ACCOUNT_CATEGORY_TABLE_LOADING = 'ACCOUNT_CATEGORY_TABLE_LOADING'
const FAILED_ACCOUNT_CATEGORY = 'FAILED_ACCOUNT_CATEGORY'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_ACCOUNT_CATEGORY = 'SET_EDIT_ACCOUNT_CATEGORY'
const SET_ACCOUNT_CATEGORY_AVATAR = 'SET_ACCOUNT_CATEGORY_AVATAR'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  categories: [],
  tree: [],
  avatar: '',
  loading: false,
  saved: false,
  newCategory: {
    name: '',
    nature: '',
    parent_id: '',
    color: '#1FBC9C',
    code: '',
    description: ''
  },
  editCategory: {
    id: '',
    name: '',
    code: '',
    parent_id: '',
    color: '#1FBC9C',
    description: ''
  },
  isCategoryLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_ACCOUNT_CATEGORY_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_ACCOUNT_CATEGORY_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_ACCOUNT_CATEGORY_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [ACCOUNT_CATEGORY_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isCategoryLoading = isLoading
  },
  [FETCHING_ACCOUNT_CATEGORIES_CATEGORY] (state, categories) {
    state.categories = categories
    console.log(categories)
  },
  [FETCHING_ACCOUNT_TREE_CATEGORY] (state, categories) {
    state.tree = categories
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [ACCOUNT_CATEGORY_CREATED] (state) {
    state.showNewModal = false
    state.newCategory = {
	  name: '',
      parent_id: '',
      code: '',
	  color: '#1FBC9C',
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
  [ACCOUNT_CATEGORY_EDIT] (state, accountId) {
    state.editCategory = Object.assign(
	  {},
	  state.categories.filter(node => node.id === accountId).shift()
    )
  },
  [ACCOUNT_CATEGORY_UPDATED] (state) {
    state.showEditModal = false
    state.editCategory = {
	  id: '',
	  name: '',
      code: '',
	  color: '',
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
  [SET_EDIT_ACCOUNT_CATEGORY] (state, profile) {
    state.editCategory.push(profile)
  },
  [CANCEL_MODAL] (state) {
    state.newCategory = {
	  name: '',
      parent_id: '',
	  color: '',
      description: ''
    }
    state.saved = false
  },
  [ACCOUNT_CATEGORY_DELETE] (state, error) {
    state.saved = true
    state.error = error
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
        this._vm.$language.t('$vuetify.menu.accounting')
	  ])
    })
  },
  [SET_ACCOUNT_CATEGORY_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_ACCOUNT_CATEGORY] (state, error) {
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
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_ACCOUNT_CATEGORY_NEW_MODAL, showModal)
    if (!showModal) {
	  commit(CANCEL_MODAL)
    }
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_ACCOUNT_CATEGORY_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_ACCOUNT_CATEGORY_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, accountId) {
    commit(SWITCH_ACCOUNT_CATEGORY_EDIT_MODAL, true)
    commit(ACCOUNT_CATEGORY_EDIT, accountId)
  },
  openShowModal ({ commit }, accountId) {
    commit(SWITCH_ACCOUNT_CATEGORY_SHOW_MODAL, true)
    commit(ACCOUNT_CATEGORY_EDIT, accountId)
  },
  async getCategories ({ commit }) {
    commit(ACCOUNT_CATEGORY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await account
	  .fetchCategories()
	  .then(({ data }) => {
        commit(FETCHING_ACCOUNT_CATEGORIES_CATEGORY, data.data)
        commit(ACCOUNT_CATEGORY_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
        return data
	  })
	  .catch(error => commit(FAILED_ACCOUNT_CATEGORY, error))
  },
  async getTree ({ commit }) {
    commit(ACCOUNT_CATEGORY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await account
	  .fetchCategoriesTree()
	  .then(({ data }) => {
        commit(FETCHING_ACCOUNT_TREE_CATEGORY, data.data)
        commit(ACCOUNT_CATEGORY_TABLE_LOADING, false)
        this.dispatch('accountCategory/getCategories', data)
        this.dispatch('auth/updateAccess', data)
        return data
	  })
	  .catch(error => commit(FAILED_ACCOUNT_CATEGORY, error))
  },
  async createCategory ({
    commit,
    dispatch
  }, newCategory) {
    commit(ENV_DATA_PROCESS, true)

    await account
	  .sendCreateRequest(newCategory)
	  .then(data => {
        commit(ACCOUNT_CATEGORY_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('accountCategory/getTree', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_ACCOUNT_CATEGORY, error))
  },
  async updateCategory ({
    commit,
    dispatch
  }, editCategory) {
    commit(ENV_DATA_PROCESS, true)
    await account
	  .sendUpdateRequest(editCategory)
	  .then(data => {
        commit(ACCOUNT_CATEGORY_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('accountCategory/getTree', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_ACCOUNT_CATEGORY, error))
  },
  async deleteCategory ({
    commit,
    dispatch
  }, accountId) {
    await account
	  .sendDeleteRequest(accountId)
	  .then(data => {
        commit(ACCOUNT_CATEGORY_DELETE)
        dispatch('accountCategory/getTree', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_ACCOUNT_CATEGORY, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
