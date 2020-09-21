<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-employment v-if="showNewModal" />
        <edit-employment v-if="showEditModal" />
        <show-employment v-if="showShowModal" />
        <v-card>
          <v-card-title>
            {{
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.employment'),
              ])
            }}
          </v-card-title>
          <v-data-table
            :headers="getUserTableColumns"
            :items="employments"
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
                @click="deleteEmploymentHandler(item.id)"
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
import NewEmployment from './NewEmployment'
import EditEmployment from './EditEmployment'
import ShowEmployment from './ShowEmployment'

export default {
  components: {
    ShowEmployment,
    NewEmployment,
    EditEmployment
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('employment', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'employments',
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
          text: this.$vuetify.lang.t('$vuetify.position'),
          value: 'position.name'
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
    this.getEmployments()
  },
  methods: {
    ...mapActions('employment', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getEmployments',
      'deleteEmployment'
    ]),
    deleteEmploymentHandler (userId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.employment')
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
          if (result.value) this.deleteEmployment(userId)
        })
    }
  }
}
</script>

<style scoped></style>
