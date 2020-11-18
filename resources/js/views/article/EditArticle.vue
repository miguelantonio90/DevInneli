<template>
  <div class="page-add-product">
    <app-loading v-show="loadingData" />
    <v-container
      v-if="!loadingData"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.titles.edit', [
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
                :complete="step > 2"
                :editable="formValid"
                step="2"
              >
                {{ $vuetify.lang.t('$vuetify.articles.inventory') }}
              </v-stepper-step>
              <v-divider />
              <v-stepper-step
                v-if="!editArticle.composite"
                step="3"
                :complete="step > 3"
                :editable="formValid"
              >
                {{ $vuetify.lang.t('$vuetify.panel.variant') }}
              </v-stepper-step>
              <v-divider v-if="!editArticle.composite" />
              <v-stepper-step
                :complete="editArticle.composite ? step > 3:step > 4"
                :editable="formValid"
                :step="`${editArticle.composite ? 3 : 4 }`"
              >
                {{ $vuetify.lang.t('$vuetify.menu.shop') }}
              </v-stepper-step>
              <v-divider />
              <v-stepper-step
                :step="`${editArticle.composite ? 4 : 5 }`"
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
                            v-model="editArticle.name"
                            :label="$vuetify.lang.t('$vuetify.firstName')"
                            :rules="formRule.required"
                            required
                            autofocus
                            @keypress="lnSpace"
                          />
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-text-field-simplemask
                            v-model="editArticle.barCode"
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
                            v-model="editArticle.ref"
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
                            v-model="editArticle.price"
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
                            v-model="editArticle.cost"
                            :disabled="editArticle.composite"
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
                          <h4>{{ $vuetify.lang.t('$vuetify.articles.sell_by') }}</h4>
                          <v-radio-group
                            v-model="editArticle.unit"
                            row
                          >
                            <v-radio
                              :label="$vuetify.lang.t('$vuetify.articles.unit')"
                              value="unit"
                            />
                            <v-radio
                              :label="$vuetify.lang.t('$vuetify.articles.p_v')"
                              value="vol"
                            />
                          </v-radio-group>
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-select
                            v-model="editArticle.category"
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
                              </v-card-actions>
                            </v-card>
                          </v-dialog>
                        </v-col>
                        <v-col
                          cols="12"
                          md="4"
                        >
                          <v-select
                            v-model="editArticle.tax"
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
                      </v-row>
                    </v-form>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      color="primary"
                      :disabled="!formValid || isActionInProgress"
                      @click="step = 2"
                    >
                      <v-icon>mdi-chevron-right</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.next') }}
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-stepper-content>

              <v-stepper-content step="2">
                <v-card>
                  <v-card-text>
                    <v-row>
                      <v-col
                        cols="12"
                        md="3"
                      >
                        <v-switch
                          v-model="editArticle.composite"
                          :disabled="articles.length===0"
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
                      <v-col
                        v-show="editArticle.composite"
                        cols="12"
                        md="3"
                      >
                        <v-select
                          ref="selectArticle"
                          :items="localArticles"
                          :label="$vuetify.lang.t('$vuetify.rule.select')"
                          item-text="name"
                          chips
                          :loading="isShopLoading"
                          :disabled="!!isShopLoading"
                          return-object
                          @input="selectArticle"
                        >
                          <template v-slot:selection="data">
                            <v-chip
                              :key="JSON.stringify(data.item)"
                              v-bind="data.attrs"
                              :input-value="data.selected"
                              :disabled="data.disabled"
                              @click:close="data.parent.selectItem(data.item)"
                            >
                              <v-avatar
                                v-if="data.item.color"
                                class="white&#45;&#45;text"
                                :color="data.item.color"
                                left
                                v-text="data.item.name.slice(0, 1).toUpperCase()"
                              />
                              <v-avatar
                                v-else
                                left
                              >
                                <v-img :src="data.item.path" />
                              </v-avatar>
                              {{ data.item.name }}
                            </v-chip>
                          </template>
                        </v-select>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col
                        v-show="!editArticle.composite"
                        cols="12"
                        md="3"
                      >
                        <v-switch
                          v-model="editArticle.track_inventory"
                          :title="$vuetify.lang.t('$vuetify.articles.track_inventory')"
                          @change="changeInventory"
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
                                <span>{{
                                  $vuetify.lang.t('$vuetify.messages.warning_article_service')
                                }}</span>
                              </v-tooltip>
                            </div>
                          </template>
                        </v-switch>
                      </v-col>
                    </v-row>
                    <v-row v-show="editArticle.composite">
                      <v-col
                        cols="12"
                        md="12"
                      >
                        <composite-list
                          :composite-list="composite"
                          style="margin-top: 0"
                          @updateComposite="updateComposite"
                        />
                      </v-col>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      color="primary"
                      @click="step = 3"
                    >
                      <v-icon>mdi-chevron-right</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.next') }}
                    </v-btn>

                    <v-btn
                      text
                      @click="step = 1"
                    >
                      {{ $vuetify.lang.t('$vuetify.actions.back') }}
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-stepper-content>

              <v-stepper-content
                v-if="!editArticle.composite"
                :step="!editArticle.composite ? 3: null"
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
                              :ref-parent="ref"
                              :updated="updated"
                              :variants-parent="editArticle.variants"
                              :variants-values-parent="variantData"
                              @updateVariants="updateVariant"
                            />
                          </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      color="primary"
                      @click="step = 4"
                    >
                      <v-icon>mdi-chevron-right</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.next') }}
                    </v-btn>

                    <v-btn
                      text
                      @click="step = 2"
                    >
                      {{ $vuetify.lang.t('$vuetify.actions.back') }}
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-stepper-content>

              <v-stepper-content :step="`${editArticle.composite ? 3 : 4}`">
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
                            <shops-articles
                              :track-inventory-parent="track_inventory"
                              :shop-data="shopData"
                              :variants-data="variantData"
                              @updateShopsData="updateShopData"
                            />
                          </v-col>
                        </v-row>
                      </v-col>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      color="primary"
                      @click="editArticle.composite ? step = 4 : step = 5"
                    >
                      <v-icon>mdi-chevron-right</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.next') }}
                    </v-btn>

                    <v-btn
                      text
                      @click="editArticle.composite ? step = 2 : step = 3"
                    >
                      {{ $vuetify.lang.t('$vuetify.actions.back') }}
                    </v-btn>
                  </v-card-actions>
                </v-card>
              </v-stepper-content>

              <v-stepper-content :step="`${editArticle.composite ? 4 : 5}`">
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
                              :data-images="editArticle.images"
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
                            :value="editArticle.color || ``"
                            @input="inputColor"
                          />
                        </v-col>
                      </v-row>
                    </v-row>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn
                      class="mb-2"
                      color="primary"
                      :disabled="!formValid || isActionInProgress"
                      :loading="isActionInProgress"
                      @click="editArticle"
                    >
                      <v-icon>mdi-check</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.save') }}
                    </v-btn>
                    <v-btn
                      class="mb-2"
                      :disabled="isActionInProgress"
                      @click="$router.push({name:'product_list'})"
                    >
                      <v-icon>mdi-close</v-icon>
                      {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
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
import { mapActions, mapState, mapGetters } from 'vuex'
import NewCategory from '../../views/category/NewCategory'
import ShopsArticles from './shop/ShopsArticles'
import Variant from './variants/Variant'
import CompositeList from './composite/CompositeList'
import NewTax from '../tax/NewTax'

export default {
  name: 'EditArticle',
  components: { NewCategory, CompositeList, ShopsArticles, Variant, NewTax },
  data () {
    return {
      step: 1,
      localArticles: [],
      track_inventory: false,
      ref: '10001',
      representation: 'image',
      showInfoAdd: false,
      composite: [],
      row: null,
      formValid: false,
      shopData: [],
      variantData: [],
      updated: true,
      formRule: this.$rules,
      loadingData: false,
      focus: false
    }
  },
  computed: {
    ...mapState('article', ['saved', 'editArticle', 'articles', 'isActionInProgress']),
    ...mapState('category', ['categories', 'isCategoryLoading']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapState('tax', ['taxes', 'isTaxLoading']),
    ...mapGetters('auth', ['user'])
  },
  created: async function () {
    this.loadingData = true
    this.formValid = false
    await this.getCategories()
    this.variants = []
    if (this.editArticle.variant_values.length > 0) {
      this.editArticle.variants.forEach((vtn) => {
        this.variants.push({
          id: vtn.id,
          name: vtn.name,
          articles_id: vtn.articles_id,
          created_at: vtn.created_at,
          updated_at: vtn.updated_at,
          value: JSON.parse(vtn.value)
        })
      })
    }
    this.variantData = this.editArticle.variant_values
    await this.getShops().then(() => {
      this.updateVariant(this.variants, this.variantData)
    })
    this.composite = []
    await this.getArticles().then(() => {
      this.localArticles = []
      this.articles.forEach((value) => {
        this.ref = parseFloat(value.ref) > parseFloat(this.ref) ? value.ref : this.ref
        this.representation = typeof value.color === 'string' ? 'color' : 'image'
        if (value.id !== this.editArticle.id) {
          if (!value.parent_id) {
            if (value.variant_values.length > 0) {
              value.variant_values.forEach((v) => {
                this.localArticles.push({
                  name: value.name + '(' + v.name + ')',
                  price: v.price,
                  cost: v.cost,
                  cant: '1',
                  composite_id: v.id
                })
              })
            } else {
              this.localArticles.push({
                name: value.name,
                price: value.price,
                cost: value.cost,
                cant: '1',
                composite_id: value.id
              })
            }
          }
        }
      })
    })
    this.editArticle.composites.forEach((value) => {
      const comp = this.articles.filter(art => art.id === value.composite_id)[0]
      this.composite.push({
        name: comp.parent_id ? this.articles.filter(art => art.id === comp.parent_id)[0].name + '(' +
                    comp.name + ')' : comp.name,
        price: value.price,
        id: value.id,
        cost: comp.cost,
        cant: value.cant,
        composite_id: value.composite_id
      })
    })
    await this.getTaxes()
    this.ref = parseFloat(this.ref) + 1
    this.loadingData = false
  },
  mounted () {
    this.track_inventory = this.editArticle.track_inventory
  },
  methods: {
    ...mapActions('article', ['updateArticle', 'toogleNewModal', 'getArticles']),
    ...mapActions('category', ['getCategories']),
    ...mapActions('tax', ['getTaxes']),
    ...mapActions('shop', ['getShops']),
    inputColor (color) {
      this.editArticle.color = color
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
    lettersNumbers (event) {
      const regex = new RegExp('^[a-zA-Z0-9]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
    },
    changeComposite () {
      if (this.editArticle.composite) {
        this.variantData = []
      } else {
        this.updated = false
      }
    },
    changeInventory () {
      this.track_inventory = this.editArticle.track_inventory
    },
    updateComposite (composite) {
      this.composite = composite
      let cost = 0.00
      let price = 0.00
      this.composite.forEach((comp) => {
        cost += comp.cant * comp.cost
        price += comp.cant * comp.price
      })

      this.editArticle.cost = cost
      this.editArticle.price = price
    },
    selectArticle (item) {
      if (this.composite.filter(art => art.composite_id === item.composite_id).length === 0) {
        this.composite.push({
          name: item.name,
          price: item.price,
          cost: item.cost,
          cant: '1',
          composite_id: item.composite_id
        })
        let totalCost = 0.00
        let totalPrice = 0.00
        this.composite.forEach((comp) => {
          totalCost += comp.cant * comp.cost
          totalPrice += comp.cant * comp.price
        })
        this.editArticle.cost = totalCost
        this.editArticle.price = totalPrice
      } else {
        this.showInfoAdd = true
      }
      this.selected = null
    },
    updateShopData (shopsDataUpdated) {
      this.shopData = shopsDataUpdated
    },
    updateVariant (variants, dataUpdated) {
      this.variantData = dataUpdated
      this.editArticle.variants = variants
      this.shopData = []
      this.shops.forEach((shop) => {
        if (variants.length > 0) {
          this.variantData.forEach((v) => {
            // eslint-disable-next-line camelcase
            const articles_shop = v.articles_shops.filter(sh => sh.shop_id === shop.id)
            this.shopData.push({
              articles_shop_id: articles_shop.length > 0 ? articles_shop[0].id : '',
              shop_id: shop.id,
              shop_name: shop.name,
              checked: articles_shop.length > 0,
              name: v.name,
              price: articles_shop.length > 0 ? articles_shop[0].price : v.price,
              under_inventory: articles_shop.length > 0 ? articles_shop[0].under_inventory : '0',
              stock: articles_shop.length > 0 ? articles_shop[0].stock : '0'
            })
          })
        } else {
          this.shopData.push({
            shop_id: shop.id,
            shop_name: shop.name,
            checked: true,
            name: '',
            price: '0.00',
            stock: '0',
            under_inventory: '0'
          })
        }
      })
      this.updated = true
    },
    editArticleHandler () {
      let valid = true
      if (!this.validateRef() || !this.validateBarCode()) { valid = false } else if (this.editArticle.composite) {
        if (this.composite.length === 0) {
          valid = false
          this.$Swal.fire({
            title: this.$vuetify.lang.t('$vuetify.titles.new', [
              this.$vuetify.lang.t('$vuetify.menu.articles')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.warning_composite'
            ),
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'red'
          })
        } else {
          this.editArticle.composites = this.composite
        }
      } else {
        this.editArticle.variantsValues = this.variantData
      }
      if (valid) { this.validCreate() }
    },
    validateBarCode () {
      let valid = true
      this.variantData.forEach((value) => {
        if (valid) {
          const localArt = this.articles.filter(art => art.barCode === value.barCode)
          if (localArt.length > 0) {
            if (localArt[0].id !== value.id) {
              valid = false
              if (!valid) {
                this.$Swal.fire({
                  title: this.$vuetify.lang.t('$vuetify.titles.edit', [
                    this.$vuetify.lang.t('$vuetify.menu.articles')
                  ]),
                  text: this.$vuetify.lang.t(
                    '$vuetify.messages.warning_barCode', [value.barCode]
                  ),
                  icon: 'warning',
                  showCancelButton: false,
                  confirmButtonText: this.$vuetify.lang.t(
                    '$vuetify.actions.accept'
                  ),
                  confirmButtonColor: 'red'
                })
              }
            }
          }
        }
      })
      if (this.variantData.filter(vd => vd.barCode === this.editArticle.barCode).length > 0) {
        valid = false
        this.$Swal.fire({
          title: this.$vuetify.lang.t('$vuetify.titles.edit', [
            this.$vuetify.lang.t('$vuetify.menu.articles')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.warning_barCode', [this.editArticle.barCode]
          ),
          icon: 'warning',
          showCancelButton: false,
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
      } else {
        const localArt = this.articles.filter(art => art.barCode === this.editArticle.barCode && art.id !== this.editArticle.id)
        if (localArt.length > 0) {
          valid = false
          this.$Swal.fire({
            title: this.$vuetify.lang.t('$vuetify.titles.edit', [
              this.$vuetify.lang.t('$vuetify.menu.articles')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.warning_barCode', [this.editArticle.barCode]
            ),
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'red'
          })
        }
      }
      return valid
    },
    validateRef () {
      let valid = true
      this.variantData.forEach((value) => {
        if (valid) {
          const localArt = this.articles.filter(art => art.ref === value.ref)
          if (localArt.length > 0) {
            if (localArt[0].id !== value.id) {
              valid = false
              if (!valid) {
                this.$Swal.fire({
                  title: this.$vuetify.lang.t('$vuetify.titles.edit', [
                    this.$vuetify.lang.t('$vuetify.menu.articles')
                  ]),
                  text: this.$vuetify.lang.t(
                    '$vuetify.messages.warning_ref', [value.ref]
                  ),
                  icon: 'warning',
                  showCancelButton: false,
                  confirmButtonText: this.$vuetify.lang.t(
                    '$vuetify.actions.accept'
                  ),
                  confirmButtonColor: 'red'
                })
              }
            }
          }
        }
      })
      if (this.variantData.filter(vd => vd.ref === this.editArticle.ref).length > 0) {
        valid = false
        this.$Swal.fire({
          title: this.$vuetify.lang.t('$vuetify.titles.edit', [
            this.$vuetify.lang.t('$vuetify.menu.articles')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.warning_ref', [this.editArticle.ref]
          ),
          icon: 'warning',
          showCancelButton: false,
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
      } else {
        const localArt = this.articles.filter(art => art.ref === this.editArticle.ref && art.id !== this.editArticle.id)
        if (localArt.length > 0) {
          valid = false
          this.$Swal.fire({
            title: this.$vuetify.lang.t('$vuetify.titles.edit', [
              this.$vuetify.lang.t('$vuetify.menu.articles')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.warning_ref', [this.editArticle.ref]
            ),
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'red'
          })
        }
      }
      return valid
    },
    closeInfoAdd () {
      this.showInfoAdd = false
    },
    uploadImage (formData, index, fileList) {
      this.editArticle.images = fileList
    },
    async validCreate () {
      if (this.$refs.form.validate()) {
        this.loading = true
        this.editArticle.shops = []
        this.shopData.forEach((value) => {
          if (value.checked) {
            this.editArticle.shops.push(value)
          }
        })
        this.editArticle.variant_values = this.variantData
        this.editArticle.composites = this.composite
        await this.updateArticle(this.editArticle)
        await this.$router.push({ name: 'product_list' })
      }
    }
  }
}
</script>

<style scoped>

</style>
