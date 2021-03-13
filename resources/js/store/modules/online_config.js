import apiConfig from '../../api/online_config'

const FETCHING_CONFIGS = 'FETCHING_CONFIGS'
const FETCHING_CONFIGS_BY_SHOP = 'FETCHING_CONFIGS_BY_SHOP'
const SWITCH_CONFIG_NEW_MODAL = 'SWITCH_CONFIG_NEW_MODAL'
const SWITCH_CONFIG_EDIT_MODAL = 'SWITCH_CONFIG_EDIT_MODAL'
const SWITCH_CONFIG_SHOW_MODAL = 'SWITCH_CONFIG_SHOW_MODAL'
const CONFIG_CREATED = 'CONFIG_CREATED'
const CONFIG_EDIT = 'CONFIG_EDIT'
const CONFIG_UPDATED = 'CONFIG_UPDATED'
const CONFIG_DELETE = 'CONFIG_DELETE'
const CONFIG_TABLE_LOADING = 'CONFIG_TABLE_LOADING'
const FAILED_CONFIG = 'FAILED_CONFIG'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_CONFIG = 'SET_EDIT_CONFIG'
const SWITCH_TRANSFER_MODAL = 'SWITCH_TRANSFER_MODAL'
const CANCEL_MODAL = 'CANCEL_MODAL'

const state = {
  showNewModal: false,
  showTransfer: false,
  showEditModal: false,
  showShowModal: false,
  showRefoundModal: false,
  configs: [],
  avatar: '',
  loading: false,
  saved: false,
  managerConfig: false,
  newConfig: {
    template: '',
    shop: {},
    logo: {},
    credentials: {
      paypal: {
        client_id: '',
        paypal_secret: ''
      }
    },
    images: []
  },
  editConfig: {
    id: '',
    template: '',
    shop: {},
    logo: {},
    images: []
  },
  showImportModal: false,
  isConfigTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false,
  articleNumber: ''
}

const mutations = {
  [SWITCH_TRANSFER_MODAL] (state, showModal) {
    state.showTransfer = showModal
  },
  [SWITCH_CONFIG_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_CONFIG_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_CONFIG_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [CONFIG_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_CONFIGS] (state, configs) {
    state.configs = configs
  },
  [FETCHING_CONFIGS_BY_SHOP] (state, config) {
    state.managerConfig = config.id !== undefined
    if (config.id !== undefined) {
	  state.ediConfig = config
    }
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [CANCEL_MODAL] (state) {
    state.newConfig = {
      template: '',
      shop: {},
      logo: {},
      credentials: {
        paypal: {
          client_id: '',
          paypal_secret: ''
        }
      },
      images: []
    }
    state.saved = true
  },
  [CONFIG_CREATED] (state) {
    state.showNewModal = false
    state.showTransfer = false
    state.newConfig = {
	  template: '',
	  shop: {},
	  images: []
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_add', [
        this._vm.$language.t('$vuetify.articles.name')
	  ])
    })
  },
  [CONFIG_EDIT] (state, articleId) {
    state.editConfig = Object.assign(
	  {},
	  state.configs.filter(node => node.id === articleId).shift()
    )
  },
  [CONFIG_UPDATED] (state) {
    state.showEditModal = false
    state.showTransfer = false
    state.editConfig = {
	  id: '',
	  template: '',
	  shop: {},
	  images: []
    }
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_up', [
        this._vm.$language.t('$vuetify.articles.name')
	  ])
    })
  },
  [SET_EDIT_CONFIG] (state, profile) {
    state.editConfig.push(profile)
  },
  [CONFIG_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
	  icon: 'success',
	  title: this._vm.$language.t('$vuetify.messages.success_del', [
        this._vm.$language.t('$vuetify.articles.name')
	  ])
    })
  },
  [FAILED_CONFIG] (state, error) {
    state.isActionInProgress = false
    state.isConfigTableLoading = false
    state.isTableLoading = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
	  icon: 'error',
	  title: this._vm.$language.t('$vuetify.messages.failed_catch', [
        this._vm.$language.t('$vuetify.articles.name')
	  ])
    })
  }
}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_CONFIG_NEW_MODAL, showModal)
    if (!showModal) {
	  commit(CANCEL_MODAL)
    }
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_CONFIG_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_CONFIG_SHOW_MODAL, showModal)
  },
  openTransferModal ({ commit }, articleId) {
    commit(CONFIG_EDIT, articleId)
  },
  openEditModal ({ commit }, articleId) {
    commit(CONFIG_EDIT, articleId)
  },
  openShowModal ({ commit }, articleId) {
    commit(SWITCH_CONFIG_SHOW_MODAL, true)
    commit(CONFIG_EDIT, articleId)
  },
  async getConfigs ({
    commit,
    dispatch
  }) {
    commit(CONFIG_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiConfig
	  .fetchConfigs()
	  .then(({ data }) => {
        commit(FETCHING_CONFIGS, data.data)
        commit(CONFIG_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_CONFIG, error))
  },
  async getConfigByShop ({
    commit,
    dispatch
  }, shopId) {
    commit(CONFIG_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await apiConfig
	  .findConfigShop(shopId)
	  .then(({ data }) => {
        commit(FETCHING_CONFIGS_BY_SHOP, data.data)
        commit(CONFIG_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_CONFIG, error))
  },
  async createConfig ({
    commit,
    dispatch
  }, newConfig) {
    commit(ENV_DATA_PROCESS, true)

    await apiConfig
	  .sendCreateRequest(newConfig)
	  .then(data => {
        commit(CONFIG_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('article/getConfigs', null, { root: true })
        this.dispatch('auth/updateAccess', data)
	  })
	  .catch(error => commit(FAILED_CONFIG, error))
  },
  async updateConfig ({
    commit,
    dispatch
  }, articleE) {
    commit(ENV_DATA_PROCESS, true)
    const request = articleE || state.editConfig

    // const request = profile || state.editUser
    await apiConfig
	  .sendUpdateRequest(request)
	  .then(response => {
        commit(CONFIG_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('article/getConfigs', null, { root: true })
        this.dispatch('auth/updateAccess', response.access)
	  })
	  .catch(error => {
        commit(ENV_DATA_PROCESS, false)
        commit(FAILED_CONFIG, error)
	  })
  },
  async deleteConfig ({
    commit,
    dispatch
  }, articleId) {
    await apiConfig
	  .sendDeleteRequest(articleId)
	  .then(response => {
        commit(CONFIG_DELETE)
        this.dispatch('article/getConfigs')
        this.dispatch('auth/updateAccess', response.access)
	  })
	  .catch(error => commit(FAILED_CONFIG, error))
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
