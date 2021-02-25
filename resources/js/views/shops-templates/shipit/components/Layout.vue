<template>
  <v-app id="inspire">
    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
      color="primary"
      dark
    >
      <!--      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />-->

      <v-toolbar-title
        style="width: 350px"
      >
        <router-link
          color="green"
          overlap
          :to="{ name: 'Home', params: $route.params}"
        >
          <v-icon>mdi-home</v-icon>&nbsp;
        </router-link>
      </v-toolbar-title>
      <v-text-field
        flat
        solo-inverted
        hide-details
        prepend-inner-icon="mdi-magnify"
        label="Search"
        class="hidden-sm-and-down pl-10 ml-4"
      />
      <v-spacer />
      <v-btn icon>
        <v-icon>mdi-account-circle</v-icon>
      </v-btn>
      <v-btn
        icon
        v-on="on"
      >
        <v-badge
          content="2"
          value="2"
          color="green"
          overlap
        >
          <v-icon>mdi-bell</v-icon>
        </v-badge>
      </v-btn>

      <router-link
        :to="{ name: 'Home', params: $route.params}"
        color="green"
        overlap
      >
        <v-icon>mdi-cart</v-icon>
      </router-link>
      <v-btn
        href="/online/cart"
        icon
        v-on="on"
      />
    </v-app-bar>
    <v-content>
      <v-bottom-navigation
        :value="activeBtn"
        color="primary"
        horizontal
      >
        <router-link
          :to="{ name: 'Home', params: $route.params}"
          tag="v-btn"
        />
        <router-link
          v-if="shopData.shop"
          :to="{ name: 'Shop', params: $route.params}"
          tag="v-btn"
        >
          {{ shopData.shop.name }}
        </router-link>
      </v-bottom-navigation>
    </v-content>
    <router-view />
    <v-footer
      :padless="true"
    >
      <v-card
        flat
        tile
        width="100%"
        class="secondary white--text text-center"
      >
        <v-card-text class="white--text pt-0" />

        <v-divider />

        <v-card-text class="white--text">
          <v-row
            v-if="shopData.shop"
            no-gutters
          >
            <v-col
              v-if="shopData.shop.phone || shopData.shop.address"
              class="col-12 col-md-4 col-sm-12"
            >
              <v-row>
                <v-col
                  class="col-12 col-sm-3 pr-4"
                  align="right"
                >
                  <v-icon class="display-2">
                    mdi-headset
                  </v-icon>
                </v-col>
                <v-col class="col-12 col-sm-9 pr-4">
                  <h3 class="font-weight-light">
                    {{ shopData.shop.phone }}
                  </h3>
                  <p class="font-weight-thin">
                    {{ shopData.shop.address }}
                  </p>
                </v-col>
              </v-row>
            </v-col>

            <v-col
              v-if="shopData.shop"
              class="col-12 col-md-4 col-sm-12"
            >
              {{ new Date().getFullYear() }} — <strong>{{ shopData.shop.name }}</strong>
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-footer>
  </v-app>
</template>
<script>
import { mapActions, mapState } from 'vuex'

export default {
	data () {
		return {
			activeBtn: 1
		}
	},
	computed: {
		...mapState('category', [
			'categories',
			'isTableLoading'
		]),
		...mapState('shop', ['shopData'])
	},
	async created () {
		await this.getShopData(this.$route.params).then(() => {
		    if (!this.shopData.shop) {
				this.$router.push({ name: '404' })
			}
		})
		console.log(this.shopData.shop)
	},
	methods: {
		...mapActions('category', ['getCategories', 'getCategoriesShop']),
		...mapActions('shop', ['getShopData'])
	}
}
</script>
