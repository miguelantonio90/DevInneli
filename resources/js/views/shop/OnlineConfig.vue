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
            :items="shops"
            :label="$vuetify.lang.t('$vuetify.menu.shop')"
            :loading="isShopLoading"
            :rules="formRule.country"
            chips
            item-text="name"
            required
            return-object
            rounded
          />
        </v-col>
        <v-col
          class="py-0"
          cols="12"
          md="4"
        >
          <v-select
            v-model="shop"
            :items="templates"
            :label="$vuetify.lang.t('$vuetify.online.template')"
            :loading="isShopLoading"
            :rules="formRule.country"
            item-text="name"
            required
            return-object
            rounded
          />
        </v-col>
      </v-row>
      <v-row>
        <v-col
          v-for="n in templateView[0]"
          :key="n.src.toString()"
          class="d-flex child-flex"
          cols="6"
        >
          <v-img
            :lazy-src="n.src"
            :src="n.src"
            aspect-ratio="1"
            class="grey lighten-2"
          >
            <template v-slot:placeholder>
              <v-row
                align="center"
                class="fill-height ma-0"
                justify="center"
              >
                <v-progress-circular
                  color="grey lighten-5"
                  indeterminate
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
      templateView: [
        [
          {
            src: require('../shops-templates/shipit/assets/template/1.jpg')
          },
          {
            src: require('../shops-templates/shipit/assets/template/2.jpg')
          }
        ]
      ],
      templates: [
        {
          name: '1'
        }
      ]
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

<style scoped></style>
