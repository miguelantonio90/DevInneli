<template>
  <v-card>
    <v-card-title>
      {{ $vuetify.lang.t('$vuetify.menu.resume') }}
      <v-spacer />
      <v-col v-if="supply.noFacture">
        {{ $vuetify.lang.t('$vuetify.tax.noFacture') }}: {{ supply.noFacture }}
      </v-col>
    </v-card-title>
    <v-card-text>
      <v-row>
        <v-col cols="6">
          {{ $vuetify.lang.t('$vuetify.pay.sub_total') }}
        </v-col>
        <v-col cols="6">
          {{ supply.totalCost }}
        </v-col>
      </v-row>
      <v-row v-if="supply.supplier">
        <v-col cols="6">
          {{ $vuetify.lang.t('$vuetify.menu.supplier') }}
        </v-col>
        <v-col cols="6">
          {{ supply.supplier.name }}
        </v-col>
      </v-row>
      <v-row
        v-for="tax in supply.taxes"
        :key="tax.name"
      >
        <v-col cols="6">
          {{ $vuetify.lang.t('$vuetify.tax.name') }}({{ tax.name }})
        </v-col>
        <v-col
          v-if="tax.percent"
          cols="6"
        >
          {{ tax.value * supply.totalCost / 100 }}( {{ tax.value }}%)
        </v-col>
        <v-col
          v-else
          cols="6"
        >
          {{ tax.value }}
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="6">
          {{ $vuetify.lang.t('$vuetify.pay.total') }}
        </v-col>
        <v-col cols="6">
          {{ total }}
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  name: 'ResumeSupply',
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
      supply: null,
      totalTax: 0,
      total: 0
    }
  },
  watch: {
    supplySelected () {
      this.supply = this.supplySelected
      this.updateData()
    },
    'supplySelected.taxes' () {
      this.updateData()
    },
    'supplySelected.totalCost' () {
      this.updateData()
    }
  },
  created () {
    this.supply = this.supplySelected
  },
  methods: {
    updateData () {
      this.totalTax = 0
      this.supplySelected.taxes.forEach((v) => {
        this.totalTax += v.percent ? this.supply.totalCost * v.value / 100 : v.value
      })
      this.total = parseFloat(parseFloat(this.supply.totalCost) + parseFloat(this.totalTax)).toFixed(2)
    }
  }
}
</script>

<style scoped>

</style>
