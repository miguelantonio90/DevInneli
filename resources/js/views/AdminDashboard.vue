<template>
  <v-container>
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

export default {
  name: 'AdminDashboard',
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
  created () {
    this.getCompanies()
  },
  methods: {
    ...mapActions('company', ['getCompanies'])
  }
}
</script>
