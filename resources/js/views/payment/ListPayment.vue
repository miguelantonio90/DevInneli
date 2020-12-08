<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-payment v-if="showNewModal" />
        <edit-payment v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.menu.pay')"
          csv-filename="Categories"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="payments"
          :manager="'payment'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="editPaymentHandler($event)"
          @delete-row="deletePaymentHandler($event)"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewPayment from './NewPayment'
import EditPayment from './EditPayment'
export default {
  name: 'ListPayment',
  components: {
    NewPayment,
    EditPayment
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('payment', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'payments',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.pay'),
          value: 'enEs'
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
    this.getPayments()
  },
  methods: {
    ...mapActions('payment', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getPayments',
      'deletePayment'
    ]),
    editPaymentHandler ($event) {
      this.openEditModal($event)
    },
    deletePaymentHandler (id) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.pay')
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
          if (result.value) this.deletePayment(id)
        })
    }
  }
}
</script>

<style scoped>

</style>
