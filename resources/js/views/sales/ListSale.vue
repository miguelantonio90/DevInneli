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
          :items="localSales"
          :options="vBindOption"
          :sort-by="['no_facture']"
          :sort-desc="[false, true]"
          multi-sort
          :is-loading="isTableLoading"
          @create-row="createSaleHandler"
          @edit-row="editSaleHandler($event)"
          @delete-row="deleteSaleHandler($event)"
        >
          <template v-slot:item.state="{ item }">
            <v-autocomplete
              v-model="item.state"
              :disabled="item.state !== 'open'"
              chips
              :items="getLocalStates"
              item-text="text"
              item-value="value"
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
                      v-html="data.item.text"
                    />
                  </v-list-item-content>
                </template>
              </template>
            </v-autocomplete>
          </template>
          <template v-slot:[`item.pay`]="{ item }">
            {{
              item.pay === 'counted' ? $vuetify.lang.t('$vuetify.pay.counted') : $vuetify.lang.t('$vuetify.pay.credit')
            }}
          </template>
          <template v-slot:[`item.payments.name`]="{ item }">
            <template v-if="item.payments">
              {{ item.payments.name }}
            </template>
            <template v-else>
              <i style="color: red">{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
            </template>
          </template>
          <template v-slot:[`item.shop.name`]="{ item }">
            <v-chip>
              {{ item.shop.name }}
            </v-chip>
          </template>
          <template v-slot:[`item.totalPrice`]="{ item }">
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
              <i style="color: red">{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
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
              <i style="color: red">{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
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
                      <td>{{ article.ref }}</td>
                      <td>{{ article.name }}</td>
                      <td>{{ article.cant }}</td>
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
                          <i style="color: red">{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
                        </template>
                      </td>
                      <td>
                        <template v-if="article.discount.length > 0">
                          <v-chip
                            v-for="(lDiscount, i) of article.discount"
                            :key="i"
                            small
                          >
                            {{ lDiscount.name }}{{ lDiscount.percent ? '('+lDiscount.value +'%) ':' ' }} <i style="color: red"> -{{ `${user.company.currency}` }} {{ lDiscount.percent ? lDiscount.value*article.cant*article.price/100 : lDiscount.value }}</i>
                          </v-chip>
                        </template>
                        <template v-else>
                          <i style="color: red">{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
                        </template>
                      </td>
                      <td>{{ `${user.company.currency + ' ' + total_pay(article)}` }}</td>
                      <td>{{ article.inventory }}</td>
                      <td>
                        <v-tooltip top>
                          <template v-slot:activator="{ on, attrs }">
                            <b><v-icon
                              v-if="article.cant > 0"
                              style="color: #ff752b"
                              class="mr-2"
                              small
                              v-bind="attrs"
                              v-on="on"
                              @click="refundArticle(item)"
                            >
                              mdi-undo
                            </v-icon></b>
                          </template>
                          <span>{{ $vuetify.lang.t('$vuetify.actions.refund') }}</span>
                        </v-tooltip>
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
  name: 'ListSale',
  data () {
    return {
      localSales: [],
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
    ...mapState('sale', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'sales',
      'isTableLoading'
    ]),
    ...mapState('article', ['articles']),
    ...mapGetters('auth', ['user', 'access_permit']),
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
          text: this.$vuetify.lang.t('$vuetify.menu.client'),
          value: 'client.firstName',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.state'),
          value: 'state',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.name'),
          value: 'taxes',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.discount'),
          value: 'discounts',
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
    },
    getLocalStates () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.sale.state.open'),
          value: 'open',
          icon: 'mdi-star-half',
          color: '#4caf50'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.sale.state.accepted'),
          value: 'accepted',
          icon: 'mdi-star',
          color: '#3f51b5'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.sale.state.cancelled'),
          value: 'cancelled',
          icon: 'mdi-star-off',
          color: '#ff0000'
        }
      ]
    }
  },
  watch: {
    sales: function () {
      this.localSales = []
      this.sales.forEach((value) => {
        const sale = value
        value.articles.forEach((v, i) => {
          if (v.parent_id) { sale.articles[i].name = this.articles.filter(art => art.id === v.parent_id)[0].name + '(' + v.name + ')' }
        })
        this.localSales.push(sale)
      })
    }
  },
  created () {
    this.localSales = []
    this.getArticles().then(() => {
      this.getSales()
    })
  },
  methods: {
    ...mapActions('sale', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getSales',
      'updateSale',
      'deleteSale'
    ]),
    ...mapActions('article', ['getArticles']),
    changeState (item) {
      this.updateSale(item)
    },
    refundArticle (item) {
      console.log(item)
    },
    total_pay (item) {
      let sum = 0
      item.taxes.forEach((v) => {
        sum += v.percent ? item.cant * item.price * v.value / 100 : v.value
      })
      let discount = 0
      item.discount.forEach((v) => {
        discount += v.percent ? item.cant * item.price * v.value / 100 : v.value
      })
      return item.cant * item.price + sum - discount
    },
    createSaleHandler () {
      this.$router.push({ name: 'vending_new' })
    },
    editSaleHandler ($event) {
      this.openEditModal($event)
      this.$router.push({ name: 'vending_edit' })
    },
    deleteSaleHandler (articleId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.sale.sale')
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
          if (result.isConfirmed) this.deleteSale(articleId)
        })
    }
  }
}
</script>

<style scoped></style>
