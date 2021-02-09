<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-refound
          v-if="showNewModal"
        />
        <v-tabs
          v-model="tab"
          style="margin-top: 10px"
          fixed-tabs
        >
          <v-tabs-slider />

          <v-tab href="#tab-1">
            {{ $vuetify.lang.t('$vuetify.supply.emit') }}
          </v-tab>

          <v-tab href="#tab-2">
            {{ $vuetify.lang.t('$vuetify.supply.received') }}
          </v-tab>
        </v-tabs>

        <v-tabs-items v-model="tab">
          <v-tab-item :value="'tab-1'">
            <app-data-table
              :title="$vuetify.lang.t('$vuetify.titles.list',
                                      [$vuetify.lang.t('$vuetify.supply.emit')])"
              :headers="getTableColumns"
              csv-filename="SupplyProducts"
              :manager="'vending'"
              :items="supplies"
              :options="vBindOption"
              :sort-by="['no_facture']"
              :sort-desc="[false, true]"
              multi-sort
              :is-loading="isTableLoading"
              @create-row="createSupplyHandler"
              @edit-row="editSupplyHandler($event)"
              @delete-row="deleteSupplyHandler($event)"
            >
              <template v-slot:[`item.state`]="{ item }">
                <template v-if="item.state.name !== 'cancelled'">
                  <v-autocomplete
                    v-model="item.state"
                    chips
                    item-text="name"
                    :items="item.state.next"
                    :style="{'width':'160px'}"
                    auto-select-first
                    return-object
                    @input="changeStateHandler(item)"
                  >
                    <template v-slot:selection="data">
                      <v-chip
                        v-bind="data.attrs"
                        :input-value="data.item.value"
                        @click="data.select"
                      >
                        <i>
                          {{ $vuetify.lang.t('$vuetify.supply_state.state.' + data.item.name) }}</i>
                      </v-chip>
                    </template>
                    <template v-slot:item="data">
                      <template v-if="typeof data.item !== 'object'">
                        <v-list-item-content v-text="$vuetify.lang.t('$vuetify.supply_state.state.' + data.item.name)" />
                      </template>
                      <template v-else>
                        <v-list-item-content>
                          <v-list-item-title>
                            {{ $vuetify.lang.t('$vuetify.supply_state.state.' + data.item.name) }}
                          </v-list-item-title>
                        </v-list-item-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </template>
                <template v-else>
                  <v-chip>
                    {{ $vuetify.lang.t('$vuetify.supply_state.state.' + item.state.name) }}
                  </v-chip>
                </template>
              </template>
              <template v-slot:[`item.to`]="{ item }">
                {{ item.to.name }} <br>({{ item.to.email }})
              </template>
              <template v-slot:[`item.sale.shop.name`]="{ item }">
                {{ item.sale.shop.name }}
              </template>
              <template v-slot:[`item.sale.totalCost`]="{ item }">
                <template>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <b><v-icon
                        :style="item.totalRefund > 0?'color: red': 'color:primary'"
                        class="mr-2"
                        small
                        v-bind="attrs"
                        v-on="on"
                      >
                        mdi-information
                      </v-icon></b>
                    </template>
                    <template>
                      <list-pay
                        :show="false"
                        :sale="item"
                        :total-price="parseFloat(item.sale.totalCost).toFixed(2).toString()"
                        :total-tax="parseFloat(item.sale.totalTax).toFixed(2)"
                        :total-discount="parseFloat(item.sale.totalDisc).toFixed(2)"
                        :sub-total="parseFloat(item.sale.subTotal).toFixed(2)"
                        :currency="user.company.currency"
                      />
                    </template>
                    <span
                      v-if="item.totalRefund > 0"
                    >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + item.sale.totalRefund}` }}</span>
                  </v-tooltip>
                </template>
                {{ `${user.company.currency + ' ' + parseFloat(item.sale.totalCost).toFixed(2)}` }}
              </template>
              <template v-slot:[`item.data-table-expand`]="{item, expand, isExpanded }">
                <v-btn
                  v-if="item.sale.articles.length > 0"
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
              <template v-slot:expanded-item="{ headers,item }">
                <td
                  :colspan="headers.length"
                  style="padding: 0 0 0 0"
                >
                  <v-simple-table dense>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.articles.ref') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.firstName') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.variants.cant') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.articles.cost') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.variants.total_cost') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.articles.new_inventory') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.actions.actions') }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="article in item.sale.articles"
                          :key="article.id"
                        >
                          <td>
                            <template v-if="article.refounds.length > 0">
                              <v-tooltip right>
                                <template v-slot:activator="{ on, attrs }">
                                  <b><v-icon
                                    v-if="article.cant > 0"
                                    style="color: red"
                                    class="mr-2"
                                    small
                                    v-bind="attrs"
                                    v-on="on"
                                  >
                                    mdi-information
                                  </v-icon></b>
                                </template>
                                <template>
                                  <detail-refund
                                    :article="article"
                                    :currency="`${user.company.currency}`"
                                  />
                                </template>
                              </v-tooltip>
                            </template>
                            {{ article.ref }}
                          </td>
                          <td>{{ article.name }}</td>
                          <td>
                            <v-tooltip top>
                              <template v-slot:activator="{ on, attrs }">
                                <b><v-icon
                                  v-if="article.cantRefund > 0"
                                  style="color: red"
                                  class="mr-2"
                                  small
                                  v-bind="attrs"
                                  v-on="on"
                                >
                                  mdi-information
                                </v-icon></b>
                              </template>
                              <span>{{ $vuetify.lang.t('$vuetify.menu.refund')+': ' + article.cantRefund + ' ' + $vuetify.lang.t('$vuetify.menu.articles') }}</span>
                            </v-tooltip>
                            {{ article.cant }}
                          </td>
                          <td>{{ `${user.company.currency + ' ' + article.cost}` }}</td>
                          <td>
                            <template
                              v-if="article.taxes.length > 0 || article.discount.length > 0"
                            >
                              <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                  <b><v-icon
                                    :color="article.moneyRefund > 0 ? 'red':'primary'"
                                    class="mr-2"
                                    small
                                    v-bind="attrs"
                                    v-on="on"
                                  >
                                    mdi-information
                                  </v-icon></b>
                                </template>
                                <template>
                                  <detail-article-cost
                                    :article="article"
                                    :currency="user.company.currency"
                                  />
                                  <span
                                    v-if="article.moneyRefund > 0"
                                  >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + article.moneyRefund}` }}</span>
                                </template>
                              </v-tooltip>
                            </template>
                            <span>{{ `${user.company.currency + ' ' + parseFloat(article.totalCost).toFixed(2)}` }}</span>
                          </td>
                          <td>
                            <template v-if="article.inventory > 0">
                              {{ article.inventory }}
                            </template>
                            <template v-else>
                              <i style="color: red">
                                <v-icon style="color: red">
                                  mdi-arrow-down-bold-circle
                                </v-icon>
                                {{ article.inventory }}
                              </i>
                            </template>
                          </td>
                          <td>
                            <template>
                              <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                  <b><v-icon
                                    style="color: #ff752b"
                                    class="mr-2"
                                    small
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="refundArticle(item, article)"
                                  >
                                    mdi-undo
                                  </v-icon></b>
                                </template>
                                <span>{{ $vuetify.lang.t('$vuetify.actions.refund') }}</span>
                              </v-tooltip>
                            </template>
                          </td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </td>
              </template>
            </app-data-table>
          </v-tab-item>
        </v-tabs-items>

        <v-tabs-items v-model="tab">
          <v-tab-item :value="'tab-2'">
            <app-data-table
              :view-new-button="false"
              :title="$vuetify.lang.t('$vuetify.titles.list',
                                      [$vuetify.lang.t('$vuetify.supply.received')])"
              :headers="getRTableColumns"
              csv-filename="SupplyProducts"
              :manager="'vending'"
              :items="received"
              :options="vBindOption"
              :sort-by="['no_facture']"
              :sort-desc="[false, true]"
              multi-sort
              :is-loading="isTableLoading"
              @create-row="createSupplyHandler"
              @edit-row="editSupplyHandler($event)"
              @delete-row="deleteSupplyHandler($event)"
            >
              <template v-slot:item.state.name="{ item }">
                <v-autocomplete
                  v-model="item.state"
                  :items="item.state.next"
                  auto-select-first
                  :style="{'width':'160px'}"
                >
                  <template v-slot:selection="data">
                    <v-chip
                      v-bind="data.attrs"
                      :input-value="data.item.value"
                      @click="data.select"
                    >
                      {{ data.item.name }}
                    </v-chip>
                  </template>
                </v-autocomplete>
              </template>
              <template v-slot:[`item.to`]="{ item }">
                {{ item.to.name }} <br>({{ item.to.email }})
              </template>
              <template v-slot:[`item.sale.shop.name`]="{ item }">
                {{ item.sale.shop.name }}
              </template>
              <template v-slot:[`item.sale.totalCost`]="{ item }">
                <template>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <b><v-icon
                        :style="item.totalRefund > 0?'color: red': 'color:primary'"
                        class="mr-2"
                        small
                        v-bind="attrs"
                        v-on="on"
                      >
                        mdi-information
                      </v-icon></b>
                    </template>
                    <template>
                      <list-pay
                        :show="false"
                        :sale="item"
                        :total-price="parseFloat(item.sale.totalCost).toFixed(2).toString()"
                        :total-tax="parseFloat(item.sale.totalTax).toFixed(2)"
                        :total-discount="parseFloat(item.sale.totalDisc).toFixed(2)"
                        :sub-total="parseFloat(item.sale.subTotal).toFixed(2)"
                        :currency="user.company.currency"
                      />
                    </template>
                    <span
                      v-if="item.totalRefund > 0"
                    >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + item.sale.totalRefund}` }}</span>
                  </v-tooltip>
                </template>
                {{ `${user.company.currency + ' ' + parseFloat(item.sale.totalCost).toFixed(2)}` }}
              </template>
              <template v-slot:[`item.data-table-expand`]="{item, expand, isExpanded }">
                <v-btn
                  v-if="item.sale.articles.length > 0"
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
              <template v-slot:expanded-item="{ headers,item }">
                <td
                  :colspan="headers.length"
                  style="padding: 0 0 0 0"
                >
                  <v-simple-table dense>
                    <template v-slot:default>
                      <thead>
                        <tr>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.articles.ref') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.firstName') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.variants.cant') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.articles.cost') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.variants.total_cost') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.articles.new_inventory') }}
                          </th>
                          <th class="text-left">
                            {{ $vuetify.lang.t('$vuetify.actions.actions') }}
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          v-for="article in item.sale.articles"
                          :key="article.id"
                        >
                          <td>
                            <template v-if="article.refounds.length > 0">
                              <v-tooltip right>
                                <template v-slot:activator="{ on, attrs }">
                                  <b><v-icon
                                    v-if="article.cant > 0"
                                    style="color: red"
                                    class="mr-2"
                                    small
                                    v-bind="attrs"
                                    v-on="on"
                                  >
                                    mdi-information
                                  </v-icon></b>
                                </template>
                                <template>
                                  <detail-refund
                                    :article="article"
                                    :currency="`${user.company.currency}`"
                                  />
                                </template>
                              </v-tooltip>
                            </template>
                            {{ article.ref }}
                          </td>
                          <td>{{ article.name }}</td>
                          <td>
                            <v-tooltip top>
                              <template v-slot:activator="{ on, attrs }">
                                <b><v-icon
                                  v-if="article.cantRefund > 0"
                                  style="color: red"
                                  class="mr-2"
                                  small
                                  v-bind="attrs"
                                  v-on="on"
                                >
                                  mdi-information
                                </v-icon></b>
                              </template>
                              <span>{{ $vuetify.lang.t('$vuetify.menu.refund')+': ' + article.cantRefund + ' ' + $vuetify.lang.t('$vuetify.menu.articles') }}</span>
                            </v-tooltip>
                            {{ article.cant }}
                          </td>
                          <td>{{ `${user.company.currency + ' ' + article.cost}` }}</td>
                          <td>
                            <template
                              v-if="article.taxes.length > 0 || article.discount.length > 0"
                            >
                              <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                  <b><v-icon
                                    :color="article.moneyRefund > 0 ? 'red':'primary'"
                                    class="mr-2"
                                    small
                                    v-bind="attrs"
                                    v-on="on"
                                  >
                                    mdi-information
                                  </v-icon></b>
                                </template>
                                <template>
                                  <detail-article-cost
                                    :article="article"
                                    :currency="user.company.currency"
                                  />
                                  <span
                                    v-if="article.moneyRefund > 0"
                                  >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + article.moneyRefund}` }}</span>
                                </template>
                              </v-tooltip>
                            </template>
                            <span>{{ `${user.company.currency + ' ' + parseFloat(article.totalCost).toFixed(2)}` }}</span>
                          </td>
                          <td>
                            <template v-if="article.inventory > 0">
                              {{ article.inventory }}
                            </template>
                            <template v-else>
                              <i style="color: red">
                                <v-icon style="color: red">
                                  mdi-arrow-down-bold-circle
                                </v-icon>
                                {{ article.inventory }}
                              </i>
                            </template>
                          </td>
                          <td>
                            <template>
                              <v-tooltip top>
                                <template v-slot:activator="{ on, attrs }">
                                  <b><v-icon
                                    style="color: #ff752b"
                                    class="mr-2"
                                    small
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="refundArticle(item, article)"
                                  >
                                    mdi-undo
                                  </v-icon></b>
                                </template>
                                <span>{{ $vuetify.lang.t('$vuetify.actions.refund') }}</span>
                              </v-tooltip>
                            </template>
                          </td>
                        </tr>
                      </tbody>
                    </template>
                  </v-simple-table>
                </td>
              </template>
            </app-data-table>
          </v-tab-item>
        </v-tabs-items>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import NewRefound from '../refund/NewRefound'
import DetailRefund from '../refund/DetailRefund'
import ListPay from '../pay/ListPay'
import DetailArticleCost from './DetailArticleCost'
export default {
  name: 'ListSupply',
  components: { DetailRefund, NewRefound, ListPay, DetailArticleCost },
  data () {
    return {
      tab: null,
      menu: false,
      fav: true,
      message: false,
      hints: true,
      search: '',
      localAccess: {},
      vBindOption: {
        itemKey: 'no_facture',
        singleExpand: false,
        showExpand: true
      }
    }
  },
  computed: {
    ...mapState('supply', ['showNewModal', 'showEditModal', 'loading', 'showShowModal', 'supplies', 'received', 'isTableLoading']),
    ...mapState('supplier', ['clientSuppliers']),
    ...mapState('article', ['articles']),
    ...mapState('refund', ['showNewModal']),
    ...mapGetters('auth', ['user', 'access_permit']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.supply_state.state.name'),
          value: 'state',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.to'),
          value: 'to',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.noFacture'),
          value: 'sale.no_facture',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.total_cost'),
          value: 'sale.totalCost',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'sale.shop.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.actions.actions'),
          value: 'actions',
          sortable: false
        }
      ]
    },
    getRTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.supply_state.state.name'),
          value: 'state.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.fromData'),
          value: 'from.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.noFacture'),
          value: 'sale.no_facture',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.total_cost'),
          value: 'sale.totalCost',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'sale.shop.name',
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
    loadData: function () {
      if (this.loadData === true) { this.loadInitData() }
    }
  },
  created () {
    this.getClientSupplier()
    this.loadInitData()
  },
  methods: {
    ...mapActions('supply', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getSupplies',
      'updateSupply',
      'deleteSupply'
    ]),
    ...mapActions('supplier', ['getClientSupplier']),
    ...mapActions('article', ['getArticles']),
    ...mapActions('refund', ['openNewModal']),
    async loadInitData () {
      await this.getArticles()
      await this.getSupplies()
    },
    refundArticle (supply, article) {
      if (article.cant === article.cantRefund && this.total_pay(article) === article.moneyRefund) {
        this.$Swal.fire({
          title: this.$vuetify.lang.t('$vuetify.actions.refund', [
            this.$vuetify.lang.t('$vuetify.menu.articles')
          ]),
          text: this.$vuetify.lang.t('$vuetify.messages.warning_refund_all'),
          icon: 'warning',
          showCancelButton: false,
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
      } else {
        this.openNewModal({ supply, article })
      }
    },
    cancelProductPreform (item, art) {
    },
    total_pay (item) {
      let sum = 0
      item.taxes.forEach((v) => {
        sum += v.percent ? item.cant * item.cost * v.value / 100 : v.value
      })
      let discount = 0
      item.discount.forEach((v) => {
        discount += v.percent ? item.cant * item.cost * v.value / 100 : v.value
      })
      return item.cant * item.cost + sum - discount - item.moneyRefund
    },
    createSupplyHandler () {
      if (this.articles.length === 0) {
        this.showMessage(true, this.$vuetify.lang.t(
          '$vuetify.messages.warning_no_article'
        ))
      } else if (this.clientSuppliers.length === 0) {
        this.showMessage(true, this.$vuetify.lang.t(
          '$vuetify.messages.warning_no_clients_supplier'
        ))
      } else {
        this.$store.state.supply.managerSupply = false
        this.$router.push({ name: 'supply_add' })
      }
    },
    editSupplyHandler ($event) {
      const supply = this.supplies.filter(sp => sp.id === $event)[0]
      if (supply.state.name === 'cancelled') {
        this.showMessage(false, this.$vuetify.lang.t(
          '$vuetify.messages.warning_supply_state_cancelled'
        ))
      } else if (supply.state.name !== 'requested') {
        this.showMessage(false, this.$vuetify.lang.t(
          '$vuetify.messages.warning_supply_state'
        ))
      } else {
        this.openEditModal($event)
        this.$store.state.supply.managerSupply = true
        this.$router.push({ name: 'supply_edit' })
      }
    },
    deleteSupplyHandler ($event) {
      const supply = this.supplies.filter(sp => sp.id === $event)[0]
      if (supply.state.name === 'cancelled') {
        this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.delete', [
              this.$vuetify.lang.t('$vuetify.supply.name')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.warning_delete'
            ),
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.cancel'
            ),
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.delete'
            ),
            confirmButtonColor: 'red'
          })
          .then((result) => {
            if (result.isConfirmed) this.deleteSupply($event)
          })
      } else {
        this.showMessage(false, this.$vuetify.lang.t(
          '$vuetify.messages.warning_supply_delete'
        ))
      }
    },
    changeStateHandler (item) {
      this.updateSupply(item)
    },
    showMessage (newM, txt) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t(newM ? '$vuetify.titles.new' : '$vuetify.titles.edit', [
            this.$vuetify.lang.t('$vuetify.menu.supply_productS')
          ]),
          text: txt,
          icon: 'warning',
          showCancelButton: false,
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
    }
  }
}
</script>

<style scoped></style>
