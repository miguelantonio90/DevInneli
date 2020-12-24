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
          v-if="sub_total > 0"
          cols="12"
          md="5"
        >
          {{ `${getCurrency + ' ' + sub_total}` }}
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
            {{ `${getCurrency + ' ' + sub_total}` }}
          </span>
        </v-col>
      </v-row>
      <v-row v-if="taxes.length > 0" />
      <v-row
        v-for="tax in taxes"
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
          <i style="color: darkblue">{{ `${getCurrency + ' ' + parseFloat(tax.value * sub_total / 100).toFixed(2)}` }} ({{ tax.value }}%)</i>
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
        v-for="disc in localDiscounts"
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
          <i style="color: red">{{ `${getCurrency + ' ' + parseFloat(disc.value * sub_total / 100).toFixed(2)}` }} ({{ disc.value }}%)</i>
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
    }
  },
  data () {
    return {
      toDay: '',
      taxes: [],
      localDiscounts: [],
      totalTax: 0,
      totalDisc: 0,
      sub_total: 0,
      total: 0
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
  watch: {
    'newSale.taxes' () {
      this.updateData()
    },
    'newSale.articles' () {
      this.updateData()
    },
    'newSale.discounts' () {
      this.updateData()
    },
    update () {
      this.updateData()
    },
    'editSale.taxes' () {
      this.updateData()
    },
    'editSale.articles' () {
      this.updateData()
    },
    'editSale.discounts' () {
      this.updateData()
    }
  },
  async created () {
    await this.updateData()
    await this.getDiscounts()
  },
  methods: {
    ...mapActions('discount', ['getDiscounts']),
    a () {
      printJS({ printable: 'ticket', type: 'html', targetStyles: ['*'] })
    },
    updateData () {
      console.log(new Date().toLocaleDateString())
      this.toDay = ''
      new Date().toLocaleDateString().split('/').reverse().forEach((v, i) => {
        this.toDay = (i > 0) ? this.toDay + '-' + v : v
      })
      this.toDay += ' ' + (new Date().getHours() + ':' + new Date().getMinutes() + ':' + new Date().getSeconds())
      this.totalTax = 0
      this.totalDisc = 0
      this.total = 0
      this.sub_total = 0
      const articles = this.edit ? this.editSale.articles : this.newSale.articles
      articles.forEach((v) => {
        this.sub_total = parseFloat(v.totalPrice) + this.sub_total
      })
      this.taxes = this.edit ? this.editSale.taxes : this.newSale.taxes
      this.taxes.forEach((v) => {
        this.totalTax += v.percent === 'true' ? this.sub_total * v.value / 100 : v.value
      })
      this.localDiscounts = this.edit ? this.editSale.discounts : this.newSale.discounts
      this.localDiscounts.forEach((v) => {
        this.totalDisc += v.percent === 'true' ? this.sub_total * v.value / 100 : v.value
      })
      this.total = (this.sub_total + parseFloat(this.totalTax) - parseFloat(this.totalDisc)).toFixed(2)
      this.total = parseFloat(this.total).toFixed(2)
      this.$emit('updateData')
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
