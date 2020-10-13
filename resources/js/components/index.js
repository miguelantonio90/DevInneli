import Vue from 'vue'
import rules from '../config/rules'
import Vuetify from '../plugins/vuetify'
import Notifications from '../plugins/notifications'
import AppDataTable from './core/app-data-table/AppDataTable'
import AvatarPicker from '../components/core/AvatarPicker'
import AppLoading from '../components/core/AppLoading'
import MaterialCard from '../components/utils/MaterialCard'
import VuePincode from '../components/core/VuePincode'
import CheckOutDialog from '../components/sales/CheckOutDialog'
import ProductCard from '../components/sales/ProductCard'
import ProductList from '../components/sales/ProductList'
import ShoppingCartTotals from '../components/sales/ShoppingCartTotals'
import ShoppingCart from '../components/sales/ShoppingCart'
import Coupons from '../components/sales/Coupons'
import AppColorPicker from '../components/core/AppColorPicker'
import AppDateTimePicker from '../components/core/AppDateTimePicker'
import AppMultiSelect from '../components/core/AppMultiSelect'

const components = [
  AppDataTable,
  AvatarPicker,
  MaterialCard,
  AppLoading,
  VuePincode,
  CheckOutDialog,
  ProductCard,
  ProductList,
  ShoppingCartTotals,
  ShoppingCart,
  Coupons,
  AppColorPicker,
  AppDateTimePicker,
  AppMultiSelect
]

// Installation of the library as a plugin
export function install (Vue, opts = {}) {
  components.forEach(component => {
    Vue.component(component.name, component)
  })

  Vue.prototype.$Toast = Notifications.Toast
  Vue.prototype.$Swal = Notifications.Swal
  Vue.prototype.$rules = rules
  Vue.prototype.$language = Vuetify.framework.lang
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
