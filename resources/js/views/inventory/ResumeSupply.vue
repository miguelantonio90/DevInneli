<template>
  <v-container>
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
        {{ `${user.company.currency + ' ' + sub_total}` }}
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
        v-if="tax.percent==='true'"
        cols="6"
      >
        <i style="color: red">{{ `${user.company.currency + ' ' + tax.value * total / 100}` }}( {{ tax.value }}%)</i>
      </v-col>
      <v-col
        v-else
        cols="6"
      >
        <i style="color: red">{{ `${user.company.currency + ' ' + tax.value}` }}</i>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="6">
        {{ $vuetify.lang.t('$vuetify.pay.total') }}
      </v-col>
      <v-col cols="6">
        {{ `${user.company.currency + ' ' + total}` }}
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapGetters, mapState } from 'vuex'

export default {
  name: 'ResumeSupply',
  props: {
    update: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      totalTax: 0,
      sub_total: 0,
      total: 0
    }
  },
  computed: {
    ...mapState('inventory', ['newInventory']),
    ...mapGetters('auth', ['user'])
  },
  watch: {
    'newInventory.taxes' () {
      this.updateData()
    },
    'newInventory.articles' () {
      this.updateData()
    },
    update () {
      this.updateData()
    }
  },
  methods: {
    updateData () {
      this.totalTax = 0
      this.total = 0
      this.sub_total = 0
      this.newInventory.articles.forEach((v) => {
        this.total = parseFloat(v.totalCost) + this.total
      })
      this.newInventory.taxes.forEach((v) => {
        console.log(v)
        this.totalTax += v.percent === 'true' ? this.total * v.value / 100 : v.value
      })
      this.sub_total = (this.total - parseFloat(this.totalTax)).toFixed(2)
      this.total = parseFloat(this.total).toFixed(2)
      this.$emit('updateData')
    }
  }
}
</script>

<style scoped>

</style>
