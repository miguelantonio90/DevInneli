<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="
            $vuetify.lang.t('$vuetify.titles.list', [
              $vuetify.lang.t('$vuetify.menu.client')
            ])
          "
          csv-filename="Categories"
          :headers="getTableColumns"
          :items="clients"
          :manager="'client'"
          :sort-by="['firstName']"
          :sort-desc="[false, true]"
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
                    {{ item.company.country }}
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
          <template v-slot:[`item.firstName`]="{ item }">
            <v-avatar>
              <v-img
                :src="
                  item.avatar ||
                    `/assets/avatar/avatar-undefined.jpg`
                "
              />
            </v-avatar>
            {{ item.firstName }}
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
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('client', ['clients', 'isTableLoading']),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'company.name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'firstName',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.lastName'),
          value: 'lastName',
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
        }
      ]
    }
  },
  created () {
    this.getClients()
  },
  methods: {
    ...mapActions('client', ['getClients'])
  }
}
</script>

<style scoped></style>
