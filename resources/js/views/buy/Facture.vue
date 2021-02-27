<template>
  <v-card>
    <v-card-text>
      <v-row>
        <v-col
            v-if="sale.client"
            class="py-0"
            cols="12"
            md="12"
        >
          <b>{{ $vuetify.lang.t('$vuetify.menu.client') }}</b>:<br>
          {{
            sale.client.firstName +
            ' ' +
            `${
                sale.client.lastName !== null
                    ? sale.client.lastName
                    : ''
            }`
          }}
        </v-col>
        <v-spacer/>
        <v-col
            v-if="sale.no_facture"
            class="py-0"
            cols="12"
            md="12"
        >
          <b>{{ $vuetify.lang.t('$vuetify.tax.noFacture') }}</b>:<br>
          {{ sale.no_facture }}
        </v-col>
      </v-row>
      <v-row>
        <v-col
            cols="12"
            md="6"
        >
          <b>{{ $vuetify.lang.t('$vuetify.pay.sub_total') }}</b>
        </v-col>
        <v-col
            v-if="subTotal > 0"
            cols="12"
            md="6"
        >
          {{ `${getCurrency}` }} {{ subTotal }}
        </v-col>
        <v-col v-else>
          <v-tooltip
              cols="12"
              md="6"
              right
          >
            <template v-slot:activator="{ on, attrs }">
              <v-icon
                  v-bind="attrs"
                  v-on="on"
                  color="primary"
                  dark
              >
                mdi-information-outline
              </v-icon>
            </template>
            <span>
              <b>{{
                  $vuetify.lang.t(
                      '$vuetify.messages.warning_tax_cost'
                  )
                }}</b>
            </span>
          </v-tooltip>
          <span
              style="color: crimson; text-decoration: line-through;"
          >
            {{ `${getCurrency + ' ' + sale.subTotal}` }}
          </span>
        </v-col>
      </v-row>
      <v-row
          v-for="tax in sale.taxes"
          :key="tax.name"
      >
        <v-col
            cols="12"
            md="6"
        >
          <b
              style="color: darkblue"
          >{{ $vuetify.lang.t('$vuetify.tax.name') }}({{
              tax.name
            }})</b>
        </v-col>
        <v-col
            v-if="tax.percent === 'true'"
            cols="12"
            md="6"
        >
          <i
              style="color: darkblue"
          >{{
              `${getCurrency +
              ' ' +
              parseFloat(
                  (tax.value * subTotal) / 100
              ).toFixed(2)}`
            }}
            ({{ tax.value }}%)</i>
        </v-col>
        <v-col
            v-else
            cols="12"
            md="6"
        >
          <i style="color: darkblue">{{
              `${getCurrency + ' ' + tax.value}`
            }}</i>
        </v-col>
      </v-row>
      <v-row
          v-for="disc in sale.discounts"
          :key="disc.name"
      >
        <v-col
            cols="12"
            md="6"
        >
          <b
              style="color: red"
          >{{ $vuetify.lang.t('$vuetify.menu.discount') }}({{
              discounts.filter(
                  discount => discount.id === disc.id
              )[0].name
            }})</b>
        </v-col>
        <v-col
            v-if="disc.percent === 'true'"
            cols="12"
            md="5"
        >
          <i
              style="color: red"
          >{{
              `${getCurrency +
              ' ' +
              parseFloat(
                  (disc.value * subTotal) / 100
              ).toFixed(2)}`
            }}
            ({{ disc.value }}%)</i>
        </v-col>
        <v-col
            v-else
            cols="12"
            md="5"
        >
          <i style="color: red">{{
              `${getCurrency + ' ' + disc.value}`
            }}</i>
        </v-col>
      </v-row>
      <v-row>
        <v-col
            cols="12"
            md="6"
        >
          <b style="text-transform: uppercase">{{
              $vuetify.lang.t('$vuetify.pay.total')
            }}</b>
        </v-col>
        <v-col
            cols="12"
            md="6"
        >
          {{ `${getCurrency + ' ' + totalPrice}` }}
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'

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
    sale: {
      type: Object,
      default: function () {
        return {}
      }
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
    }
  },
  computed: {
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
    ...mapActions('discount', ['getDiscounts'])
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
  font-family: "Times New Roman";
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
