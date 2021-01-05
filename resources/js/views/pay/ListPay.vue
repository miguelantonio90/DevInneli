<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-payment
          v-if="showNewModal"
          :pays="pays"
          :payments="payments"
          :pending="total - totalPays"
          @addPayment="addToPayment"
        />
        <app-data-table
          :view-show-filter="false"
          :view-edit-button="false"
          :view-new-button="true"
          :view-delete-button="true"
          :title="$vuetify.lang.t('$vuetify.variants.total_price') +': '+ currency+' ' + parseFloat(total).toFixed(2)"
          csv-filename="Categories"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="pays"
          :manager="'payment'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="addNewPayment"
          @delete-row="deletePaymentHandler($event)"
        >
          <template v-slot:[`item.method.method`]="{ item }">
            {{ $vuetify.lang.t('$vuetify.payment.' + item.method.method) }}
          </template>
          <template v-slot:[`item.name`]="{ item }">
            {{ $vuetify.lang.t('$vuetify.payment.' + item.method.name) }}
          </template>

          <template v-slot:[`item.cant`]="{ item }">
            <v-edit-dialog
              large
              persistent
              :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
              :save-text="$vuetify.lang.t('$vuetify.actions.save')"
              @open="openEditCant(item)"
              @save="saveEditCant(item)"
            >
              <div>{{ `${currency + ' ' + item.cant}` }}</div>
              <template v-slot:input>
                <div class="mt-4 title">
                  {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                </div>
                <v-text-field-money
                  v-model="cant"
                  :rules="numberRules"
                  :label="$vuetify.lang.t('$vuetify.actions.edit')"
                  required
                  :properties="{
                    clearable: true
                  }"
                  :options="{
                    length: 15,
                    precision: 2,
                    empty: 0.00,
                  }"
                />
              </template>
            </v-edit-dialog>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>

import { mapActions, mapState } from 'vuex'
import NewPayment from './NewPay'
export default {
  name: 'ListPay',
  components: {
    NewPayment
  },
  props: {
    edit: {
      type: Boolean,
      default: false
    },
    total: {
      type: Number,
      default: 0.00
    },
    currency: {
      type: String,
      default: ''
    }

  },
  data () {
    return {
      search: '',
      cant: 0.00,
      totalPays: 0.00,
      pays: [],
      numberRules: [
        v => !!v || 'Input is required!',
        v =>
          v < this.maxLength ||
                `${this.errorName} must be less than ${this.maxLength} numbers`,
        v =>
          v > this.minLength ||
                `${this.errorName} must be greater than ${this.minLength} numbers`
      ],
      maxLength: 100,
      minLength: 0,
      errorName: ''
    }
  },
  computed: {
    ...mapState('payment', [
      'payments',
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'isTableLoading'
    ]),
    ...mapState('sale', ['newSale', 'editSale']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.pay.pay'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.payment.name'),
          value: 'method.method',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cant'),
          value: 'cant',
          sortable: false
        },
        {
          text: this.$vuetify.lang.t('$vuetify.actions.actions'),
          value: 'actions',
          sortable: false
        }
      ]
    }
  },
  watch: {
    pays: function () {
      this.calcTotalPay()
      this.updateStore()
    }
  },
  created () {
    this.getPayments()
    this.pays = this.edit ? this.editSale.pays : this.newSale.pays
  },
  methods: {
    ...mapActions('payment', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getPayments',
      'deletePayment'
    ]),
    addNewPayment () {
      if (this.totalPays < this.total) { this.toogleNewModal(true) } else {
        this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.new', [
              this.$vuetify.lang.t('$vuetify.payment.name')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.warning_excess_money'
            ),
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'red'
          })
      }
    },
    updateStore () {
      if (this.edit) {
        this.editSale.pays = this.pays
      } else {
        this.newSale.pays = this.pays
      }
    },
    deletePaymentHandler (item) {
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
          this.pays.splice(this.pays.indexOf(item), 1)
        })
    },
    addToPayment (pay) {
      this.pays.push(pay)
      this.toogleNewModal(false)
      this.calcTotalPay()
      this.updateStore()
    },
    openEditCant (item) {
      this.cant = item.cant
    },
    saveEditCant (item) {
      if (this.totalPays - item.cant + parseFloat(this.cant) <= this.total) {
        item.cant = this.cant
      } else {
        item.cant = parseFloat((this.total - this.totalPays).toString()) + parseFloat(item.cant)
      }
      item.cant = parseFloat((item.cant).toString()).toFixed(2)
      this.calcTotalPay()
      this.updateStore()
    },
    calcTotalPay () {
      this.totalPays = 0.00
      this.pays.forEach(v => {
        this.totalPays += parseFloat(v.cant)
      })
    }
  }
}
</script>

<style scoped>

</style>