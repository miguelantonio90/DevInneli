import article from '../../api/article'

const FETCHING_ARTICLES = 'FETCHING_ARTICLES'
const SWITCH_ARTICLE_NEW_MODAL = 'SWITCH_ARTICLE_NEW_MODAL'
const SWITCH_ARTICLE_EDIT_MODAL = 'SWITCH_ARTICLE_EDIT_MODAL'
const SWITCH_ARTICLE_SHOW_MODAL = 'SWITCH_ARTICLE_SHOW_MODAL'
const ARTICLE_CREATED = 'ARTICLE_CREATED'
const ARTICLE_EDIT = 'ARTICLE_EDIT'
const ARTICLE_UPDATED = 'ARTICLE_UPDATED'
const ARTICLE_DELETE = 'ARTICLE_DELETE'
const ARTICLE_TABLE_LOADING = 'ARTICLE_TABLE_LOADING'
const FAILED_ARTICLE = 'FAILED_ARTICLE'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_ARTICLE = 'SET_EDIT_ARTICLE'
const SET_ARTICLE_AVATAR = 'SET_ARTICLE_AVATAR'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  articles: [],
  avatar: '',
  loading: false,
  saved: false,
  newArticle: {
    name: '',
    price: '',
    unit: '',
    cost: '',
    ref: '',
    barCode: '',
    composite: false,
    track_inventory: false,
    inventory: true,
    itbis: false,
    lay: true,
    category: [],
    shops: []
  },
  editArticle: {
    id: '',
    name: '',
    price: '',
    unit: '',
    ref: '',
    cost: '',
    barCode: '',
    composite: false,
    inventory: true,
    itbis: true,
    lay: true,
    category: [],
    shops: []
  },
  isArticleTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_ARTICLE_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_ARTICLE_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_ARTICLE_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [ARTICLE_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_ARTICLES] (state, articles) {
    state.articles = articles
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [ARTICLE_CREATED] (state) {
    state.showNewModal = false
    state.newArticle = {
      name: '',
      price: '',
      unit: '',
      ref: '',
      cost: '',
      barCode: '',
      composite: false,
      inventory: true,
      itbis: true,
      lay: true,
      shops: []
    }
    state.saved = true
  },
  [ARTICLE_EDIT] (state, articleId) {
    state.editArticle = state.articles.filter((node) => node.id === articleId)[0]
  },
  [ARTICLE_UPDATED] (state) {
    state.showEditModal = false
    state.editArticle = {
      id: '',
      name: '',
      price: '',
      unit: '',
      ref: '',
      cost: '',
      barCode: '',
      composite: false,
      inventory: true,
      itbis: true,
      lay: true,
      category: [],
      shops: []
    }
    state.saved = true
  },
  [SET_EDIT_ARTICLE] (state, profile) {
    state.editArticle.push(profile)
  },
  [ARTICLE_DELETE] (state) {
    state.saved = true
  },
  [SET_ARTICLE_AVATAR] (state, avatar) {
    state.avatar = avatar
    state.saved = true
  },
  [FAILED_ARTICLE] (state) {
    state.saved = false
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_ARTICLE_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_ARTICLE_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_ARTICLE_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, articleId) {
    commit(SWITCH_ARTICLE_EDIT_MODAL, true)
    commit(ARTICLE_EDIT, articleId)
  },
  openShowModal ({ commit }, articleId) {
    commit(SWITCH_ARTICLE_SHOW_MODAL, true)
    commit(ARTICLE_EDIT, articleId)
  },
  async getArticles ({ commit }) {
    commit(ARTICLE_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await article
      .fetchArticles()
      .then(({ data }) => {
        commit(FETCHING_ARTICLES, data.data)
        commit(ARTICLE_TABLE_LOADING, false)
      }).catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async createArticle ({ commit, dispatch }, newArticle) {
    commit(ENV_DATA_PROCESS, true)
    commit('CLEAR_ERRORS', null, { root: true })

    await article
      .sendCreateRequest(newArticle)
      .then(() => {
        commit(ARTICLE_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('article/getArticles', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async updateArticle ({ commit, dispatch }, profile) {
    commit('CLEAR_ERRORS', null, { root: true })
    const request = profile || state.editArticle

    await article
      .sendUpdateRequest(request)
      .then(() => {
        commit(ARTICLE_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('article/getArticles', null, { root: true })
      })
      .catch((error) => commit('SET_ERRORS', error, { root: true }))
  },
  async deleteArticle ({ commit, dispatch }, articleId) {
    commit('CLEAR_ERRORS', null, { root: true })

    await article
      .sendDeleteRequest(articleId)
      .then(() => {
        commit(ARTICLE_DELETE)
        dispatch('article/getArticles', null, { root: true })
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
