<template>
  <div
    id="create"
    class="page-add-sales"
  >
    <v-speed-dial
      v-if="sale.articles.length > 0 && !showExtraData"
      v-model="fab"
      style="margin-top: 0px; position: absolute;top: 15%; left: 0; z-index: 2"
      :top="top"
      :bottom="bottom"
      :right="right"
      :left="left"
      :direction="direction"
      :open-on-hover="hover"
      :transition="transition"
    >
      <template v-slot:activator>
        <v-btn
          v-model="fab"
          color="blue darken-2"
          dark
          fab
        >
          {{ totalArticles }}
          <v-icon>
            mdi-newspaper
          </v-icon>
        </v-btn>
      </template>
      <v-btn
        fab
        dark
        small
        color="green"
        @click="showCartArticles=totalArticles > 0; showExtraData = false"
      >
        {{ totalArticles }}
        <v-icon>mdi-cart</v-icon>
      </v-btn>
      <v-btn
        fab
        dark
        small
        color="indigo"
        @click="showExtraData = true; showCartArticles=false"
      >
        <v-icon>mdi-file-document</v-icon>
      </v-btn>
      <v-btn
        v-if="!showCartArticles"
        fab
        dark
        small
        color="red"
        @click="clearArticles"
      >
        <v-icon>mdi-cart-off</v-icon>
      </v-btn>
    </v-speed-dial>
    <app-loading v-show="loadingData" />
    <v-card
      v-if="!loadingData"
    >
      <v-card-text>
        <cart-articles
          v-if="totalArticles > 0"
          v-show="showCartArticles"
          :edit="managerSale"
          :sale="sale"
          :total-price="parseFloat(totalPrice).toFixed(2)"
          :total-tax="parseFloat(totalTax).toFixed(2)"
          :total-discount="parseFloat(totalDisc).toFixed(2)"
          :sub-total="parseFloat(subTotal).toFixed(2)"
          @cancelCartArticle="showCartArticles=false"
          @updateSaleData="updateSaleData"
        />
        <extra-data
          v-show="showExtraData"
          :edit="managerSale"
          :sale="sale"
          :total-price="parseFloat(totalPrice).toFixed(2)"
          :total-tax="parseFloat(totalTax).toFixed(2)"
          :total-discount="parseFloat(totalDisc).toFixed(2)"
          :sub-total="parseFloat(subTotal).toFixed(2)"
          @updateData="calcTotalSale"
          @cancelExtraData="showExtraData=false"
        />
        <v-card
          v-show="!showCartArticles && !showExtraData"
        >
          <v-form
            ref="form"
            v-model="formValid"
            style="padding: 0"
            lazy-validation
          >
            <v-app-bar>
              <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
              <v-autocomplete
                v-model="sale.shop"
                style="margin-top: 30px"
                :disabled="managerSale"
                solo
                :items="shops"
                item-text="name"
                :loading="isShopLoading"
                return-object
                required
                :rules="formRule.country"
                @change="updateDataArticle"
              />
              <v-spacer />
              <v-menu
                v-if="categories.length > 0"
                open-on-hover
                offset-y
              >
                <template v-slot:activator="{ on }">
                  <v-btn v-on="on">
                    <span>
                      {{ $vuetify.lang.t('$vuetify.menu.category').toUpperCase() }}</span>
                  </v-btn>
                </template>
                <v-card
                  class="mx-auto"
                  max-width="344"
                  outlined
                >
                  <v-list-item
                    v-for="(item, i) in categories"
                    :key="i"
                  >
                    <v-list-item-content>
                      <v-list-item-title
                        @click="filterArticles(item)"
                        v-text="item.name.toUpperCase()"
                      />
                    </v-list-item-content>
                  </v-list-item>
                  <v-list-item>
                    <v-list-item-content>
                      <v-list-item-title
                        @click="filterArticles('')"
                        v-text="$vuetify.lang.t('$vuetify.component.select_all').toUpperCase()"
                      />
                    </v-list-item-content>
                  </v-list-item>
                </v-card>
              </v-menu>
              <v-text-field
                v-model="txtFilter"
                flat
                :label="$vuetify.lang.t('$vuetify.actions.search')"
                solo-inverted
                hide-details
                prepend-inner-icon="mdi-magnify"
                class="hidden-sm-and-down pl-10 ml-4"
              />
            </v-app-bar>
            <v-navigation-drawer
              v-model="drawer"
              absolute
              bottom
              temporary
            >
              <v-list-item>
                <v-list-item-content>
                  <v-select
                    v-model="sale.box"
                    :rules="formRule.country"
                    :items="localBoxes"
                    :disabled="managerSale"
                    required
                    :label="$vuetify.lang.t('$vuetify.menu.box')"
                    item-text="name"
                    return-object
                  >
                    <template v-slot:append-outer>
                      <v-tooltip
                        bottom
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon
                            v-bind="attrs"
                            v-on="on"
                            @click="$store.dispatch('boxes/toogleNewModal',true)"
                          >
                            mdi-plus
                          </v-icon>
                        </template>
                        <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
                      </v-tooltip>
                    </template>
                  </v-select>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-autocomplete
                    v-model="sale.client"
                    clearable
                    :items="clients"
                    :label="$vuetify.lang.t('$vuetify.menu.client')"
                    item-text="firstName"
                    :loading="isClientTableLoading"
                    :disabled="!!isClientTableLoading"
                    return-object
                  >
                    <template v-slot:append-outer>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon
                            v-bind="attrs"
                            v-on="on"
                            @click="
                              $store.dispatch(
                                'client/toogleNewModal',
                                true
                              )
                            "
                          >
                            mdi-plus
                          </v-icon>
                        </template>
                        <span>{{
                          $vuetify.lang.t("$vuetify.titles.newAction")
                        }}</span>
                      </v-tooltip>
                    </template>
                    <template v-slot:selection="data">
                      <v-chip
                        v-bind="data.attrs"
                        :input-value="data.selected"
                        @click="data.select"
                      >
                        <v-avatar left>
                          <v-img
                            :src="
                              data.item.avatar ||
                                '/assets/avatar/avatar-undefined.jpg'
                            "
                          />
                        </v-avatar>
                        {{
                          data.item.firstName +
                            " " +
                            `${
                              data.item.lastName !== null
                                ? data.item.lastName
                                : ""
                            }`
                        }}
                      </v-chip>
                    </template>
                    <template v-slot:item="data">
                      <template>
                        <v-list-item-avatar>
                          <v-img
                            :src="
                              data.item.avatar ||
                                '/assets/avatar/avatar-undefined.jpg'
                            "
                          />
                        </v-list-item-avatar>
                        <v-list-item-content>
                          <v-list-item-title>
                            {{
                              data.item.firstName +
                                " " +
                                `${
                                  data.item.lastName !== null
                                    ? data.item.lastName
                                    : ""
                                }`
                            }}
                          </v-list-item-title>
                          <v-list-item-subtitle>
                            {{ `${data.item.email}` }}
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-text-field
                    v-model="sale.no_facture"
                    :label="$vuetify.lang.t('$vuetify.tax.noFacture')"
                    required
                    readonly
                    :rules="formRule.required"
                  />
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-select
                    v-model="sale.taxes"
                    chips
                    clearable
                    deletable-chips
                    :items="taxes"
                    multiple
                    :label="$vuetify.lang.t('$vuetify.tax.nameGeneral')"
                    item-text="name"
                    :loading="isTaxLoading"
                    :disabled="!!isTaxLoading || sale.articles.length === 0"
                    return-object
                    required
                    :rules="formRule.country"
                  >
                    <template v-slot:append-outer>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon
                            v-bind="attrs"
                            v-on="on"
                            @click="
                              $store.dispatch(
                                'tax/toogleNewModal',
                                true
                              )
                            "
                          >
                            mdi-plus
                          </v-icon>
                        </template>
                        <span>{{
                          $vuetify.lang.t("$vuetify.titles.newAction")
                        }}</span>
                      </v-tooltip>
                    </template>
                  </v-select>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-select
                    v-model="sale.discounts"
                    chips
                    clearable
                    deletable-chips
                    :items="localDiscounts"
                    multiple
                    :label="$vuetify.lang.t('$vuetify.sale.discountGeneral')"
                    item-text="name"
                    :loading="isTaxLoading"
                    :disabled="!!isTaxLoading || sale.articles.length === 0"
                    return-object
                    required
                    :rules="formRule.country"
                  >
                    <template v-slot:append-outer>
                      <v-tooltip bottom>
                        <template v-slot:activator="{ on, attrs }">
                          <v-icon
                            v-bind="attrs"
                            v-on="on"
                            @click="
                              $store.dispatch(
                                'discount/toogleNewModal',
                                true
                              )
                            "
                          >
                            mdi-plus
                          </v-icon>
                        </template>
                        <span>{{
                          $vuetify.lang.t("$vuetify.titles.newAction")
                        }}</span>
                      </v-tooltip>
                    </template>
                  </v-select>
                </v-list-item-content>
              </v-list-item>
            </v-navigation-drawer>
            <v-row>
              <v-col
                cols="12"
                md="4"
              >
                <v-autocomplete
                  id="slc_box"
                  v-model="sale.box"
                  style="margin-left: 15px;margin-top: 15px"
                  :rules="formRule.country"
                  :items="localBoxes"
                  :disabled="managerSale"
                  required
                  :label="$vuetify.lang.t('$vuetify.menu.box')"
                  item-text="name"
                  return-object
                >
                  <template v-slot:append-outer>
                    <v-tooltip
                      bottom
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-icon
                          id="btn_add_box"
                          v-bind="attrs"
                          v-on="on"
                          @click="$store.dispatch('boxes/toogleNewModal',true)"
                        >
                          mdi-plus
                        </v-icon>
                      </template>
                      <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
                    </v-tooltip>
                  </template>
                </v-autocomplete>
              </v-col>
            </v-row>
            <v-row
              v-if="localArticles.length > 0"
            >
              <v-col
                class="col-md-12 col-sm-12 col-xs-12"
              >
                <v-row
                  v-if="articlesFilter.length > 0"
                  dense
                >
                  <v-col
                    cols="12"
                    sm="8"
                    class="pl-6 pt-6"
                  >
                    <small>{{ $vuetify.lang.t('$vuetify.online.showing') }} {{ page*8 +1 }}-{{ articlesFilter.length > page*8 + 8 ? page*8 + 8 : articlesFilter.length }} {{ $vuetify.lang.t('$vuetify.online.of') }} {{ articlesFilter.length }}</small>
                  </v-col>
                </v-row>

                <v-divider />
                <v-row>
                  <v-col
                    v-for="item in articlesFilter.slice(page*8, page*8 + 8)"
                    :key="item.id"
                    cols="12"
                    md="3"
                    style="margin: 15px"
                  >
                    <article-sale
                      :article="item"
                      :currency="user.company.currency"
                      @addToSale="selectArticle"
                    />
                  </v-col>
                </v-row>
                <div
                  v-if="articlesFilter.length > 8"
                  class="text-center mt-12"
                >
                  <v-pagination
                    v-model="page"
                    :length="Math.round(articlesFilter.length/8 -1)"
                  />
                </div>
              </v-col>
            </v-row>
            <v-row
              v-else
              style="margin: 20px"
            >
              {{ $vuetify.lang.t('$vuetify.sale.no_article') }}
            </v-row>
          </v-form>
        </v-card>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          :disabled="isActionInProgress"
          @click="handleClose"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <template v-if="sale.pays.length === 0">
          <v-btn
            class="mb-2"
            color="success"
            :disabled="!formValid || isActionInProgress || !sale.box"
            :loading="isActionInProgress"
            @click="saleHandler('preform')"
          >
            <v-icon>mdi-calendar-clock</v-icon>
            {{ $vuetify.lang.t('$vuetify.sale.state.preform') }}
          </v-btn>
        </template>
        <template v-else>
          <v-btn
            v-show="(sale.state === 'preform' && sale.pays.length > 0) || sale.state === 'open'"
            class="mb-2"
            color="success"
            :disabled="!formValid || isActionInProgress"
            :loading="isActionInProgress"
            @click="saleHandler('open')"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.sale.state.open') }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
            :disabled="!formValid || isActionInProgress"
            :loading="isActionInProgress"
            @click="saleHandler('accepted')"
          >
            <v-icon>mdi-check-all</v-icon>
            {{ $vuetify.lang.t('$vuetify.sale.state.accepted') }}
          </v-btn>
        </template>
      </v-card-actions>
      <v-dialog
        v-model="showInfoAdd"
        max-width="500px"
      >
        <v-card>
          <v-card-title class="headline">
            {{ $vuetify.lang.t('$vuetify.messages.dont_add') }}
          </v-card-title>
          <v-card-actions>
            <v-spacer />
            <v-btn
              class="mb-2"
              color="primary"
              @click="closeInfoAdd"
            >
              <v-icon>mdi-check</v-icon>
              {{ $vuetify.lang.t('$vuetify.actions.accept') }}
            </v-btn>
            <v-spacer />
          </v-card-actions>
        </v-card>
      </v-dialog>
      <new-discount v-if="this.$store.state.discount.showNewModal" />
      <new-box v-if="this.$store.state.boxes.showNewModal" />
      <new-modifiers v-if="this.$store.state.modifiers.showNewModal" />
    </v-card>
    <app-tour
      name="saleManager"
      :steps="stepsHelp"
    />
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import ExtraData from './ExtraData'
import NewDiscount from '../discount/NewDiscount'
import NewModifiers from '../modifiers/NewModifiers'
import NewBox from '../boxes/NewBox'
import utils from '../../util'
import DetailArticlePrice from './DetailArticlePrice'
import ArticleSale from './Sale/ArticleSale'
import CartArticles from './Sale/CartArticles'

export default {
  name: 'ManagerSale',
  components: {
    CartArticles,
    // DetailArticlePrice,
    ArticleSale,
    NewBox,
    NewDiscount,
    NewModifiers,
    ExtraData
  },
  data () {
    return {
      drawer: false,
      txtFilter: '',
      showCartArticles: false,
      showExtraData: false,
      totalArticles: 0,
      direction: 'right',
      fab: false,
      fling: false,
      hover: false,
      tabs: null,
      top: true,
      right: false,
      bottom: false,
      left: true,
      transition: 'slide-y-reverse-transition',
      dialog: false,
      page: 0,
      showModify: false,
      sale: {},
      articleSelected: {},
      loadingData: false,
      editedIndex: -1,
      localArticles: [],
      articlesFilter: [],
      localDiscounts: [],
      localModifiers: [],
      localBoxes: [],
      update: false,
      panel: [0, 1, 2],
      totalTax: 0,
      totalDisc: 0,
      subTotal: 0,
      totalPrice: 0,
      formValid: false,
      showInfoAdd: false,
      vBindOption: {
        itemKey: 'name',
        singleExpand: false,
        showExpand: true
      },
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('category', ['categories']),
    ...mapState('sale', ['managerSale', 'sales', 'saleNumber', 'newSale', 'editSale', 'isActionInProgress']),
    ...mapState('article', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'articles',
      'isTableLoading'
    ]),
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapState('discount', ['discounts']),
    ...mapState('modifiers', ['modifiers']),
    ...mapState('inventory', ['inventories']),
    ...mapState('boxes', ['boxes', 'showNewModal']),
    ...mapGetters('sale', ['getNumberFacture']),
    ...mapState('client', ['clients', 'isClientTableLoading']),
    ...mapState('tax', ['taxes', 'isTaxLoading']),
    ...mapState('payment', ['payments', 'isPaymentLoading']),
    ...mapState('discount', ['discounts']),
    ...mapGetters('auth', ['user']),
    factureNumber: {
      get () {
        return this.getNumberFacture
      },
      set (newNumber) {
        return newNumber
      }
    },
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.articles.ref'),
          value: 'ref',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          with: '15%',
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.inventory'),
          value: 'inventory',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.price'),
          value: 'price',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cant'),
          value: 'cant',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.total_price'),
          value: 'totalPrice',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.actions.actions'),
          value: 'actions',
          sortable: false
        }
      ]
    },
    stepsHelp () {
      return [{
        target: '#slc_shop',
        content: this.$vuetify.lang.t('$vuetify.helpSaleManager.selectShop'),
        params: {
          placement: 'top'
        }
      }, {
        target: '#slc_box',
        content: this.$vuetify.lang.t('$vuetify.helpSaleManager.selectBox'),
        params: {
          placement: 'top'
        }
      }, {
        // target: '#btn_add_box',
        content: this.$vuetify.lang.t('$vuetify.helpSaleManager.addBox'),
        params: {
          placement: 'top'
        }
      }, {
        target: '#slc_article',
        content: this.$vuetify.lang.t('$vuetify.helpSaleManager.selectArticle'),
        params: {
          placement: 'left'
        }
      }]
    },
    getDifferencePay () {
      let totalCalcP = 0.00
      this.sale.pays.forEach(v => {
        totalCalcP += parseFloat(v.cant)
      })
      return parseFloat(this.totalPrice) - parseFloat(totalCalcP)
    }
  },
  watch: {
    txtFilter: function () {
      this.articlesFilter = this.txtFilter !== '' ? this.localArticles.filter(art => art.name.toLowerCase().includes(this.txtFilter.toLowerCase()) || art.barCode.toLowerCase().includes(this.txtFilter.toLowerCase())) : this.localArticles
    },
    discounts: function () {
      this.getLocalDiscounts()
    },
    modifiers: function () {
      this.getLocalModifiers()
    },
    boxes: function () {
      this.getLocalBoxes()
    },
    'sale.taxes' () {
      this.calcTotalSale()
    },
    'sale.articles' () {
      this.totalArticles = this.sale.articles.length === 0 ? 0 : this.totalArticles
      this.calcTotalSale()
    },
    'sale.discounts' () {
      this.calcTotalSale()
    }
  },
  async created () {
    this.loadingData = true
    this.sale = !this.managerSale ? this.newSale : this.editSale
    if (this.managerSale) {
      this.calcTotalSale()
    }
    await this.getArticles()
    await this.getShops().then(() => {
      if (!this.managerSale) {
        this.sale.shop = this.shops[0]
      }
    })
    await this.getDiscounts().then(() => {
      this.getLocalDiscounts()
    })
    await this.getBoxes()
    await this.getCategories()
    await this.getModifiers().then(() => {
      this.getLocalModifiers()
    })
    await this.getBoxes()
    await this.updateDataArticle()
    if (!this.managerSale) {
      await this.fetchSaleNumber().then(() => {
        this.sale.no_facture = this.generateNF()
      })
    }
    await this.getClients()
    await this.getTaxes()
    await this.getPayments()
    await this.getDiscounts().then(() => {
      this.getLocalDiscounts()
    })
    this.loadingData = false
  },
  methods: {
    ...mapActions('client', ['getClients']),
    ...mapActions('tax', ['getTaxes']),
    ...mapActions('payment', ['getPayments']),
    ...mapActions('discount', ['getDiscounts']),
    ...mapActions('boxes', ['toogleNewModal', 'getBoxes']),
    ...mapActions('category', ['getCategories']),
    ...mapActions('inventory', ['getInventories']),
    ...mapActions('article', ['getArticles']),
    ...mapActions('shop', ['getShops']),
    ...mapActions('sale', ['getSales', 'createSale', 'updateSale', 'fetchSaleNumber']),
    ...mapActions('discount', ['getDiscounts']),
    ...mapActions('modifiers', ['getModifiers']),
    clearArticles () {
      console.log(this.totalArticles)
      this.sale.articles.forEach((art) => {
        this.articlesFilter.filter(article => article.id === art.id)[0].cant = 1
        art.cant = 1
      })
      this.sale.articles.splice(0, this.sale.articles.length)
      this.totalArticles = 0
    },
    updateSaleData (sale) {
      this.sale = sale
      this.calcTotalSale()
    },
    filterArticles (category) {
      if (category !== '') {
        this.articlesFilter = this.localArticles.filter(art => art.category_id === category.id)
      } else {
        this.articlesFilter = this.localArticles
      }
      this.page = 0
    },
    generateNF () {
      const seqer = utils.serialMaker()
      seqer.set_prefix('F' + new Date().getFullYear() + '-')
      seqer.set_seq(this.factureNumber)
      return seqer.gensym()
    },
    async updateDataArticle () {
      this.showExtraData = false
      this.showCartArticles = false
      this.getLocalBoxes()
      this.localArticles = []
      if (this.sale.shop) {
        await this.articles.forEach((value) => {
          if (value.variant_values.length > 0) {
            value.variant_values.forEach((v) => {
              const artS = v.articles_shops.filter(artS => artS.shop_id === this.sale.shop.id)
              if (artS.length > 0) {
                this.validAddToLocalArticle(v, value, artS)
              }
            })
          } else {
            const artS = value.articles_shops.filter(artS => artS.shop_id === this.sale.shop.id)
            if (artS.length > 0) {
              this.validAddToLocalArticle(value, value, artS)
            }
          }
        })
      }
      this.articlesFilter = this.localArticles
    },
    validAddToLocalArticle (v, value, artS) {
      let inventory = 0
      if (!value.track_inventory) {
        this.addToLocalArticle(v, value, 0, artS[0])
      } else {
        if (artS.length > 0) {
          inventory = artS[0].stock
        }
        this.addToLocalArticle(v, value, inventory, artS[0])
      }
    },
    addToLocalArticle (v, value, inventory, artS) {
      this.localArticles.push({
        ref: value.ref,
        name: value.name + '(' + v.name + ')',
        category: !value.category ? '' : value.category.name,
        category_id: !value.category ? '' : value.category.id,
        path: value.path,
        images: value.images,
        barCode: value.barCode,
        taxes: v.tax,
        discount: [],
        modifiers: [],
        color: value.color,
        price: artS.price,
        cost: v.cost ? v.cost : 0,
        inventory: inventory || 0,
        cant: 1,
        totalCant: (inventory || 0) + 1,
        totalCost: v.cost,
        totalPrice: v.price,
        article_id: v.id
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
    getLocalBoxes () {
      this.localBoxes = []
      if (this.sale.shop) {
        this.localBoxes = this.boxes[this.sale.shop.id].boxes.filter(bx => bx.digital === 0)
        if (this.localBoxes.length > 0) {
          this.sale.box = this.localBoxes[0]
        }
      }
    },
    selectArticle (item) {
      if (item) {
        if (this.sale.articles.filter(art => art.article_id === item.article_id).length === 0) {
          this.sale.articles.push(item)
        } else {
          this.sale.articles.filter(art => art.article_id === item.article_id)[0].cant = this.sale.articles.filter(art => art.article_id === item.article_id)[0].cant + 1
        }
        this.calcTotalArticle(item)
        this.calcTotalSale()
      }
    },
    deleteItem (item) {
      this.sale.articles.splice(this.sale.articles.indexOf(item), 1)
      this.calcTotalSale()
    },
    closeInfoAdd () {
      this.showInfoAdd = false
    },
    createPreform () {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.newF', [
            this.$vuetify.lang.t('$vuetify.sale.sale')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.warning_preform'
          ),
          icon: 'warning',
          showCancelButton: true,
          cancelButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.cancel'
          ),
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'success'
        })
        .then((result) => {
          if (result.isConfirmed) this.createNewSale('preform')
        })
    },
    async saleHandler (state) {
      if (parseFloat(this.getDifferencePay) !== 0 && this.sale.state === 'accepted') {
        this.loading = false
        this.shopMessageError(this.$vuetify.lang.t(
          '$vuetify.messages.warning_difference_price', [(-this.getDifferencePay + this.user.company.currency).toString()]
        ))
      } else {
        if (!this.sale.online && !this.sale.box) {
          this.loading = false
          this.shopMessageError(this.$vuetify.lang.t(
            '$vuetify.messages.warning_no_box'
          ))
        } else {
          if (this.sale.articles.length > 0) {
            if (this.$refs.form.validate()) {
              this.loading = true
              this.sale.state = state
              if (!this.managerSale) {
                await this.createSale(this.sale).then(() => {
                  this.$router.push({ name: 'vending' })
                })
              } else {
                await this.updateSale(this.sale).then(() => {
                  this.$router.push({ name: 'vending' })
                })
              }
            }
          } else {
            this.loading = false
            this.shopMessageError(this.$vuetify.lang.t(
              '$vuetify.messages.warning_cant_article'
            ))
          }
        }
      }
    },
    shopMessageError (message) {
      this.$Swal.fire({
        title: this.$vuetify.lang.t('$vuetify.titles.newF', [
          this.$vuetify.lang.t('$vuetify.sale.sale')
        ]),
        text: message,
        icon: 'warning',
        showCancelButton: false,
        confirmButtonText: this.$vuetify.lang.t(
          '$vuetify.actions.accept'
        ),
        confirmButtonColor: 'red'
      })
    },
    handleClose () {
      this.localArticles = []
      this.articlesFilter = []
      this.sale.articles = []
      this.$router.push({ name: 'vending' })
    },
    calcTotalArticle: function (item) {
      this.editedIndex = this.sale.articles.indexOf(item)
      this.sale.articles[this.editedIndex].totalCost = parseFloat(this.sale.articles[this.editedIndex].cost *
                this.sale.articles[this.editedIndex].cant).toFixed(2)
      this.sale.articles[this.editedIndex].totalCant = parseFloat(parseFloat(this.sale.articles[this.editedIndex].inventory) -
                parseFloat(this.sale.articles[this.editedIndex].cant) || 0).toFixed(2)
      item.totalPrice = item.cant * item.price + this.articleTotalPrice(item) - this.articleTotalDiscount(item) + this.totalModifier(item)
      this.calcTotalSale()
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
    },
    calcTotalSale () {
      this.totalArticles = 0
      this.totalTax = 0
      this.totalDisc = 0
      this.totalPrice = 0
      this.subTotal = 0
      this.sale.articles.forEach((v) => {
        this.subTotal = parseFloat(v.totalPrice) + this.subTotal
        this.totalArticles += v.cant
      })
      this.sale.taxes.forEach((v) => {
        this.totalTax += v.percent === 'true' ? this.subTotal * v.value / 100 : v.value
      })
      this.sale.discounts.forEach((v) => {
        this.totalDisc += v.percent === 'true' ? this.subTotal * v.value / 100 : v.value
      })
      this.totalPrice = (this.subTotal + parseFloat(this.totalTax) - parseFloat(this.totalDisc)).toFixed(2)
      this.totalPrice = parseFloat(this.totalPrice).toFixed(2)
    },
    showModifyArticle ($event) {
      this.showModify = true
      this.articleSelected = $event
    },
    closeModify () {
      this.showModify = false
    },
    saveModify () {
      this.calcTotalArticle(this.articleSelected)
      this.showModify = false
    },
    showHelp () {
      this.$tours.saleManager.start()
    }
  }
}
</script>

<style>
/* This is for documentation purposes and will not be needed in your application */
#create .v-speed-dial {
    position: absolute;
}

#create .v-btn--floating {
    position: relative;
}
</style>
