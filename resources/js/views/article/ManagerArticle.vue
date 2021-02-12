<template>
  <div class="page-add-product">
    <app-loading v-show="loadingData" />
    <v-container
      v-if="!loadingData"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t(managerArticle ? '$vuetify.titles.edit': '$vuetify.titles.new', [
              $vuetify.lang.t('$vuetify.articles.name'),
            ])
          }}</span>
        </v-card-title>
        <v-card-text>
          <v-stepper v-model="step">
            <v-stepper-header>
              <v-stepper-step
                :complete="step > 1"
                :editable="formValid"
                step="1"
              >
                {{ $vuetify.lang.t('$vuetify.panel.basic') }}
              </v-stepper-step>
              <v-divider />

              <v-stepper-step
                v-if="!article.composite"
                step="2"
                :complete="step > 2"
                :editable="formValid"
              >
                {{ $vuetify.lang.t('$vuetify.panel.variant') }}
              </v-stepper-step>
              <v-divider v-if="!article.composite" />

              <v-stepper-step
                :complete="article.composite ? step > 2:step > 3"
                :editable="formValid"
                :step="`${article.composite ? 2 : 3 }`"
              >
                {{ $vuetify.lang.t('$vuetify.menu.shop') }}
              </v-stepper-step>
              <v-divider />
              <v-stepper-step
                :step="`${article.composite ? 3 : 4 }`"
                editable
              >
                {{ $vuetify.lang.t('$vuetify.representation.representation') }}
              </v-stepper-step>
            </v-stepper-header>

            <v-stepper-items>
              <v-stepper-content step="1">
                <v-card>
                  <v-card-text>
                    <v-form
                      ref="form"
                      v-model="formValid"
                      style="padding: 0"
                      lazy-validation
                    >
                      <v-row>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-text-field
                            v-model="article.name"
                            :label="$vuetify.lang.t('$vuetify.firstName')"
                            :rules="formRule.required"
                            required
                            autofocus
                            @keypress="lnSpace"
                          />
                        </v-col>
                        <v-col>
                          <v-autocomplete
                            v-model="article.um"
                            :disabled="isActionInProgress"
                            :items="arrayUM"
                            :label="$vuetify.lang.t('$vuetify.um.name')"
                            :filter="customFilter"
                            item-text="name"
                            auto-select-first
                            return-object
                          >
                            <template v-slot:selection="data">
                              {{ $vuetify.lang.t('$vuetify.um.' + data.item.name) }}
                              <template v-if="data.item.presentation">
                                <template v-if="data.item.presentation.split('__').length === 0">
                                  ({{ data.item.presentation }})
                                </template>
                                ({{ data.item.presentation.split('__')[0] }}<sup>{{ data.item.presentation.split('__')[1] }}</sup>)
                              </template>
                            </template>
                            <template v-slot:item="data">
                              <template v-if="typeof data.item !== 'object'">
                                <v-list-item-content>
                                  {{ $vuetify.lang.t('$vuetify.um.' + data.item.header) }}
                                </v-list-item-content>
                              </template>
                              <template v-else>
                                <v-list-item-content>
                                  <v-list-item-title>
                                    {{ $vuetify.lang.t('$vuetify.um.' + data.item.name) }}
                                    <template v-if="data.item.presentation">
                                      <template v-if="data.item.presentation.split('__').length === 0">
                                        ({{ data.item.presentation }})
                                      </template>
                                      ({{ data.item.presentation.split('__')[0] }}<sup>{{ data.item.presentation.split('__')[1] }}</sup>)
                                    </template>
                                  </v-list-item-title>
                                </v-list-item-content>
                              </template>
                            </template>
                          </v-autocomplete>
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-text-field-simplemask
                            v-model="article.barCode"
                            :label="$vuetify.lang.t('$vuetify.barCode')"
                            :properties="{
                              clearable: true,
                            }"
                            :options="{
                              inputMask: '##-####-####-###',
                              outputMask: '#############',
                              empty: null,
                              alphanumeric: true,
                            }"
                            :focus="focus"
                            @focus="focus = false"
                          />
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-text-field
                            v-model="article.ref"
                            :label="$vuetify.lang.t('$vuetify.articles.ref')"
                            :rules="formRule.required"
                            required
                            @keypress="numbers"
                          />
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-text-field-money
                            v-model="article.price"
                            :label="$vuetify.lang.t('$vuetify.price')"
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
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-text-field-money
                            v-model="article.cost"
                            :disabled="article.composite"
                            :label="$vuetify.lang.t('$vuetify.articles.cost')"
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
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-select
                            v-model="article.category"
                            :items="categories"
                            :label="$vuetify.lang.t('$vuetify.menu.category')"
                            item-text="name"
                            :loading="isCategoryLoading"
                            :disabled="!!isCategoryLoading"
                            return-object
                          >
                            <template v-slot:append-outer>
                              <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                  <v-icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="$store.dispatch('category/toogleNewModal',true)"
                                  >
                                    mdi-plus
                                  </v-icon>
                                </template>
                                <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
                              </v-tooltip>
                            </template>
                          </v-select>
                          <v-dialog
                            v-model="showInfoAdd"
                            max-width="500px"
                          >
                            <v-card>
                              <v-card-title class="headline">
                                {{ $vuetify.lang.t('$vuetify.messages.dont_add') }}
                              </v-card-title>
                              <v-card-actions>
                                <v-spacer />
                                <v-btn
                                  class="mb-2"
                                  color="primary"
                                  @click="closeInfoAdd"
                                >
                                  <v-icon>mdi-check</v-icon>
                                  {{ $vuetify.lang.t('$vuetify.actions.accept') }}
                                </v-btn>
                                <v-spacer />

                                <v-btn
                                  class="mb-2"
                                  :disabled="isActionInProgress"
                                  @click="$router.push({name:'product_list'})"
                                >
                                  <v-icon>mdi-close</v-icon>
                                  {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
                                </v-btn>
                                <v-btn
                                  class="mb-2"
                                  color="primary"
                                  :disabled="!formValid || isActionInProgress"
                                  :loading="isActionInProgress"
                                  @click="managerArticleHandler"
                                >
                                  <v-icon>mdi-check</v-icon>
                                  {{ $vuetify.lang.t('$vuetify.actions.save') }}
                                </v-btn>
                              </v-card-actions>
                            </v-card>
                          </v-dialog>
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-select
                            v-model="article.tax"
                            chips
                            clearable
                            deletable-chips
                            :items="taxes"
                            multiple
                            :label="$vuetify.lang.t('$vuetify.tax.name')"
                            item-text="name"
                            :loading="isTaxLoading"
                            :disabled="!!isTaxLoading"
                            return-object
                            required
                            :rules="formRule.country"
                          >
                            <template v-slot:append-outer>
                              <v-tooltip bottom>
                                <template v-slot:activator="{ on, attrs }">
                                  <v-icon
                                    v-bind="attrs"
                                    v-on="on"
                                    @click="$store.dispatch('tax/toogleNewModal',true)"
                                  >
                                    mdi-plus
                                  </v-icon>
                                </template>
                                <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
                              </v-tooltip>
                            </template>
                          </v-select>
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-switch
                            v-show="!article.id"
                            v-model="article.composite"
                            :title="$vuetify.lang.t('$vuetify.articles.composite_text')"
                            @change="changeComposite"
                          >
                            <template v-slot:label>
                              <div>
                                {{ $vuetify.lang.t('$vuetify.articles.composite') }}
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
                                  <span>{{
                                    $vuetify.lang.t('$vuetify.articles.composite_text')
                                  }}</span>
                                </v-tooltip>
                              </div>
                            </template>
                          </v-switch>
                        </v-col>
                      </v-row>
                      <v-row v-show="article.composite">
                        <v-col
                          cols="12"
                          md="12"
                        >
                          <composite-list
                            :article="article"
                            style="margin-top: 0"
                          />
                        </v-col>
                      </v-row>
                    </v-form>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      color="primary"
                      :disabled="!formValid || isActionInProgress"
                      @click="step = 2"
                    >
                      {{ $vuetify.lang.t('$vuetify.actions.next') }}
                      <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                    <v-spacer />

                    <v-btn
                      class="mb-2"
                      :disabled="isActionInProgress"
                      @click="$router.push({name:'product_list'})"
                    >
                      <v-icon>mdi-close</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
                    </v-btn>
                    <v-btn
                      class="mb-2"
                      color="primary"
                      :disabled="!formValid || isActionInProgress"
                      :loading="isActionInProgress"
                      @click="managerArticleHandler"
                    >
                      {{ $vuetify.lang.t('$vuetify.actions.save') }}
                      <v-icon>mdi-check</v-icon>
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-stepper-content>

              <v-stepper-content
                v-if="!article.composite"
                :step="!article.composite ? 2: null"
              >
                <v-card>
                  <v-card-text>
                    <v-row>
                      <v-col
                        cols="12"
                        md="12"
                      >
                        <v-row>
                          <v-col
                            cols="12"
                            md="12"
                          >
                            <variant
                              :article="article"
                              @updateVariant="updateDataShops"
                            />
                          </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      text
                      @click="step = 1"
                    >
                      <v-icon>mdi-chevron-left</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.back') }}
                    </v-btn>
                    <v-btn
                      color="primary"
                      @click="step = 3"
                    >
                      {{ $vuetify.lang.t('$vuetify.actions.next') }}
                      <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                    <v-spacer />

                    <v-btn
                      class="mb-2"
                      :disabled="isActionInProgress"
                      @click="$router.push({name:'product_list'})"
                    >
                      <v-icon>mdi-close</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
                    </v-btn>
                    <v-btn
                      class="mb-2"
                      color="primary"
                      :disabled="!formValid || isActionInProgress"
                      :loading="isActionInProgress"
                      @click="managerArticleHandler"
                    >
                      <v-icon>mdi-check</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.save') }}
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-stepper-content>

              <v-stepper-content :step="`${article.composite ? 2 : 3}`">
                <v-card>
                  <v-card-text>
                    <v-row>
                      <v-col
                        cols="12"
                        md="12"
                      >
                        <v-row>
                          <v-col
                            cols="12"
                            md="12"
                          >
                            <shops-articles :article="article" />
                          </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      text
                      @click="article.composite ? step = 1 : step = 2"
                    >
                      <v-icon>mdi-chevron-left</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.back') }}
                    </v-btn>
                    <v-btn
                      color="primary"
                      @click="article.composite ? step = 3 : step = 4"
                    >
                      {{ $vuetify.lang.t('$vuetify.actions.next') }}
                      <v-icon>mdi-chevron-right</v-icon>
                    </v-btn>
                    <v-spacer />

                    <v-btn
                      class="mb-2"
                      :disabled="isActionInProgress"
                      @click="$router.push({name:'product_list'})"
                    >
                      <v-icon>mdi-close</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
                    </v-btn>
                    <v-btn
                      class="mb-2"
                      color="primary"
                      :disabled="!formValid || isActionInProgress"
                      :loading="isActionInProgress"
                      @click="managerArticleHandler"
                    >
                      <v-icon>mdi-check</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.save') }}
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-stepper-content>

              <v-stepper-content :step="`${article.composite ? 3 : 4}`">
                <v-card>
                  <v-card-text>
                    <v-row>
                      <v-col
                        cols="12"
                        md="12"
                      >
                        <v-radio-group
                          v-model="representation"
                          row
                        >
                          <v-row>
                            <v-col
                              cols="12"
                              md="4"
                            >
                              <v-radio
                                :label="$vuetify.lang.t('$vuetify.representation.image')"
                                value="image"
                              />
                            </v-col>
                            <v-col
                              cols="12"
                              md="8"
                            >
                              <v-radio
                                :label="$vuetify.lang.t('$vuetify.representation.color_shape')"
                                value="color"
                              />
                            </v-col>
                          </v-row>
                        </v-radio-group>
                      </v-col>
                      <v-row>
                        <v-col
                          v-show="representation===`image`"
                          cols="12"
                          md="12"
                        >
                          <div
                            id="multiple-image"
                            style="display: flex; justify-content: center;"
                          >
                            <app-upload-multiple-image
                              :data-images="article.images"
                              :upload-success="uploadImage"
                            />
                          </div>
                        </v-col>
                        <v-col
                          v-show="representation===`color`"
                          cols="12"
                          md="9"
                        >
                          <app-color-picker
                            :value="article.color || ``"
                            @input="inputColor"
                          />
                        </v-col>
                      </v-row>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      text
                      @click="article.composite ? step = 2 : step = 3"
                    >
                      <v-icon>mdi-chevron-left</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.back') }}
                    </v-btn>
                    <v-spacer />
                    <v-btn
                      class="mb-2"
                      :disabled="isActionInProgress"
                      @click="$router.push({name:'product_list'})"
                    >
                      <v-icon>mdi-close</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
                    </v-btn>
                    <v-btn
                      class="mb-2"
                      color="primary"
                      :disabled="!formValid || isActionInProgress"
                      :loading="isActionInProgress"
                      @click="managerArticleHandler"
                    >
                      <v-icon>mdi-check</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.save') }}
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-stepper-content>
            </v-stepper-items>
          </v-stepper>
        </v-card-text>
        <new-category v-if="$store.state.category.showNewModal" />
        <new-tax v-if="$store.state.tax.showNewModal" />
      </v-card>
    </v-container>
  </div>
</template>
<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import NewCategory from '../../views/category/NewCategory'
import NewTax from '../tax/NewTax'
import ShopsArticles from './shop/ShopsArticles'
import CompositeList from './composite/CompositeList'
import Variant from './variants/Variant'

export default {
	name: 'ManagerArticle',
	components: { NewCategory, NewTax, CompositeList, Variant, ShopsArticles },
	data () {
		return {
			formValid: false,
			step: 1,
			updated: false,
			formRule: this.$rules,
			loadingData: false,
			representation: 'color',
			showInfoAdd: false,
			composite: [],
			row: null,
			focus: false,
			localArticles: [],
			article: {}
		}
	},
	computed: {
		...mapState('article', ['saved', 'managerArticle', 'articleNumber', 'newArticle', 'editArticle', 'articles', 'isActionInProgress']),
		...mapState('statics', ['arrayUM']),
		...mapState('category', ['categories', 'isCategoryLoading']),
		...mapState('shop', ['shops', 'isShopLoading']),
		...mapState('tax', ['taxes', 'isTaxLoading']),
		...mapGetters('auth', ['user', 'userPin']),
		...mapGetters('article', ['getNumberArticle']),
		articleNumber: {
			get () {
				return this.getNumberArticle
			},
			set (newNumber) {
				return newNumber
			}
		}
	},
	watch: {
		'article.price': function (newVal, oldV) {
			this.article.articles_shops.forEach(shop => {
				shop.price = parseFloat(shop.price).toFixed(2) === '0.00' || parseFloat(shop.price).toFixed(2) === parseFloat(oldV).toFixed(2) ? this.article.price : parseFloat(shop.price).toFixed(2)
			})
			this.article.variant_values.forEach(variant => {
				variant.price = parseFloat(variant.price).toFixed(2) === '0.00' || parseFloat(variant.price).toFixed(2) === parseFloat(oldV).toFixed(2) ? this.article.price : parseFloat(variant.price).toFixed(2)
			})
		},
		'article.composite': function (val) {
			this.updateDataShops()
			if (!val) {
				this.article.variant_values.forEach(variant => {
					variant.price = parseFloat(variant.price).toFixed(2) === '0.00' ? this.article.price : parseFloat(variant.price).toFixed(2)
				})
			}
		},
		'article.variant_values': function () {
			this.updateDataShops()
		}
	},
	async created () {
		this.article = {
			name: '',
			um: '',
			price: 0.00,
			unit: 'unit',
			cost: 0.00,
			ref: this.articleNumber,
			barCode: '',
			variants: [],
			tax: [],
			variant_values: [],
			composite: false,
			track_inventory: false,
			category: [],
			color: '',
			articles_shops: [],
			composites: [],
			images: []
		}
		this.loadingData = true
		console.log(this.managerArticle)
		this.article = this.managerArticle ? this.editArticle : this.newArticle
		this.article.cost = parseFloat(this.article.cost).toFixed(2)
		this.article.price = parseFloat(this.article.price).toFixed(2)
		this.article.um = !this.managerArticle ? this.arrayUM[0] : this.editArticle.um ? JSON.parse(this.editArticle.um) : this.arrayUM[0]
		await this.getShops().then(() => {
			this.updateDataShops()
		})
		await this.getCategories()
		await this.getTaxes()
		await this.fetchArticleNumber().then(() => {
			this.article.ref = ++this.articleNumber
		})
		this.loadingData = false
	},
	methods: {
		...mapActions('article', ['createArticle', 'updateArticle', 'fetchArticleNumber', 'toogleNewModal', 'getArticles']),
		...mapActions('category', ['getCategories']),
		...mapActions('tax', ['getTaxes']),
		...mapActions('shop', ['getShops']),
		customFilter (item, queryText, itemText) {
			return this.$vuetify.lang.t('$vuetify.um.' + item.name).toLowerCase().indexOf(queryText.toLowerCase()) > -1
		},
		numbers (event) {
			const regex = new RegExp('^\\d+(.\\d{1,2})?$')
			const key = String.fromCharCode(
				!event.charCode ? event.which : event.charCode
			)
			if (!regex.test(key)) {
				event.preventDefault()
				return false
			}
		},
		lnSpace (event) {
			const regex = new RegExp('^[a-zA-Z0-9 ]+$')
			const key = String.fromCharCode(
				!event.charCode ? event.which : event.charCode
			)
			if (!regex.test(key)) {
				event.preventDefault()
				return false
			}
		},
		closeInfoAdd () {
			this.showInfoAdd = false
		},
		changeComposite () {
			if (this.article.composite) {
				this.article.variant_values = []
			} else {
				this.updated = false
			}
		},
		updateDataShops () {
			if (this.shops.length > 0) {
				this.article.articles_shops = []
				this.shops.forEach((shop) => {
					if (this.article.variants.length > 0 && !this.article.composite) {
						this.article.variant_values.forEach((v) => {
							// eslint-disable-next-line camelcase
							const articles_shop = v.articles_shops.filter(sh => sh.shop_id === shop.id)
							this.article.articles_shops.push({
								articles_shop_id: articles_shop.length > 0 ? articles_shop[0].id : '',
								shop_id: shop.id,
								shop_name: shop.name,
								checked: articles_shop.length > 0,
								name: v.name,
								price: articles_shop.length > 0 ? articles_shop[0].price : v.price,
								under_inventory: articles_shop.length > 0 ? articles_shop[0].under_inventory : '0.00',
								stock: articles_shop.length > 0 ? articles_shop[0].stock : '0.00'
							})
						})
					} else {
						this.article.articles_shops.push({
							shop_id: shop.id,
							shop_name: shop.name,
							checked: this.managerArticle ? this.editArticle.articles_shops.filter(sh => sh.id === shop.id).length > 0 : true,
							name: '',
							price: parseFloat(this.article.price).toFixed(2),
							stock: '0.00',
							under_inventory: '0.00'
						})
					}
				})
			}
		},
		inputColor (color) {
			this.article.color = color
		},
		uploadImage (formData, index, fileList) {
			this.article.images = fileList
		},
		managerArticleHandler () {
			let valid = true
			console.log(this.article)
			if (!this.article.category) {
				valid = false
				this.showAlertMessage(this.$vuetify.lang.t(
					'$vuetify.messages.warning_no_category'
				))
			} else {
				if (!this.validateRef() || !this.validateBarCode()) {
					valid = false
				} else {
					if (this.article.composite) {
						if (this.article.composite.length === 0) {
							this.showAlertMessage(this.$vuetify.lang.t(
								'$vuetify.messages.warning_composite'
							))
						}
					}
				}
			}
			if (valid) { this.validCreate() }
		},
		validateBarCode () {
			let valid = true
			this.article.variant_values.forEach((value) => {
				if (value.barCode) {
					const compareBarCode = this.articles.filter(art => art.barCode === value.barCode)
					if (compareBarCode.length > 0) {
						if (compareBarCode[0].id !== value.id) {
							valid = false
							this.showAlertMessage(this.$vuetify.lang.t('$vuetify.messages.warning_barCode', [value.barCode], [compareBarCode[0].name], [value.name]
							))
						} else if (this.article.barCode === compareBarCode[0].barCode && valid) {
							valid = false
							this.showAlertMessage(this.$vuetify.lang.t('$vuetify.messages.warning_barCode', [value.barCode], [compareBarCode[0].name], [this.article.name]
							))
						}
					}
				}
			})
			if (this.article.barCode && valid) {
				const existBarCode = this.articles.filter(art => art.barCode === this.article.barCode)
				if (existBarCode.length > 0) {
					if (this.article.barCode === existBarCode[0].barCode && this.article.id !== existBarCode[0].id) {
						valid = false
						this.showAlertMessage(this.$vuetify.lang.t('$vuetify.messages.warning_barCode', [this.article.barCode], [existBarCode[0].name], [this.article.name]
						))
					}
				}
			}
			return valid
		},
		validateRef () {
			let valid = true
			this.article.variant_values.forEach((value) => {
				if (valid) {
					const localArt = this.articles.filter(art => art.ref === value.ref)
					if (localArt.length > 0) {
						if (localArt[0].id !== value.id) {
							valid = false
							if (!valid) {
								this.showAlertMessage(this.$vuetify.lang.t(
									'$vuetify.messages.warning_ref', [value.ref]
								))
							}
						}
					}
				}
			})
			if (this.article.variant_values.filter(vd => vd.ref === this.article.ref).length > 0) {
				valid = false
				this.showAlertMessage(this.$vuetify.lang.t(
					'$vuetify.messages.warning_ref', [this.article.ref]
				))
			} else {
				const localArt = this.articles.filter(art => art.ref === this.article.ref && art.id !== this.article.id)
				if (localArt.length > 0) {
					valid = false
					this.showAlertMessage(this.$vuetify.lang.t(
						'$vuetify.messages.warning_ref', [this.article.ref]
					))
				}
			}
			return valid
		},
		showAlertMessage (textMsg) {
			this.$Swal.fire({
				title: this.$vuetify.lang.t(this.managerArticle ? '$vuetify.titles.edit' : '$vuetify.titles.new', [
					this.$vuetify.lang.t('$vuetify.menu.article')
				]),
				text: textMsg,
				icon: 'warning',
				showCancelButton: false,
				confirmButtonText: this.$vuetify.lang.t(
					'$vuetify.actions.accept'
				),
				confirmButtonColor: 'red'
			})
		},
		async validCreate () {
			if (this.$refs.form.validate()) {
				this.loading = true
				!this.managerArticle ? await this.createArticle(this.article) : await this.updateArticle(this.article)
				await this.$router.push({ name: 'product_list' })
				this.loading = false
			}
		}
	}
}
</script>

<style scoped>

</style>
