<template>
  <v-container>
    <v-row>
      <v-col cols="6">
        <v-select
          v-model="supply.supplier"
          clearable
          :items="suppliers"
          :label="$vuetify.lang.t('$vuetify.menu.supplier')"
          item-text="name"
          :loading="isSupplierTableLoading"
          :disabled="!!isSupplierTableLoading"
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
                  @click="$store.dispatch('supplier/toogleNewModal',true)"
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
          v-model="supply.shop"
          clearable
          :items="shops"
          :label="$vuetify.lang.t('$vuetify.menu.shop')"
          item-text="name"
          :loading="isShopLoading"
          :disabled="!!isShopLoading"
          return-object
          required
          :rules="formRule.country"
          @input="updateStore"
        />
      </v-col>
      <v-col cols="6">
        <v-text-field
          v-model="supply.no_facture"
          :label="$vuetify.lang.t('$vuetify.tax.noFacture')"
          required
          :rules="formRule.required"
          @onchange="updateStore"
        />
      </v-col>
      <v-col cols="6">
        <v-select
          v-model="supply.taxes"
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
          v-model="supply.pay"
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
        v-show="supply.pay === 'counted'"
        cols="6"
      >
        <v-select
          v-model="supply.payments"
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
    <new-supplier v-if="$store.state.supplier.showNewModal" />
    <new-tax v-if="$store.state.tax.showNewModal" />
    <new-payment v-if="$store.state.payment.showNewModal" />
  </v-container>
</template>
<script>
import { mapActions, mapState } from 'vuex'
import NewSupplier from '../supplier/NewSupplier'
import NewTax from '../tax/NewTax'
import NewPayment from '../payment/NewPayment'

export default {
  name: 'DetailSupplier',
  components: { NewPayment, NewTax, NewSupplier },
  props: {
    edit: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      formRule: this.$rules,
      supply: {}
    }
  },
  computed: {
    ...mapState('supplier', ['suppliers', 'isSupplierTableLoading']),
    ...mapState('tax', ['taxes', 'isTaxLoading']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapState('payment', ['payments', 'isPaymentLoading']),
    ...mapState('inventory', ['newInventory', 'editInventory']),
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
  created () {
    this.getSuppliers()
    this.getTaxes()
    this.getShops()
    this.getPayments()
    this.supply = this.edit ? this.editInventory : this.newInventory
    console.log(this.supply)
  },
  methods: {
    ...mapActions('supplier', ['getSuppliers']),
    ...mapActions('tax', ['getTaxes']),
    ...mapActions('shop', ['getShops']),
    ...mapActions('payment', ['getPayments']),
    updateStore () {
      if (this.edit) {
        this.$store.state['inventory/editInventory'].supplier = this.supply.supplier
        this.$store.state['inventory/editInventory'].taxes = this.supply.taxes
        this.$store.state['inventory/editInventory'].pay = this.supply.pay
        this.$store.state['inventory/editInventory'].payments = this.supply.payments
        this.$store.state['inventory/editInventory'].shop = this.supply.shop
        this.$store.state['inventory/editInventory'].no_facture = this.supply.no_facture
      } else {
        this.$store.state['inventory/newInventory'].supplier = this.supply.supplier
        this.$store.state['inventory/newInventory'].taxes = this.supply.taxes
        this.$store.state['inventory/newInventory'].pay = this.supply.pay
        this.$store.state['inventory/newInventory'].payments = this.supply.payments
        this.$store.state['inventory/newInventory'].shop = this.supply.shop
        this.$store.state['inventory/newInventory'].no_facture = this.supply.no_facture
      }
      this.$emit('updateData')
    }
  }
}
</script>

<style scoped>

</style>
