import Vue from 'vue'
import router from './router/'
import store from './store/'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import * as AppComponents from './components'
import '@mdi/font/css/materialdesignicons.css'
import './registerServiceWorker'
import VueTelInputVuetify from 'vue-tel-input-vuetify'

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
