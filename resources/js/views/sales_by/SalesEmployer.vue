<template>
  <div class="page-add-inventory">
    <app-loading v-show="loadingData" />
    <v-container v-if="!loadingData">
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.menu.sell_user')
          }}</span>
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col
              class="py-0"
              cols="12"
              md="6"
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
                <template v-slot:activator="{ on }">
                  <v-text-field
                    v-model="dateRangeText"
                    prepend-icon="mdi-calendar"
                    readonly
                    v-on="on"
                  />
                </template>
                <v-date-picker
                  v-model="dates"
                  range
                />
              </v-menu>
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
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
                @change="changeShop"
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
                    :md="localSalesByEmployer.length > 0?4:12"
                  >
                    <v-card
                      class="mx-auto"
                      tile
                    >
                      <v-list dense>
                        <v-subheader>
                          {{ localSalesByEmployer.length > 0? $vuetify.lang.t('$vuetify.report.top5Category'):
                            $vuetify.lang.t('$vuetify.report.noTop5') }}
                        </v-subheader>
                        <v-list-item-group
                          color="primary"
                        >
                          <v-list-item
                            v-for="(item, i) in localSalesByEmployer.slice(0,5)"
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
                              <v-list-item-title v-text="item.name.firstName" />
                              <v-list-item-subtitle v-text="`${item.data.netPrice + ' ' + user.company.currency }` " />
                            </v-list-item-content>
                          </v-list-item>
                        </v-list-item-group>
                      </v-list>
                    </v-card>
                  </v-col>
                  <v-col
                    v-if="localSalesByEmployer.length > 0"
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
                  :items="localSalesByEmployer"
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
                />
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
  name: 'SalesEmployer',
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
      localSalesByEmployer: []
    }
  },
  computed: {
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapState('sale', ['salesByEmployer', 'isTableLoading']),
    ...mapGetters('auth', ['user']),
    dateRangeText () {
      return this.dates.join(' ---> ')
    },
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name.firstName',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.report.grossPrice'),
          value: 'data.grossPrice',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cant'),
          value: 'data.cantTransactions',
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
        },
        {
          text: this.$vuetify.lang.t('$vuetify.report.netPrice'),
          value: 'data.netPrice',
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
    await this.getShops().then(() => {
      this.localShops = this.shops
    })
    await this.loadSalesByEmployer()
    this.loadingData = false
  },
  methods: {
    ...mapActions('shop', ['getShops']),
    ...mapActions('sale', ['getSaleByEmployer']),
    async changeShop () {
      this.loadingData = true
      if (this.localShops.length === 0) { this.localShops.push(this.shops[0]) } else { await this.loadSalesByEmployer() }
      this.loadingData = false
    },
    async loadSalesByEmployer () {
      await this.getSaleByEmployer({
        shops: this.localShops,
        dates: this.dates
      }).then(() => {
        this.localSalesByEmployer = this.salesByEmployer.sort(function (a, b) {
          if (a.data.netPrice > b.data.netPrice) return -1
          if (a.data.netPrice < b.data.netPrice) return 1
          return 0
        })
      })
      this.loadData()
    },
    loadData: function () {
      const categories = []
      const series = { grossPrice: [], totalDiscount: [], netPrice: [], totalCost: [], totalTax: [] }
      this.localSalesByEmployer.slice(0, 4).forEach((v) => {
        categories.push(v.name.firstName)
        series.grossPrice.push(v.data.grossPrice)
        series.totalDiscount.push(v.data.totalDiscount)
        series.netPrice.push(v.data.netPrice)
        series.totalCost.push(v.data.totalCost)
        series.totalTax.push(v.data.totalTax)
      })
      this.chartOptions = {
        chart: {
          type: 'column'
        },
        title: {
          text: this.$vuetify.lang.t('$vuetify.report.barGraphics')
        },
        subtitle: {
          text: 'Inneli.com'
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
      console.log($event)
    }
  }
}
</script>

<style scoped>
</style>
