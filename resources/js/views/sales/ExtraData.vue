<template>
  <v-container>
    <new-client v-if="$store.state.client.showNewModal" />
    <new-tax v-if="$store.state.tax.showNewModal" />
    <new-payment v-if="$store.state.payment.showNewModal" />
    <new-discount v-if="this.$store.state.discount.showNewModal" />
    <v-row>
      <v-col
        class="py-0"
        cols="12"
        md="8"
      >
        <v-autocomplete
          v-model="sale.client"
          clearable
          :items="clients"
          :label="$vuetify.lang.t('$vuetify.menu.client')"
          item-text="firstName"
          :loading="isClientTableLoading"
          :disabled="!!isClientTableLoading"
          return-object
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
          <template v-slot:selection="data">
            <v-chip
              v-bind="data.attrs"
              :input-value="data.selected"
              @click="data.select"
            >
              <v-avatar left>
                <v-img :src="data.item.avatar || '/assets/avatar/avatar-undefined.jpg'" />
              </v-avatar>
              {{ data.item.firstName+' '+ `${data.item.lastName!==null?data.item.lastName:''}` }}
            </v-chip>
          </template>
          <template v-slot:item="data">
            <template>
              <v-list-item-avatar>
                <v-img :src="data.item.avatar || '/assets/avatar/avatar-undefined.jpg'" />
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title>
                  {{ data.item.firstName+' '+ `${data.item.lastName!==null?data.item.lastName:''}` }}
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
        md="4"
      >
        <v-text-field
          v-model="sale.no_facture"
          :label="$vuetify.lang.t('$vuetify.tax.noFacture')"
          required
          readonly
          :rules="formRule.required"
          @onchange="updateStore"
        />
      </v-col>
      <v-col
        class="py-0"
        cols="12"
        md="6"
      >
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
      <v-col
        class="py-0"
        cols="12"
        md="6"
      >
        <v-select
          v-model="sale.discounts"
          chips
          clearable
          deletable-chips
          :items="localDiscounts"
          multiple
          :label="$vuetify.lang.t('$vuetify.menu.discount')"
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
      <list-pay
        :total="parseFloat(total)"
        :currency="user.company.currency"
      />
    </v-row>
  </v-container>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import NewClient from '../client/NewClient'
import NewTax from '../tax/NewTax'
import NewPayment from '../payment/NewPayment'
import NewDiscount from '../discount/NewDiscount'
import ListPay from '../pay/ListPay'

export default {
  name: 'ExtraData',
  components: { ListPay, NewPayment, NewTax, NewClient, NewDiscount },
  props: {
    edit: {
      type: Boolean,
      default: false
    },
    total: {
      type: String,
      default: '0.00'
    }
  },
  data () {
    return {
      formRule: this.$rules,
      localDiscounts: [],
      sale: {}
    }
  },
  computed: {
    ...mapState('client', ['clients', 'isClientTableLoading']),
    ...mapState('tax', ['taxes', 'isTaxLoading']),
    ...mapState('payment', ['payments', 'isPaymentLoading']),
    ...mapState('sale', ['newSale', 'editSale']),
    ...mapState('discount', ['discounts']),
    ...mapGetters('auth', ['user']),
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
  watch: {
    discounts: function () {
      this.getLocalDiscounts()
    }
  },
  async created () {
    await this.getClients()
    await this.getTaxes()
    await this.getPayments()
    await this.getDiscounts().then(() => {
      this.getLocalDiscounts()
    })
    this.sale = this.edit ? this.editSale : this.newSale
  },
  methods: {
    ...mapActions('client', ['getClients']),
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
    },
    updateStore () {
      if (this.edit) {
        this.editSale.client = this.sale.client
        this.editSale.taxes = this.sale.taxes
        this.editSale.pay = this.sale.pay
        this.editSale.payments = this.sale.payments
        this.editSale.no_facture = this.sale.no_facture
        this.editSale.discounts = this.sale.discounts
      } else {
        this.newSale.client = this.sale.client
        this.newSale.taxes = this.sale.taxes
        this.newSale.pay = this.sale.pay
        this.newSale.payments = this.sale.payments
        this.newSale.no_facture = this.sale.no_facture
        this.newSale.discounts = this.sale.discounts
      }
      this.$emit('updateData')
    }
  }
}
</script>

<style scoped>

</style>
