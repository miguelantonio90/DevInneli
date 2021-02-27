import colors from 'vuetify/es5/util/colors'

const state = {
  mode: 'light'
}

// getters
const getters = {
  getThemeColor: state => {
    return colors[state.themeColor].base
  }
}

const actions = {}

// mutations
const mutations = {
  setThemeColor (state, payload) {
    state.themeColor = payload
  }
}

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
}
