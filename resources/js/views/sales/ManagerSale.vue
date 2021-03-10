<template>
  <div class="page-add-sales">
    <app-loading v-show="loadingData" />
    <v-dialog
      v-model="showDiscount"
      max-width="400"
      persistent
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{ $vuetify.lang.t('$vuetify.access.access.manager_discount') }}</span>
        </v-card-title>
        <v-card-text>
          <v-autocomplete
            v-model="articleSelected.discount"
            chips
            multiple
            :items="localDiscounts"
            item-text="name"
            return-object
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
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            class="mb-2"
            @click="closeDiscount"
          >
            <v-icon>mdi-close</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
            @click="saveDiscount()"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.accept') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog
      v-model="showModify"
      max-width="400"
      persistent
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{ $vuetify.lang.t('$vuetify.access.access.manager_mod') }}</span>
        </v-card-title>
        <v-card-text>
          <v-autocomplete
            v-model="articleSelected.modifiers"
            chips
            multiple
            :items="localModifiers"
            item-text="name"
            return-object
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
        <v-card-actions>
          <v-spacer />
          <v-btn
            class="mb-2"
            @click="closeModify"
          >
            <v-icon>mdi-close</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
            @click="saveModify()"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.accept') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-container
      v-if="!loadingData"
    >
      <v-card>
        <v-card-title>
          <v-app-bar
            flat
            dense
            color="rgba(0, 0, 0, 0)"
          >
            {{
              $vuetify.lang.t(managerSale? '$vuetify.titles.edit' : '$vuetify.titles.newF', [
                $vuetify.lang.t('$vuetify.sale.sale'),
              ])
            }}
            <v-spacer />
            <v-tooltip
              bottom
            >
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  color="teal darken-2"
                  dark
                  v-bind="attrs"
                  icon
                  medium
                  v-on="on"
                  @click="showHelp"
                >
                  <v-icon>mdi-help</v-icon>
                </v-btn>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.guide') }}</span>
            </v-tooltip>
          </v-app-bar>
        </v-card-title>
        <v-card-text>
          <v-form
            ref="form"
            v-model="formValid"
            style="padding: 0"
            lazy-validation
          >
            <v-expansion-panels
              v-model="panel"
              style="margin: 0"
              multiple
            >
              <v-expansion-panel style="margin: 0">
                <v-expansion-panel-header>
                  <div>
                    <v-icon>mdi-database-edit</v-icon>
                    <span style="text-transform: uppercase;font-weight: bold">
                      {{ $vuetify.lang.t('$vuetify.panel.basic') }}
                    </span>
                  </div>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-row>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="3"
                    >
                      <v-select
                        id="slc_shop"
                        v-model="sale.shop"
                        chips
                        rounded
                        :disabled="managerSale"
                        solo
                        :items="shops"
                        :label="$vuetify.lang.t('$vuetify.menu.shop')"
                        item-text="name"
                        :loading="isShopLoading"
                        return-object
                        required
                        :rules="formRule.country"
                        @change="updateDataArticle"
                      />
                    </v-col>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="3"
                    >
                      <v-switch
                        v-model="sale.online"
                        :disabled="managerSale"
                        :title="$vuetify.lang.t('$vuetify.articles.online_text')"
                      >
                        <template v-slot:label>
                          <div>
                            {{ $vuetify.lang.t('$vuetify.articles.onlineSale') }}
                            <v-tooltip
                              right
                              class="md-6"
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <v-icon
                                  color="primary"
                                  dark
                                  v-bind="attrs"
                                  v-on="on"
                                >
                                  mdi-information-outline
                                </v-icon>
                              </template>
                              <span>{{
                                $vuetify.lang.t('$vuetify.sale.online_text')
                              }}</span>
                            </v-tooltip>
                          </div>
                        </template>
                      </v-switch>
                    </v-col>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="2"
                    >
                      <v-select
                        v-if="!sale.online"
                        id="slc_box"
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
                      </v-select>
                    </v-col>
                    <v-col
                      class="py-0"
                      cols="12"
                      :md="sale.online?'5':'4'"
                    >
                      <v-autocomplete
                        id="slc_article"
                        ref="selectArticle"
                        :disabled="!sale.shop"
                        :hint="!sale.shop ? $vuetify.lang.t('$vuetify.sale.selectShop') : localArticles.length > 0 ? $vuetify.lang.t('$vuetify.sale.selectArticle') : $vuetify.lang.t('$vuetify.sale.emptyArticle')"
                        persistent-hint
                        chips
                        :label="$vuetify.lang.t('$vuetify.menu.articles')"
                        :items="localArticles"
                        item-text="name"
                        return-object
                        @input="selectArticle"
                      >
                        <template v-slot:selection="data">
                          <v-chip
                            style="max-width: 160px"
                            v-bind="data.attrs"
                            :input-value="data.selected"
                            @click="data.select"
                          >
                            <v-avatar
                              v-if="data.item.color && data.item.images.length === 0"
                              class="white--text"
                              :color="data.item.color"
                              left
                              v-text="data.item.name.slice(0, 1).toUpperCase()"
                            />
                            <v-avatar
                              v-else
                              left
                            >
                              <v-img :src="data.item.path" />
                            </v-avatar>
                            {{ data.item.name }}
                          </v-chip>
                        </template>
                        <template v-slot:item="data">
                          <template>
                            <v-list-item-avatar>
                              <v-avatar
                                v-if="data.item.color && data.item.images.length === 0"
                                class="white--text"
                                :color="data.item.color"
                                left
                                v-text="data.item.name.slice(0, 1).toUpperCase()"
                              />
                              <v-avatar
                                v-else
                                left
                              >
                                <v-img :src="data.item.path" />
                              </v-avatar>
                            </v-list-item-avatar>
                            <v-list-item-content>
                              <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                              <v-list-item-subtitle>
                                {{ `${user.company.currency + ' ' + data.item.price}` }}
                              </v-list-item-subtitle>
                            </v-list-item-content>
                          </template>
                        </template>
                      </v-autocomplete>
                    </v-col>
                    <v-col
                      cols="12"
                      md="12"
                    >
                      <app-data-table
                        :view-show-filter="false"
                        :view-edit-button="false"
                        :view-new-button="false"
                        :view-tour-button="false"
                        :view-mod-button="true"
                        :view-discount-button="true"
                        :headers="getTableColumns"
                        :items="sale.articles"
                        csv-filename="ProductBuys"
                        :sort-by="['name']"
                        :sort-desc="[false, true]"
                        multi-sort
                        :is-loading="isTableLoading"
                        @delete-row="deleteItem($event)"
                        @manager-modify-row="showModifyArticle($event)"
                        @manager-discount-row="showDiscountArticle($event)"
                      >
                        <template v-slot:[`item.name`]="{ item }">
                          <v-chip
                            :key="JSON.stringify(item)"
                          >
                            <v-avatar
                              v-if="item.color && item.images.length === 0"
                              class="white--text"
                              :color="item.color"
                              left
                              v-text="item.name.slice(0, 1).toUpperCase()"
                            />
                            <v-avatar
                              v-else
                              left
                            >
                              <v-img :src="item.path" />
                            </v-avatar>
                            {{ item.name }}
                          </v-chip>
                        </template>
                        <template v-slot:[`item.price`]="{ item }">
                          <v-edit-dialog
                            :return-value.sync="item.price"
                            large
                            persistent
                            :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                            :save-text="$vuetify.lang.t('$vuetify.actions.save')"
                            @save="calcTotalArticle(item)"
                          >
                            <div>{{ `${user.company.currency + ' ' + item.price}` }}</div>
                            <template v-slot:input>
                              <div class="mt-4 title">
                                {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                              </div>
                              <v-text-field-money
                                v-model="item.price"
                                :label="$vuetify.lang.t('$vuetify.actions.edit')"
                                required
                                :properties="{
                                  clearable: true
                                }"
                                :options="{
                                  length: 15,
                                  precision: 2,
                                  empty: 0.00,
                                }"
                              />
                            </template>
                          </v-edit-dialog>
                        </template>
                        <template v-slot:[`item.cant`]="{ item }">
                          <v-edit-dialog
                            :return-value.sync="item.cant"
                            large
                            persistent
                            :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                            :save-text="$vuetify.lang.t('$vuetify.actions.save')"
                            @save="calcTotalArticle(item)"
                          >
                            <div>{{ item.cant }}</div>
                            <template v-slot:input>
                              <div class="mt-4 title">
                                {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                              </div>
                              <v-text-field-money
                                v-model="item.cant"
                                :label="$vuetify.lang.t('$vuetify.actions.save') "
                                :properties="{
                                  clearable: true,
                                }"
                                :options="{
                                  length: 15,
                                  precision: 2,
                                  empty: 0.00,
                                }"
                              />
                            </template>
                          </v-edit-dialog>
                        </template>
                        <template v-slot:[`item.totalPrice`]="{ item }">
                          <template>
                            <v-tooltip
                              v-show="item.taxes.length > 0 || item.discount.length > 0 "
                              bottom
                            >
                              <template v-slot:activator="{ on, attrs }">
                                <b><v-icon
                                  class="mr-2"
                                  small
                                  v-bind="attrs"
                                  v-on="on"
                                >
                                  mdi-information
                                </v-icon></b>
                              </template>
                              <template>
                                <detail-article-price
                                  :article="item"
                                  :currency="user.company.currency"
                                />
                              </template>
                              <span
                                v-if="item.totalRefund > 0"
                              >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + item.totalRefund}` }}</span>
                            </v-tooltip>
                          </template>
                          {{ `${user.company.currency + ' ' + parseFloat(item.totalPrice).toFixed(2)}` }}
                        </template>
                      </app-data-table>
                    </v-col>
                    <v-col
                      v-show="sale.articles.length > 0 "
                      cols="12"
                      md="6"
                    />
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-col
                v-show="sale.articles.length > 0"
                cols="12"
                md="12"
              >
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    <div>
                      <v-icon>mdi-database-plus</v-icon>
                      <span style="text-transform: uppercase;font-weight: bold">
                        {{ $vuetify.lang.t('$vuetify.pay.extra_data') }}
                      </span>
                    </div>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <extra-data
                      :edit="managerSale"
                      :sale="sale"
                      :total-price="parseFloat(totalPrice).toFixed(2)"
                      :total-tax="parseFloat(totalTax).toFixed(2)"
                      :total-discount="parseFloat(totalDisc).toFixed(2)"
                      :sub-total="parseFloat(subTotal).toFixed(2)"
                      @updateData="calcTotalSale"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-col>
            </v-expansion-panels>
          </v-form>
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
              :disabled="!formValid || isActionInProgress"
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
      </v-card>
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
    </v-container>
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

export default {
  name: 'ManagerSale',
  components: {
    DetailArticlePrice,
    NewBox,
    NewDiscount,
    NewModifiers,
    ExtraData
  },
  data () {
    return {
      showDiscount: false,
      showModify: false,
      sale: {},
      articleSelected: {},
      loadingData: false,
      editedIndex: -1,
      localArticles: [],
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
    ...mapGetters('auth', ['user']),
    ...mapGetters('sale', ['getNumberFacture']),
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
        target: '#btn_add_box',
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
    await this.getModifiers().then(() => {
      this.getLocalModifiers()
    })
    this.loadingData = false
    await this.getBoxes()
    console.log('aaaaaaaa')
    await this.updateDataArticle()
    if (!this.managerSale) {
      await this.fetchSaleNumber().then(() => {
        this.sale.no_facture = this.generateNF()
      })
    }
    this.loadingData = false
  },
  methods: {
    ...mapActions('boxes', ['toogleNewModal', 'getBoxes']),
    ...mapActions('inventory', ['getInventories']),
    ...mapActions('article', ['getArticles']),
    ...mapActions('shop', ['getShops']),
    ...mapActions('sale', ['getSales', 'createSale', 'updateSale', 'fetchSaleNumber']),
    ...mapActions('discount', ['getDiscounts']),
    ...mapActions('modifiers', ['getModifiers']),
    generateNF () {
      const seqer = utils.serialMaker()
      seqer.set_prefix('F' + new Date().getFullYear() + '-')
      seqer.set_seq(this.factureNumber)
      return seqer.gensym()
    },
    async updateDataArticle () {
      this.sale.articles = []
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
        path: value.path,
        images: value.images,
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
        this.localBoxes = this.boxes[this.sale.shop.id].boxes
        if (this.localBoxes.length > 0) {
          this.sale.box = this.localBoxes[0]
        }
      }
    },
    selectArticle (item) {
      if (item) {
        if (this.sale.articles.filter(art => art.article_id === item.article_id).length === 0) {
          this.sale.articles.push(item)
          this.calcTotalSale()
        } else {
          this.showInfoAdd = true
        }
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
      this.totalTax = 0
      this.totalDisc = 0
      this.totalPrice = 0
      this.subTotal = 0
      this.sale.articles.forEach((v) => {
        this.subTotal = parseFloat(v.totalPrice) + this.subTotal
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
    showDiscountArticle ($event) {
      this.showDiscount = true
      this.articleSelected = $event
    },
    closeDiscount () {
      this.showDiscount = false
    },
    saveDiscount () {
      this.calcTotalArticle(this.articleSelected)
      this.showDiscount = false
    },
    showHelp () {
      this.$tours.saleManager.start()
    }
  }
}
</script>

  <style scoped />
