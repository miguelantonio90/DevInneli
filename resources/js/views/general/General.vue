<template>
  <v-container
    fluid
  >
    <v-card>
      <v-tabs
        v-if="!isMobile"
        vertical
      >
        <v-tab
          v-for="item in tabsData.tabName"
          :key="item.name"
          style="align-self: flex-start"
        >
          <v-icon left>
            {{ item.icon }}
          </v-icon>
          <span style="text-transform: capitalize">{{ item.name }}</span>
        </v-tab>

        <v-tab-item
          v-for="item in tabsData.itemsTabs"
          :key="item.key"
        >
          <v-card flat>
            <v-card-text>
              <component :is="item.content" />
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs>
      <v-tabs
        v-else
        v-model="tab"
        background-color="transparent"
        icons-and-text
      >
        <v-tab
          v-for="item in tabsData.tabName"
          :key="item.name"
        >
          <v-icon>
            {{ item.icon }}
          </v-icon>
          <span style="text-transform: capitalize">{{ item.name }}</span>
        </v-tab>
      </v-tabs>

      <v-tabs-items v-model="tab">
        <v-tab-item
          v-for="item in tabsData.itemsTabs"
          :key="item.key"
        >
          <v-card flat>
            <v-card-text>
              <component :is="item.content" />
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </v-container>
</template>

<script>
import ShopList from '../shop/ListShop'
import ListShopAdmin from '../shop/ListShopAdmin'
import ExpenseCategory from '../expense_category/ListExpenseCategory'
import ExpenseCategoryAdmin from '../expense_category/ListAdmin'
import ExchangeRate from '../exchange_rate/ListExchangeRate'
import ListPayment from '../payment/ListPayment'
import ListPaymentAdmin from '../payment/ListPaymentAdmin'
import TypeOfOrder from '../type_order/ListTypeOfOrder'
import TypeOfOrderAdmin from '../type_order/ListAdmin'
import ListTax from '../tax/ListTax'
import ListTaxAdmin from '../tax/ListTaxAdmin'
import ListDiscount from '../discount/ListDiscount'
import ListAccess from '../access/ListAccess'
import ListAccessAdmin from '../access/ListAccessAdmin'
import { mapGetters } from 'vuex'
export default {
  name: 'General2',
  components: {
    ShopList,
    ListPayment,
    ExpenseCategory,
    ExchangeRate,
    TypeOfOrder,
    ListTax,
    ListDiscount,
    ListAccess,
    ListShopAdmin,
    ListAccessAdmin,
    ListPaymentAdmin,
    ExpenseCategoryAdmin,
    TypeOfOrderAdmin,
    ListTaxAdmin
  },
  data () {
    return {
      tab: null,
      isMobile: false,
      itemsTabs: [
        [
          { key: 'shop-list', content: 'shop-list' },
          { key: 'list-access', content: 'list-access' },
          // { key: 'list-payment', content: 'list-payment' },
          { key: 'expense-category', content: 'expense-category' },
          { key: 'exchange-rate', content: 'exchange-rate' },
          { key: 'type-of-order', content: 'type-of-order' },
          { key: 'tax', content: 'list-tax' },
          { key: 'discount', content: 'list-discount' }
        ],
        [
          { key: 'shop-list', content: 'list-shop-admin' },
          { key: 'list-access', content: 'list-access-admin' },
          // { key: 'list-payment', content: 'list-payment-admin' },
          { key: 'expense-category', content: 'expense-category-admin' },
          { key: 'exchange-rate', content: 'exchange-rate' },
          { key: 'type-of-order', content: 'type-of-order-admin' },
          { key: 'tax', content: 'list-tax-admin' },
          { key: 'discount', content: 'list-discount' }
        ]
      ],
      tabName: [
        { name: this.$vuetify.lang.t('$vuetify.menu.shop'), icon: 'mdi-shopping', access: 'manager_shop' },
        { name: this.$vuetify.lang.t('$vuetify.menu.access'), icon: 'mdi-key', access: 'manager_access' },
        // { name: this.$vuetify.lang.t('$vuetify.menu.pay'), icon: 'mdi-cash-multiple', access: 'manager_payment' },
        { name: this.$vuetify.lang.t('$vuetify.menu.expense_category'), icon: 'mdi-marker-check', access: 'manager_expense_category' },
        { name: this.$vuetify.lang.t('$vuetify.menu.exchange_rate'), icon: 'mdi-bank', access: 'manager_exchange_rate' },
        { name: this.$vuetify.lang.t('$vuetify.menu.type_of_order'), icon: 'mdi-food', access: 'manager_type_of_order' },
        { name: this.$vuetify.lang.t('$vuetify.menu.tax_list'), icon: 'mdi-wallet', access: 'manager_tax' },
        { name: this.$vuetify.lang.t('$vuetify.menu.discount'), icon: 'mdi-cash-minus', access: 'manager_discount' }
      ]
    }
  },
  computed: {
    ...mapGetters('auth', ['isAdminIn', 'access_permit']),
    tabsData () {
      const result = { tabName: [], itemsTabs: [] }
      this.tabName.forEach((v, i) => {
        const access = this.access_permit.filter(a => a.title.name === v.access)
        if (access.length > 0) {
          if (access[0].title.value === true) {
            result.tabName.push(v)
            result.itemsTabs.push(this.isAdminIn ? this.itemsTabs[1][i] : this.itemsTabs[0][i])
          }
        }
      })
      return result
    }
  },
  watch: {
    access_permit () {
      return this.access_permit
    }
  },
  mounted () {
    this.onResize()
    window.addEventListener('resize', this.onResize, { passive: true })
  },
  beforeDestroy () {
    if (typeof window !== 'undefined') {
      window.removeEventListener('resize', this.onResize, { passive: true })
    }
  },
  methods: {
    onResize () {
      this.isMobile = window.innerWidth < 600
    }
  }
}
</script>

<style scoped>
.v-slide-group__content {
  align-items: flex-start !important;
}
</style>
