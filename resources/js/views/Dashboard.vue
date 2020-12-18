<template>
  <div class="page-dashboard">
    <app-loading v-show="loadingData" />
    <v-container v-if="!loadingData">
      <v-row>
        <v-col
          v-for="(st,i) in statistic"
          v-show="statistic.length > 0"
          :key="i"
          style="margin-bottom: 15px"
          class="py-0"
          cols="6"
          md="2"
        >
          <mini-statistic
            color="indigo"
            :icon="st.icon"
            :sub-title="st.sub_title"
            @click="goToClick(st.goToClick)"
          />
        </v-col>
      </v-row>
      <v-card v-if="statisticDeny.length > 0">
        <v-card-subtitle>
          {{ $vuetify.lang.t('$vuetify.sector.others') }}
        </v-card-subtitle>
        <v-card-text>
          <v-row>
            <v-col
              v-for="(st,i) in statisticDeny"
              v-show="statistic.length > 0"
              :key="i"
              style="margin-bottom: 15px"
              class="py-0"
              cols="6"
              md="2"
            >
              <mini-statistic
                disabled
                color="red"
                :icon="st.icon"
                :sub-title="st.sub_title"
              />
            </v-col>
          </v-row>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>

<script>
import API from '../api'
import MiniStatistic from '../components/widgets/statistic/MiniStatistic'
import Material from 'vuetify/es5/util/colors'
import AppLoading from '../components/core/AppLoading'
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'PageDashboard',
  components: {
    AppLoading,
    MiniStatistic
  },
  data: () => ({
    loadingData: false,
    color: Material,
    statistic: [],
    statisticDeny: [],
    localSalesByPayments: [],
    localSalesByCategories: []
  }),
  computed: {
    ...mapGetters('auth', ['user', 'access_permit']),
    siteTrafficData () {
      return API.getMonthVisit
    },
    locationData () {
      return API.getLocation
    }
  },
  watch: {
    access_permit () {
      this.completeStaticTic()
    }
  },
  async created () {
    this.loadingData = true
    await this.getUserLogin()
    await this.completeStaticTic()
    this.loadingData = false
  },
  methods: {
    ...mapActions('user', ['getUserLogin']),
    completeStaticTic () {
      console.log(this.access_permit)
      this.statistic = []
      this.statisticDeny = []
      if (this.access_permit.length > 0) {
        (this.access_permit.filter(permit => permit.title.name === 'manager_shop')[0].title.value === true) ? this.statistic.push({
          icon: 'mdi-home',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_shop'),
          goToClick: 'setting'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_shop')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_category')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-tag',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_category'),
          goToClick: 'category_list'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_category')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_article')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-shopping',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_article'),
          goToClick: 'product_list'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_article')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_mod')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-tag-text-outline',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_mod'),
          goToClick: 'modifiers_list'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_mod')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_client')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-account-multiple',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_client'),
          goToClick: 'clients_list'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_client')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_vending')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-currency-usd',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_vending'),
          goToClick: 'vending'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_vending')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_supplier')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-account-switch',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_supplier'),
          goToClick: 'supplier_list'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_supplier')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_buy')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-cart',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_buy'),
          goToClick: 'supply_product'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_buy')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_sell')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-chart-bar',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_sell'),
          goToClick: 'sell_product'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_sell')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_access')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-key',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_access'),
          goToClick: 'access'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_access')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_employer')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-account-star',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_employer'),
          goToClick: 'employer_list'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_employer')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_assistence')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-cards',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_assistence'),
          goToClick: 'assistance'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_assistence')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_payment')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-cash-multiple',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_payment'),
          goToClick: 'pay'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_payment')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_expense_category')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-marker-check',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_expense_category'),
          goToClick: 'expense_category'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_expense_category')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_exchange_rate')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-bank',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_exchange_rate'),
          goToClick: 'exchange_rate'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_exchange_rate')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_type_of_order')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-food',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_type_of_order'),
          goToClick: 'type_of_order'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_type_of_order')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_tax')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-wallet',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_tax'),
          goToClick: 'tax_list'
        }) : this.statisticDeny.push({
          icon: 'mdi-wallet',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_tax')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_discount')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-cash-minus',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_discount'),
          goToClick: 'discount'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_discount')
        })
      }
    },
    goToClick (name) {
      this.$router.push({ name: name })
    }
  }
}
</script>
