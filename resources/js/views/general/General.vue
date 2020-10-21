<template>
  <v-container>
    <v-card>
      <v-tabs
        v-model="tab"
        background-color="primary"
        dark
        horizontal
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
import ListPayment from '../payment/ListPayment'
import ListSupplier from "../supplier/ListSupplier";
export default {
  name: 'General',
  components: { ShopList, ListPayment, ListSupplier},
  data () {
    return {
      tab: null,
      itemsTabs: [
        { key: 'shop-list', content: 'shop-list' },
        { key: 'list-payment', content: 'list-payment' },
        { key: 'list-supplier', content: 'list-supplier' },
      ]
    }
  },
  computed: {
    tabName () {
      return [
        { name: this.$vuetify.lang.t('$vuetify.menu.shop') },
        { name: this.$vuetify.lang.t('$vuetify.menu.pay') },
        { name: this.$vuetify.lang.t('$vuetify.menu.supplier') },
      ]
    }
  }
}
</script>

<style scoped>

</style>
