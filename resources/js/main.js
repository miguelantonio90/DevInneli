import Vue from 'vue'
import router from './router/'
import store from './store/'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import './plugins/vuetify-mask.js'
import * as AppComponents from './components'
import '@mdi/font/css/materialdesignicons.css'
import './registerServiceWorker'
import VueTelInputVuetify from 'vue-tel-input-vuetify'
// Import the styles too, typically in App.vue or main.js
import 'vue-swatches/dist/vue-swatches.css'

Vue.config.productionTip = false

Vue.use(VueTelInputVuetify, {
  vuetify
})
Vue.use({ install: AppComponents.install })

new Vue({
  router,
  store,
  vuetify,
  render: (h) => h(App)
}).$mount('#app')
