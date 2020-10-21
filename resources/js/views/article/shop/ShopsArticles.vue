<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="shopData"
    >
      <template v-slot:item.checked="{ item }">
        <v-simple-checkbox v-model="item.checked" />
      </template>
      <template v-slot:item.price="props">
        <v-edit-dialog
          :return-value.sync="props.item.price"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
          @save="updateShopsData"
        >
          <div>{{ props.item.price }}</div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t('$vuetify.actions.edit') }}
            </div>
          </template>
          <template v-slot:input>
            <v-text-field-money
              v-model="props.item.price"
              :label="$vuetify.lang.t('$vuetify.actions.edit')"
              required
              :properties="{
                prefix: '$',
                clearable: true
              }"
              :options="{
                locale: 'en',
                length: 11,
                precision: 2,
                empty: 0.00,
              }"
            />
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:item.stock="props">
        <v-edit-dialog
          :return-value.sync="props.item.stock"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
          @save="updateShopsData"
        >
          <div>{{ props.item.stock }}</div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t('$vuetify.actions.edit') }}
            </div>
          </template>
          <template v-slot:input>
            <v-text-field
              v-model="props.item.stock"
              :rules="[max25chars]"
              label="Edit"
              single-line
              counter
              autofocus
            />
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:item.under_inventory="props">
        <v-edit-dialog
          :return-value.sync="props.item.under_inventory"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
          @save="updateShopsData"
        >
          <div>{{ props.item.under_inventory }}</div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t('$vuetify.actions.edit') }}
            </div>
          </template>
          <template v-slot:input>
            <v-text-field
              v-model="props.item.under_inventory"
              :rules="[max25chars]"
              label="Edit"
              single-line
              counter
              autofocus
            />
          </template>
        </v-edit-dialog>
      </template>
    </v-data-table>
    <v-snackbar
      v-model="snack"
      :timeout="3000"
      :color="snackColor"
    >
      {{ snackText }}

      <template v-slot:action="{ attrs }">
        <v-btn
          v-bind="attrs"
          text
          @click="snack = false"
        >
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>
<script>
/* eslint-disable vue/require-prop-types */

export default {
  name: 'ShopsArticles',
  props: ['variantsData', 'shopData', 'trackInventoryParent'],
  data () {
    return {
      snack: false,
      snackColor: '',
      snackText: '',
      max25chars: v => v.length <= 25 || 'Input too long!',
      pagination: {},
      headers: [],
        track_inventory:false
    }
  },
  computed: {},
  watch: {
    variantsData: function () {
      this.initialize()
    },
      trackInventoryParent: function (val) {
      this.track_inventory = this.trackInventoryParent
      this.initialize()
    }
  },
  created () {
    this.initialize()
  },
  mounted () {
  },
  methods: {
    initialize () {
      this.headers = []
      this.headers = [
        {
          text: this.$vuetify.lang.t('$vuetify.shop_article.enabled'),
          value: 'checked'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'name'
        }
      ]
      if (this.variantsData.length > 0) {
        this.headers.push({
          text: this.$vuetify.lang.t('$vuetify.variants.variant'),
          value: 'variant'
        })
      }
      this.headers.push({
        text: this.$vuetify.lang.t('$vuetify.variants.price'),
        value: 'price'
      })
      if (this.track_inventory) {
        this.headers.push({
          text: this.$vuetify.lang.t('$vuetify.shop_article.stock'),
          value: 'stock'
        })
        this.headers.push({
          text: this.$vuetify.lang.t('$vuetify.shop_article.under_inventory'),
          value: 'under_inventory'
        })
      }
    },
    updateShopsData () {
      this.$emit('updateShopData', this.shopData)
    }
  }
}
</script>
<style scoped>

</style>
