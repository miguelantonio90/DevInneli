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
  name: 'SalesProduct',
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
      dates: ['2019-09-10', '2019-09-20']
    }
  },
  computed: {
    ...mapState('shop', ['shops', 'isShopLoading']),
    dateRangeText () {
      return this.dates.join(' ---> ')
    }
  },
  created () {
    this.loadingData = true
    this.getShops().then(() => {
      this.loadingData = false
    })
  },
  methods: {
    ...mapActions('shop', ['getShops'])
  }
}
</script>

<style scoped>
</style>
