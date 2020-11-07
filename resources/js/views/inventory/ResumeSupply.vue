<template>
  <v-container>
    <v-row>
      <v-col
        v-if="edit ? editInventory.supplier:newInventory.supplier"
        cols="md-7"
      >
        <b>{{ $vuetify.lang.t('$vuetify.provider') }}</b>:
        {{ edit ? editInventory.supplier.name:newInventory.supplier.name }}
      </v-col>
      <v-spacer />
      <v-col
        v-if="edit ? editInventory.no_facture:newInventory.no_facture"
        cols="md-5"
      >
        <b>{{ $vuetify.lang.t('$vuetify.tax.noFacture') }}</b>:
        {{ edit ? editInventory.no_facture:newInventory.no_facture }}
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
        <i style="color: red">{{ `${getCurrency + ' ' + tax.value * total / 100}` }}( {{ tax.value }}%)</i>
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
  name: 'ResumeSupply',
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
    ...mapState('inventory', ['newInventory', 'editInventory']),
    ...mapGetters('auth', ['user']),
    getCurrency () {
      return this.currency
    }
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
    },
    'editInventory.taxes' () {
      this.updateData()
    },
    'editInventory.articles' () {
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
      const articles = this.edit ? this.editInventory.articles : this.newInventory.articles
      articles.forEach((v) => {
        this.total = parseFloat(v.cant * v.cost) + this.total
      })
      this.taxes = this.edit ? this.editInventory.taxes : this.newInventory.taxes
      this.taxes.forEach((v) => {
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
