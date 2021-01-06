<template>
  <v-container>
    <div
      id="receipt"
    >
      <v-row>
        <v-col
          v-if="edit ? editSale.client:newSale.client"
          class="py-0"
          cols="12"
          md="7"
        >
          <b>{{ $vuetify.lang.t('$vuetify.menu.client') }}</b>:<br>
          {{ edit ?
            editSale.client.firstName+' '+ `${editSale.client.lastName!==null?editSale.client.lastName:''}`
            :newSale.client.firstName+' '+ `${newSale.client.lastName!==null?newSale.client.lastName:''}` }}
        </v-col>
        <v-spacer />
        <v-col
          v-if="edit ? editSale.no_facture:newSale.no_facture"
          class="py-0"
          cols="12"
          md="5"
        >
          <b>{{ $vuetify.lang.t('$vuetify.tax.noFacture') }}</b>:<br>
          {{ edit ? editSale.no_facture:newSale.no_facture }}
        </v-col>
      </v-row>
      <v-row>
        <v-col
          cols="12"
          md="7"
        >
          <b>{{ $vuetify.lang.t('$vuetify.pay.sub_total') }}</b>
        </v-col>
        <v-col
          v-if="subTotal > 0"
          cols="12"
          md="5"
        >
          {{ `${getCurrency + ' ' + subTotal}` }}
        </v-col>
        <v-col v-else>
          <v-tooltip
            right
            cols="12"
            md="5"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                color="primary"
                dark
                v-bind="attrs"
                v-on="on"
              >
                mdi-information-outline
              </v-icon>
            </template>
            <span>
              <b>{{ $vuetify.lang.t('$vuetify.messages.warning_tax_cost') }}</b>
            </span>
          </v-tooltip><span style="color: crimson; text-decoration: line-through;">
            {{ `${getCurrency + ' ' + subTotal}` }}
          </span>
        </v-col>
      </v-row>
      <v-row v-if="taxes.length > 0" />
      <v-row
        v-for="tax in (edit ? editSale.taxes:newSale.taxes)"
        :key="tax.name"
      >
        <v-col
          cols="12"
          md="7"
        >
          <b style="color: darkblue">{{ $vuetify.lang.t('$vuetify.tax.name') }}({{ tax.name }})</b>
        </v-col>
        <v-col
          v-if="tax.percent==='true'"
          cols="12"
          md="5"
        >
          <i style="color: darkblue">{{ `${getCurrency + ' ' + parseFloat(tax.value * subTotal / 100).toFixed(2)}` }} ({{ tax.value }}%)</i>
        </v-col>
        <v-col
          v-else
          cols="12"
          md="5"
        >
          <i style="color: darkblue">{{ `${getCurrency + ' ' + tax.value}` }}</i>
        </v-col>
      </v-row>
      <v-row
        v-for="disc in (edit ? editSale.discounts:newSale.discounts)"
        :key="disc.name"
      >
        <v-col
          cols="12"
          md="7"
        >
          <b style="color: red">{{ $vuetify.lang.t('$vuetify.menu.discount') }}({{ discounts.filter(discount=>discount.id === disc.id)[0].name }})</b>
        </v-col>
        <v-col
          v-if="disc.percent==='true'"
          cols="12"
          md="5"
        >
          <i style="color: red">{{ `${getCurrency + ' ' + parseFloat(disc.value * subTotal / 100).toFixed(2)}` }} ({{ disc.value }}%)</i>
        </v-col>
        <v-col
          v-else
          cols="12"
          md="5"
        >
          <i style="color: red">{{ `${getCurrency + ' ' + disc.value}` }}</i>
        </v-col>
      </v-row>
      <v-row>
        <v-col
          cols="12"
          md="7"
        >
          <b style="text-transform: uppercase">{{ $vuetify.lang.t('$vuetify.pay.total') }}</b>
        </v-col>
        <v-col
          cols="12"
          md="5"
        >
          {{ `${getCurrency + ' ' + total}` }}
        </v-col>
      </v-row>
    </div>
  </v-container>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import printJS from 'print-js'
export default {
  name: 'Facture',
  props: {
    edit: {
      type: Boolean,
      default: false
    },
    update: {
      type: Boolean,
      default: false
    },
    currency: {
      type: String,
      default: ''
    },
    totalTax: {
      type: String,
      default: '0.00'
    },
    totalDisc: {
      type: String,
      default: '0.00'
    },
    subTotal: {
      type: String,
      default: '0.00'
    },
    total: {
      type: String,
      default: '0.00'
    }
  },
  data () {
    return {
      taxes: [],
      localDiscounts: []
    }
  },
  computed: {
    ...mapState('sale', ['newSale', 'editSale']),
    ...mapGetters('auth', ['user', 'userPin']),
    ...mapState('discount', ['discounts']),
    getCurrency () {
      return this.currency
    }
  },
  async created () {
    await this.getDiscounts()
  },
  methods: {
    ...mapActions('discount', ['getDiscounts']),
    a () {
      printJS({ printable: 'ticket', type: 'html', targetStyles: ['*'] })
    }
  }
}
</script>

<style scoped>

.profile {
    width: 150px;
    height: 150px;
    border-radius: 50%;
}
* {
    font-size: 12px;
    font-family: 'Times New Roman';
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
}

td.producto,
th.producto {
    width: 75px;
    max-width: 75px;
}

td.cantidad,
th.cantidad {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}

td.precio,
th.precio {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}

.centrado {
    text-align: center;
    align-content: center;
}

.ticket {
    width: 155px;
    max-width: 155px;
}

img {
    max-width: inherit;
    width: inherit;
}

</style>
