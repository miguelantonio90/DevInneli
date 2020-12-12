<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.supplier')])"
          csv-filename="Categories"
          :headers="getTableColumns"
          :items="suppliers"
          :manager="'supplier'"
          :sort-by="['firstName']"
          :sort-desc="[false, true]"
          multi-sort
        >
          <template v-slot:[`item.company.name`]="{ item }">
            {{ item.company.name }}
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-avatar left>
                    {{ arrayCountry.filter(cou=>cou.id===item.company.country)[0].emoji }}
                  </v-avatar>
                  {{ item.company.country }}
                </v-chip>
              </template>
              <span>{{ arrayCountry.filter(cou=>cou.id===item.company.country)[0].name }}</span>
            </v-tooltip>
          </template>
          <template v-slot:[`item.nameCountry`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-avatar left>
                    {{ item.countryFlag }}
                  </v-avatar>
                  {{ item.country }}
                </v-chip>
              </template>
              <span>{{ item.nameCountry }}</span>
            </v-tooltip>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'ListSupplierAdmin',
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('supplier', [
      'suppliers',
      'isTableLoading'
    ]),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'company.name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.email'),
          value: 'email'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'nameCountry',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.phone'),
          value: 'phone',
          select_filter: true
        }
      ]
    }
  },
  created () {
    this.getSuppliers()
  },
  methods: {
    ...mapActions('supplier', ['getSuppliers'])
  }
}
</script>

<style scoped></style>
