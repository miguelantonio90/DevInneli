<template>
  <div class="page-add-inventory">
    <app-loading v-show="loadingData" />
    <v-container v-if="!loadingData">
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.menu.sell_product')
          }}</span>
        </v-card-title>
        <v-card-text>
          <v-row>
            <v-col md="4">
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
            <v-col md="4">
              <v-select
                v-model="localShops"
                chips
                rounded
                solo
                clearable
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
                  <v-icon>mdi-database-edit</v-icon>
                  <span style="text-transform: uppercase;font-weight: bold">
                    {{ $vuetify.lang.t('$vuetify.panel.basic') }}
                  </span>
                </div>
              </v-expansion-panel-header>
              <v-expansion-panel-content>
                <highcharts :options="chartOptions" />
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
import { mapActions, mapState } from 'vuex'
export default {
  name: 'SalesCategory',
  components: { AppLoading, highcharts: Chart },
  data () {
    return {
      loadingData: false,
      panel: [0],
      chartOptions: {
        series: [{
          data: [1, 2, 3] // sample data
        }]
      },
      menu: false,
      formRule: this.$rules,
      dates: [],
      localShops: []
    }
  },
  computed: {
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapState('sale', ['salesByCategories']),
    dateRangeText () {
      return this.dates.join(' ---> ')
    }
  },
  mounted () {
    let toDay; let lastWeek = ''
    new Date().toLocaleDateString().split('/').reverse().forEach((v, i) => {
      toDay = (i > 0) ? toDay + '-' + v : v
    })
    new Date((new Date()).getTime() - 6 * 24 * 60 * 60 * 1000).toLocaleDateString().split('/').reverse().forEach((v, i) => {
      lastWeek = (i > 0) ? lastWeek + '-' + v : v
    })
    this.dates = [lastWeek, toDay]
  },
  async created () {
    this.loadingData = true
    await this.getShops().then(() => {
      this.localShops = this.shops
    })
    await this.getSalesByCategories({
      shops: this.shops,
      dates: this.dates
    }).then(() => {
      this.loadingData = false
    })
  },
  methods: {
    ...mapActions('shop', ['getShops']),
    ...mapActions('sale', ['getSalesByCategories'])
  }
}
</script>

<style scoped>
</style>
