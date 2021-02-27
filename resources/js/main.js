import Vue from 'vue'
import router from './router/'
import store from './store/'
import App from './App.vue'
import VueTour from 'vue-tour'
import IdleVue from 'idle-vue'
import vuetify from './plugins/vuetify'
import './plugins/vuetify-mask.js'
import * as AppComponents from './components'
import './registerServiceWorker'
import VueTelInputVuetify from 'vue-tel-input-vuetify'
import HighchartsVue from 'highcharts-vue'
// Import the styles too, typically in App.vue or main.js
import '@mdi/font/css/materialdesignicons.css'
import 'vue-swatches/dist/vue-swatches.css'
import 'vue-tour/dist/vue-tour.css'

Vue.use(HighchartsVue)
Vue.use(VueTour)

const eventsHub = new Vue()

Vue.use(IdleVue, {
  eventEmitter: eventsHub,
  store,
  idleTime: 600000, // 10 min,
  startAtIdle: false
})

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
