<template>
  <div class="page-add-inventory">
    <app-loading v-show="loadingData" />
    <v-container v-if="!loadingData">
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.menu.sell_types_payment')
          }}</span>
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col
              class="py-0"
              cols="12"
              md="4"
            >
              <v-menu
                ref="menu"
                v-model="menu"
                :close-on-content-click="false"
                :nudge-right="40"
                :return-value.sync="dates"
                transition="scale-transition"
                offset-y
                min-width="290px"
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-text-field
                    v-model="dateRangeText"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-bind="attrs"
                    v-on="on"
                  />
                </template>
                <v-date-picker
                  v-model="dates"
                  range
                >
                  <v-spacer />
                  <v-btn
                    text
                    color="primary"
                    @click="menu = false"
                  >
                    {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
                  </v-btn>
                  <v-btn
                    text
                    color="primary"
                    @click="saveDates"
                  >
                    {{ $vuetify.lang.t('$vuetify.actions.accept') }}
                  </v-btn>
                </v-date-picker>
              </v-menu>
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="7"
            >
              <v-select
                v-model="localShops"
                chips
                rounded
                solo
                :items="shops"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                item-text="name"
                :loading="isShopLoading"
                :disabled="!!isShopLoading"
                return-object
                required
                multiple
                :rules="formRule.country"
              />
            </v-col>
          </v-row>
          <v-expansion-panels
            v-model="panel"
            style="margin: 0"
            multiple
          >
            <v-expansion-panel style="margin: 0">
              <v-expansion-panel-header>
                <div>
                  <v-icon>mdi-chart-line</v-icon>
                  <span style="text-transform: uppercase;font-weight: bold">
                    {{ $vuetify.lang.t('$vuetify.report.graphics') }}
                  </span>
                </div>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <v-row>
                  <v-col
                    class="py-0"
                    cols="12"
                    :md="localSalesByPayments.length > 0?4:12"
                  >
                    <v-card
                      class="mx-auto"
                      tile
                    >
                      <v-list dense>
                        <v-subheader>
                          {{ localSalesByPayments.length > 0? $vuetify.lang.t('$vuetify.report.top5Payment'):
                            $vuetify.lang.t('$vuetify.report.noTop5') }}
                        </v-subheader>
                        <v-list-item-group
                          color="primary"
                        >
                          <v-list-item
                            v-for="(item, i) in localSalesByPayments.slice(0,5)"
                            :key="i"
                          >
                            <v-list-item-icon>
                              <v-chip
                                :color="item.color"
                                dark
                              >
                                {{ item.color }}
                              </v-chip>
                            </v-list-item-icon>
                            <v-list-item-content>
                              <v-list-item-title v-text="`${$vuetify.lang.t('$vuetify.payment.' + item.name)}`" />
                              <v-list-item-subtitle v-text="`${item.data.netPrice + ' ' + user.company.currency }` " />
                            </v-list-item-content>
                          </v-list-item>
                        </v-list-item-group>
                      </v-list>
                    </v-card>
                  </v-col>
                  <v-col
                    v-if="localSalesByPayments.length > 0"
                    class="py-0"
                    cols="12"
                    md="7"
                  >
                    <highcharts :options="chartOptions" />
                  </v-col>
                </v-row>
              </v-expansion-panel-content>
            </v-expansion-panel>
            <v-expansion-panel style="margin: 0">
              <v-expansion-panel-header>
                <div>
                  <v-icon>mdi-table-large</v-icon>
                  <span style="text-transform: uppercase;font-weight: bold">
                    {{ $vuetify.lang.t('$vuetify.panel.basic') }}
                  </span>
                </div>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <app-data-table
                  :title="$vuetify.lang.t('$vuetify.titles.list',
                                          [$vuetify.lang.t('$vuetify.menu.vending'),])"
                  :headers="getTableColumns"
                  csv-filename="ProductBuys"
                  :items="localSalesByPayments"
                  :options="vBindOption"
                  :sort-by="['no_facture']"
                  :sort-desc="[false, true]"
                  multi-sort
                  :view-show-filter="false"
                  :view-edit-button="false"
                  :view-new-button="false"
                  :view-delete-button="false"
                  :is-loading="isTableLoading"
                  @rowClick="rowClick"
                >
                  <template v-slot:[`item.name`]="{ item }">
                    {{ $vuetify.lang.t('$vuetify.payment.' + item.name) }}
                  </template>
                  <template v-slot:[`item.data.grossPrice`]="{ item }">
                    {{ `${user.company.currency + ' ' + item.data.grossPrice}` }}
                  </template>
                  <template v-slot:[`item.data.netPrice`]="{ item }">
                    {{ `${user.company.currency + ' ' + item.data.netPrice}` }}
                  </template>
                  <template v-slot:[`item.data.totalTax`]="{ item }">
                    {{ `${user.company.currency + ' ' + item.data.totalTax}` }}
                  </template>
                  <template v-slot:[`item.data.totalDiscount`]="{ item }">
                    {{ `${user.company.currency + ' ' + item.data.totalDiscount}` }}
                  </template>
                </app-data-table>
              </v-expansion-panel-content>
            </v-expansion-panel>
          </v-expansion-panels>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>
<script>
import AppLoading from '../../components/core/AppLoading'
import { Chart } from 'highcharts-vue'
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'SalesPayment',
  components: { AppLoading, highcharts: Chart },
  data () {
    return {
      loadingData: false,
      panel: [0],
      seriesData: [],
      chartOptions: {},
      vBindOption: {
        itemKey: 'no_facture',
        singleExpand: false,
        showExpand: false
      },
      menu: false,
      formRule: this.$rules,
      dates: [],
      localShops: [],
      localSalesByPayments: []
    }
  },
  computed: {
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapState('sale', ['salesByPayments', 'isTableLoading']),
    ...mapState('payment', ['paymentsConst']),
    ...mapGetters('auth', ['user']),
    dateRangeText () {
      const d1 = this.dates[0]
      const d2 = this.dates[1]
      console.log(this.dates)
      if (d1 > d2) {
        this.dates = [d2, d1]
      }
      return this.dates.join(' ---> ')
    },
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cant'),
          value: 'data.cantTransactions',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.report.grossPrice'),
          value: 'data.grossPrice',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.report.netPrice'),
          value: 'data.netPrice',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.report.discountsSale'),
          value: 'data.totalDiscount',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.total_pay_tax'),
          value: 'data.totalTax',
          select_filter: true
        }
      ]
    }
  },
  async created () {
    this.loadingData = true
    let toDay
    let lastWeek = ''
    new Date().toLocaleDateString().split('/').reverse().forEach((v, i) => {
      toDay = (i > 0) ? toDay + '-' + v : v
    })
    new Date((new Date()).getTime() - 6 * 24 * 60 * 60 * 1000).toLocaleDateString().split('/').reverse().forEach((v, i) => {
      lastWeek = (i > 0) ? lastWeek + '-' + v : v
    })
    this.dates = [lastWeek, toDay]
    await this.loadPaymentsConst()
    await this.getShops().then(() => {
      this.localShops = this.shops
    })
    await this.getSalesByPayment({
      shops: this.shops,
      dates: this.dates
    }).then(() => {
      this.localSalesByPayments = this.salesByPayments.sort(function (a, b) {
        if (a.data.netPrice > b.data.netPrice) return -1
        if (a.data.netPrice < b.data.netPrice) return 1
        return 0
      })
    })
    await this.loadData()
    this.loadingData = false
  },
  methods: {
    ...mapActions('shop', ['getShops']),
    ...mapActions('sale', ['getSalesByPayment']),
    ...mapActions('payment', ['loadPaymentsConst']),
    saveDates () {
      this.$refs.menu.save(this.dates)
      this.loadSalesByEmployer()
    },
    loadData: function () {
      const categories = []
      const series = { grossPrice: [], totalDiscount: [], netPrice: [], totalCost: [], totalTax: [] }
      this.localSalesByPayments.slice(0, 4).forEach((v) => {
        if (v.name !== undefined) {
          categories.push(this.paymentsConst.filter(pay => (pay.value === v.name))[0].name)
          series.grossPrice.push(v.data.grossPrice)
          series.totalDiscount.push(v.data.totalDiscount)
          series.netPrice.push(v.data.netPrice)
          series.totalCost.push(v.data.totalCost)
          series.totalTax.push(v.data.totalTax)
        }
      })
      this.chartOptions = {
        chart: {
          type: 'column'
        },
        title: {
          text: this.$vuetify.lang.t('$vuetify.report.barGraphics')
        },
        xAxis: {
          categories: categories,
          crosshair: true
        },
        yAxis: {
          min: 0,
          title: {
            text: this.user.company.currency
          }
        },
        tooltip: {
          headerFormat: '<span style="font-size:10px"><b>{point.key}</b></span><table>',
          pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f} ' + this.user.company.currency + '</b></td></tr>',
          footerFormat: '</table>',
          shared: true,
          useHTML: true
        },
        plotOptions: {
          column: {
            pointPadding: 0.2,
            borderWidth: 0
          }
        },
        series: [{
          name: this.$vuetify.lang.t('$vuetify.report.grossSale'),
          data: series.grossPrice

        }, {
          name: this.$vuetify.lang.t('$vuetify.report.discountsSale'),
          data: series.totalDiscount

        }, {
          name: this.$vuetify.lang.t('$vuetify.report.netPrice'),
          data: series.netPrice

        }, {
          name: this.$vuetify.lang.t('$vuetify.variants.total_cost'),
          data: series.totalCost

        }, {
          name: this.$vuetify.lang.t('$vuetify.tax.total_pay_tax'),
          data: series.totalTax

        }]
      }
    },
    rowClick ($event) {
    }
  }
}
</script>

<style scoped>
</style>
