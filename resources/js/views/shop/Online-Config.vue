<template>
  <v-card>
    <v-card-title>
      <span class="headline">
        {{ $vuetify.lang.t('$vuetify.menu.shop_online') }}
      </span>
    </v-card-title>
    <v-card-text>
      <v-row>
        <v-col
          class="py-0"
          cols="12"
          md="4"
        >
          <v-select
            id="slc_shop"
            v-model="shop"
            chips
            rounded
            :items="shops"
            :label="$vuetify.lang.t('$vuetify.menu.shop')"
            item-text="name"
            :loading="isShopLoading"
            return-object
            required
            :rules="formRule.country"
          />
        </v-col>
        <v-col
          class="py-0"
          cols="12"
          md="4"
        >
          <v-select
            v-model="shop"
            rounded
            :items="templates"
            :label="$vuetify.lang.t('$vuetify.online.template')"
            item-text="name"
            :loading="isShopLoading"
            return-object
            required
            :rules="formRule.country"
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col
          v-for="n in templateView[0]"
          :key="n"
          class="d-flex child-flex"
          cols="6"
        >
          <v-img
            :src="n.src"
            :lazy-src="n.src"
            aspect-ratio="1"
            class="grey lighten-2"
          >
            <template v-slot:placeholder>
              <v-row
                class="fill-height ma-0"
                align="center"
                justify="center"
              >
                <v-progress-circular
                  indeterminate
                  color="grey lighten-5"
                />
              </v-row>
            </template>
          </v-img>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'OnlineConfig',
  data () {
    return {
      formRule: this.$rules,
      shop: {},
      templateView: [[{
        src: require('../shops-templates/shipit/assets/template/1.jpg')
      }, {
        src: require('../shops-templates/shipit/assets/template/2.jpg')
      }]],
      templates: [{
        name: '1'
      }]
    }
  },
  computed: {
    ...mapState('shop', ['shops', 'isShopLoading'])
  },
  async created () {
    await this.getShops()
  },
  methods: {
    ...mapActions('shop', ['getShops'])
  }
}
</script>

<style scoped>

</style>
