<template>
  <v-container>
    <v-row>
      <v-col
        v-if="edit ? editSale.client:newSale.client"
        cols="md-7"
      >
        <b>{{ $vuetify.lang.t('$vuetify.menu.client') }}</b>:<br>
        {{ edit ?
          editSale.client.firstName+' '+ `${editSale.client.lastName!==null?editSale.client.lastName:''}`
          :newSale.client.firstName+' '+ `${newSale.client.lastName!==null?newSale.client.lastName:''}` }}
      </v-col>
      <v-spacer />
      <v-col
        v-if="edit ? editSale.no_facture:newSale.no_facture"
        cols="md-5"
      >
        <b>{{ $vuetify.lang.t('$vuetify.tax.noFacture') }}</b>:<br>
        {{ edit ? editSale.no_facture:newSale.no_facture }}
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="7">
        <b>{{ $vuetify.lang.t('$vuetify.pay.sub_total') }}</b>
      </v-col>
      <v-col
        v-if="sub_total > 0"
        cols="5"
      >
        {{ `${getCurrency + ' ' + sub_total}` }}
      </v-col>
      <v-col v-else>
        <v-tooltip
          right
          class="md-7"
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
      <v-col cols="7">
        <b>{{ $vuetify.lang.t('$vuetify.tax.name') }}({{ tax.name }})</b>
      </v-col>
      <v-col
        v-if="tax.percent==='true'"
        cols="5"
      >
        <i style="color: red">{{ `${getCurrency + ' ' + tax.value * sub_total / 100}` }}( {{ tax.value }}%)</i>
      </v-col>
      <v-col
        v-else
        cols="5"
      >
        <i style="color: red">{{ `${getCurrency + ' ' + tax.value}` }}</i>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="7">
        <b style="text-transform: uppercase">{{ $vuetify.lang.t('$vuetify.pay.total') }}</b>
      </v-col>
      <v-col cols="5">
        {{ `${getCurrency + ' ' + total}` }}
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapGetters, mapState } from 'vuex'

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
      taxes: [],
      totalTax: 0,
      sub_total: 0,
      total: 0
    }
  },
  computed: {
    ...mapState('sale', ['newSale', 'editSale']),
    ...mapGetters('auth', ['user']),
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
    update () {
      this.updateData()
    },
    'editSale.taxes' () {
      this.updateData()
    },
    'editSale.articles' () {
      this.updateData()
    }
  },
  async created () {
    await this.updateData()
  },
  methods: {
    updateData () {
      this.totalTax = 0
      this.total = 0
      this.sub_total = 0
      const articles = this.edit ? this.editSale.articles : this.newSale.articles
      articles.forEach((v) => {
        this.sub_total = parseFloat(v.cant * v.price) + this.sub_total
      })
      this.taxes = this.edit ? this.editSale.taxes : this.newSale.taxes
      this.taxes.forEach((v) => {
        this.totalTax += v.percent === 'true' ? this.sub_total * v.value / 100 : v.value
      })
      this.total = (this.sub_total + parseFloat(this.totalTax)).toFixed(2)
      this.total = parseFloat(this.total).toFixed(2)
      this.$emit('updateData')
    }
  }
}
</script>

<style scoped>

</style>
