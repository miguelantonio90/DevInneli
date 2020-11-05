<template>
  <div class="page-add-product">
    <app-loading v-show="loadingData" />
    <v-container
      v-if="!loadingData"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t('$vuetify.titles.newF', [
              $vuetify.lang.t('$vuetify.supply.name'),
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
                      <v-autocomplete
                        chips
                        rounded
                        solo
                        :items="localArticles"
                        item-text="name"
                        return-object
                        @input="selectArticle"
                      />
                    </v-col>
                    <v-col
                      cols="12"
                      md="12"
                    >
                      <v-data-table
                        :headers="getTableColumns"
                        :items="newInventory.articles"
                      >
                        <template v-slot:item.cost="{ item }">
                          <v-edit-dialog
                            :return-value.sync="item.cost"
                            large
                            persistent
                            :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                            :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
                            @save="calcTotal(item)"
                          >
                            <div>{{ `${user.company.currency + ' ' + item.cost }` }}</div>
                            <template v-slot:input>
                              <div class="mt-4 title">
                                {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                              </div>
                            </template>
                            <template v-slot:input>
                              <v-text-field-money
                                v-model="item.cost"
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
                        <template v-slot:item.price="{ item }">
                          <div>
                            {{ `${user.company.currency + ' ' + item.price }` }}
                          </div>
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
                              <v-text-field
                                v-model="item.cant"
                                label="Edit"
                                single-line
                                counter
                                autofocus
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
                      v-show="newInventory.articles.length > 0 "
                      cols="12"
                      md="6"
                    />
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-col
                v-show="newInventory.articles.length > 0"
                cols="12"
                md="6"
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
                    <detail-supplier @updateData="update = true" />
                  </v-expansion-panel-content>
                </v-expansion-panel>
              </v-col>
              <v-col
                v-show="newInventory.articles.length > 0"
                cols="12"
                md="6"
              >
                <v-expansion-panel
                  v-show="newInventory.articles.length > 0"
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
                    <resume-supply
                      :update="update"
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
            @click="$router.push({name:'supply_list'})"
          >
            <v-icon>mdi-close</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
            :disabled="!formValid"
            :loading="isActionInProgress"
            @click="createNewInventory"
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
import DetailSupplier from './DatailSupplier'
import ResumeSupply from './ResumeSupply'
import AppLoading from '../../components/core/AppLoading'

export default {
  name: 'NewInventory',
  components: { AppLoading, ResumeSupply, DetailSupplier },
  data () {
    return {
      loadingData: false,
      editedIndex: -1,
      localArticles: [],
      update: false,
      panel: [0, 1, 2],
      showInfoAdd: false
    }
  },
  computed: {
    ...mapState('inventory', ['newInventory', 'isActionInProgress']),
    ...mapState('article', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'articles',
      'isTableLoading'
    ]),
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
          text: this.$vuetify.lang.t('$vuetify.articles.inventory'),
          value: 'inventory',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.price'),
          value: 'price',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.cost'),
          value: 'cost',
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
          text: this.$vuetify.lang.t('$vuetify.variants.total_cost'),
          value: 'totalCost',
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
  created () {
    this.loadingData = true
    this.getArticles().then(() => {
      this.articles.forEach((value) => {
        if (!value.parent_id) {
          let inventory = 0
          if (value.variant_values.length > 0) {
            value.variant_values.forEach((v) => {
              if (v.articles_shops.length > 0) {
                v.articles_shops.forEach((k) => {
                  inventory += k.stock ? parseFloat(k.stock) : 0
                })
              }
              this.localArticles.push({
                ref: value.ref,
                name: value.name + '(' + v.name + ')',
                price: v.price ? v.price : 0,
                cost: v.cost ? v.cost : 0,
                inventory: inventory || 0,
                cant: 1,
                totalCost: v.cost,
                totalPrice: v.price,
                article_id: v.id
              })
            })
          } else {
            if (value.articles_shops.length > 0) {
              value.articles_shops.forEach((k) => {
                inventory += k.stock ? parseFloat(k.stock) : 0
              })
            }
            this.localArticles.push({
              ref: value.ref,
              name: value.name,
              price: value.price ? value.price : 0,
              cost: value.cost ? value.cost : 0,
              inventory: inventory || 0,
              cant: 1,
              totalCost: value.cost,
              totalPrice: value.price,
              article_id: value.id
            })
          }
        }
      })
    })
    this.loadingData = false
  },
  methods: {
    ...mapActions('inventory', ['createInventory']),
    ...mapActions('article', ['getArticles']),
    selectArticle (item) {
      if (item) {
        if (this.newInventory.articles.filter(art => art.article_id === item.article_id).length === 0) {
          this.newInventory.articles.push(item)
        } else {
          this.showInfoAdd = true
        }
      }
    },
    deleteItem (item) {
      this.newInventory.articles.splice(this.newInventory.articles.indexOf(item), 1)
      this.update = true
    },
    calcTotal: function (item) {
      this.editedIndex = this.newInventory.articles.indexOf(item)
      this.newInventory.articles[this.editedIndex].totalPrice = parseFloat(this.newInventory.articles[this.editedIndex].price * this.newInventory.articles[this.editedIndex].cant).toFixed(2)
      this.newInventory.articles[this.editedIndex].totalCost = parseFloat(this.newInventory.articles[this.editedIndex].cost * this.newInventory.articles[this.editedIndex].cant).toFixed(2)
      this.update = true
    },
    closeInfoAdd () {
      this.showInfoAdd = false
    },
    async createNewInventory () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createInventory(this.newInventory)
        await this.$router.push({ name: 'product_list' })
      }
    }
  }

}
</script>

<style scoped>

</style>
