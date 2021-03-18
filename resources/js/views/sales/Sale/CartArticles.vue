<template>
  <v-card>
    <v-app-bar>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-spacer />

      <v-btn
        icon

        @click="$emit('cancelCartArticle')"
      >
        <v-icon>mdi-close</v-icon>
      </v-btn>
    </v-app-bar>
    <v-navigation-drawer
      v-model="drawer"
      absolute
      bottom
      temporary
    >
      <sale-left-drawer
        :edit="managerSale"
        :sale="sale"
        :total-price="parseFloat(totalPrice).toFixed(2)"
        :total-tax="parseFloat(totalTax).toFixed(2)"
        :total-discount="parseFloat(totalDiscount).toFixed(2)"
        :sub-total="parseFloat(subTotal).toFixed(2)"
        @updateData="$emit('calcTotalSale')"
      />
      <v-divider />

      <facture
        :currency="user.company.currency || ''"
        :edit="true"
        :sale="sale"
        :sub-total="parseFloat(subTotal).toFixed(2)"
        :total-discount="
          parseFloat(totalDiscount).toFixed(2)
        "
        :total-price="parseFloat(totalPrice).toFixed(2)"
        :total-tax="parseFloat(totalTax).toFixed(2)"
      />
    </v-navigation-drawer>

    <v-card-text>
      <v-row>
        <v-col
          v-for="article in sale.articles"
          :key="article.id"
          cols="12"
          sm="6"
          md="4"
          style="margin-top: 10px"
        >
          <v-card
            class="mx-auto"
            color="grey lighten-4"
            max-width="400"
          >
            <v-img
              :aspect-ratio="16/9"
              :src="article.images.length > 0 ? article.images[0].path : ''"
            />
            <v-card-text class="headline font-weight-bold">
              <v-list-item>
                <v-list-item-content>
                  {{ article.name }}
                </v-list-item-content>
              </v-list-item>
              <v-subheader inset>
                <v-list-item>
                  <v-list-item-title>{{ $vuetify.lang.t('$vuetify.articles.price') + ' '+`${user.company.currency + ' ' + parseFloat(article.price).toFixed(2)}` }}</v-list-item-title>
                </v-list-item>
              </v-subheader>
            </v-card-text>
            <v-card-actions>
              <v-btn
                color="orange lighten-2"
                text
              >
                {{ $vuetify.lang.t('$vuetify.variants.total_price') + ' ' + user.company.currency + ' ' + article.totalPrice }}
              </v-btn>

              <v-spacer />

              <v-btn
                icon
                @click="show = !show"
              >
                <v-icon>{{ show ? 'mdi-chevron-up' : 'mdi-chevron-down' }}</v-icon>
              </v-btn>
            </v-card-actions>

            <v-expand-transition>
              <div v-show="show">
                <v-divider />

                <v-card-text>
                  <v-list-item>
                    <v-list-item-avatar>
                      <v-icon
                        @click="minusArticle(article)"
                      >
                        mdi-minus-circle-outline
                      </v-icon>
                    </v-list-item-avatar>

                    <v-list-item-content>
                      <v-text-field-money
                        v-model="article.cant"
                        :label="$vuetify.lang.t('$vuetify.variants.cant')"
                        :properties="{
                          clearable: false,
                        }"
                        :options="{
                          length: 15,
                          precision: 2,
                          empty: 0.00,
                        }"
                      />
                    </v-list-item-content>

                    <v-list-item-avatar>
                      <v-icon
                        @click="plusArticle(article)"
                      >
                        mdi-plus-circle-outline
                      </v-icon>
                    </v-list-item-avatar>
                  </v-list-item>
                  <v-autocomplete
                    v-model="article.discount"
                    chips
                    multiple
                    :items="localDiscounts"
                    :label="$vuetify.lang.t('$vuetify.access.access.manager_discount')"
                    item-text="name"
                    return-object
                    @change="updateSale(article)"
                  >
                    <template v-slot:append-outer>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon
                            v-bind="attrs"
                            v-on="on"
                            @click="$store.dispatch('discount/toogleNewModal',true)"
                          >
                            mdi-plus
                          </v-icon>
                        </template>
                        <span>{{
                          $vuetify.lang.t('$vuetify.titles.newAction')
                        }}</span>
                      </v-tooltip>
                    </template>
                  </v-autocomplete>
                  <v-autocomplete
                    v-model="article.modifiers"
                    chips
                    multiple
                    :items="localModifiers"
                    :label="$vuetify.lang.t('$vuetify.access.access.manager_mod')"
                    item-text="name"
                    return-object
                    @change="updateSale(article)"
                  >
                    <template v-slot:append-outer>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon
                            v-bind="attrs"
                            v-on="on"
                            @click="$store.dispatch('modifiers/toogleNewModal',true)"
                          >
                            mdi-plus
                          </v-icon>
                        </template>
                        <span>{{
                          $vuetify.lang.t('$vuetify.titles.newAction')
                        }}</span>
                      </v-tooltip>
                    </template>
                  </v-autocomplete>
                </v-card-text>
              </div>
            </v-expand-transition>
          </v-card>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
import SaleLeftDrawer from './SaleLeftDrawer'
import { mapActions, mapGetters, mapState } from 'vuex'
import Facture from '../../buy/Facture'
export default {
  name: 'CartArticles',
  components: { Facture, SaleLeftDrawer },
  props: {
    edit: {
      type: Boolean,
      default: false
    },
    totalPrice: {
      type: String,
      default: '0.00'
    },
    totalTax: {
      type: String,
      default: '0.00'
    },
    totalDiscount: {
      type: String,
      default: '0.00'
    },
    subTotal: {
      type: String,
      default: '0.00'
    },
    sale: {
      type: Object,
      default: function () {
        return {
          no_facture: '',
          pay: '',
          online: false,
          pays: [],
          box: null,
          state: 'open',
          discounts: [],
          taxes: [],
          payments: null,
          articles: [],
          shop: null,
          client: null
        }
      }
    },
    dialog: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      show: false,
      drawer: true,
      localDiscounts: [],
      localModifiers: [],
      mini: true
    }
  },
  computed: {
    ...mapState('sale', ['managerSale']),
    ...mapState('discount', ['discounts']),
    ...mapState('modifiers', ['modifiers']),
    ...mapGetters('auth', ['user'])
  },
  watch: {
    'sale.articles': function () {
      if (this.sale.articles.length === 0) {
        this.dialog = false
      }
    },
    modifiers: function () {
      this.getLocalModifiers()
    },
    discounts: function () {
      this.getLocalDiscounts()
    }
  },
  async created () {
    await this.getDiscounts().then(() => {
      this.getLocalDiscounts()
    })
    await this.getModifiers().then(() => {
      this.getLocalModifiers()
    })
  },
  methods: {
    ...mapActions('discount', ['getDiscounts']),
    ...mapActions('modifiers', ['getModifiers']),
    getLocalModifiers () {
      this.modifiers.forEach((v) => {
        this.localModifiers.push({
          id: v.id,
          name: v.percent ? v.name + '(' + v.value + '%)' : v.name + '(' + this.user.company.currency + v.value + ')',
          value: v.value,
          percent: v.percent
        })
      })
    },
    getLocalDiscounts () {
      this.discounts.forEach((v) => {
        this.localDiscounts.push({
          id: v.id,
          name: v.percent ? v.name + '(' + v.value + '%)' : v.name + '(' + this.user.company.currency + v.value + ')',
          value: v.value,
          percent: v.percent
        })
      })
    },
    minusArticle (article) {
      if (article.cant > 0) { article.cant = article.cant - 1 } else {
        this.sale.articles.slice(article, 1)
      }
      this.updateSale(article)
    },
    plusArticle (article) {
      article.cant = article.cant + 1
      this.updateSale(article)
    },
    updateSale (item) {
      this.editedIndex = this.sale.articles.indexOf(item)
      this.sale.articles[this.editedIndex].totalCost = parseFloat(this.sale.articles[this.editedIndex].cost *
                this.sale.articles[this.editedIndex].cant).toFixed(2)
      this.sale.articles[this.editedIndex].totalCant = parseFloat(parseFloat(this.sale.articles[this.editedIndex].inventory) -
                parseFloat(this.sale.articles[this.editedIndex].cant) || 0).toFixed(2)
      item.totalPrice = item.cant * item.price + this.articleTotalPrice(item) - this.articleTotalDiscount(item) + this.totalModifier(item)
      this.$emit('updateSaleData', this.sale)
    },
    articleTotalPrice (item) {
      let tax = 0
      if (item.taxes.length > 0) {
        item.taxes.forEach((v) => {
          tax += v.percent ? item.cant * item.price * v.value / 100 : v.value
        })
      }
      return tax
    },
    totalModifier (item) {
      let modf = 0
      if (item.modifiers.length > 0) {
        item.modifiers.forEach((v) => {
          modf += v.percent ? item.cant * item.price * v.value / 100 : v.value
        })
      }
      return modf
    },
    articleTotalDiscount (item) {
      let disc = 0
      if (item.discount.length > 0) {
        item.discount.forEach((v) => {
          disc += v.percent ? item.cant * item.price * v.value / 100 : v.value
        })
      }
      return disc
    }
  }
}
</script>

<style scoped>

</style>
