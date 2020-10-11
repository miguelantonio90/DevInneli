<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-assistance v-if="showNewModal" />
        <edit-assistance v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.assistance'),])"
          :columns="getUserTableColumns"
          :rows="assistances"
          :is-loading="isTableLoading"
          sort-options="firstName"
          show-avatar
          @create-row="toogleNewModal(true)"
          @edit-row="editAssistanceHandler($event)"
          @delete-row="deleteAssistanceHandler($event)"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import EditAssistance from './EditAssistance'
import NewAssistance from './NewAssistance'

export default {
  components: {
    EditAssistance,
    NewAssistance
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('assistance', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'assistances',
      'isTableLoading'
    ]),
    getUserTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.assistance.entry'),
          value: 'datetimeEntry'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.assistance.exit'),
          value: 'datetimeExit'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.employee'),
          value: 'user.firstName'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shop.name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.assistance.total_hours'),
          value: 'totalHours'
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
    this.getAssistances()
  },
  methods: {
    ...mapActions('assistance', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getAssistances',
      'deleteAssistance'
    ]),
    editAssistanceHandler ($event) {
      this.openEditModal($event)
    },
    deleteAssistanceHandler (assistanceId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.assistance')
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
          if (result.value) this.deleteAssistance(assistanceId)
        })
    }
  }
}
</script>

<style scoped></style>
