<template>
  <v-container>
    <v-row>
      <v-col
          class="py-0"
          cols="12"
      >
        <v-card>
          <app-data-table
              :headers="getTableColumns"
              :is-loading="isTableLoading"
              :items="shops"
              :manager="'shop'"
              :sort-by="['name']"
              :sort-desc="[false, true]"
              :title="
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.shop')
              ])
            "
              csv-filename="Shops"
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
                          cou =>
                              cou.id === item.company.country
                      )[0].name
                    }}</span>
                </v-tooltip>
              </template>
            </template>
            <template v-slot:[`item.name`]="{ item }">
              <v-icon>mdi-shopping</v-icon>
              {{ item.name }}
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
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'ListShopAdmin',
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('shop', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'shops',
      'isTableLoading'
    ]),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'company.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.name'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'nameCountry',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.phone'),
          value: 'phone'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.actions.actions'),
          value: 'actions',
          sortable: false
        }
      ]
    }
  },
  created () {
    this.getShops()
  },
  methods: {
    ...mapActions('shop', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getShops',
      'deleteShop'
    ])
  }
}
</script>

<style scoped></style>
