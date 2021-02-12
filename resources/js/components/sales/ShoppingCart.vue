<template>
  <div>
    <div
      class="display-1"
      style="text-align:center"
    >
      Your Cart
    </div>

    <v-card>
      <v-list two-line>
        <template v-for="item in items">
          <v-list-item-title
            :key="item.title"
            avatar
          >
            <v-list-item-avatar>
              <img :src="item.image">
            </v-list-item-avatar>

            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
              <v-list-item-subtitle>
                Quantity: {{ item.quantity }} &nbsp; - &nbsp;
                {{ item.price | money }}
              </v-list-item-subtitle>
            </v-list-item-content>

            <v-list-item-action>
              <v-btn
                icon
                ripple
                @click.native="removeItem(item)"
              >
                <v-icon class="grey--text text--lighten-1">
                  close
                </v-icon>
              </v-btn>
            </v-list-item-action>
          </v-list-item-title>
        </template>
      </v-list>

      <shopping-cart-totals />

      <v-card-actions class="justify-center">
        <v-btn
          v-if="item_count > 0"
          primary
          @click.native.stop="checkout()"
        >
          Check out
        </v-btn>
      </v-card-actions>
    </v-card>

    <check-out-dialog v-model="checkoutDialog" />
  </div>
</template>

<script>
export default {
	name: 'ShoppingCard',
	data () {
		return {
			checkoutDialog: false
		}
	},

	computed: {
		items () { return this.$store.getters.cartItems },
		item_count () { return this.$store.getters.cartItemCount }
	},

	methods: {

		removeItem (item) {
			this.$store.dispatch('removeItemFromCart', item)
		},

		checkout () {
			this.checkoutDialog = true
		}

	}

}
</script>
