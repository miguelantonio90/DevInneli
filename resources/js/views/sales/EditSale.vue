<template>
  <div class="page-add-sales">
    <app-loading v-show="loadingData" />
    <v-container
      v-if="!loadingData"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.titles.edit', [
              $vuetify.lang.t('$vuetify.sale.sale'),
            ])
          }}</span>
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
                      md="4"
                    >
                      <v-select
                        v-model="editSale.shop"
                        chips
                        rounded
                        disabled
                        solo
                        clearable
                        :items="shops"
                        :label="$vuetify.lang.t('$vuetify.menu.shop')"
                        item-text="name"
                        :loading="isShopLoading"
                        return-object
                        required
                        :rules="formRule.country"
                      />
                    </v-col>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="3"
                    >
                      <v-select
                        v-model="editSale.box"
                        disabled
                        :rules="formRule.country"
                        :items="localBoxes"
                        required
                        :label="$vuetify.lang.t('$vuetify.menu.box')"
                        item-text="name"
                        return-object
                      >
                        <template v-slot:append-outer>
                          <v-tooltip bottom>
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
                    </v-col>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="5"
                    >
                      <v-autocomplete
                        ref="selectArticle"
                        :disabled="!editSale.shop"
                        :hint="!editSale.shop ? $vuetify.lang.t('$vuetify.sale.selectShop') : localArticles.length > 0 ? $vuetify.lang.t('$vuetify.sale.selectArticle') : $vuetify.lang.t('$vuetify.sale.emptyArticle')"
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
                        :headers="getTableColumns"
                        :items="editSale.articles"
                        csv-filename="ProductBuys"
                        :options="vBindOption"
                        :sort-by="['name']"
                        :sort-desc="[false, true]"
                        multi-sort
                        :is-loading="isTableLoading"
                        @delete-row="deleteItem($event)"
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
                            @save="calcTotal(item)"
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
                        <template v-slot:[`item.discount`]="{ item }">
                          <v-autocomplete
                            v-model="item.discount"
                            chips
                            multiple
                            :items="localDiscounts"
                            item-text="name"
                            return-object
                            @input="total_pay(item)"
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
                        </template>
                        <template v-slot:[`item.cant`]="{ item }">
                          <v-edit-dialog
                            :return-value.sync="item.cant"
                            large
                            persistent
                            :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                            :save-text="$vuetify.lang.t('$vuetify.actions.save')"
                            @save="calcTotal(item)"
                          >
                            <div>{{ item.cant }}</div>
                            <template v-slot:input>
                              <div class="mt-4 title">
                                {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                              </div>
                              <v-text-field-integer
                                v-model="item.cant"
                                :label="$vuetify.lang.t('$vuetify.actions.save') "
                                :properties="{
                                  clearable: true,
                                }"
                                :options="{
                                  inputMask: '#########',
                                  outputMask: '#########',
                                  empty: 1,
                                  applyAfter: false,
                                }"
                              />
                            </template>
                          </v-edit-dialog>
                        </template>
                        <template v-slot:[`item.totalPrice`]="{ item }">
                          {{
                            `${user.company.currency + ' ' + parseFloat(item.totalPrice).toFixed(2)}`
                          }}
                        </template>
                        <template
                          v-slot:[`item.data-table-expand`]="{item, expand, isExpanded }"
                        >
                          <v-btn
                            v-show="item.taxes.length > 0"
                            color="primary"
                            fab
                            x-small
                            dark
                            @click="expand(!isExpanded)"
                          >
                            <v-icon v-if="isExpanded">
                              mdi-chevron-up
                            </v-icon>
                            <v-icon v-else>
                              mdi-chevron-down
                            </v-icon>
                          </v-btn>
                        </template>
                        <template
                          v-slot:expanded-item="{ headers, item }"
                        >
                          <template
                            v-show="item.taxes.length > 0"
                          >
                            <td
                              :colspan="headers.length"
                              style="padding: 0 0 0 0"
                            >
                              <v-simple-table dense>
                                <template v-slot:default>
                                  <thead>
                                    <tr>
                                      <th class="text-left">
                                        {{ $vuetify.lang.t('$vuetify.tax.name') }}
                                      </th>
                                      <th class="text-left">
                                        {{ $vuetify.lang.t('$vuetify.tax.value') }}
                                      </th>
                                      <th class="text-left">
                                        {{
                                          $vuetify.lang.t('$vuetify.tax.to_pay_tax')
                                        }}
                                      </th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr
                                      v-for="(tax) in item.taxes"
                                      :key="tax.name"
                                    >
                                      <td style="color: #0d47a1">
                                        {{ tax.name }}
                                      </td>
                                      <td style="color: #0d47a1">
                                        {{
                                          tax.percent ? tax.value + '%' : tax.value
                                        }}
                                      </td>
                                      <td style="color: #0d47a1">
                                        {{ `${user.company.currency}` }} {{
                                          tax.percent ? parseFloat(tax.value * item.cant * item.price / 100).toFixed(2) : parseFloat(tax.value).toFixed(2)
                                        }}
                                      </td>
                                    </tr>
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <td
                                        style="margin-left: 10px"
                                        colspan="2"
                                        class="text-left"
                                      >
                                        <b style="color: #db0610">{{
                                          $vuetify.lang.t('$vuetify.tax.total_pay_tax')
                                        }}</b>
                                      </td>
                                      <td>
                                        <b style="color: #db0610">
                                          {{ `${user.company.currency}` }} {{
                                            parseFloat(total_pay(item)).toFixed(2)
                                          }}</b>
                                      </td>
                                    </tr>
                                  </tfoot>
                                </template>
                              </v-simple-table>
                            </td>
                          </template>
                        </template>
                      </app-data-table>
                    </v-col>
                    <v-col
                      v-show="editSale.articles.length > 0 "
                      cols="12"
                      md="6"
                    />
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-col
                v-show="editSale.articles.length > 0"
                cols="12"
                md="7"
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
                      :edit="true"
                      :pays-show="editSale.pays"
                      :total="total.toString()"
                      @updateData="updateData"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-col>
              <v-col
                v-show="editSale.articles.length > 0"
                cols="12"
                md="5"
              >
                <v-expansion-panel
                  v-show="editSale.articles.length > 0"
                  cols="12"
                  md="6"
                >
                  <v-expansion-panel-header>
                    <div>
                      <v-icon>mdi-database-plus</v-icon>
                      <span style="text-transform: uppercase;font-weight: bold">
                        {{ $vuetify.lang.t('$vuetify.menu.resume') }}
                      </span>
                    </div>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <facture
                      :edit="true"
                      :update="update"
                      :currency="user.company.currency || ''"
                      :total="total.toString()"
                      :sub-total="sub_total.toString()"
                      :total-tax="totalTax.toString()"
                      :total-disc="totalDisc.toString()"
                      @updateData="update = false"
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
          <template v-if="editSale.pays.length === 0">
            <v-btn
              class="mb-2"
              color="success"
              :disabled="!formValid || isActionInProgress"
              :loading="isActionInProgress"
              @click="editSaleHandler('preform')"
            >
              <v-icon>mdi-calendar-clock</v-icon>
              {{ $vuetify.lang.t('$vuetify.sale.state.preform') }}
            </v-btn>
          </template>
          <template v-else>
            <v-btn
              v-show="editSale.state === 'open'"
              class="mb-2"
              color="success"
              :disabled="!formValid || isActionInProgress"
              :loading="isActionInProgress"
              @click="editSaleHandler('open')"
            >
              <v-icon>mdi-check</v-icon>
              {{ $vuetify.lang.t('$vuetify.sale.state.open') }}
            </v-btn>
            <v-btn
              class="mb-2"
              color="primary"
              :disabled="!formValid || isActionInProgress"
              :loading="isActionInProgress"
              @click="editSaleHandler('accepted')"
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
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import ExtraData from './ExtraData'
import AppLoading from '../../components/core/AppLoading'
import Facture from './Facture'
import NewDiscount from '../discount/NewDiscount'
import NewBox from '../boxes/NewBox'

export default {
  name: 'EditSale',
  components: { NewDiscount, AppLoading, Facture, ExtraData, NewBox },
  data () {
    return {
      loadingData: false,
      editedIndex: -1,
      localArticles: [],
      localBoxes: [],
      totalTax: 0,
      totalDisc: 0,
      sub_total: 0,
      total: 0,
      localDiscounts: [],
      update: false,
      panel: [0, 1, 2],
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
    ...mapState('sale', ['editSale', 'isActionInProgress']),
    ...mapState('article', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'articles',
      'isTableLoading'
    ]),
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapState('discount', ['discounts']),
    ...mapGetters('auth', ['user']),
    ...mapState('boxes', ['boxes', 'showNewModal']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.articles.ref'),
          value: 'ref',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.price'),
          value: 'price',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.discount'),
          value: 'discount',
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
    }
  },
  watch: {
    discounts: function () {
      this.getLocalDiscounts()
    },
    boxes: function () {
      this.getLocalBoxes()
    },
    'editSale.taxes' () {
      this.updateData()
    },
    'editSale.articles' () {
      this.updateData()
    },
    'editSale.discounts' () {
      this.updateData()
    }
  },
  async mounted () {
    await this.editSale.articles.forEach((v) => {
      this.calcTotal(v)
    })
  },
  async created () {
    this.loadingData = true
    await this.getArticles()
    await this.getShops()
    await this.getDiscounts().then(() => {
      this.getLocalDiscounts()
    })
    await this.getBoxes()
    await this.updateDataArticle()
    this.loadingData = false
  },
  methods: {
    ...mapActions('boxes', ['toogleNewModal', 'getBoxes']),
    ...mapActions('sale', ['updateSale']),
    ...mapActions('article', ['getArticles']),
    ...mapActions('shop', ['getShops']),
    ...mapActions('discount', ['getDiscounts']),
    async updateDataArticle () {
      this.localArticles = []
      if (this.editSale.shop) {
        await this.articles.forEach((value) => {
          if (!value.parent_id) {
            let inventory = 0
            if (value.variant_values.length > 0) {
              value.variant_values.forEach((v) => {
                inventory = 0
                const artS = v.articles_shops.filter(artS => artS.shop_id === this.editSale.shop.shop_id)
                if (artS.length > 0) {
                  inventory = artS[0].stock
                }
                if ((inventory > 0 || !value.track_inventory) && artS.length > 0) {
                  this.localArticles.push({
                    ref: value.ref,
                    name: value.name + '(' + v.name + ')',
                    category: !value.category ? '' : value.category.name,
                    path: value.path,
                    images: value.images,
                    taxes: v.tax,
                    discount: [],
                    color: value.color,
                    price: artS[0].price ? artS[0].price : 0,
                    cost: v.cost ? v.cost : 0,
                    inventory: inventory || 0,
                    cant: 1,
                    totalCant: (inventory || 0) + 1,
                    totalCost: v.cost,
                    totalPrice: v.price,
                    article_id: v.id
                  })
                }
              })
            } else {
              const artS = value.articles_shops.filter(artS => artS.shop_id === this.editSale.shop.shop_id)
              if (artS.length > 0) {
                inventory = artS[0].stock
              }
              if ((inventory > 0 || !value.track_inventory) && artS.length > 0) {
                this.localArticles.push({
                  ref: value.ref,
                  name: value.name,
                  category: !value.category ? '' : value.category.name,
                  path: value.path,
                  images: value.images,
                  taxes: value.tax,
                  discount: [],
                  color: value.color,
                  price: artS[0].price ? artS[0].price : 0,
                  cost: value.cost ? value.cost : 0,
                  inventory: inventory || 0,
                  cant: 1,
                  totalCant: (inventory || 0) + 1,
                  totalCost: value.cost,
                  totalPrice: value.price,
                  article_id: value.id
                })
              }
            }
          }
        })
      }
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
    getLocalBoxes () {
      this.localBoxes = []
      this.localBoxes = this.boxes.filter(bx => bx.shop_id === this.editSale.shop.id)
    },
    calcTotal: function (item) {
      this.editedIndex = this.editSale.articles.indexOf(item)
      this.editSale.articles[this.editedIndex].totalCost = parseFloat(item.cost * item.cant).toFixed(2)
      const total = parseFloat(item.inventory) - parseFloat(item.cant) || 0
      this.editSale.articles[this.editedIndex].totalCant = parseFloat(total).toFixed(2)
      this.total_pay(item)
      this.update = true
      this.updateData()
    },
    total_pay (item) {
      let suma = 0
      if (item.taxes.length > 0) {
        item.taxes.forEach((v) => {
          suma += v.percent ? item.cant * item.price * v.value / 100 : v.value
        })
      }
      item.totalPrice = item.cant * item.price + suma - this.total_discount(item)
      this.update = true
      this.updateData()
      return suma
    },
    total_discount (item) {
      let disc = 0
      if (item.discount.length > 0) {
        item.discount.forEach((v) => {
          disc += v.percent ? item.cant * item.price * v.value / 100 : v.value
        })
      }
      return disc
    },
    selectArticle (item) {
      if (item) {
        if (this.editSale.articles.filter(art => art.article_id === item.article_id).length === 0) {
          this.editSale.articles.push(item)
        } else {
          this.showInfoAdd = true
        }
      }
    },
    deleteItem (item) {
      this.editSale.articles.splice(this.editSale.articles.indexOf(item), 1)
      this.update = true
    },
    closeInfoAdd () {
      this.showInfoAdd = false
    },
    async editSaleHandler (state) {
      if (this.editSale.articles.length > 0) {
        if (this.$refs.form.validate()) {
          this.loading = true
          this.editSale.state = state
          await this.updateSale(this.editSale)
          await this.$router.push({ name: 'vending' })
        }
      } else {
        this.shopMessageError(this.$vuetify.lang.t(
          '$vuetify.messages.warning_cant_article'
        ))
      }
    },
    shopMessageError (message) {
      this.$Swal.fire({
        title: this.$vuetify.lang.t('$vuetify.titles.edit', [
          this.$vuetify.lang.t('$vuetify.menu.vending')
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
      this.editSale.articles = []
      this.$router.push({ name: 'vending' })
    },
    updateData () {
      this.totalTax = 0
      this.totalDisc = 0
      this.total = 0
      this.sub_total = 0
      this.editSale.articles.forEach((v) => {
        this.sub_total = parseFloat(v.totalPrice) + this.sub_total
      })
      this.taxes = this.edit ? this.editSale.taxes
        : this.editSale.taxes.forEach((v) => {
          this.totalTax += v.percent === 'true' ? this.sub_total * v.value / 100 : v.value
        })
      this.editSale.discounts.forEach((v) => {
        this.totalDisc += v.percent === 'true' ? this.sub_total * v.value / 100 : v.value
      })
      this.total = (this.sub_total + parseFloat(this.totalTax) - parseFloat(this.totalDisc)).toFixed(2)
      this.total = parseFloat(this.total).toFixed(2)
      this.update = true
    }
  }
}
</script>

<style scoped>

</style>
