<template>
  <v-container>
    <v-card>
      <v-tabs
        v-model="tab"
        fixed-tabs
        color="deep-purple accent-4"
        show-arrows
      >
        <v-tab
          v-for="item in tabName"
          :key="item.name"
        >
          {{ item.name }}
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
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
      </v-tabs-items>
    </v-card>
  </v-container>
</template>

<script>
import ShopList from '../shop/ListShop'
import ExpenseCategory from '../expense_category/List'
import ExchangeRate from '../exchange_rate/List'
import ListPayment from '../payment/ListPayment'

export default {
  name: 'General',
  components: { ShopList, ListPayment, ExpenseCategory, ExchangeRate },
  data () {
    return {
      tab: null,
      itemsTabs: [
        { key: 'shop-list', content: 'shop-list' },
        { key: 'list-payment', content: 'list-payment' },
        { key: 'expense-category', content: 'expense-category' },
        { key: 'exchange-rate', content: 'exchange-rate' }
      ]
    }
  },
  computed: {
    tabName () {
      return [
        { name: this.$vuetify.lang.t('$vuetify.menu.shop') },
        { name: this.$vuetify.lang.t('$vuetify.menu.pay') },
        { name: this.$vuetify.lang.t('$vuetify.menu.expense_category') },
        { name: this.$vuetify.lang.t('$vuetify.menu.exchange_rate') }
      ]
    }
  }
}
</script>

<style scoped>

</style>
