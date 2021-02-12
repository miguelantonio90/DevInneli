<template>
  <div>
    <v-row>
      <v-col
        cols="12"
        md="3"
      >
        <v-switch
          v-show="!article.id"
          v-if="!article.composite "
          v-model="article.track_inventory"
          :title="$vuetify.lang.t('$vuetify.articles.track_inventory')"
        >
          <template v-slot:label>
            <div>
              {{
                $vuetify.lang.t('$vuetify.articles.track_inventory') }}
              <v-tooltip
                right
                class="md-6"
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
                <p>
                  {{
                    $vuetify.lang.t('$vuetify.messages.warning_article_service')
                  }}
                </p>
              </v-tooltip>
            </div>
          </template>
        </v-switch>
      </v-col>
    </v-row>
    <v-data-table
      :headers="getHeader"
      :items="article.articles_shops"
    >
      <template v-slot:[`item.checked`]="{ item }">
        <v-simple-checkbox
          v-model="item.checked"
        >
          />
        </v-simple-checkbox>
      </template>
      <template v-slot:[`item.price`]="{ item }">
        <v-edit-dialog
          :return-value.sync="item.price"
          large
          persistent
          :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
          :save-text="$vuetify.lang.t('$vuetify.actions.save')"
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
	props: {
		article: {
			type: Object,
			default: function () {
				return {}
			}
		}
	},
	data () {
		return {
			snack: false,
			snackColor: '',
			snackText: '',
			pagination: {}
		}
	},
	computed: {
		...mapGetters('auth', ['user']),
		getHeader () {
			let headers = []
			headers = [
				{
					text: this.$vuetify.lang.t('$vuetify.shop_article.enabled'),
					value: 'checked'
				},
				{
					text: this.$vuetify.lang.t('$vuetify.menu.shop'),
					value: 'shop_name'
				}
			]
			if (!this.article.composite && this.article.variant_values.length > 0) {
				headers.push({
					text: this.$vuetify.lang.t('$vuetify.variants.variant'),
					value: 'name'
				})
			}
			headers.push({
				text: this.$vuetify.lang.t('$vuetify.variants.price'),
				value: 'price'
			})
			if (this.article.track_inventory) {
				headers.push({
					text: this.$vuetify.lang.t('$vuetify.shop_article.stock'),
					value: 'stock'
				})
				headers.push({
					text: this.$vuetify.lang.t('$vuetify.shop_article.under_inventory'),
					value: 'under_inventory'
				})
			}
			return headers
		}
	},
	created () {
	}
}
</script>
<style scoped>

</style>
