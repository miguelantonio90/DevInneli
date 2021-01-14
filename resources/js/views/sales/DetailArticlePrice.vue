<template>
  <v-card width="350">
    <v-card-text>
      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <b style="text-transform: uppercase">{{ $vuetify.lang.t('$vuetify.price') }}</b>
        </v-col>
        <v-col
          cols="12"
          md="6"
        >
          {{ `${currency + ' ' + parseFloat(article.price).toFixed(2)}` }}
        </v-col>
      </v-row>
      <v-row
        v-for="tax in article.taxes"
        :key="tax.name"
      >
        <v-col
          cols="12"
          md="6"
        >
          <b style="color: darkblue">{{ $vuetify.lang.t('$vuetify.tax.name') }}({{ tax.name }})</b>
        </v-col>
        <v-col
          v-if="tax.percent==='true'"
          cols="12"
          md="6"
        >
          <i style="color: darkblue">+{{ `${currency + ' ' + parseFloat(tax.value * article.price / 100).toFixed(2)}` }} ({{ tax.value }}%)</i>
        </v-col>
        <v-col
          v-else
          cols="12"
          md="6"
        >
          <i style="color: darkblue">+{{ `${currency + ' ' + tax.value}` }}</i>
        </v-col>
      </v-row>
      <v-row
        v-for="disc in article.discount"
        :key="disc.name"
      >
        <v-col
          cols="12"
          md="6"
        >
          <b style="color: red">{{ disc.name }}</b>
        </v-col>
        <v-col
          v-if="disc.percent==='true'"
          cols="12"
          md="6"
        >
          <i style="color: red">-{{ `${currency + ' ' + parseFloat(disc.value * article.price / 100).toFixed(2)}` }}</i>
        </v-col>
        <v-col
          v-else
          cols="12"
          md="6"
        >
          <i style="color: red">-{{ `${currency + ' ' + disc.value}` }}</i>
        </v-col>
      </v-row>
      <v-row>
        <v-col
          cols="12"
          md="6"
        >
          <b style="text-transform: uppercase">{{ $vuetify.lang.t('$vuetify.pay.total') }}</b>
        </v-col>
        <v-col
          cols="12"
          md="6"
        >
          {{ `${currency + ' ' + parseFloat(article.totalPrice).toFixed(2)}` }}
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  name: 'DetailArticlePrice',
  props: {
    article: {
      type: Object,
      default: function () {
        return {}
      }
    },
    currency: {
      type: String,
      default: ''
    }
  }
}
</script>

<style scoped>

</style>
