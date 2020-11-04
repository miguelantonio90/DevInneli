<template>
  <v-card>
    <v-card-title>
      {{ $vuetify.lang.t('$vuetify.pay.extra_data') }}
    </v-card-title>
    <v-card-text>
      <v-row>
        <v-col cols="6">
          <v-select
            v-model="supply.supplier"
            clearable
            :items="suppliers"
            :label="$vuetify.lang.t('$vuetify.menu.supplier')"
            item-text="name"
            item-value="id"
            :loading="isSupplierTableLoading"
            :disabled="!!isSupplierTableLoading"
            return-object
            @input="updateSupplyData"
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
            @input="updateSupplyData"
          />
        </v-col>
        <v-col cols="6">
          <v-text-field
            v-model="supply.noFacture"
            :label="$vuetify.lang.t('$vuetify.tax.noFacture')"
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
            @change="updateSupplyData"
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
            :items="getPay()"
            :label="$vuetify.lang.t('$vuetify.pay.pay')"
            item-text="text"
            item-value="value"
            @input="updateSupplyData"
          />
        </v-col>
        <v-col
          v-show="supply.pay === 'counted'"
          cols="6"
        >
          <v-select
            v-model="supply.payment"
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
    </v-card-text>
  </v-card>
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
    supplySelected: {
      type: Object,
      default: function () {
        return {
          supply: {
            ref: '',
            name: '',
            price: 0,
            cost: 0,
            inventory: 0,
            taxes: [],
            cant: 1,
            shop: {},
            pay: '',
            payment: {},
            supplier: '',
            noFacture: '',
            totalCost: 0,
            totalPrice: 0,
            article_id: ''
          }
        }
      }
    }
  },
  data () {
    return {
      supply: null
    }
  },
  computed: {
    ...mapState('supplier', ['suppliers', 'isSupplierTableLoading']),
    ...mapState('tax', ['taxes', 'isTaxLoading']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapState('payment', ['payments', 'isPaymentLoading'])
  },
  watch: {
    supplySelected () {
      this.supply = this.supplySelected
    }
  },
  created () {
    this.supply = this.supplySelected
    this.getSuppliers()
    this.getTaxes()
    this.getShops()
    this.getPayments()
  },
  methods: {
    ...mapActions('supplier', ['getSuppliers']),
    ...mapActions('tax', ['getTaxes']),
    ...mapActions('shop', ['getShops']),
    ...mapActions('payment', ['getPayments']),
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
    },
    updateSupplyData () {
      this.$emit('updateSupplyData', this.supply)
    }
  }
}
</script>

<style scoped>

</style>
