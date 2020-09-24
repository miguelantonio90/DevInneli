<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-access v-if="showNewModal" />
        <edit-access v-if="showEditModal" />
        <v-card>
          <v-card-title>
            {{
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.access'),
              ])
            }}
          </v-card-title>
          <v-data-table
            :headers="getTableColumns"
            :items="roles"
            :loading="isTableLoading"
            :search="search"
            class="elevation-1"
            sort-by="name"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-text-field
                  v-model="search"
                  :label="
                    $vuetify.lang.t('$vuetify.actions.search')
                  "
                  append-icon="mdi-magnify"
                  hide-details
                  single-line
                />
                <v-spacer />
                <v-btn
                  class="mb-2"
                  color="primary"
                  @click="toogleNewModal(true)"
                >
                  <v-icon>mdi-plus</v-icon>
                  {{ $vuetify.lang.t('$vuetify.actions.new') }}
                </v-btn>
              </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
              <v-icon
                class="mr-2"
                small
                @click="openShowModal(item.id)"
              >
                mdi-eye
              </v-icon>
              <v-icon
                class="mr-2"
                small
                @click="openEditModal(item.id)"
              >
                mdi-pencil
              </v-icon>
              <v-icon
                small
                @click="deleteRoleHandler(item.id)"
              >
                mdi-delete
              </v-icon>
            </template>
            <template v-slot:item.name="{ item }">
              <v-icon>mdi-account-key</v-icon>
              {{ item.name }}
            </template>
            <template v-slot:item.accessEmail="{ item }">
              <v-icon
                v-if="item.accessEmail"
                small
              >
                mdi-check
              </v-icon>
              <v-icon
                v-else
                small
              >
                mdi-cancel
              </v-icon>
            </template>
            <template v-slot:item.accessPin="{ item }">
              <v-icon
                v-if="item.accessPin"
                small
              >
                mdi-check
              </v-icon>
              <v-icon
                v-else
                small
              >
                mdi-cancel
              </v-icon>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewAccess from './NewAccess'
import EditAccess from './EditAccess'

export default {
  components: {
    NewAccess,
    EditAccess
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('role', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'roles',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.access.name'),
          value: 'name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.access.accessPin'),
          value: 'accessPin'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.access.accessEmail'),
          value: 'accessEmail'
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
    this.getRoles()
  },
  methods: {
    ...mapActions('role', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getRoles',
      'deleteRole'
    ]),
    deleteRoleHandler (roleId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.access')
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
          if (result.value) this.deleteUser(roleId)
        })
    }
  }
}
</script>

<style scoped></style>
