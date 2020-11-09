<template>
  <div class="page-add-sales">
    <app-loading v-show="loadingData" />
    <v-container
      v-if="!loadingData"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.titles.newF', [
              $vuetify.lang.t('$vuetify.sale.sale'),
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
                      class="py-0"
                      cols="12"
                      md="5"
                    >
                      <v-select
                        v-model="newSale.shop"
                        chips
                        rounded
                        solo
                        clearable
                        :items="shops"
                        :label="$vuetify.lang.t('$vuetify.menu.shop')"
                        item-text="name"
                        :loading="isShopLoading"
                        :disabled="!!isShopLoading"
                        return-object
                        required
                        :rules="formRule.country"
                        @input="updateDataArticle"
                        @change="updateDataArticle"
                      />
                    </v-col>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="5"
                    >
                      <v-autocomplete
                        ref="selectArticle"
                        :disabled="!newSale.shop"
                        :hint="!newSale.shop ? $vuetify.lang.t('$vuetify.sale.selectShop') : localArticles.length > 0 ? $vuetify.lang.t('$vuetify.sale.selectArticle') : $vuetify.lang.t('$vuetify.sale.emptyArticle')"
                        persistent-hint
                        chips
                        :label="$vuetify.lang.t('$vuetify.menu.articles')"
                        :items="localArticles"
                        item-text="name"
                        return-object
                        @input="selectArticle"
                      >
                        <template v-slot:selection="data">
                          <v-chip
                            v-bind="data.attrs"
                            :input-value="data.selected"
                            @click="data.select"
                          >
                            <v-avatar
                              v-if="data.item.color && data.item.images.length === 0"
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
                        <template v-slot:item="data">
                          <template>
                            <v-list-item-avatar>
                              <v-avatar
                                v-if="data.item.color && data.item.images.length === 0"
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
                            </v-list-item-avatar>
                            <v-list-item-content>
                              <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                              <v-list-item-subtitle>
                                {{ `${user.company.currency + ' ' + data.item.price }` }}
                              </v-list-item-subtitle>
                            </v-list-item-content>
                          </template>
                        </template>
                      </v-autocomplete>
                    </v-col>
                    <v-col
                      cols="12"
                      md="12"
                    >
                      <v-data-table
                        :headers="getTableColumns"
                        :items="newSale.articles"
                      >
                        <template v-slot:item.name="{ item }">
                          <v-chip
                            :key="JSON.stringify(item)"
                          >
                            <v-avatar
                              v-if="item.color && item.images.length === 0"
                              class="white--text"
                              :color="item.color"
                              left
                              v-text="item.name.slice(0, 1).toUpperCase()"
                            />
                            <v-avatar
                              v-else
                              left
                            >
                              <v-img :src="item.path" />
                            </v-avatar>
                            {{ item.name }}
                          </v-chip>
                        </template>
                        <template v-slot:item.price="{ item }">
                          <v-edit-dialog
                            :return-value.sync="item.price"
                            large
                            persistent
                            :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                            :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
                            @save="calcTotal(item)"
                          >
                            <div>{{ `${user.company.currency + ' ' + item.price }` }}</div>
                            <template v-slot:input>
                              <div class="mt-4 title">
                                {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                              </div>
                            </template>
                            <template v-slot:input>
                              <v-text-field-money
                                v-model="item.price"
                                :label="$vuetify.lang.t('$vuetify.actions.edit')"
                                required
                                :properties="{
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
                        <template v-slot:item.cant="{ item }">
                          <v-edit-dialog
                            :return-value.sync="item.cant"
                            large
                            persistent
                            :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                            :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
                            @save="calcTotal(item)"
                          >
                            <div>{{ item.cant }}</div>
                            <template v-slot:input>
                              <div class="mt-4 title">
                                {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                              </div>
                            </template>
                            <template v-slot:input>
                              <v-text-field-integer
                                v-model="item.cant"
                                label="Edit"
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
                        <template v-slot:item.totalCost="{ item }">
                          {{ `${user.company.currency + ' ' + item.totalCost }` }}
                        </template>
                        <template v-slot:item.totalPrice="{ item }">
                          {{ `${user.company.currency + ' ' + item.totalPrice }` }}
                        </template>
                        <template v-slot:item.actions="{ item }">
                          <v-icon
                            small
                            @click="deleteItem(item)"
                          >
                            mdi-delete
                          </v-icon>
                        </template>
                      </v-data-table>
                    </v-col>
                    <v-col
                      v-show="newSale.articles.length > 0 "
                      cols="12"
                      md="6"
                    />
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-col
                v-show="newSale.articles.length > 0"
                cols="12"
                md="8"
              >
                <v-expansion-panel>
                  <v-expansion-panel-header>
                    <div>
                      <v-icon>mdi-database-plus</v-icon>
                      <span style="text-transform: uppercase;font-weight: bold">
                        {{ $vuetify.lang.t('$vuetify.pay.extra_data') }}
                      </span>
                    </div>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <extra-data @updateData="update = true" />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-col>
              <v-col
                v-show="newSale.articles.length > 0"
                cols="12"
                md="4"
              >
                <v-expansion-panel
                  v-show="newSale.articles.length > 0"
                  cols="12"
                  md="6"
                >
                  <v-expansion-panel-header>
                    <div>
                      <v-icon>mdi-database-plus</v-icon>
                      <span style="text-transform: uppercase;font-weight: bold">
                        {{ $vuetify.lang.t('$vuetify.menu.resume') }}
                      </span>
                    </div>
                  </v-expansion-panel-header>
                  <v-expansion-panel-content>
                    <facture
                      :edit="false"
                      :update="update"
                      :currency="user.company.currency || ''"
                      @updateData="update = false"
                    />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-col>
            </v-expansion-panels>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            class="mb-2"
            :disabled="isActionInProgress"
            @click="handleClose"
          >
            <v-icon>mdi-close</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
            :disabled="!formValid || isActionInProgress"
            :loading="isActionInProgress"
            @click="createNewSale"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.save') }}
          </v-btn>
        </v-card-actions>
      </v-card>
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
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import ExtraData from './ExtraData'
import AppLoading from '../../components/core/AppLoading'
import Facture from './Facture'
export default {
  name: 'NewSale',
  components: { AppLoading, Facture, ExtraData },
  data () {
    return {
      loadingData: false,
      editedIndex: -1,
      localArticles: [],
      update: false,
      panel: [0, 1, 2],
      formValid: false,
      showInfoAdd: false,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('sale', ['newSale', 'isActionInProgress']),
    ...mapState('article', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'articles',
      'isTableLoading'
    ]),
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapGetters('auth', ['user']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.articles.ref'),
          value: 'ref',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.price'),
          value: 'price',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cant'),
          value: 'cant',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.total_price'),
          value: 'totalPrice',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.actions.actions'),
          value: 'actions',
          sortable: false
        }
      ]
    }
  },
  async created () {
    this.loadingData = true
    await this.getArticles()
    await this.getShops()
    this.loadingData = false
  },
  methods: {
    ...mapActions('sale', ['createSale']),
    ...mapActions('article', ['getArticles']),
    ...mapActions('shop', ['getShops']),
    async updateDataArticle () {
      this.localArticles = []
      this.newSale.articles = []
      if (this.newSale.shop) {
        await this.articles.forEach((value) => {
          if (value.track_inventory) {
            if (!value.parent_id) {
              let inventory = 0
              if (value.variant_values.length > 0) {
                value.variant_values.forEach((v) => {
                  inventory = 0
                  if (v.articles_shops.length > 0) {
                    const artS = v.articles_shops.filter(artS => artS.shop_id === this.newSale.shop.id)
                    inventory = artS.length > 0 ? artS[0].stock : 0
                  }
                  if (inventory > 0) {
                    this.localArticles.push({
                      ref: value.ref,
                      name: value.name + '(' + v.name + ')',
                      category: value.category.name,
                      path: value.path,
                      images: value.images,
                      color: value.color,
                      price: v.price ? v.price : 0,
                      cost: v.cost ? v.cost : 0,
                      inventory: inventory || 0,
                      cant: 1,
                      totalCant: (inventory || 0) + 1,
                      totalCost: v.cost,
                      totalPrice: v.price,
                      article_id: v.id
                    })
                  }
                })
              } else {
                if (value.articles_shops.length > 0) {
                  const artS = value.articles_shops.filter(artS => artS.shop_id === this.newSale.shop.id)
                  inventory = artS.length > 0 ? artS[0].stock : 0
                }
                if (inventory > 0) {
                  this.localArticles.push({
                    ref: value.ref,
                    name: value.name,
                    category: value.category.name,
                    path: value.path,
                    images: value.images,
                    color: value.color,
                    price: value.price ? value.price : 0,
                    cost: value.cost ? value.cost : 0,
                    inventory: inventory || 0,
                    cant: 1,
                    totalCant: (inventory || 0) + 1,
                    totalCost: value.cost,
                    totalPrice: value.price,
                    article_id: value.id
                  })
                }
              }
            }
          }
        })
      }
    },
    selectArticle (item) {
      if (item) {
        if (this.newSale.articles.filter(art => art.article_id === item.article_id).length === 0) {
          this.newSale.articles.push(item)
        } else {
          this.showInfoAdd = true
        }
      }
    },
    deleteItem (item) {
      this.newSale.articles.splice(this.newSale.articles.indexOf(item), 1)
      this.update = true
    },
    calcTotal: function (item) {
      this.editedIndex = this.newSale.articles.indexOf(item)
      this.newSale.articles[this.editedIndex].totalPrice = parseFloat(this.newSale.articles[this.editedIndex].price * this.newSale.articles[this.editedIndex].cant).toFixed(2)
      this.newSale.articles[this.editedIndex].totalCost = parseFloat(this.newSale.articles[this.editedIndex].cost * this.newSale.articles[this.editedIndex].cant).toFixed(2)
      const total = parseFloat(this.newSale.articles[this.editedIndex].inventory) - parseFloat(this.newSale.articles[this.editedIndex].cant) || 0
      this.newSale.articles[this.editedIndex].totalCant = parseFloat(total).toFixed(2)
      this.update = true
    },
    closeInfoAdd () {
      this.showInfoAdd = false
    },
    async createNewSale () {
      if (this.newSale.articles.length > 0) {
        if (this.$refs.form.validate()) {
          this.loading = true
          await this.createSale(this.newSale)
          await this.$router.push({ name: 'vending' })
        }
      } else {
        this.shopMessageError(this.$vuetify.lang.t(
          '$vuetify.messages.warning_cant_article'
        ))
      }
    },
    shopMessageError (message) {
      this.$Swal.fire({
        title: this.$vuetify.lang.t('$vuetify.titles.newF', [
          this.$vuetify.lang.t('$vuetify.menu.vending')
        ]),
        text: message,
        icon: 'warning',
        showCancelButton: false,
        confirmButtonText: this.$vuetify.lang.t(
          '$vuetify.actions.accept'
        ),
        confirmButtonColor: 'red'
      })
    },
    handleClose () {
      this.localArticles = []
      this.newSale.articles = []
      this.$router.push({ name: 'vending' })
    }
  }
}
</script>

<style scoped>

</style>
