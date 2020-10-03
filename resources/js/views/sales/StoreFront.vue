<template>
  <main>
    <v-container fluid>
      <coupons />

      <v-layout
        row
        wrap
      >
        <v-flex
          xs12
          sm8
          md9
          lg10
          order-xs2
          order-sm1
          style="flex:1"
        >
          <product-list />
        </v-flex>
        <v-flex
          xs12
          sm4
          md3
          lg2
          order-xs1
          order-sm2
          style="flex:1;min-width:360px"
        >
          <s-shopping-cart />
        </v-flex>
      </v-layout>
    </v-container>
  </main>
</template>

<script>
import { mapState, mapActions } from 'vuex'
export default {
  name: 'StoreFront',
  computed: {
    ...mapState('products', ['items']),
    ...mapState('shoppingCart', ['items'])
  },
  created () {
    this.loadProducts()
  },
  methods: {
    ...mapActions('products', ['loadProducts'])
  }
}
</script>

<style scoped>

</style>
