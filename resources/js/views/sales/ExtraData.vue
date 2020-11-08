<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <v-select
          v-model="sale.client"
          clearable
          :items="clients"
          :label="$vuetify.lang.t('$vuetify.menu.client')"
          item-text="firstName"
          :loading="isClientTableLoading"
          :disabled="!!isClientTableLoading"
          return-object
          required
          :rules="formRule.country"
          @input="updateStore"
        >
          <template v-slot:append-outer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  @click="$store.dispatch('client/toogleNewModal',true)"
                >
                  mdi-plus
                </v-icon>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
            </v-tooltip>
          </template>
        </v-select>
      </v-col>
      <v-col cols="6">
        <v-text-field
          v-model="sale.no_facture"
          :label="$vuetify.lang.t('$vuetify.tax.noFacture')"
          required
          :rules="formRule.required"
          @onchange="updateStore"
        />
      </v-col>
      <v-col cols="6">
        <v-select
          v-model="sale.taxes"
          chips
          clearable
          deletable-chips
          :items="taxes"
          multiple
          :label="$vuetify.lang.t('$vuetify.tax.name')"
          item-text="name"
          :loading="isTaxLoading"
          :disabled="!!isTaxLoading"
          return-object
          required
          :rules="formRule.country"
          @input="updateStore"
        >
          <template v-slot:append-outer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  @click="$store.dispatch('tax/toogleNewModal',true)"
                >
                  mdi-plus
                </v-icon>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
            </v-tooltip>
          </template>
        </v-select>
      </v-col>
      <v-col cols="6">
        <v-select
          v-model="sale.pay"
          clearable
          :items="getPay"
          :label="$vuetify.lang.t('$vuetify.pay.pay')"
          item-text="text"
          item-value="value"
          required
          :rules="formRule.country"
          @input="updateStore"
        />
      </v-col>
      <v-col
        v-show="sale.pay === 'counted'"
        cols="6"
      >
        <v-select
          v-model="sale.payments"
          clearable
          :items="payments"
          :label="$vuetify.lang.t('$vuetify.payment.name')"
          item-text="name"
          return-object
        >
          <template v-slot:append-outer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  @click="$store.dispatch('payment/toogleNewModal',true)"
                >
                  mdi-plus
                </v-icon>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
            </v-tooltip>
          </template>
        </v-select>
      </v-col>
    </v-row>
    <new-client v-if="$store.state.client.showNewModal" />
    <new-tax v-if="$store.state.tax.showNewModal" />
    <new-payment v-if="$store.state.payment.showNewModal" />
  </v-container>
</template>
<script>
import { mapActions, mapState } from 'vuex'
import NewClient from '../client/NewClient'
import NewTax from '../tax/NewTax'
import NewPayment from '../payment/NewPayment'

export default {
  name: 'ExtraData',
  components: { NewPayment, NewTax, NewClient },
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      formRule: this.$rules,
      sale: {}
    }
  },
  computed: {
    ...mapState('client', ['clients', 'isClientTableLoading']),
    ...mapState('tax', ['taxes', 'isTaxLoading']),
    ...mapState('payment', ['payments', 'isPaymentLoading']),
    ...mapState('sale', ['newSale', 'editSale']),
    getPay () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.pay.counted'),
          value: 'counted'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.pay.credit'),
          value: 'credit'
        }
      ]
    }
  },
  async created () {
    await this.getClients()
    await this.getTaxes()
    await this.getPayments()
    this.sale = this.edit ? this.editSale : this.newSale
  },
  methods: {
    ...mapActions('client', ['getClients']),
    ...mapActions('tax', ['getTaxes']),
    ...mapActions('payment', ['getPayments']),
    updateStore () {
      if (this.edit) {
        this.editSale.client = this.sale.client
        this.editSale.taxes = this.sale.taxes
        this.editSale.pay = this.sale.pay
        this.editSale.payments = this.sale.payments
        this.editSale.no_facture = this.sale.no_facture
      } else {
        this.newSale.client = this.sale.client
        this.newSale.taxes = this.sale.taxes
        this.newSale.pay = this.sale.pay
        this.newSale.payments = this.sale.payments
        this.newSale.no_facture = this.sale.no_facture
      }
      this.$emit('updateData')
    }
  }
}
</script>

<style scoped>

</style>
