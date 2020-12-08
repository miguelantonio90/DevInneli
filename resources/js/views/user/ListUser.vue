<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-user v-if="showNewModal" />
        <edit-user v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.user'),])"

          :is-loading="isTableLoading"
          csv-filename="Employees"
          :headers="getTableColumns"
          :items="users"
          :manager="'employer'"
          :sort-by="['firstName']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="editUserHandler($event)"
          @delete-row="deleteUserHandler($event)"
        >
          <template
            v-slot:[`item.firstName`]="{ item }"
          >
            <v-avatar>
              <v-img :src="item.avatar || `/assets/avatar/avatar-undefined.jpg`" />
            </v-avatar>
            {{ item.firstName }}
          </template>
          <template v-slot:[`item.shopsNames`]="{ item }">
            <v-chip
              v-for="(shop, i) of item.shopsNames"
              :key="i"
            >
              {{ shop }}
            </v-chip>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewUser from './NewUser'
import EditUser from './EditUser'

export default {
  components: {
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
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'firstName',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.lastName'),
          value: 'lastName'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.email'),
          value: 'email'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.position'),
          value: 'position.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shopsNames',
          select_filter_many: true
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
    editUserHandler ($event) {
      this.openEditModal($event)
    },
    deleteUserHandler (userId) {
      (this.users.filter(c => c.id === userId)[0].position.key === 'super_manager') ? this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.no_action', [
            this.$vuetify.lang.t('$vuetify.actions.delete')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.error_delete_manager'
          ),
          icon: 'warning',
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
        .then((result) => {
        }) : this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.user')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.warning_delete'
          ),
          showCancelButton: true,
          cancelButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.cancel'
          ),
          icon: 'warning',
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
