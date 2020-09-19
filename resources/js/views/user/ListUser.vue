<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-user v-if="showNewModal" />
        <edit-user v-if="showEditModal" />
        <show-user v-if="showShowModal" />
        <v-card>
          <v-card-title>
            {{
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.user.user'),
              ])
            }}
          </v-card-title>
          <v-data-table
            :headers="getUserTableColumns"
            :items="users"
            :loading="isTableLoading"
            :search="search"
            class="elevation-1"
            sort-by="firstName"
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
                  <v-icon>mdi-account-plus</v-icon>
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
                @click="deleteUserHandler(item.id)"
              >
                mdi-delete
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
import NewUser from '../../views/user/NewUser'
import EditUser from '../../views/user/EditUser'
import ShowUser from '../../views/user/ShowUser'

export default {
  components: {
    ShowUser,
    NewUser,
    EditUser
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('user', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'users',
      'isTableLoading'
    ]),
    getUserTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'firstName'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.lastName'),
          value: 'lastName'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.username'),
          value: 'username'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.email'),
          value: 'email'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'country'
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
    this.getUsers()
  },
  methods: {
    ...mapActions('user', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getUsers',
      'deleteUser'
    ]),
    deleteUserHandler (userId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.user.user')
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
          if (result.value) this.deleteUser(userId)
        })
    }
  }
}
</script>

<style scoped></style>
