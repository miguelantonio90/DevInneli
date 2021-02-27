<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="taxes"
          :manager="'tax'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          :title="
            $vuetify.lang.t('$vuetify.titles.list', [
              $vuetify.lang.t('$vuetify.menu.tax_list')
            ])
          "
          csv-filename="Taxes"
          multi-sort
        >
          <template v-slot:[`item.company.name`]="{ item }">
            <template>
              {{ item.company.name }}
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-chip
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-avatar left>
                      {{
                        arrayCountry.filter(
                          cou =>
                            cou.id ===
                            item.company.country
                        )[0].emoji
                      }}
                    </v-avatar>
                    {{ item.country }}
                  </v-chip>
                </template>
                <span>{{
                  arrayCountry.filter(
                    cou => cou.id === item.company.country
                  )[0].name
                }}</span>
              </v-tooltip>
            </template>
          </template>
          <template v-slot:[`item.percent`]="{ item }">
            {{
              item.percent === 'true'
                ? $vuetify.lang.t('$vuetify.tax.percent')
                : $vuetify.lang.t('$vuetify.tax.permanent')
            }}
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'ListTaxAdmin',
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('tax', ['taxes', 'isTableLoading']),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'company.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.value'),
          value: 'value'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.tax.rate'),
          value: 'percent'
        }
      ]
    }
  },
  created () {
    this.getTaxes()
  },
  methods: {
    ...mapActions('tax', ['getTaxes'])
  }
}
</script>

<style scoped></style>
