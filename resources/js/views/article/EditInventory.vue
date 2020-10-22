<template>
  <div class="page-edit-product">
    <app-loading v-show="loadingData" />
    <v-container
      v-if="!loadingData"
      fill-height
      fluid
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
                      md="4"
                    >
                      <v-text-field
                        v-model="editArticle.name"
                        :label="$vuetify.lang.t('$vuetify.firstName')"
                        :rules="formRule.required"
                        required
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
                          required:true,
                          rules:formRule.required
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
                      <v-text-field-money
                        v-model="editArticle.price"
                        :label="$vuetify.lang.t('$vuetify.price')"
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
                      v-show="!editArticle.composite"
                      cols="12"
                      md="3"
                    >
                      <v-switch
                        v-model="editArticle.track_inventory"
                        class="md-6"
                        :label="$vuetify.lang.t('$vuetify.articles.track_inventory')"
                        @change="changeInventory"
                      />
                    </v-col>
                  </v-row>
                  <v-row v-show="editArticle.composite">
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
              <v-expansion-panel v-show="!editArticle.composite">
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
                            :updated="updated"
                            :variants-parent="editArticle.variants"
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
                            :track-inventory-parent="track_inventory"
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
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            class="mb-2"
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
            @click="editArticleHandler"
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
import AppLoading from '../../components/core/AppLoading'

export default {
  name: 'EditInventory',
  components: { AppLoading, CompositeList, ShopsArticles, Variant },
  data () {
    return {
      track_inventory: false,
      variants: [],
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
      loadingData: false,
      focus: false
    }
  },
  computed: {
    ...mapState('article', ['saved', 'editArticle', 'articles', 'isActionInProgress']),
    ...mapState('category', ['categories', 'isCategoryLoading']),
    ...mapState('shop', ['shops', 'isShopLoading'])
  },
  created: async function () {
    this.loadingData = true
    this.formValid = false
    await this.getCategories()
    this.variants = []
    this.composite = []
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
    this.variantData = this.editArticle.variants_values
    await this.getShops().then(() => {
      this.shops.forEach((shop) => {
        if (this.variants.length > 0) {
          this.variantData.forEach((v) => {
            this.shopData.push({
              shop_id: shop.id,
              name: shop.name,
              checked: this.editArticle.variants_shops.filter(sh => sh.variant === v.variant).length > 0,
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
    })
    await this.getArticles()
    if (this.editArticle.composite === 0) {
      this.editArticle.composite = false
    } else if (this.editArticle.composite === 1) {
      this.editArticle.composite = true
      this.editArticle.composites.forEach((cmp) => {
        this.composite.push({
          composite_id: cmp.composite_id,
          name: cmp.composite_name,
          price: cmp.price,
          cost: cmp.price * cmp.cant,
          cant: cmp.cant,
          id: cmp.id
        })
      })
    }
    if (this.editArticle.track_inventory === 0) {
      this.editArticle.track_inventory = false
    } else if (this.editArticle.track_inventory === 1) {
      this.editArticle.track_inventory = true
    }
    this.track_inventory = this.editArticle.track_inventory
    if (this.editArticle.unit === 1) {
      this.editArticle.unit = 'unit'
    }
    this.representation = this.articles.color ? 'color' : 'image'
    this.loadingData = false
  },
  mounted () {
    this.changeInventory()
  },
  methods: {
    ...mapActions('article', ['updateArticle', 'toogleNewModal', 'getArticles']),
    ...mapActions('category', ['getCategories']),
    ...mapActions('shop', ['getShops']),
    inputColor (color) {
      this.editArticle.color = color
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
        this.editArticle.cost = totalCost
      } else {
        this.showInfoAdd = true
      }
      this.selected = null
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
      this.composite.forEach((comp) => {
        cost += comp.cant * comp.price
      })
      this.editArticle.cost = cost
    },
    updateVariant (variants, dataUpdated) {
      this.variantData = dataUpdated
      this.editArticle.variants = variants
      this.shopData = []
      this.shops.forEach((shop) => {
        if (variants.length > 0) {
          this.variantData.forEach((v) => {
            this.shopData.push({
              shop_id: shop.id,
              name: shop.name,
              checked: this.editArticle.variants_shops.filter(sh => sh.variant === v.variant).length > 0,
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
    editArticleHandler () {
      if (this.editArticle.composite) {
        if (this.composite.length === 0) {
          this.$Swal
            .fire({
              title: this.$vuetify.lang.t('$vuetify.titles.new', [
                this.$vuetify.lang.t('$vuetify.articles.name')
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
    },
    uploadImage (formData, index, fileList) {
      this.newArticle.images = fileList
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
        this.editArticle.variantsValues = this.variantData
        this.editArticle.composites = this.composite
        await this.updateArticle(this.editArticle)
        await this.$router.push({ name: 'product_list' })
      }
    },
    closeInfoAdd () {
      this.showInfoAdd = false
    }
  }
}
</script>

<style scoped>

</style>
