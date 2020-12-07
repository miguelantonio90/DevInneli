import keys from '../../api/keys'

const FETCHING_KEY = 'FETCHING_KEY'
const SWITCH_KEY_NEW_MODAL = 'SWITCH_KEY_NEW_MODAL'
const SWITCH_KEY_EDIT_MODAL = 'SWITCH_KEY_EDIT_MODAL'
const SWITCH_KEY_SHOW_MODAL = 'SWITCH_KEY_SHOW_MODAL'
const KEY_CREATED = 'KEY_CREATED'
const KEY_EDIT = 'KEY_EDIT'
const KEY_UPDATED = 'KEY_UPDATED'
const KEY_DELETE = 'KEY_DELETE'
const KEY_TABLE_LOADING = 'KEY_TABLE_LOADING'
const FAILED_KEY = 'FAILED_KEY'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const LOAD_KEY_CONST = 'LOAD_KEY_CONST'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  keys: [],
  keysToSelect: [],
  avatar: '',
  loading: false,
  saved: false,
  newKey: {
    key: '',
    access_permit: [
      {
        title: {
          name: 'manager_article',
          value: false
        },
        actions: [
          {
            name: 'article_list',
            value: false
          },
          {
            name: 'article_add',
            value: false
          },
          {
            name: 'article_edit',
            value: false
          },
          {
            name: 'article_delete',
            value: false
          }
        ]

      },
      {
        title: {
          name: 'manager_vending',
          value: false
        },
        actions: [{
          name: 'vending_list',
          value: false
        },
        {
          name: 'vending_add',
          value: false
        }, {
          name: 'vending_edit',
          value: false
        }, {
          name: 'vending_delete',
          value: false
        }
        ]
      },
      {
        title: {
          name: 'manager_category',
          value: false
        },
        actions: [{
          name: 'category_list',
          value: false
        }, {
          name: 'category_add',
          value: false
        }, {
          name: 'category_edit',
          value: false
        }, {
          name: 'category_delete',
          value: false
        }
        ]
      },
      {
        title: {
          name: 'manager_mod',
          value: false
        },
        actions: [{
          name: 'mod_list',
          value: false
        }, {
          name: 'mod_add',
          value: false
        }, {
          name: 'mod_edit',
          value: false
        }, {
          name: 'mod_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_supplier',
          value: false
        },
        actions: [{
          name: 'supplier_list',
          value: false
        }, {
          name: 'supplier_add',
          value: false
        }, {
          name: 'supplier_edit',
          value: false
        }, {
          name: 'supplier_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_buy',
          value: false
        },
        actions: [{
          name: 'buy_list',
          value: false
        }, {
          name: 'buy_add',
          value: false
        }, {
          name: 'buy_edit',
          value: false
        }, {
          name: 'buy_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_sell',
          value: false
        },
        actions: [{
          name: 'sell_by_product',
          value: false
        }, {
          name: 'sell_by_category',
          value: false
        }, {
          name: 'sell_by_employer',
          value: false
        }, {
          name: 'sell_by_payments',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_employer',
          value: false
        },
        actions: [{
          name: 'employer_list',
          value: false
        }, {
          name: 'employer_add',
          value: false
        }, {
          name: 'employer_edit',
          value: false
        }, {
          name: 'employer_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_assistance',
          value: false
        },
        actions: [{
          name: 'assistance_list',
          value: false
        }, {
          name: 'assistance_add',
          value: false
        }, {
          name: 'assistance_edit',
          value: false
        }, {
          name: 'assistance_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_client',
          value: false
        },
        actions: [{
          name: 'client_list',
          value: false
        }, {
          name: 'client_add',
          value: false
        }, {
          name: 'client_edit',
          value: false
        }, {
          name: 'client_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_shop',
          value: false
        },
        actions: [{
          name: 'shop_list',
          value: false
        }, {
          name: 'shop_add',
          value: false
        }, {
          name: 'shop_edit',
          value: false
        }, {
          name: 'shop_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_access',
          value: false
        },
        actions: [{
          name: 'access_list',
          value: false
        }, {
          name: 'access_add',
          value: false
        }, {
          name: 'access_edit',
          value: false
        }, {
          name: 'access_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_payment',
          value: false
        },
        actions: [{
          name: 'payment_list',
          value: false
        }, {
          name: 'payment_add',
          value: false
        }, {
          name: 'payment_edit',
          value: false
        }, {
          name: 'payment_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_exchange_rate',
          value: false
        },
        actions: [{
          name: 'exchange_rate_list',
          value: false
        }, {
          name: 'exchange_rate_add',
          value: false
        }, {
          name: 'exchange_rate_edit',
          value: false
        }, {
          name: 'exchange_rate_delete',
          value: false
        }]
      },
      {
        title: {
          name: 'manager_type_of_order',
          value: false
        },
        actions: [{
          name: 'type_of_order_list',
          value: false
        }, {
          name: 'type_of_order_add',
          value: false
        }, {
          name: 'type_of_order_edit',
          value: false
        }, {
          name: 'type_of_order_delete',
          value: false
        }]
      }
    ]
  },
  editKey: {
    id: '',
    key: '',
    access_permit: []
  },
  isKeyLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [LOAD_KEY_CONST] (state) {
  },
  [SWITCH_KEY_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_KEY_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_KEY_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [KEY_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isKeyLoading = isLoading
  },
  [FETCHING_KEY] (state, keys) {
    state.keys = keys
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [KEY_CREATED] (state) {
    state.showNewModal = false
    state.newKey = {
      name: '',
      accessPin: false,
      accessEmail: false,
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [KEY_EDIT] (state, keyId) {
    state.editKey = Object.assign({}, state.keys
      .filter(node => node.id === keyId)
      .shift()
    )
    state.editKey.access_permit = JSON.parse(state.editKey.access_permit)
  },
  [KEY_UPDATED] (state) {
    state.showEditModal = false
    state.editKey = {
      id: '',
      name: '',
      accessPin: false,
      accessEmail: false,
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [KEY_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [FAILED_KEY] (state, error) {
    state.saved = false
    state.error = error
    state.isKeyLoading = false
    state.isActionInProgress = false
    state.isTableLoading = false
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(LOAD_KEY_CONST)
    commit(SWITCH_KEY_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(LOAD_KEY_CONST)
    commit(SWITCH_KEY_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_KEY_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, keyId) {
    commit(SWITCH_KEY_EDIT_MODAL, true)
    commit(KEY_EDIT, keyId)
  },
  openShowModal ({ commit }, keyId) {
    commit(SWITCH_KEY_SHOW_MODAL, true)
    commit(KEY_EDIT, keyId)
  },
  async getKeys ({ commit }) {
    commit(KEY_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await keys
      .fetchKeys()
      .then(({ data }) => {
        commit(FETCHING_KEY, data.data)
        commit(KEY_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch(error => commit(FAILED_KEY, error))
  },
  loadKeysPermitConst ({ commit }) {
    commit(LOAD_KEY_CONST)
  },
  async createKey ({ commit, dispatch }, newKey) {
    commit(ENV_DATA_PROCESS, true)

    await keys
      .sendCreateRequest(newKey)
      .then((data) => {
        commit(KEY_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('keys/getKeys', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch(error => commit(FAILED_KEY, error))
  },
  async updateKey ({ commit, dispatch }, editKey) {
    commit(ENV_DATA_PROCESS, true)

    await keys
      .sendUpdateRequest(editKey)
      .then((data) => {
        commit(KEY_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('keys/getKeys', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch(error => commit(FAILED_KEY, error))
  },
  async deleteKey ({ commit, dispatch }, keyId) {
    await keys
      .sendDeleteRequest(keyId)
      .then((data) => {
        commit(KEY_DELETE)
        this.dispatch('keys/getKeys')
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch(error => commit(FAILED_KEY, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
