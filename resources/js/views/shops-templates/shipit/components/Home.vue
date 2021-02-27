<template>
  <div>
    <v-carousel hide-delimiters>
      <v-carousel-item
        v-for="image in shopData.images"
        :key="image.path"
        :src="image.path"
      >
        <v-row
          class="fill-height"
          align="center"
          justify="center"
        >
          <div class="display-2 white--text pl-5 pr-5 hidden-sm-only" /><br>
        </v-row>
      </v-carousel-item>
    </v-carousel>
    <div
      v-if="articlesMerge.length > 0"
      class="pl-4 pr-4 row"
    >
      <div
        v-for="art in articlesMerge.slice(0,1)"
        :key="art.path"
        class="col-md-6 col-sm-6 col-xs-12"
      >
        <v-card>
          <v-img
            :src="art.path"
            class="white--text align-center"
            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
            height="400px"
          >
            <h1 class="text-center font-size">
              {{ art.name.upperCase() }}
            </h1>
            <div class="text-center">
              <v-btn
                class="white--text "
                outlined
                @click="gotTo(art)"
              >
                {{ art.name.upperCase() }}
              </v-btn>
            </div>

            <!--            <v-expand-transition>-->
            <!--              <div-->
            <!--                v-if="hover"-->
            <!--                class="d-flex transition-fast-in-fast-out orange darken-2 v-card&#45;&#45;reveal display-3 white&#45;&#45;text"-->
            <!--                style="height: 100%;"-->
            <!--              >-->

            <!--                <h3>Top Picks</h3><br/>-->
            <!--                <h3>sdfs</h3>-->
            <!--              </div>-->
            <!--            </v-expand-transition>-->
          </v-img>
        </v-card>
        <!--        </v-hover>-->
      </div>
    </div>
    <div
      v-if="articlesMerge.length > 0"
      class="pl-4 pr-4 row"
    >
      <div
        v-for="art in articlesMerge.slice(2,4)"
        :key="art.path"
        class="col-md-4 col-sm-4 col-xs-12"
      >
        <v-card outlined>
          <v-img
            :src="art.path"
            class="white--text align-center"
            gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
            height="300px"
          >
            <h1 class="text-center font-size">
              {{ art.name.upperCase() }}
            </h1>
            <div class="text-center mt-2">
              <v-btn
                class="white--text caption"
                href="/shop"
                text
              >
                SHOP NOW
              </v-btn>
            </div>
          </v-img>
        </v-card>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  data () {
    return {
      activeBtn: 0
    }
  },
  computed: {
    ...mapState('category', [
      'categories',
      'isTableLoading'
    ]),
    ...mapState('article', ['articlesMerge']),
    ...mapState('shop', ['shopData'])
  },
  async created () {
    await this.getShopData(this.$route.params).then(() => {
    }).catch((error) => {
      if (error === 500) { this.$router.push({ name: '404' }) }
    })
  },
  methods: {
    ...mapActions('category', ['getCategories', 'getCategoriesShop']),
    ...mapActions('article', ['getArticlesMerge']),
    ...mapActions('shop', ['getShopData']),
    goTo (article) {
		    this.$router.push({
        name: 'Product',
        params: {
          compName: this.$route.params.compName,
          shopName: this.$route.params.shopName,
          article: article
        }
      })
    }
  }
}
</script>
<style>
  .v-card--reveal {
    align-items: center;
    bottom: 0;
    justify-content: center;
    opacity: .5;
    position: absolute;
    width: 100%;
  }
</style>
