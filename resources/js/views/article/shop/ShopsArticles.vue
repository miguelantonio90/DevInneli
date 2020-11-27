<template>
  <div>
    <v-data-table
      :headers="headers"
      :items="shopData"
    >
      <template v-slot:[`item.checked`]="{ item }">
        <v-simple-checkbox v-model="item.checked" />
      </template>
      <template v-slot:[`item.price`]="{ item }">
        <v-edit-dialog
          :return-value.sync="item.price"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
          @save="updateShopsData"
        >
          <div>{{ `${user.company.currency +' '+item.price}` }}</div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t('$vuetify.actions.edit') }}
            </div>
            <v-text-field-money
              v-model="item.price"
              :label="$vuetify.lang.t('$vuetify.actions.edit')"
              required
              :properties="{
                prefix: user.company.currency,
                clearable: true
              }"
              :options="{
                length: 15,
                precision: 2,
                empty: 0.00,
              }"
            />
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:[`item.stock`]="{ item }">
        <v-edit-dialog
          :return-value.sync="item.stock"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
          @save="updateShopsData"
        >
          <div>{{ item.stock }}</div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t('$vuetify.actions.edit') }}
            </div>
            <v-text-field-integer
              v-model="item.stock"
              :label="$vuetify.lang.t('$vuetify.actions.save') "
              :properties="{
                clearable: true,
              }"
              :options="{
                inputMask: '#########',
                outputMask: '#########',
                empty: 1,
                applyAfter: false,
              }"
            />
          </template>
        </v-edit-dialog>
      </template>
      <template v-slot:[`item.under_inventory`]="{ item }">
        <v-edit-dialog
          :return-value.sync="item.under_inventory"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
          @save="updateShopsData"
        >
          <div>{{ item.under_inventory }}</div>
          <template v-slot:input>
            <div class="mt-4 title">
              {{ $vuetify.lang.t('$vuetify.actions.edit') }}
            </div>
            <v-text-field-integer
              v-model="item.under_inventory"
              :label="$vuetify.lang.t('$vuetify.actions.save') "
              :properties="{
                clearable: true,
              }"
              :options="{
                inputMask: '#########',
                outputMask: '#########',
                empty: 1,
                applyAfter: false,
              }"
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
import { mapGetters } from 'vuex'

export default {
  name: 'ShopsArticles',
  // eslint-disable-next-line vue/require-prop-types
  props: ['variantsData', 'shopData', 'trackInventoryParent'],
  data () {
    return {
      snack: false,
      snackColor: '',
      snackText: '',
      max25chars: v => v.length <= 25 || 'Input too long!',
      pagination: {},
      headers: [],
      track_inventory: false
    }
  },
  computed: {
    ...mapGetters('auth', ['user'])
  },
  watch: {
    variantsData: function () {
      this.initialize()
    },
    trackInventoryParent: function () {
      this.track_inventory = this.trackInventoryParent
      this.initialize()
    }
  },
  created () {
    this.track_inventory = this.trackInventoryParent
    this.initialize()
  },
  mounted () {
    this.track_inventory = this.trackInventoryParent
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
          value: 'shop_name'
        }
      ]
      if (this.variantsData.length > 0) {
        this.headers.push({
          text: this.$vuetify.lang.t('$vuetify.variants.variant'),
          value: 'name'
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
