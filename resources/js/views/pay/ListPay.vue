<template>
  <v-container>
    <new-payment
      v-if="showNewModal"
      :pays="sale.pays"
      :payments="payments"
      :currency="currency"
      :pending="totalPrice - totalPays"
      @addPayment="addToPayment"
    />
    <v-card>
      <v-card-text>
        <v-row>
          <v-col
            cols="12"
            md="4"
          >
            <facture
              :edit="true"
              :sale="sale"
              :total-price="parseFloat(totalPrice).toFixed(2)"
              :total-tax="parseFloat(totalTax).toFixed(2)"
              :total-discount="parseFloat(totalDiscount).toFixed(2)"
              :sub-total="parseFloat(subTotal).toFixed(2)"
              :currency="currency || ''"
              @updateData="update = false"
            />
          </v-col>
          <v-col
            class="py-0"
            cols="12"
            md="8"
          >
            <app-data-table
              style="margin-top: 10px"
              :view-show-filter="false"
              :view-edit-button="false"
              :view-new-button="show"
              :view-tour-button="show"
              :hide-footer="!show"
              :view-delete-button="show"
              :title="show ?$vuetify.lang.t('$vuetify.variants.total_price') +': '+ currency+' ' + parseFloat(totalPrice).toFixed(2): ''"
              csv-filename="Categories"
              :headers="getTableColumns"
              :items="sale.pays"
              :manager="'payment'"
              :sort-by="['name']"
              :sort-desc="[false, true]"
              multi-sort
              @create-row="addNewPayment"
              @delete-row="deletePaymentHandler($event)"
            >
              <template v-slot:[`item.method`]="{ item }">
                {{ $vuetify.lang.t('$vuetify.payment.' + item.method) }}
              </template>
              <template v-slot:[`item.name`]="{ item }">
                {{ $vuetify.lang.t('$vuetify.payment.' + item.name) }}
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
                  <div>{{ `${currency + ' ' + parseFloat(item.cant).toFixed(2)}` }}</div>
                  <template v-slot:input>
                    <div class="mt-4 title">
                      {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                    </div>
                    <v-text-field-money
                      v-model="cant"
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
              <template v-slot:[`item.cant_pay`]="{ item }">
                <div v-if="item.method ==='cash'">
                  {{ `${ item.currency.currency ? item.currency.currency : currency }` }} {{ parseFloat(item.cant_pay).toFixed(2) }}
                </div>
                <div v-else>
                  --
                </div>
              </template>
              <template v-slot:[`item.cant_back`]="{ item }">
                <div v-if="item.method ==='cash'">
                  {{ `${item.cant_back? currency:'' }` }} {{ parseFloat(item.cant_back).toFixed(2) }}
                </div>
                <div v-else>
                  --
                </div>
              </template>
            </app-data-table>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-container>
</template>
<script>

import { mapActions, mapState } from 'vuex'
import NewPayment from './NewPay'
import Facture from '../sales/Facture'
export default {
  name: 'ListPay',
  components: {
    NewPayment,
    Facture
  },
  props: {
    edit: {
      type: Boolean,
      default: false
    },
    totalPrice: {
      type: String,
      default: '0.00'
    },
    totalTax: {
      type: String,
      default: '0.00'
    },
    totalDiscount: {
      type: String,
      default: '0.00'
    },
    subTotal: {
      type: String,
      default: '0.00'
    },
    sale: {
      type: Object,
      default: function () {
        return {}
      }
    },
    show: {
      type: Boolean,
      default: true
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
    getTableColumns () {
      const data = [
        {
          text: this.$vuetify.lang.t('$vuetify.payment.name'),
          value: 'method',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.payment.cant_pay'),
          value: 'cant_pay',
          sortable: false
        },
        {
          text: this.$vuetify.lang.t('$vuetify.payment.cant_charge'),
          value: 'cant',
          sortable: false
        },
        {
          text: this.$vuetify.lang.t('$vuetify.payment.cant_back'),
          value: 'cant_back',
          sortable: false
        }
      ]
      if (this.show) {
        data.push(
          {
            text: this.$vuetify.lang.t('$vuetify.actions.actions'),
            value: 'actions',
            sortable: false
          })
      }
      return data
    }
  },
  watch: {
    'sale.pays': function () {
      this.calcTotalPay()
    }
  },
  created () {
    this.getPayments()
    this.calcTotalPay()
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
      if (this.totalPays < this.totalPrice) { this.toogleNewModal(true) } else {
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
        .then(() => {
          this.sale.pays.splice(this.sale.pays.indexOf(item), 1)
        })
    },
    addToPayment (pay) {
      this.sale.pays.push({
        name: pay.method.name,
        method: pay.method.method,
        cant: pay.cant,
        mora: pay.mora,
        cantMora: pay.cantMora,
        payment_id: pay.method.id,
        cant_pay: pay.cant_pay,
        currency: pay.currency,
        cant_back: pay.cant_back
      })
      this.toogleNewModal(false)
      this.calcTotalPay()
    },
    openEditCant (item) {
      this.cant = item.cant
    },
    saveEditCant (item) {
      if (this.totalPays - item.cant + parseFloat(this.cant) <= this.totalPrice) {
        item.cant = this.cant
      } else {
        item.cant = parseFloat((this.totalPrice - this.totalPays).toString()) + parseFloat(item.cant)
      }
      item.cant = parseFloat((item.cant).toString()).toFixed(2)
      item.cant_back = item.currency.id !== '' ? item.cant_pay * item.currency.change - item.cant : item.cant_pay - item.cant
      this.calcTotalPay()
    },
    calcTotalPay () {
      this.totalPays = 0.00
      this.sale.pays.forEach(v => {
        this.totalPays += parseFloat(v.cant)
      })
    }
  }
}
</script>

<style scoped>

</style>
