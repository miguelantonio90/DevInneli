import boxes from '../../api/boxes'

const FETCHING_BOXES = 'FETCHING_BOXES'
const SWITCH_BOX_NEW_MODAL = 'SWITCH_BOX_NEW_MODAL'
const SWITCH_BOX_EDIT_MODAL = 'SWITCH_BOX_EDIT_MODAL'
const SWITCH_BOX_SHOW_MODAL = 'SWITCH_BOX_SHOW_MODAL'
const BOX_CREATED = 'BOX_CREATED'
const BOX_EDIT = 'BOX_EDIT'
const BOX_OPEN = 'BOX_OPEN'
const BOX_CLOSE = 'BOX_CLOSE'
const BOX_REFOUND = 'BOX_REFOUND'
const BOX_UPDATED = 'BOX_UPDATED'
const BOX_DELETE = 'BOX_DELETE'
const BOX_TABLE_LOADING = 'BOX_TABLE_LOADING'
const FAILED_BOX = 'FAILED_BOX'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_BOX = 'SET_EDIT_BOX'
const SWITCH_TRANSFER_MODAL = 'SWITCH_TRANSFER_MODAL'

const state = {
  showNewModal: false,
  showTransfer: false,
  showEditModal: false,
  showShowModal: false,
  showRefoundModal: false,
  boxes: [],
  avatar: '',
  loading: false,
  saved: false,
  newBox: {
    name: '',
    price: 0.00,
    unit: 'unit',
    cost: 0.00,
    ref: '10001',
    barCode: '',
    variants: [],
    tax: [],
    variantsValues: [],
    composite: false,
    track_inventory: false,
    category: [],
    color: '',
    shops: [],
    composites: [],
    images: []
  },
  editBox: {
    id: '',
    name: '',
    shop: {}
  },
  box: {
    id: '',
    openMoney: 0.00,
    closeMoney: 0.00
  },
  showImportModal: false,
  isBoxTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_TRANSFER_MODAL] (state, showModal) {
    state.showTransfer = showModal
  },
  [SWITCH_BOX_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_BOX_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_BOX_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [BOX_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
  },
  [FETCHING_BOXES] (state, boxes) {
    console.log(boxes)
    state.boxes = boxes
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [BOX_CREATED] (state) {
    state.showNewModal = false
    state.showTransfer = false
    state.newBox = {
      name: '',
      shop: {}
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.box')]
      )
    })
  },
  [BOX_EDIT] (state, articleId) {
    state.editBox = Object.assign({}, state.boxes
      .filter(node => node.id === articleId)
      .shift()
    )
  },
  [BOX_OPEN] (state, articleId) {
    state.editBox = Object.assign({}, state.boxes
      .filter(node => node.id === articleId)
      .shift()
    )
  },
  [BOX_CLOSE] (state, articleId) {
    state.editBox = Object.assign({}, state.boxes
      .filter(node => node.id === articleId)
      .shift()
    )
  },
  [BOX_UPDATED] (state) {
    state.showEditModal = false
    state.showTransfer = false
    state.editBox = {
      id: '',
      name: '',
      shop: {}
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.box')]
      )
    })
  },
  [SET_EDIT_BOX] (state, profile) {
    state.editBox.push(profile)
  },
  [BOX_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.box')]
      )
    })
  },
  [FAILED_BOX] (state, error) {
    state.isActionInProgress = false
    state.isBoxTableLoading = false
    state.isTableLoading = false
    state.saved = false
    state.error = error
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.box')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleTransferModal ({ commit }, showModal) {
    commit(SWITCH_TRANSFER_MODAL, showModal)
  },
  toogleNewModal ({ commit }, showModal) {
    commit(SWITCH_BOX_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(SWITCH_BOX_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_BOX_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, articleId) {
    commit(BOX_EDIT, articleId)
    commit(SWITCH_BOX_EDIT_MODAL, true)
  },
  openBoxModal ({ commit }, articleId) {
    commit(BOX_EDIT, articleId)
    commit(SWITCH_BOX_EDIT_MODAL, true)
  },
  closeBoxModal ({ commit }, articleId) {
    commit(BOX_EDIT, articleId)
    commit(SWITCH_BOX_EDIT_MODAL, true)
  },
  openShowModal ({ commit }, articleId) {
    commit(SWITCH_BOX_SHOW_MODAL, true)
    commit(BOX_EDIT, articleId)
  },
  openRefoundModal ({ commit }, { sale, article }) {
    commit(BOX_REFOUND, { sale, article })
  },
  async getBoxes ({ commit, dispatch }) {
    commit(BOX_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await boxes
      .fetchBoxes()
      .then(({ data }) => {
        console.log(data.data)
        commit(FETCHING_BOXES, data.data)
        commit(BOX_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data.access)
      }).catch((error) => commit(FAILED_BOX, error))
  },
  async createBox ({ commit, dispatch }, newBox) {
    commit(ENV_DATA_PROCESS, true)

    await boxes
      .sendCreateRequest(newBox)
      .then((data) => {
        commit(BOX_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('boxes/getBoxes', null, { root: true })
        this.dispatch('auth/updateAccess', data.access)
      })
      .catch((error) => commit(FAILED_BOX, error))
  },
  async updateBox ({ commit, dispatch }, boxesE) {
    commit(ENV_DATA_PROCESS, true)
    const request = boxesE || state.editBox

    // const request = profile || state.editUser
    await boxes
      .sendUpdateRequest(request)
      .then((response) => {
        commit(BOX_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('boxes/getBoxes', null, { root: true })
        this.dispatch('auth/updateAccess', response.access)
      })
      .catch((error) => {
        commit(ENV_DATA_PROCESS, false)
        commit(FAILED_BOX, error)
      })
  },
  async deleteBox ({ commit, dispatch }, boxeId) {
    await boxes
      .sendDeleteRequest(boxeId)
      .then((response) => {
        commit(BOX_DELETE)
        this.dispatch('boxes/getBoxes')
        this.dispatch('auth/updateAccess', response.access)
      })
      .catch((error) => commit(FAILED_BOX, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
