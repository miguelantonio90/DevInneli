<template>
  <v-card>
    <v-card-subtitle>
      {{ $vuetify.lang.t('$vuetify.menu.resume') }}
    </v-card-subtitle>
    <v-card-text>
      <v-row>
        <v-col cols="6">
          {{ $vuetify.lang.t('$vuetify.pay.sub_total') }}
        </v-col>
        <v-col cols="6" />
      </v-row>
      <v-row v-for="tax in supply.taxes">
        <v-col cols="6">
          {{ tax.name }}
        </v-col>
        <v-col cols="6" />
      </v-row>
      <v-row>
        <v-col cols="6">
          {{ $vuetify.lang.t('$vuetify.pay.total') }}
        </v-col>
        <v-col cols="6" />
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  name: 'ResumeSupply',
  props: {
    item: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      supply: null
    }
  },
  created () {

  }
}
</script>

<style scoped>

</style>
