import boxes from '../../api/boxes'

const FETCHING_BOXES = 'FETCHING_BOXES'
const SWITCH_BOX_NEW_MODAL = 'SWITCH_BOX_NEW_MODAL'
const SWITCH_BOX_EDIT_MODAL = 'SWITCH_BOX_EDIT_MODAL'
const SWITCH_BOX_SHOW_MODAL = 'SWITCH_BOX_SHOW_MODAL'
const BOX_CREATED = 'BOX_CREATED'
const BOX_EDIT = 'BOX_EDIT'
const EDIT_OPEN_CLOSE = 'EDIT_OPEN_CLOSE'
const BOX_OPEN_CLOSE = 'BOX_OPEN_CLOSE'
const BOX_UPDATED = 'BOX_UPDATED'
const BOX_DELETE = 'BOX_DELETE'
const BOX_TABLE_LOADING = 'BOX_TABLE_LOADING'
const FAILED_BOX = 'FAILED_BOX'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const SET_EDIT_BOX = 'SET_EDIT_BOX'
const SWITCH_OPEN_CLOSE_MODAL = 'SWITCH_OPEN_CLOSE_MODAL'

const state = {
  showNewModal: false,
  opencloseBox: false,
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
  openClose: {
    id: '',
    box: {},
    sales: [],
    open_money: 0.00,
    close_money: 0.00,
    open_to: {},
    closeBy: {}
  },
  showImportModal: false,
  isBoxTableLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [SWITCH_OPEN_CLOSE_MODAL] (state, showModal) {
    state.opencloseBox = showModal
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
    state.boxes = boxes
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [BOX_CREATED] (state) {
    state.showNewModal = false
    state.opencloseBox = false
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
  [BOX_EDIT] (state, boxId) {
    state.editBox = Object.assign({}, state.boxes
      .filter(node => node.id === boxId)
      .shift()
    )
  },
  [EDIT_OPEN_CLOSE] (state, data) {
    // state.openClose.id = data.id
    // state.openClose.open_money = data.open_money
    // state.openClose.openTo = data.open_to
    // state.openClose.sales = data.sales
    state.openClose = data
  },
  [BOX_OPEN_CLOSE] (state, boxId) {
    state.openClose.box = Object.assign({}, state.boxes
      .filter(node => node.id === boxId)
      .shift()
    )
  },
  [BOX_UPDATED] (state) {
    state.showEditModal = false
    state.opencloseBox = false
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
  toogleOpenCloseModal ({ commit }, showModal) {
    commit(SWITCH_OPEN_CLOSE_MODAL, showModal)
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
  openEditModal ({ commit }, boxId) {
    commit(BOX_EDIT, boxId)
    commit(SWITCH_BOX_EDIT_MODAL, true)
  },
  async openCloseModal ({ commit, state }, boxId) {
    commit(BOX_OPEN_CLOSE, boxId)
    if (state.openClose.box.state === 'open') {
      await boxes
        .fetchOpenClose(boxId)
        .then(({ data }) => {
          commit(EDIT_OPEN_CLOSE, data.data)
          commit(BOX_TABLE_LOADING, false)
          this.dispatch('auth/updateAccess', data)
        }).catch((error) => commit(FAILED_BOX, error))
    }
    commit(SWITCH_OPEN_CLOSE_MODAL, true)
  },
  async getBoxes ({ commit, dispatch }) {
    commit(BOX_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await boxes
      .fetchBoxes()
      .then(({ data }) => {
        commit(FETCHING_BOXES, data.data)
        commit(BOX_TABLE_LOADING, false)
        this.dispatch('auth/updateAccess', data)
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
        this.dispatch('auth/updateAccess', data)
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
  async openCloseBox ({ commit, dispatch }, boxesE) {
    commit(ENV_DATA_PROCESS, true)
    const request = boxesE || state.editBox

    // const request = profile || state.editUser
    await boxes
      .sendOpenCloseBox(request)
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
