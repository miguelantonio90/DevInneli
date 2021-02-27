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
              :items="keys"
              :manager="'key'"
              :sort-by="['key']"
              :sort-desc="[false, true]"
              :title="
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.keys')
              ])
            "
              csv-filename="Keys"
              multi-sort
          >
            <template v-slot:[`item.name`]="{ item }">
              <template>
                {{ item.name }}
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
                                  item.country
                          )[0].emoji
                        }}
                      </v-avatar>
                      {{ item.country }}
                    </v-chip>
                  </template>
                  <span>{{
                      arrayCountry.filter(
                          cou => cou.id === item.country
                      )[0].name
                    }}</span>
                </v-tooltip>
              </template>
            </template>
            <template v-slot:[`item.key`]="{ item }">
              <v-icon>mdi-account-key</v-icon>
              {{ item.key }}
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
  name: 'ListKeysAdmin',
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('keys', ['keys', 'isTableLoading']),
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.access.name'),
          value: 'key',
          select_filter: true
        }
      ]
    }
  },
  created () {
    this.getKeys()
  },
  methods: {
    ...mapActions('keys', ['getKeys'])
  }
}
</script>

<style scoped></style>
