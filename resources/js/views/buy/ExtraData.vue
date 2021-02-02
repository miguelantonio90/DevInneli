<template>
  <v-container>
    <new-supplier v-if="$store.state.supplier.showNewModal" />
    <new-tax v-if="$store.state.tax.showNewModal" />
    <new-payment v-if="$store.state.payment.showNewModal" />
    <new-discount v-if="$store.state.discount.showNewModal" />
    <v-row>
      <v-col
        class="py-0"
        cols="12"
        md="6"
      >
        <v-autocomplete
          v-model="sale.supplier"
          clearable
          :items="suppliers"
          :label="$vuetify.lang.t('$vuetify.menu.supplier')"
          item-text="firstName"
          :loading="isSupplierTableLoading"
          return-object
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
          <template v-slot:selection="data">
            <v-chip
              v-bind="data.attrs"
              :input-value="data.selected"
              @click="data.select"
            >
              <v-avatar left>
                <v-img :src="data.item.avatar || '/assets/avatar/avatar-undefined.jpg'" />
              </v-avatar>
              {{ data.item.name }}
            </v-chip>
          </template>
          <template v-slot:item="data">
            <template>
              <v-list-item-avatar>
                <v-img :src="data.item.avatar || '/assets/avatar/avatar-undefined.jpg'" />
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>
                  {{ data.item.name }}
                </v-list-item-title>
                <v-list-item-subtitle>
                  {{ `${data.item.email }` }}
                </v-list-item-subtitle>
              </v-list-item-content>
            </template>
          </template>
        </v-autocomplete>
      </v-col>
      <v-col
        class="py-0"
        cols="12"
        md="3"
      >
        <v-text-field
          v-model="sale.no_facture"
          :label="$vuetify.lang.t('$vuetify.tax.noFacture')"
          required
          :rules="formRule.required"
        />
      </v-col>
      <v-col
        class="py-0"
        cols="12"
        md="3"
      >
        <v-select
          v-model="sale.taxes"
          chips
          clearable
          deletable-chips
          :items="taxes"
          multiple
          :label="$vuetify.lang.t('$vuetify.tax.nameGeneral')"
          item-text="name"
          :loading="isTaxLoading"
          :disabled="!!isTaxLoading"
          return-object
          required
          :rules="formRule.country"
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
      <v-col
        class="py-0"
        cols="12"
        md="4"
      >
        <v-select
          v-model="sale.discounts"
          chips
          clearable
          deletable-chips
          :items="localDiscounts"
          multiple
          :label="$vuetify.lang.t('$vuetify.sale.discountGeneral')"
          item-text="name"
          :loading="isTaxLoading"
          :disabled="!!isTaxLoading"
          return-object
          required
          :rules="formRule.country"
        >
          <template v-slot:append-outer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-icon
                  v-bind="attrs"
                  v-on="on"
                  @click="$store.dispatch('discount/toogleNewModal',true)"
                >
                  mdi-plus
                </v-icon>
              </template>
              <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
            </v-tooltip>
          </template>
        </v-select>
      </v-col>
      <v-col
        cols="12"
        md="12"
      >
        <list-pay
          :edit="edit"
          :sale="sale"
          :total-price="parseFloat(getTotalCost).toFixed(2)"
          :total-tax="parseFloat(totalTax).toFixed(2)"
          :total-discount="parseFloat(totalDiscount).toFixed(2)"
          :sub-total="parseFloat(subTotal).toFixed(2)"
          :currency="user.company.currency"
        />
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import NewTax from '../tax/NewTax'
import NewPayment from '../payment/NewPayment'
import NewDiscount from '../discount/NewDiscount'
import ListPay from '../pay/ListPay'
import NewSupplier from '../supplier/NewSupplier'

export default {
  name: 'ExtraData',
  components: { NewSupplier, ListPay, NewPayment, NewTax, NewDiscount },
  props: {
    edit: {
      type: Boolean,
      default: false
    },
    sale: {
      type: Object,
      default: function () {
        return {}
      }
    },
    totalCost: {
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
    }
  },
  data () {
    return {
      formRule: this.$rules,
      localDiscounts: []
    }
  },
  computed: {
    ...mapState('supplier', ['suppliers', 'isSupplierTableLoading']),
    ...mapState('tax', ['taxes', 'isTaxLoading']),
    ...mapState('payment', ['payments', 'isPaymentLoading']),
    ...mapState('sale', ['newSale', 'editSale']),
    ...mapState('discount', ['discounts']),
    ...mapGetters('auth', ['user']),
    getTotalCost () {
      return this.totalCost
    }
  },
  watch: {
    discounts: function () {
      this.getLocalDiscounts()
    }
  },
  async created () {
    await this.getSuppliers()
    await this.getTaxes()
    await this.getPayments()
    await this.getDiscounts().then(() => {
      this.getLocalDiscounts()
    })
  },
  methods: {
    ...mapActions('supplier', ['getSuppliers']),
    ...mapActions('tax', ['getTaxes']),
    ...mapActions('payment', ['getPayments']),
    ...mapActions('discount', ['getDiscounts']),
    getLocalDiscounts () {
      this.discounts.forEach((v) => {
        this.localDiscounts.push({
          id: v.id,
          name: v.percent ? v.name + '(' + v.value + '%)' : v.name + '(' + this.user.company.currency + v.value + ')',
          value: v.value,
          percent: v.percent
        })
      })
    }
  }
}
</script>

<style scoped>

</style>
