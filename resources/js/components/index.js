import Vue from 'vue'
import Notifications from '../plugins/notifications'
import AppDataTable from '../components/core/AppDataTable'
import AvatarPicker from '../components/core/AvatarPicker'
import MaterialCard from '../components/utils/MaterialCard'

const components = [
  AppDataTable,
  AvatarPicker,
  MaterialCard
]

// Installation of the library as a plugin
export function install (Vue, opts = {}) {
  components.forEach(component => {
    Vue.component(component.name, component)
  })

  Vue.prototype.$Toast = Notifications.Toast
  Vue.prototype.$Swal = Notifications.Swal
}

if (typeof window !== 'undefined' && window.Vue) {
  Vue.use({ install })
}

// Library export as a plugin
export default { install: install }

// Export of the components individually
export {
  AppDataTable,
  AvatarPicker,
  MaterialCard
}
