<template>
  <div class="page-add-product">
    <app-loading v-show="loadingData" />
    <v-container
      v-if="!loadingData"
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
                  <div>
                    <v-icon>mdi-database-edit</v-icon>
                    <span style="text-transform: uppercase;font-weight: bold">
                      {{ $vuetify.lang.t('$vuetify.panel.basic') }}
                    </span>
                  </div>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-row>
                    <v-col
                      cols="12"
                      md="4"
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
                      md="4"
                    >
                      <v-text-field-simplemask
                        v-model="newArticle.barCode"
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
                      <v-text-field
                        v-model="newArticle.ref"
                        :label="$vuetify.lang.t('$vuetify.articles.ref')"
                        :rules="formRule.required"
                        required
                        @keypress="numbers"
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="2"
                    >
                      <v-text-field-money
                        v-model="newArticle.price"
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
                      md="2"
                    >
                      <v-text-field-money
                        v-model="newArticle.cost"
                        :disabled="newArticle.composite"
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
                    <v-col
                      cols="12"
                      md="4"
                    >
                      <v-select
                        v-model="newArticle.category"
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
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  <div>
                    <v-icon>mdi-book-open-variant</v-icon>
                    <span style="text-transform: uppercase;font-weight: bold">
                      {{ $vuetify.lang.t('$vuetify.articles.inventory') }}
                    </span>
                  </div>
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
                              class="white--text"
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
                      v-show="!newArticle.composite"
                      cols="12"
                      md="3"
                    >
                      <v-switch
                        v-model="newArticle.track_inventory"
                        class="md-6"
                        :label="$vuetify.lang.t('$vuetify.articles.track_inventory')"
                        @change="changeInventory"
                      />
                    </v-col>
                  </v-row>
                  <v-row v-show="newArticle.composite">
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
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-expansion-panel v-show="!newArticle.composite">
                <v-expansion-panel-header>
                  <div>
                    <v-icon>mdi-order-bool-descending-variant</v-icon>
                    <span style="text-transform: uppercase;font-weight: bold">
                      {{ $vuetify.lang.t('$vuetify.panel.variant') }}
                    </span>
                  </div>
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
                  <div>
                    <v-icon>mdi-shopping</v-icon>
                    <span style="text-transform: uppercase;font-weight: bold">
                      {{ $vuetify.lang.t('$vuetify.menu.shop') }}
                    </span>
                  </div>
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
                  <div>
                    <v-icon>mdi-palette</v-icon>
                    <span style="text-transform: uppercase;font-weight: bold">
                      {{ $vuetify.lang.t('$vuetify.representation.representation') }}
                    </span>
                  </div>
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
                        md="9"
                      >
                        <app-color-picker
                          :value="newArticle.color || ``"
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
            @click="createNewArticle"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.save') }}
          </v-btn>
        </v-card-actions>
        <new-category v-if="$store.state.category.showNewModal" />
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

export default {
  name: 'NewArticle',
  components: { NewCategory, CompositeList, ShopsArticles, Variant },
  data () {
    return {
      track_inventory: false,
      ref: '10001',
      representation: 'image',
      showInfoAdd: false,
      composite: [],
      row: null,
      panel: [0],
      formValid: false,
      shopData: [],
      variantData: [],
      updated: true,
      formRule: this.$rules,
      loadingData: false,
      focus: false,
      localArticles: []
    }
  },
  computed: {
    ...mapState('article', ['saved', 'newArticle', 'articles', 'isActionInProgress']),
    ...mapState('category', ['categories', 'isCategoryLoading']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapGetters('auth', ['user'])
  },
  created: async function () {
    this.loadingData = true
    this.formValid = false
    await this.getShops().then(() => {
      this.shops.forEach((shop) => {
        this.shopData.push({
          shop_id: shop.id,
          shop_name: shop.name,
          checked: true,
          name: '',
          price: '0.00',
          stock: '',
          under_inventory: ''
        })
      })
    })
    await this.getCategories()
    await this.getArticles().then(() => {
      this.articles.forEach((value) => {
        console.log(value)
        this.ref = parseFloat(value.ref) > parseFloat(this.ref) ? value.ref : this.ref
        if (!value.article_id) {
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
      })
    })
    this.ref = parseFloat(this.ref) + 1
    this.newArticle.ref = this.ref
    this.loadingData = false
  },
  methods: {
    ...mapActions('article', ['createArticle', 'toogleNewModal', 'getArticles']),
    ...mapActions('category', ['getCategories']),
    ...mapActions('shop', ['getShops']),
    inputColor (color) {
      this.newArticle.color = color
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
      if (this.newArticle.composite) {
        this.variantData = []
      } else {
        this.updated = false
      }
    },
    changeInventory () {
      this.track_inventory = this.newArticle.track_inventory
    },
    updateComposite (composite) {
      this.composite = composite
      let cost = 0.00
      let price = 0.00
      this.composite.forEach((comp) => {
        cost += comp.cant * comp.cost
        price += comp.cant * comp.price
      })
      this.newArticle.cost = cost
      this.newArticle.price = price
    },
    selectArticle (item) {
      console.log(item)
      if (this.composite.filter(art => art.composite_id === item.composite_id).length === 0) {
        this.composite.push({
          name: item.name,
          price: item.price,
          cost: item.cost,
          cant: '1',
          composite_id: item.composite_id
        })
        let totalCost = 0.00
        this.composite.forEach((comp) => {
          totalCost += comp.cant * comp.cost
        })
        this.newArticle.cost = totalCost
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
      this.newArticle.variants = variants
      this.shopData = []
      this.shops.forEach((shop) => {
        if (variants.length > 0) {
          this.variantData.forEach((v) => {
            this.shopData.push({
              shop_id: shop.id,
              shop_name: shop.name,
              checked: true,
              name: v.name,
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
            price: '0.00',
            stock: '0',
            under_inventory: '0'
          })
        }
      })
      this.updated = true
    },
    createNewArticle () {
      let valid = true
      if (!this.validateRef() || !this.validateBarCode()) {
        valid = false
      } else {
        if (this.newArticle.composite) {
          if (this.composite.length === 0) {
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
            this.newArticle.composites = this.composite
          }
        } else {
          this.newArticle.variantsValues = this.variantData
        }
      }
      if (valid) { this.validCreate() }
    },
    validateBarCode () {
      let valid = true
      this.variantData.forEach((value) => {
        if (valid) {
          valid = !this.articles.filter(art => art.barCode === value.barCode).length > 0
          if (!valid) {
            this.$Swal.fire({
              title: this.$vuetify.lang.t('$vuetify.titles.new', [
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
      })
      if (this.variantData.filter(vd => vd.barCode === this.newArticle.barCode).length > 0 ||
                this.articles.filter(art => art.barCode === this.newArticle.barCode).length > 0) {
        valid = false
        this.$Swal.fire({
          title: this.$vuetify.lang.t('$vuetify.titles.new', [
            this.$vuetify.lang.t('$vuetify.menu.articles')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.warning_barCode', [this.newArticle.barCode]
          ),
          icon: 'warning',
          showCancelButton: false,
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
      }
      return valid
    },
    validateRef () {
      let valid = true
      this.variantData.forEach((value) => {
        if (valid) {
          valid = !this.articles.filter(art => art.ref === value.ref).length > 0
          if (!valid) {
            this.$Swal.fire({
              title: this.$vuetify.lang.t('$vuetify.titles.new', [
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
      })
      if (this.variantData.filter(vd => vd.ref === this.newArticle.ref).length > 0 ||
                this.articles.filter(art => art.ref === this.newArticle.ref).length > 0) {
        valid = false
        this.$Swal.fire({
          title: this.$vuetify.lang.t('$vuetify.titles.new', [
            this.$vuetify.lang.t('$vuetify.menu.articles')
          ]),
          text: this.$vuetify.lang.t(
            '$vuetify.messages.warning_ref', [this.newArticle.ref]
          ),
          icon: 'warning',
          showCancelButton: false,
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.accept'
          ),
          confirmButtonColor: 'red'
        })
      }
      return valid
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
        await this.createArticle(this.newArticle)
        await this.$router.push({ name: 'product_list' })
      }
    }
  }
}
</script>

<style scoped>

</style>
