import Vue from 'vue'
import Moment from 'moment'
import localStorage from '../config/localStorage'
import rules from '../config/rules'
import Vuetify from '../plugins/vuetify'
import Notifications from '../plugins/notifications'
import CircleStatistic from './widgets/statistic/CircleStatistic'
import LinearStatistic from './widgets/statistic/LinearStatistic'
import MiniStatistic from './widgets/statistic/MiniStatistic'
import VWidget from './VWidget'
import PlainTable from './widgets/list/PlainTable'
import AppDataTable from './core/app-data-table/AppDataTable'
import AvatarPicker from './core/AvatarPicker'
import AppLoading from './core/AppLoading'
import MaterialCard from './utils/MaterialCard'
import VuePincode from './core/VuePincode'
import CheckOutDialog from './sales/CheckOutDialog'
import ProductCard from './sales/ProductCard'
import ProductList from './sales/ProductList'
import ShoppingCartTotals from './sales/ShoppingCartTotals'
import ShoppingCart from './sales/ShoppingCart'
import Coupons from './sales/Coupons'
import AppColorPicker from './core/AppColorPicker'
import AppDateTimePicker from './core/AppDateTimePicker'
import AppUploadMultipleImage from './core/AppUploadMultipleImage'
import AppTour from './core/AppTour'

const components = [
  CircleStatistic,
  LinearStatistic,
  MiniStatistic,
  VWidget,
  PlainTable,
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
  AppUploadMultipleImage,
  AppTour
]

// Installation of the library as a plugin
export function install (Vue, opts = {}) {
  components.forEach(component => {
    Vue.component(component.name, component)
  })

  Moment.locale(localStorage.getLanguage() ||
    window.navigator.language.split('-')[0])

  Vue.prototype.$Toast = Notifications.Toast
  Vue.prototype.$Swal = Notifications.Swal
  Vue.prototype.$rules = rules
  Vue.prototype.$language = Vuetify.framework.lang
  Vue.prototype.$moment = Moment
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
