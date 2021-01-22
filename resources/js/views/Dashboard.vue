<template>
  <div class="page-dashboard">
    <app-loading v-show="loadingData" />
    <v-container v-if="!loadingData">
      <v-row
        id="statistic_dashboard"
        cols="12"
      >
        <v-col
          cols="6"
          md="3"
        >
          <linear-statistic
            class="my-4"
            :title="$vuetify.lang.t('$vuetify.dashboard.sales')"
            :sub-title="$vuetify.lang.t('$vuetify.dashboard.salesSub')"
            :currency="user.company.currency"
            :quantity="salesStatics.totalSales ? salesStatics.totalSales.toString():0"
            icon="mdi-trending-up"
            color="success"
            :value="100"
          />
        </v-col>
        <v-col
          cols="6"
          md="3"
        >
          <linear-statistic
            class="my-4"
            :title="$vuetify.lang.t('$vuetify.dashboard.orders')"
            :sub-title="$vuetify.lang.t('$vuetify.dashboard.ordersSub')"
            :quantity="salesStatics.totalOrders ? salesStatics.totalOrders.toString(): 0"
            icon="mdi-trending-up"
            color="info"
            :value="100"
          />
        </v-col>
        <v-col
          cols="6"
          md="3"
        >
          <linear-statistic
            class="my-4"
            :title="$vuetify.lang.t('$vuetify.dashboard.revenue')"
            :sub-title="$vuetify.lang.t('$vuetify.dashboard.revenueSub')"
            :currency="user.company.currency"
            :quantity="salesStatics.totalRevenue ? salesStatics.totalRevenue.toString(): 0"
            icon="mdi-trending-up"
            color="primary"
            :value="100"
          />
        </v-col>
        <v-col
          cols="6"
          md="3"
        >
          <linear-statistic
            class="mt-4"
            :title="$vuetify.lang.t('$vuetify.dashboard.costs')"
            :sub-title="$vuetify.lang.t('$vuetify.dashboard.costsSub')"
            :currency="user.company.currency"
            :quantity="salesStatics.totalExpenses ? salesStatics.totalExpenses.toString(): 0"
            icon="mdi-trending-down"
            color="orange"
            :value="100"
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
          <span style="text-transform: uppercase">{{ item.name }}</span>
        </v-tab>
      </v-tabs>
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <v-card flat>
            <v-card-text>
              <v-row>
                <v-col
                  v-for="(st,i) in statistic"
                  v-show="statistic.length > 0"
                  :key="i"
                  md="3"
                >
                  <mini-statistic
                    color="primary"
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
                      md="3"
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
        <v-tab-item>
          <v-card flat>
            <v-card-text>
              <v-row>
                <v-col md="6">
                  <plain-table
                    v-if="articlesMerge.length > 0"
                    :title="$vuetify.lang.t('$vuetify.dashboard.mergeTable')"
                    :headers="columnHeaders"
                    :items="articlesMerge"
                  />
                  <v-widget
                    v-else
                    :title="$vuetify.lang.t('$vuetify.dashboard.mergeTable')"
                    content-bg="white"
                  >
                    <div slot="widget-content">
                      <mini-statistic
                        color="primary"
                        icon="mdi-shopping"
                        :sub-title="$vuetify.lang.t('$vuetify.access.access.manager_article')"
                        @click="goToClick('product_list')"
                      />
                    </div>
                  </v-widget>
                </v-col>
                <v-col md="6">
                  <v-widget
                    :title="$vuetify.lang.t('$vuetify.dashboard.timeLine')"
                    content-bg="white"
                  >
                    <div slot="widget-content">
                      <v-timeline
                        v-if="salesByLimit.length > 0"
                        align-top
                        dense
                      >
                        <v-timeline-item
                          v-for="(item, index) in salesByLimit"
                          :key="index"
                          :color="item.color"
                          small
                        >
                          <v-row class="pt-1">
                            <v-col cols="3">
                              <strong>{{ item.timeString }}</strong>
                            </v-col>
                            <v-col>
                              <strong :style="{color:item.color}">{{ item.status }}</strong>
                              <div>{{ item.text }}</div>
                            </v-col>
                          </v-row>
                        </v-timeline-item>
                      </v-timeline>
                      <mini-statistic
                        v-else
                        color="primary"
                        icon="mdi-currency-usd"
                        :sub-title="$vuetify.lang.t('$vuetify.access.access.manager_vending')"
                        @click="goToClick('vending')"
                      />
                    </div>
                  </v-widget>
                </v-col>
                <!--<v-col
                  v-for="(item, index) in trending"
                  :key="'c-trending' + index"
                  cols="4"
                >
                  <circle-statistic
                    :title="item.subheading"
                    :sub-title="item.headline"
                    :caption="item.caption"
                    :icon="item.icon.label"
                    :color="item.linear.color"
                    :value="item.linear.value"
                  />
                </v-col>-->
              </v-row>
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs-items>
    </v-container>
  </div>
</template>

<script>
import Material from 'vuetify/es5/util/colors'
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'PageDashboard',
  data () {
    return {
      tab: null,
      timer: '',
      loadingData: false,
      color: Material,
      statistic: [],
      statisticDeny: [],
      localSalesByPayments: [],
      localSalesByCategories: [],
      tabName: [
        {
          name: this.$vuetify.lang.t('$vuetify.dashboard.access'),
          icon: 'mdi-key',
          access: 'manager_access'
        },
        {
          name: this.$vuetify.lang.t('$vuetify.dashboard.info'),
          icon: 'mdi-info',
          access: 'manager_access'
        }
      ],
      trending: [
        {
          subheading: 'Email',
          headline: '15+',
          caption: 'email opens',
          percent: 15,
          icon: {
            label: 'email',
            color: 'info'
          },
          linear: {
            value: 15,
            color: 'info'
          }
        },
        {
          subheading: 'Tasks',
          headline: '90%',
          caption: 'tasks completed.',
          percent: 90,
          icon: {
            label: 'list',
            color: 'primary'
          },
          linear: {
            value: 90,
            color: 'success'
          }
        },
        {
          subheading: 'Issues',
          headline: '100%',
          caption: 'issues fixed.',
          percent: 100,
          icon: {
            label: 'bug_report',
            color: 'primary'
          },
          linear: {
            value: 100,
            color: 'error'
          }
        }
      ]
    }
  },
  computed: {
    ...mapState('sale', ['salesByLimit', 'salesStatics']),
    ...mapState('article', ['articlesMerge']),
    ...mapGetters('auth', ['user', 'access_permit']),
    tabsData () {
      const result = {
        tabName: [],
        itemsTabs: []
      }
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
    },
    columnHeaders () {
      return [
        {
          text: '',
          align: 'center',
          sortable: false,
          value: 'avatar'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          align: 'left',
          value: 'name'
        }, {
          text: this.$vuetify.lang.t('$vuetify.articles.price'),
          align: 'left',
          value: 'price'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.cost'),
          align: 'left',
          value: 'cost'
        }, {
          text: this.$vuetify.lang.t('$vuetify.articles.percent'),
          value: 'progress'
        }
      ]
    }
  },
  watch: {
    access_permit: function () {
      this.completeStaticTic()
    }
  },
  async created () {
    this.loadingData = true
    await this.getUserLogin()
    await this.completeStaticTic()
    await this.getSaleByLimit(5)
    await this.getArticlesMerge()
    await this.getSaleStatics()
    this.timer = await setTimeout(() => {
      this.getSaleByLimit(5)
      this.getArticlesMerge()
      this.getSaleStatics()
    }, 60000)
    this.loadingData = false
  },
  beforeDestroy () {
    clearInterval(this.timer)
  },
  methods: {
    ...mapActions('user', ['getUserLogin']),
    ...mapActions('sale', ['getSaleByLimit', 'getSaleStatics']),
    ...mapActions('article', ['getArticlesMerge']),
    completeStaticTic () {
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
        this.access_permit.filter(permit => permit.title.name === 'manager_boxes')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-database',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_boxes'),
          goToClick: 'boxes'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_boxes')
        })
        this.access_permit.filter(permit => permit.title.name === 'manager_refunds')[0].title.value === true ? this.statistic.push({
          icon: 'mdi-undo',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_refunds'),
          goToClick: 'refund'
        }) : this.statisticDeny.push({
          icon: 'mdi-close',
          sub_title: this.$vuetify.lang.t('$vuetify.access.access.manager_refunds')
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
