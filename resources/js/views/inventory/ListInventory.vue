<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.vending'),])"
          :headers="getTableColumns"
          csv-filename="SaleProducts"
          :manager="'vending'"
          :items="localInventories"
          :options="vBindOption"
          :sort-by="['no_facture']"
          :sort-desc="[false, true]"
          multi-sort
          :is-loading="isTableLoading"
          @create-row="createInventoryHandler"
          @edit-row="editInventoryHandler($event)"
          @delete-row="deleteInventoryHandler($event)"
        >
          <template v-slot:item.no_facture="{ item }">
            <template>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <b><v-icon
                    style="color: red"
                    class="mr-2"
                    small
                    v-bind="attrs"
                    @click="openPrintModal('ticket', item.id)"
                    v-on="on"
                  >
                    mdi-printer
                  </v-icon></b>
                </template>
                <span>{{ $vuetify.lang.t('$vuetify.report.print_ticket') }}</span>
              </v-tooltip>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <b><v-icon
                    style="color: #1a5dd6"
                    class="mr-2"
                    small
                    v-bind="attrs"
                    @click="openPrintModal('letter', item.id)"
                    v-on="on"
                  >
                    mdi-printer-settings
                  </v-icon></b>
                </template>
                <span>{{ $vuetify.lang.t('$vuetify.report.print_letter') }}</span>
              </v-tooltip>
            </template>
            {{ item.no_facture }}
          </template>
          <template v-slot:item.state="{ item }">
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <template v-if="item.state !== 'preform'">
                  <v-autocomplete
                    v-model="item.state"
                    :disabled="item.state !== 'open'"
                    chips
                    :items="getLocalStates"
                    item-text="text"
                    item-value="value"
                    :style="{'width':'160px'}"
                    v-bind="attrs"
                    v-on="on"
                    @input="changeState(item)"
                  >
                    <template v-slot:selection="data">
                      <v-chip
                        v-bind="data.attrs"
                        :input-value="data.item.value"
                        @click="data.select"
                      >
                        <i :style="'color: ' + data.item.color">
                          <v-icon left>
                            {{ data.item.icon }}
                          </v-icon>
                          {{ data.item.text }}</i>
                      </v-chip>
                    </template>
                    <template v-slot:item="data">
                      <template v-if="typeof data.item !== 'object'">
                        <v-list-item-content v-text="data.item" />
                      </template>
                      <template v-else>
                        <v-list-item-icon>
                          <v-icon
                            left
                            :style="'color: ' + data.item.color"
                          >
                            {{ data.item.icon }}
                          </v-icon>
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-title
                            :style="'color: ' + data.item.color"
                          >
                            {{ data.item.text }}
                          </v-list-item-title>
                        </v-list-item-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </template>
                <template v-else>
                  <v-chip>
                    <i style="color: #0288d1"> <v-icon>mdi-calendar-clock</v-icon>
                      {{ $vuetify.lang.t('$vuetify.sale.state.preform') }}
                    </i>
                  </v-chip>
                </template>
              </template>
            </v-tooltip>
          </template>
          <template v-slot:[`item.shop.name`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                >
                  {{ item.shop.name }}
                </v-chip>
              </template>
              <span>
                {{ $vuetify.lang.t('$vuetify.menu.box')+'- ' + item.box.name }}</span>
            </v-tooltip>
          </template>
          <template v-slot:[`item.totalPrice`]="{ item }">
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
                    :pays-show="item.pays"
                    :currency="user.company.currency"
                  />
                </template>
                <span
                  v-if="item.totalRefund > 0"
                >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + item.totalRefund}` }}</span>
              </v-tooltip>
            </template>
            {{ `${user.company.currency + ' ' + item.totalPrice}` }}
          </template>
          <template v-slot:[`item.totalCost`]="{ item }">
            {{ `${user.company.currency + ' ' + item.totalCost}` }}
          </template>
          <template v-slot:[`item.taxes`]="{item}">
            <template v-if="item.taxes.length > 0">
              <v-chip
                v-for="(lTax, i) of item.taxes"
                :key="i"
              >
                {{ lTax.name }}{{ lTax.percent ? '('+lTax.value +'%)':'' }}
              </v-chip>
            </template>
            <template v-else>
              <i>{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
            </template>
          </template>
          <template v-slot:[`item.discounts`]="{item}">
            <template v-if="item.discounts.length > 0">
              <v-chip
                v-for="(lDiscount, i) of item.discounts"
                :key="i"
              >
                {{ lDiscount.name }}{{ lDiscount.percent ? '('+lDiscount.value +'%)':'' }}
              </v-chip>
            </template>
            <template v-else>
              <i>{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
            </template>
          </template>
          <template v-slot:[`item.data-table-expand`]="{item, expand, isExpanded }">
            <v-btn
              v-if="item.articles.length > 0"
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
                        {{ $vuetify.lang.t('$vuetify.articles.price') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.tax.name') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.menu.discount') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.variants.total_price') }}
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
                      v-for="article in item.articles"
                      :key="article.name"
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
                      <td>{{ `${user.company.currency + ' ' + article.price}` }}</td>
                      <td>
                        <template v-if="article.taxes.length > 0">
                          <v-chip
                            v-for="(lTax, i) of article.tax"
                            :key="i"
                            small
                          >
                            {{ lTax.name }}{{ lTax.percent ? '('+lTax.value +'%)':'' }} +{{ `${user.company.currency}` }} {{ lTax.percent ? lTax.value*article.cant*article.price/100 : lTax.value }}
                          </v-chip>
                        </template>
                        <template v-else>
                          <i>{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
                        </template>
                      </td>
                      <td>
                        <template v-if="article.discount.length > 0">
                          <v-chip
                            v-for="(lDiscount, i) of article.discount"
                            :key="i"
                            small
                          >
                            {{ lDiscount.name }}{{ lDiscount.percent ? '('+lDiscount.value +'%) ':' ' }} <i> -{{ `${user.company.currency}` }} {{ lDiscount.percent ? lDiscount.value*article.cant*article.price/100 : lDiscount.value }}</i>
                          </v-chip>
                        </template>
                        <template v-else>
                          <i>{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
                        </template>
                      </td>
                      <td>
                        <v-tooltip top>
                          <template v-slot:activator="{ on, attrs }">
                            <b><v-icon
                              v-if="article.moneyRefund > 0"
                              style="color: red"
                              class="mr-2"
                              small
                              v-bind="attrs"
                              v-on="on"
                            >
                              mdi-information
                            </v-icon></b>
                          </template>
                          <span>{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + article.moneyRefund}` }}</span>
                        </v-tooltip>
                        <span>{{ `${user.company.currency + ' ' + total_pay(article)}` }}</span>
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
                        <template v-if="item.state !=='preform'">
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
                        <template v-else>
                          <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                              <b><v-icon
                                v-if="article.cant > 0"
                                style="color: #ff752b"
                                class="mr-2"
                                small
                                v-bind="attrs"
                                v-on="on"
                                @click="cancelProductPreform(item, article)"
                              >
                                mdi-cancel
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
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'ListInventory',
  data () {
    return {
      localInventories: [],
      search: '',
      vBindOption: {
        itemKey: 'no_facture',
        singleExpand: false,
        showExpand: true
      }
    }
  },
  computed: {
    ...mapState('inventory', [
      'managerInventory',
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'inventories',
      'isTableLoading'
    ]),
    ...mapGetters('auth', ['user']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.tax.noFacture'),
          value: 'no_facture',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.pay.pay'),
          value: 'pay',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.payment.name'),
          value: 'payments.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.supplier.name'),
          value: 'supplier.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.total_cost'),
          value: 'totalCost',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.total_price'),
          value: 'totalPrice',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shop.name',
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
  created () {
    this.getInventories()
  },
  methods: {
    ...mapActions('inventory', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getInventories',
      'deleteInventory'
    ]),
    createInventoryHandler () {
      this.managerInventory = false
      this.$router.push({ name: 'supply_add' })
    },
    editInventoryHandler ($event) {
      this.managerInventory = true
      this.openEditModal($event)
      this.$router.push({ name: 'supply_edit' })
    },
    deleteInventoryHandler (articleId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.supply_productS')
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
          if (result.isConfirmed) this.deleteInventory(articleId)
        })
    }
  }
}
</script>

<style scoped></style>
