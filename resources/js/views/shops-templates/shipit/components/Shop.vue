<template>
  <div>
    <v-container>
      <div class="row">
        <div
          class="col-md-3 col-sm-3 col-xs-12"
        >
          <v-card outlined>
            <v-list dense>
              <v-card-subtitle>
                {{ $vuetify.lang.t('$vuetify.menu.category').toUpperCase() }}
              </v-card-subtitle>
              <v-list-item-group
                color="primary"
              >
                <v-list-item
                  v-for="(item, i) in shopData.categories"
                  :key="i"
                >
                  <v-list-item-content>
                    <v-list-item-title
                      @click="filterArticles(item)"
                      v-text="item.name.toUpperCase()"
                    />
                  </v-list-item-content>
                </v-list-item>
              </v-list-item-group>
            </v-list>
            <v-divider />
          </v-card>
        </div>
        <div
          class="col-md-9 col-sm-9 col-xs-12"
        >
          <v-row
            v-if="localArticles.length > 0"
            dense
          >
            <v-col
              cols="12"
              sm="8"
              class="pl-6 pt-6"
            >
              <small>{{ $vuetify.lang.t('$vuetify.online.showing') }} {{ page*12 +1 }}-{{ localArticles.length > page*12 + 12 ? page*12 + 12 : localArticles.length }} {{ $vuetify.lang.t('$vuetify.online.of') }} {{ localArticles.length }}</small>
            </v-col>
          </v-row>

          <v-divider />
          <v-row>
            <v-col
              v-for="item in localArticles.slice(page*12 +1, page*12 + 12)"
              cols="12"
              md="4"
            >
              <v-hover v-slot="{ hover }">
                <v-card
                  class="mx-auto"
                  color="grey lighten-4"
                  max-width="600"
                >
                  <v-avatar
                    v-if="item.color && item.images.length === 0"
                    class="v-image v-responsive theme--light"
                    :color="item.color"
                  >
                    <v-expand-transition>
                      <div
                        v-if="hover"
                        class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal display-3 white--text"
                        style="height: 100%;"
                      />
                      {{ item.price }}
                    </v-expand-transition>
                  </v-avatar>
                  <v-img
                    v-else
                    :aspect-ratio="16/9"
                    :src="item.path"
                  >
                    <v-expand-transition>
                      <div
                        v-if="hover"
                        class="d-flex transition-fast-in-fast-out orange darken-2 v-card--reveal display-3 white--text"
                        style="height: 100%;"
                      />
                      {{ item.name.toString().toUpperCase() }}
                    </v-expand-transition>
                  </v-img>
                  <v-card-text
                    class="pt-6"
                    style="position: relative;"
                  >
                    <v-btn
                      absolute
                      color="orange"
                      class="white--text"
                      fab
                      large
                      right
                      top
                    >
                      <v-icon>mdi-cart</v-icon>
                    </v-btn>
                    <div class="font-weight-light grey--text title mb-2">
                      {{ item.name.toString().toUpperCase() }}
                    </div>
                    <div class="font-weight-light title mb-2">
                      {{ item.description }}
                    </div>
                  </v-card-text>
                </v-card>
              </v-hover>
            </v-col>
          </v-row>
          <div
            v-if="localArticles.length > 12"
            class="text-center mt-12"
          >
            <v-pagination
              v-model="page"
              :length="Math.round(localArticles.length/12 -1)"
            />
          </div>
        </div>
      </div>
    </v-container>
  </div>
</template>
<script>
import { mapActions, mapState } from 'vuex'

export default {
	data: () => ({
		page: 0,
		localArticles: [],
		min: 0,
		max: 10000
	}),
	computed: {
		...mapState('category', [
			'categories',
			'isTableLoading'
		]),
		...mapState('shop', ['shopData'])
	},
	created () {
		this.getShopData(this.$route.params).then(() => {
		    this.localArticles = this.shopData.articles
		}).catch((error) => {
		    console.log(error)
		    if (error === 500) { this.$router.push({ name: '404' }) }
		})
	},
	methods: {
		...mapActions('category', ['getCategories', 'getCategoriesShop']),
		...mapActions('shop', ['getShopData']),
		filterArticles (category) {
		    this.localArticles = this.shopData.articles.filter(art => art.category_id === category.id)
			this.page = 0
		}
	}
}
</script>
<style>
.v-card--reveal {
    align-items: center;
    bottom: 0;
    justify-content: center;
    opacity: .8;
    position: absolute;
    width: 100%;
}
</style>
