<template>
  <div class="page-dashboard">
    <app-loading v-show="loadingData" />
    <v-container v-if="!loadingData">
      <!--      <v-row>
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
      </v-card>-->
      <v-row cols="12">
        <v-col md="3">
          <linear-statistic
            class="my-4"
            title="Sales"
            sub-title="Sales increase"
            icon="mdi-trending-up"
            color="success"
            :value="15"
          />
        </v-col>
        <v-col md="3">
          <linear-statistic
            class="my-4"
            title="Orders"
            sub-title="Increase"
            icon="mdi-trending-up"
            color="pink"
            :value="30"
          />
        </v-col>
        <v-col md="3">
          <linear-statistic
            class="my-4"
            title="Revenue"
            sub-title="Revenue increase"
            icon="mdi-trending-up"
            color="primary"
            :value="50"
          />
        </v-col>
        <v-col md="3">
          <linear-statistic
            class="mt-4"
            title="Cost"
            sub-title="Cost reduce"
            icon="mdi-trending-down"
            color="orange"
            :value="25"
          />
        </v-col>
      </v-row>
      <v-tabs
        v-model="tab"
        centered
      >
        <v-tab
          v-for="item in tabsData.tabName"
          :key="item.name"
        >
          <span style="text-transform: capitalize">{{ item.name }}</span>
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-card flat>
            <v-card-text>
              <v-row>
                <v-col md="6">
                  <plain-table />
                </v-col>
                <v-col md="6">
                  <v-widget
                    title="Activities"
                    content-bg="white"
                  >
                    <div slot="widget-content">
                      <v-timeline
                        align-top
                        dense
                      >
                        <v-timeline-item
                          v-for="(item, index) in activity"
                          :key="index"
                          :color="item.color"
                          small
                        >
                          <v-row class="pt-1">
                            <v-col cols="3">
                              <strong>{{ item.timeString }}</strong>
                            </v-col>
                            <v-col>
                              <strong>New Icon</strong>
                              <div>{{ item.text }}</div>
                            </v-col>
                          </v-row>
                        </v-timeline-item>
                      </v-timeline>
                    </div>
                  </v-widget>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-tab-item>
        <v-tab-item>
          <v-card flat>
            <v-card-text>
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
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-container>
  </div>
</template>

<script>
import API from '../api'
import Material from 'vuetify/es5/util/colors'
import { mapActions, mapGetters } from 'vuex'

export default {
  name: 'PageDashboard',
  data: () => ({
    tab: null,
    loadingData: false,
    color: Material,
    statistic: [],
    statisticDeny: [],
    localSalesByPayments: [],
    localSalesByCategories: [],
    tabName: [
      { name: 'Informacion', icon: 'mdi-info', access: 'manager_access' },
      { name: 'Accesos', icon: 'mdi-key', access: 'manager_access' }
    ]
  }),
  computed: {
    ...mapGetters('auth', ['user', 'access_permit']),
    activity () {
      return [
        {
          avatar:
              'https://s3.amazonaws.com/uifaces/faces/twitter/ludwiczakpawel/128.jpg',
          timeString: 'Just now',
          color: 'primary',
          text: 'Michael finished  one task just now.'
        },
        {
          avatar: 'https://s3.amazonaws.com/uifaces/faces/twitter/suprb/128.jpg',
          timeString: '30 min ago',
          color: 'teal',
          text: 'Jim created a new  task.'
        },
        {
          avatar: 'https://s3.amazonaws.com/uifaces/faces/twitter/suprb/128.jpg',
          timeString: '1 hour ago',
          color: 'indigo',
          text: 'Li completed the PSD to html convert.'
        },
        {
          avatar: 'https://s3.amazonaws.com/uifaces/faces/twitter/suprb/128.jpg',
          timeString: '3 hour ago',
          color: 'pink',
          text: 'Michael upload a new pic.'
        }
      ]
    },
    siteTrafficData () {
      return API.getMonthVisit
    },
    locationData () {
      return API.getLocation
    },
    tabsData () {
      const result = { tabName: [], itemsTabs: [] }
      this.tabName.forEach((v, i) => {
        const access = this.access_permit.filter(a => a.title.name === v.access)
        if (access.length > 0) {
          if (access[0].title.value === true) {
            result.tabName.push(v)
            // result.itemsTabs.push(this.isAdminIn ? this.itemsTabs[1][i] : this.itemsTabs[0][i])
          }
        }
      })
      return result
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
