<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <v-card>
          <app-data-table
            :title="$vuetify.lang.t('$vuetify.titles.list',
                                    [$vuetify.lang.t('$vuetify.menu.shop'),])"

            :is-loading="isTableLoading"
            csv-filename="Configs"
            :headers="getTableColumns"
            :items="configs"
            :manager="'shop'"
            :sort-by="['name']"
            :sort-desc="[false, true]"
            multi-sort
            @create-row="newConfigHandler"
            @edit-row="editConfigHandler($event)"
            @delete-row="deleteConfigHandler($event)"
          >
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
import { mapActions, mapState } from 'vuex'

export default {
  name: 'ListOnlineConfig',
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('online', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'configs',
      'managerConfig',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.online.template'),
          value: 'template',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shop.name',
          select_filter: true
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
    this.getConfigs()
  },
  methods: {
    ...mapActions('online', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getConfigs',
      'deleteConfig'
    ]),
    newConfigHandler () {
      this.$store.state.online.managerConfig = false
      this.$router.push({ name: 'config_add' })
    },
    editConfigHandler ($event) {
      this.$store.state.online.managerConfig = true
      this.openEditModal($event)
      this.$router.push({ name: 'config_edit' })
    },
    deleteConfigHandler (shopId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.no_action', [
            this.$vuetify.lang.t('$vuetify.actions.delete')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.error_delete_shop'
          ),
          icon: 'warning',
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
    }
  }
}
</script>

<style scoped>

</style>
