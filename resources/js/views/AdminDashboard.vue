<template>
  <v-container>
    <v-row>
      <!-- mini statistic start -->
      <v-col cols="3">
        <mini-statistic
          color="indigo"
          icon="mdi-home"
          :title="$vuetify.lang.t('$vuetify.company')"
          :sub-title="companies.length.toString()"
        />
      </v-col>
      <v-col cols="3">
        <mini-statistic
          color="red"
          icon="mdi-shopping"
          :title="$vuetify.lang.t('$vuetify.menu.shop')"
          :sub-title="cantShops.toString()"
        />
      </v-col>
      <v-col cols="3">
        <mini-statistic
          color="light-blue"
          icon="mdi-account-star"
          :title="$vuetify.lang.t('$vuetify.menu.user')"
          :sub-title="cantEmployers.toString()"
        />
      </v-col>
      <v-col cols="3">
        <mini-statistic
          color="purple"
          icon="mdi-instagram"
          :title="$vuetify.lang.t('$vuetify.menu.articles')"
          :sub-title="cantArticles.toString()"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.company')])"

          :is-loading="isActionInProgress"
          csv-filename="Access"
          :headers="getTableColumns"
          :items="companies"
          :manager="'access'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
        >
          <template v-slot:[`item.country`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-avatar left>
                    {{ arrayCountry.filter(cou=>cou.id===item.country)[0].emoji }}
                  </v-avatar>
                  {{ item.country }}
                </v-chip>
              </template>
              <span>{{ item.country }}</span>
            </v-tooltip>
          </template>
          <template v-slot:[`item.employers`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  class="mr-3"
                  v-on="on"
                  v-text="'mdi-account-star'"
                />
                {{ item.employers.length }}
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.titles.show', [
                $vuetify.lang.t('$vuetify.menu.employee'),
              ]) }}</span>
            </v-tooltip>
          </template>
          <template v-slot:[`item.suppliers`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  class="mr-3"
                  v-on="on"
                  v-text="'mdi-account-multiple'"
                />
                {{ item.suppliers.length }}
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.titles.show', [
                $vuetify.lang.t('$vuetify.menu.supplier'),
              ]) }}</span>
            </v-tooltip>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex'
import MiniStatistic from '../components/widgets/statistic/MiniStatistic'

export default {
  name: 'AdminDashboard',
  components: {
    MiniStatistic
  },
  data () {
    return {
      cantArticles: 0,
      cantShops: 0,
      cantEmployers: 0
    }
  },
  computed: {
    ...mapState('company', [
      'companies',
      'isActionInProgress'
    ]),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'country'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.email'),
          value: 'email'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.currency'),
          value: 'currency'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.employee'),
          value: 'employers'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.supplier'),
          value: 'suppliers'
        }
      ]
    }
  },
  watch: {
    companies: function () {
      this.cantEmployers = 0
      this.cantShops = 0
      this.cantArticles = 0
      this.companies.forEach((v) => {
        this.cantEmployers += v.employers.length
        this.cantShops += v.shops.length
        this.cantArticles += v.articles.length
      })
    }
  },
  created () {
    this.getCompanies()
  },
  methods: {
    ...mapActions('company', ['getCompanies'])
  }
}
</script>
