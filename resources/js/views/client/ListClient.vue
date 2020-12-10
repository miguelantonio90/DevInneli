<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-client v-if="showNewModal" />
        <edit-client v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.client'),])"
          csv-filename="Categories"
          :headers="getTableColumns"
          :items="clients"
          :manager="'client'"
          :sort-by="['firstName']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="editClientHandler($event)"
          @delete-row="deleteClientHandler($event)"
        >
          <template
            v-slot:[`item.firstName`]="{ item }"
          >
            <v-avatar>
              <v-img :src="item.avatar || `/assets/avatar/avatar-undefined.jpg`" />
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
import { mapActions, mapState } from 'vuex'
import EditClient from '../client/EditClient'
import NewClient from '../client/NewClient'

export default {
  components: {
    EditClient,
    NewClient
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('client', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'clients',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
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
    this.getClients()
  },
  methods: {
    ...mapActions('client', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getClients',
      'deleteClient'
    ]),
    editClientHandler ($event) {
      this.openEditModal($event)
    },
    deleteClientHandler (clientId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.client')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.warning_delete'
          ),
          icon: 'warning',
          showCancelButton: true,
          cancelButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.cancel'
          ),
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.delete'
          ),
          confirmButtonColor: 'red'
        })
        .then((result) => {
          if (result.value) this.deleteClient(clientId)
        })
    }
  }
}
</script>

<style scoped></style>
