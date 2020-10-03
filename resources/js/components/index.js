import Vue from 'vue'
import Notifications from '../plugins/notifications'
import AppDataTable from '../components/core/AppDataTable'
import AvatarPicker from '../components/core/AvatarPicker'
import AppLoading from '../components/core/AppLoading'
import MaterialCard from '../components/utils/MaterialCard'
import VuePincode from '../components/core/VuePincode'
import CheckOutDialog from '../components/shop/CheckOutDialog'
import ProductCard from '../components/shop/ProductCard'
import ProductList from '../components/shop/ProductList'
import ShoppingCartTotals from '../components/shop/ShoppingCartTotals'
import ShoppingCart from '../components/shop/ShoppingCart'
import Coupons from '../components/shop/Coupons'

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
  Coupons
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
