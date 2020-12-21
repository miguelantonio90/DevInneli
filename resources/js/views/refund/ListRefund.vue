<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <edit-refound v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.refund'),])"
          csv-filename="Refunds"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="refunds"
          :view-new-button="false"
          :manager="'refunds'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @edit-row="editPaymentHandler($event)"
          @delete-row="deletePaymentHandler($event)"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import EditRefound from './EditRefound'
export default {
  name: 'ListPayment',
  components: {
    EditRefound
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('refund', [
      'showEditModal',
      'refunds',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.tax.noFacture'),
          value: 'facture',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.name'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cant'),
          value: 'cant',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.payment.cash'),
          value: 'money'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.date'),
          value: 'created_at'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.created_by'),
          value: 'created_by'
        }
      ]
    }
  },
  created () {
    this.getRefunds()
  },
  methods: {
    ...mapActions('refund', [
      'toogleNewModal',
      'openEditModal',
      'getRefunds',
      'deleteRefund'
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
          if (result.value) this.deleteRefund(id)
        })
    }
  }
}
</script>

<style scoped>

</style>
