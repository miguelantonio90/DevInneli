import colors from 'vuetify/es5/util/colors'
import localStorage from '../../config/localStorage'

const state = {
	mode: 'light',
	themeColor: localStorage.getTheme(),
	windowHeight: 0,
	windowWidth: 0
}

// getters
const getters = {
	getThemeColor: (state) => {
		return colors[state.themeColor].base
	},
	windowHeight: (state) => state.windowHeight,
	windowWidth: (state) => state.windowWidth
}

// actions
const actions = {}

// mutations
const mutations = {
	setThemeColor (state, payload) {
		state.themeColor = payload
	},
	setWindowHeight (state, { windowHeight }) {
		state.windowHeight = windowHeight
	},
	setWindowWidth (state, { windowWidth }) {
		state.windowWidth = windowWidth
	}
}

export default {
	namespaced: true,
	state,
	getters,
	actions,
	mutations
}
