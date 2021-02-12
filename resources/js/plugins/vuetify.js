import Vue from 'vue'

import Vuetify from 'vuetify'
// locale
import es from '../locale/es'
import en from '../locale/en'
import localStorage from '../config/localStorage'

Vue.use(Vuetify)

export default new Vuetify({
	lang: {
		locales: {
			es,
			en
		},
		current:
      localStorage.getLanguage() ||
      window.navigator.language.split('-')[0]
	},
	theme: {
		options: {
			customProperties: true
		},
		themes: {
			/* light: {
        primary: '#ee44aa',
        secondary: '#424242',
        accent: '#82B1FF',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107'
      } */
		}
	}
})
