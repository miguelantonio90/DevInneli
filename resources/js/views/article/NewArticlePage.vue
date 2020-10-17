<template>
  <div class="page-add-product">
    <app-loading v-show="loadingData" />
    <v-container
      v-if="!loadingData"
      fill-height
      fluid
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.titles.new', [
              $vuetify.lang.t('$vuetify.articles.name'),
            ])
          }}</span>
        </v-card-title>
        <v-card-text>
          <v-form
            ref="form"
            v-model="formValid"
            style="padding: 0"
            lazy-validation
          >
            <v-expansion-panels
              v-model="panel"
              style="margin: 0"
              multiple
            >
              <v-expansion-panel style="margin: 0">
                <v-expansion-panel-header>
                  <h3>
                    {{
                      $vuetify.lang.t('$vuetify.panel.basic')
                    }}
                  </h3>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-row>
                    <v-col
                      cols="12"
                      md="3"
                    >
                      <v-text-field
                        v-model="newArticle.name"
                        :label="$vuetify.lang.t('$vuetify.firstName')"
                        :rules="formRule.required"
                        required
                        @keypress="lnSpace"
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="3"
                    >
                      <v-text-field
                        v-model="newArticle.barCode"
                        :rules="formRule.required"
                        :label="$vuetify.lang.t('$vuetify.barCode')"
                        required
                        @keypress="lettersNumbers"
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="3"
                    >
                      <v-text-field
                        v-model="newArticle.price"
                        default="0.00"
                        :label="$vuetify.lang.t('$vuetify.price')"
                        autocomplete="off"
                        required
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="3"
                    >
                      <v-select
                        v-model="newArticle.category"
                        :items="categories"
                        :label="$vuetify.lang.t('$vuetify.menu.category')"
                        item-text="name"
                        :loading="isCategoryLoading"
                        :disabled="!!isCategoryLoading"
                        return-object
                      />
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
                      md="3"
                    >
                      <v-text-field
                        v-model="newArticle.cost"
                        :disabled="newArticle.composite"
                        :label="$vuetify.lang.t('$vuetify.articles.cost')"
                        autocomplete="off"
                        required
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="3"
                    >
                      <v-text-field
                        v-model="newArticle.ref"
                        :label="$vuetify.lang.t('$vuetify.variants.ref')"
                        autocomplete="off"
                        required
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="4"
                    >
                      <h4>{{ $vuetify.lang.t('$vuetify.articles.sell_by') }}</h4>
                      <v-radio-group
                        v-model="newArticle.unit"
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
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  <h3>{{ $vuetify.lang.t('$vuetify.articles.inventory') }}</h3>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-row>
                    <v-col
                      cols="12"
                      md="3"
                    >
                      <v-switch
                        v-model="newArticle.composite"
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
                      v-show="newArticle.composite"
                      cols="12"
                      md="3"
                    >
                      <v-select
                        ref="selectArticle"
                        :items="articles"
                        :label="$vuetify.lang.t('$vuetify.rule.select')"
                        item-text="name"
                        :loading="isShopLoading"
                        :disabled="!!isShopLoading"
                        return-object
                        @input="selectArticle"
                      />
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col
                      v-show="!newArticle.composite"
                      cols="12"
                      md="3"
                    >
                      <v-switch
                        v-model="newArticle.track_inventory"
                        class="md-6"
                        :label="$vuetify.lang.t('$vuetify.articles.track_inventory')"
                      />
                    </v-col>
                  </v-row>
                  <v-row v-show="newArticle.composite">
                    <v-col
                      cols="12"
                      md="12"
                    >
                      <composite-list
                        :composite_list="composite"
                        style="margin-top: 0"
                        @updateComposite="updateComposite"
                      />
                    </v-col>
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel v-show="!newArticle.composite">
                <v-expansion-panel-header>
                  <h3>
                    {{
                      $vuetify.lang.t('$vuetify.panel.variant')
                    }}
                  </h3>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
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
                            :variants-parent="newArticle.variants"
                            :variants-values-parent="variantData"
                            @updateVariants="updateVariant"
                          />
                        </v-col>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  <h3>
                    {{
                      $vuetify.lang.t('$vuetify.menu.shop')
                    }}
                  </h3>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
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
                            :track_inventory="newArticle.track_inventory"
                            :shop-data="shopData"
                            :variants-data="variantData"
                            @updateShopsData="updateShopData"
                          />
                        </v-col>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  <h3>
                    {{
                      $vuetify.lang.t('$vuetify.representation.representation')
                    }}
                  </h3>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
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
                            :data-images="newArticle.images"
                            :upload-success="uploadImage"
                          />
                        </div>
                      </v-col>
                      <v-col
                        v-show="representation===`color`"
                        cols="12"
                        md="12"
                      >
                        <app-color-picker
                          :value="newArticle.color"
                          @input="inputColor"
                        />
                      </v-col>
                    </v-row>
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            class="mb-2"
            color="error"
            @click="$router.push({name:'product_list'})"
          >
            <v-icon>mdi-close</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
            :disabled="!formValid"
            :loading="isActionInProgress"
            @click="createNewArticle"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.save') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-container>
  </div>
</template>

<script>

import { mapActions, mapState } from 'vuex'
import ShopsArticles from './shop/ShopsArticles'
import Variant from './variants/Variant'
import CompositeList from './composite/CompositeList'

export default {
  name: 'NewArticlePage',
  components: { CompositeList, ShopsArticles, Variant },
  data () {
    return {
      ref: '10001',
      representation: 'image',
      showInfoAdd: false,
      composite: [],
      row: null,
      panel: [0, 1, 2],
      formValid: false,
      shopData: [],
      variantData: [],
      updated: true,
      formRule: this.$rules,
      loadingData: false
    }
  },
  computed: {
    ...mapState('article', ['saved', 'newArticle', 'articles', 'isActionInProgress']),
    ...mapState('category', ['categories', 'isCategoryLoading']),
    ...mapState('shop', ['shops', 'isShopLoading'])
  },
  created: async function () {
    this.loadingData = true
    this.formValid = false
    await this.getShops().then(() => {
      this.shops.forEach((shop) => {
        this.shopData.push({
          shop_id: shop.id,
          name: shop.name,
          checked: true,
          variant: '',
          price: '0.00',
          stock: '',
          under_inventory: ''
        })
      })
    })
    await this.getCategories()
    await this.getArticles()
    this.loadingData = false
    this.ref = '10001'
  },
  mounted () {
    this.ref = '10001'
  },
  methods: {
    ...mapActions('article', ['createArticle', 'toogleNewModal', 'getArticles']),
    ...mapActions('category', ['getCategories']),
    ...mapActions('shop', ['getShops']),
    inputColor (color) {
      this.newArticle.color = color
    },
    selectArticle (item) {
      if (this.composite.filter(art => art.id === item.id).length === 0) {
        this.composite.push({
          name: item.name,
          price: item.price,
          cost: item.price,
          cant: '1',
          composite_id: item.id
        })
        let totalCost = 0.00
        this.composite.forEach((comp) => {
          totalCost += comp.cant * comp.price
        })
        this.newArticle.cost = totalCost
      } else {
        this.showInfoAdd = true
      }
      this.selected = null
    },
    changeComposite () {
      if (this.newArticle.composite) {
        this.variantData = []
      } else {
        this.updated = false
      }
    },
    updateComposite (composite) {
      this.composite = composite
      let cost = 0.00
      this.composite.forEach((comp) => {
        cost += comp.cant * comp.price
      })
      this.newArticle.cost = cost
    },
    updateVariant (variants, dataUpdated) {
      this.variantData = dataUpdated
      this.newArticle.variants = variants
      this.shopData = []
      this.shops.forEach((shop) => {
        if (variants.length > 0) {
          this.variantData.forEach((v) => {
            this.shopData.push({
              shop_id: shop.id,
              name: shop.name,
              checked: true,
              variant: v.variant,
              price: v.price,
              stock: '0',
              under_inventory: '0'
            })
          })
        } else {
          this.shopData.push({
            shop_id: shop.id,
            name: shop.name,
            checked: true,
            variant: '',
            price: '0.00',
            stock: '',
            under_inventory: ''
          })
        }
      })
      this.updated = true
    },
    updateShopData (shopsDataUpdated) {
      this.shopData = shopsDataUpdated
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
    createNewArticle () {
      if (parseFloat(this.newArticle.cost) >= parseFloat(this.newArticle.price)) {
        this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.new', [
              this.$vuetify.lang.t('$vuetify.menu.articles')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.warning_price'
            ),
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'red'
          })
      } else {
        if (this.newArticle.composite) {
          if (this.composite.length === 0) {
            this.$Swal
              .fire({
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
            this.validCreate()
          }
        } else {
          this.validCreate()
        }
      }
    },
    closeInfoAdd () {
      this.showInfoAdd = false
    },
    uploadImage (formData, index, fileList) {
      this.newArticle.images = fileList
    },
    async validCreate () {
      if (this.$refs.form.validate()) {
        this.loading = true
        this.newArticle.shops = []
        this.shopData.forEach((value) => {
          if (value.checked) {
            this.newArticle.shops.push(value)
          }
        })
        this.newArticle.variantsValues = this.variantData
        this.newArticle.composites = this.composite
        await this.createArticle(this.newArticle).then(() => {
          if (this.saved) {
            this.loading = false
            const msg = this.$vuetify.lang.t(
              '$vuetify.messages.success_profile'
            )
            this.$Toast.fire({
              icon: 'success',
              title: msg
            })
            this.$router.push({ name: 'product_list' })
          }
        })
      }
    }
  }
}
</script>

<style scoped>

</style>
