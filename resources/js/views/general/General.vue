<template>
  <v-container
    fluid
  >
    <v-card>
      <v-tabs
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
    </v-card>
  </v-container>
</template>

<script>
import ShopList from '../shop/ListShop'
import ExpenseCategory from '../expense_category/List'
import ExchangeRate from '../exchange_rate/List'
import ListPayment from '../payment/ListPayment'
import TypeOfOrder from '../type_order/List'
import ListTax from '../tax/ListTax'
import ListDiscount from '../discount/ListDiscount'
import ListAccess from '../access/ListAccess'
import ListKeys from '../keys/ListKeys'
import { mapGetters } from 'vuex'
export default {
  name: 'General2',
  components: { ShopList, ListPayment, ExpenseCategory, ExchangeRate, TypeOfOrder, ListTax, ListDiscount, ListAccess, ListKeys },
  data () {
    return {
      tab: null,
      itemsTabs: [
        { key: 'shop-list', content: 'shop-list' },
        { key: 'list-keys', content: 'list-keys' },
        { key: 'list-access', content: 'list-access' },
        { key: 'list-payment', content: 'list-payment' },
        { key: 'expense-category', content: 'expense-category' },
        { key: 'exchange-rate', content: 'exchange-rate' },
        { key: 'type-of-order', content: 'type-of-order' },
        { key: 'tax', content: 'list-tax' },
        { key: 'discount', content: 'list-discount' }
      ],
      tabName: [
        { name: this.$vuetify.lang.t('$vuetify.menu.shop'), icon: 'mdi-shopping', access: 'manager_shop' },
        { name: this.$vuetify.lang.t('$vuetify.menu.keys'), icon: 'mdi-key', access: 'manager_key' },
        { name: this.$vuetify.lang.t('$vuetify.menu.access'), icon: 'mdi-key', access: 'manager_access' },
        { name: this.$vuetify.lang.t('$vuetify.menu.pay'), icon: ' mdi-cash-multiple', access: 'manager_payment' },
        { name: this.$vuetify.lang.t('$vuetify.menu.expense_category'), icon: 'mdi-marker-check', access: 'manager_expense_category' },
        { name: this.$vuetify.lang.t('$vuetify.menu.exchange_rate'), icon: 'mdi-bank', access: 'manager_exchange_rate' },
        { name: this.$vuetify.lang.t('$vuetify.menu.type_of_order'), icon: 'mdi-food', access: 'manager_type_of_order' },
        { name: this.$vuetify.lang.t('$vuetify.menu.tax_list'), icon: 'mdi-wallet', access: 'manager_tax' },
        { name: this.$vuetify.lang.t('$vuetify.menu.discount'), icon: 'mdi-cash-minus', access: 'manager_discount' }
      ]
    }
  },
  computed: {
    ...mapGetters('auth', ['access_permit']),
    tabsData () {
      const result = { tabName: [], itemsTabs: [] }
      this.tabName.forEach((v, i) => {
        const access = this.access_permit.filter(a => a.title.name === v.access)
        if (access.length > 0) {
          if (access[0].title.value === true) {
            result.tabName.push(v)
            result.itemsTabs.push(this.itemsTabs[i])
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
  }
}
</script>

<style scoped>
.v-slide-group__content {
  align-items: flex-start !important;
}
</style>
