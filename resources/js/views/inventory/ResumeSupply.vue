<template>
  <v-card>
    <v-card-title>
      {{ $vuetify.lang.t('$vuetify.menu.resume') }}
    </v-card-title>
    <v-card-text>
      <v-row>
        <v-col v-if="newInventory.supplier">
          <b>{{ $vuetify.lang.t('$vuetify.to') }}</b>: {{ newInventory.supplier.name }}
        </v-col>
        <v-spacer />
        <v-col
          v-if="newInventory.noFacture"
          cols="md-6"
        >
          {{ $vuetify.lang.t('$vuetify.tax.noFacture') }}: {{ newInventory.noFacture }}
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="6">
          {{ $vuetify.lang.t('$vuetify.pay.sub_total') }}
        </v-col>
        <v-col cols="6">
          {{ sub_total }}
        </v-col>
      </v-row>
      <v-row v-if="newInventory.supplier" />
      <v-row
        v-for="tax in newInventory.taxes"
        :key="tax.name"
      >
        <v-col cols="6">
          {{ $vuetify.lang.t('$vuetify.tax.name') }}({{ tax.name }})
        </v-col>
        <v-col
          v-if="tax.percent"
          cols="6"
        >
          {{ tax.value * newInventory.totalCost / 100 }}( {{ tax.value }}%)
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
import { mapState } from 'vuex'

export default {
  name: 'ResumeSupply',
  data () {
    return {
      totalTax: 0,
      sub_total: 0,
      total: 0
    }
  },
  computed: {
    ...mapState('inventory', ['newInventory'])
  },
  watch: {
    'newInventory.taxes' () {
      this.updateData()
    },
    'newInventory.articles' () {
      this.updateData()
    }
  },
  methods: {
    updateData () {
      this.totalTax = 0
      this.total = 0
      this.newInventory.articles.forEach((v) => {
        this.total += v.totalCost
      })
      this.newInventory.taxes.forEach((v) => {
        this.totalTax += v.percent ? this.newInventory.totalCost * v.value / 100 : v.value
      })
      this.sub_total = (this.total - parseFloat(this.totalTax)).toFixed(2)
    }
  }
}
</script>

<style scoped>

</style>
