<template>
  <v-container
    fluid
  >
    <v-card>
      <v-tabs
        vertical
      >
        <v-tab
          v-for="item in tabName"
          :key="item.name"
          style="align-self: flex-start"
        >
          <v-icon left>
            {{ item.icon }}
          </v-icon>
          <span style="text-transform: capitalize">{{ item.name }}</span>
        </v-tab>

        <v-tab-item
          v-for="item in itemsTabs"
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
export default {
  name: 'General2',
  components: { ShopList, ListPayment, ExpenseCategory, ExchangeRate, TypeOfOrder, ListTax, ListDiscount, ListAccess },
  data () {
    return {
      tab: null,
      itemsTabs: [
        { key: 'shop-list', content: 'shop-list' },
        { key: 'list-access', content: 'list-access' },
        { key: 'list-payment', content: 'list-payment' },
        { key: 'expense-category', content: 'expense-category' },
        { key: 'exchange-rate', content: 'exchange-rate' },
        { key: 'type-of-order', content: 'type-of-order' },
        { key: 'tax', content: 'list-tax' },
        { key: 'discount', content: 'list-discount' }
      ]
    }
  },
  computed: {
    tabName () {
      return [
        { name: this.$vuetify.lang.t('$vuetify.menu.shop'), icon: 'mdi-shopping' },
        { name: this.$vuetify.lang.t('$vuetify.menu.access'), icon: 'mdi-key' },
        { name: this.$vuetify.lang.t('$vuetify.menu.pay'), icon: ' mdi-cash-multiple' },
        { name: this.$vuetify.lang.t('$vuetify.menu.expense_category'), icon: 'mdi-marker-check' },
        { name: this.$vuetify.lang.t('$vuetify.menu.exchange_rate'), icon: 'mdi-bank' },
        { name: this.$vuetify.lang.t('$vuetify.menu.type_of_order'), icon: 'mdi-food' },
        { name: this.$vuetify.lang.t('$vuetify.menu.tax_list'), icon: 'mdi-wallet' },
        { name: this.$vuetify.lang.t('$vuetify.menu.discount'), icon: 'mdi-cash-minus' }
      ]
    }
  }
}
</script>

<style scoped>
.v-slide-group__content {
  align-items: flex-start !important;
}
</style>
