<template>
  <div class="page-add-inventorys">
    <app-loading v-show="loadingData" />
    <v-dialog
      v-model="showDiscount"
      max-width="400"
      persistent
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{ $vuetify.lang.t('$vuetify.access.access.manager_discount') }}</span>
        </v-card-title>
        <v-card-text>
          <v-autocomplete
            v-model="articleSelected.discount"
            chips
            multiple
            :items="localDiscounts"
            item-text="name"
            return-object
          >
            <template v-slot:append-outer>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    v-bind="attrs"
                    v-on="on"
                    @click="$store.dispatch('discount/toogleNewModal',true)"
                  >
                    mdi-plus
                  </v-icon>
                </template>
                <span>{{
                  $vuetify.lang.t('$vuetify.titles.newAction')
                }}</span>
              </v-tooltip>
            </template>
          </v-autocomplete>
        </v-card-text>
        <v-card-actions>
          <v-spacer />
          <v-btn
            class="mb-2"
            @click="closeDiscount"
          >
            <v-icon>mdi-close</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
          </v-btn>
          <v-btn
            class="mb-2"
            color="primary"
            @click="saveDiscount()"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.accept') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-container
      v-if="!loadingData"
    >
      <v-card>
        <v-card-title>
          <span class="headline">{{
            $vuetify.lang.t(managerInventory? '$vuetify.titles.edit' : '$vuetify.titles.newF', [
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
                      md="4"
                    >
                      <v-select
                        v-model="inventory.shop"
                        chips
                        rounded
                        disabled
                        solo
                        clearable
                        :items="shops"
                        :label="$vuetify.lang.t('$vuetify.menu.shop')"
                        item-text="name"
                        :loading="isShopLoading"
                        return-object
                        required
                        :rules="formRule.country"
                      />
                    </v-col>
                    <v-col
                      class="py-0"
                      cols="12"
                      md="5"
                    >
                      <v-autocomplete
                        ref="selectArticle"
                        :disabled="!inventory.shop"
                        :hint="!inventory.shop ? $vuetify.lang.t('$vuetify.sale.selectShop') : localArticles.length > 0 ? $vuetify.lang.t('$vuetify.sale.selectArticle') : $vuetify.lang.t('$vuetify.sale.emptyArticle')"
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
                                {{ `${user.company.currency + ' ' + data.item.cost}` }}
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
                      <app-data-table
                        :view-show-filter="false"
                        :view-edit-button="false"
                        :view-new-button="false"
                        :view-discount-button="true"
                        :headers="getTableColumns"
                        :items="inventory.articles"
                        csv-filename="ProductBuys"
                        :sort-by="['name']"
                        :sort-desc="[false, true]"
                        multi-sort
                        :is-loading="isTableLoading"
                        @delete-row="deleteItem($event)"
                        @manager-discount-row="showDiscountArticle($event)"
                      >
                        <template v-slot:[`item.name`]="{ item }">
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
                        <template v-slot:[`item.cant`]="{ item }">
                          <v-edit-dialog
                            :return-value.sync="item.cant"
                            large
                            persistent
                            :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                            :save-text="$vuetify.lang.t('$vuetify.actions.save')"
                            @save="calcTotalArticle(item)"
                          >
                            <div>{{ item.cant }}</div>
                            <template v-slot:input>
                              <div class="mt-4 title">
                                {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                              </div>
                              <v-text-field-money
                                v-model="item.cant"
                                :label="$vuetify.lang.t('$vuetify.actions.save') "
                                :properties="{
                                  clearable: true,
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
                        <template v-slot:[`item.totalCost`]="{ item }">
                          <template v-if="item.taxes.length > 0 || item.discount.length > 0">
                            <v-tooltip bottom>
                              <template v-slot:activator="{ on, attrs }">
                                <b><v-icon
                                  class="mr-2"
                                  small
                                  v-bind="attrs"
                                  v-on="on"
                                >
                                  mdi-information
                                </v-icon></b>
                              </template>
                              <template>
                                <detail-article-cost
                                  :article="item"
                                  :currency="user.company.currency"
                                />
                              </template>
                              <span
                                v-if="item.totalRefund > 0"
                              >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + item.totalRefund}` }}</span>
                            </v-tooltip>
                          </template>
                          {{ `${user.company.currency + ' ' + parseFloat(item.totalCost).toFixed(2)}` }}
                        </template>
                      </app-data-table>
                    </v-col>
                    <v-col
                      v-show="inventory.articles.length > 0 "
                      cols="12"
                      md="6"
                    />
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-col
                v-show="inventory.articles.length > 0"
                cols="12"
                md="12"
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
                    <extra-data
                      :edit="managerInventory"
                      :sale="inventory"
                      :total-cost="parseFloat(totalCost).toFixed(2)"
                      :total-tax="parseFloat(totalTax).toFixed(2)"
                      :total-discount="parseFloat(totalDisc).toFixed(2)"
                      :sub-total="parseFloat(subTotal).toFixed(2)"
                      @updateData="calcTotalSale"
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
            :disabled="!formValid || isActionInProgress || getDifference !== 0"
            :loading="isActionInProgress"
            @click="inventoryHandler()"
          >
            <v-icon>mdi-check</v-icon>
            {{ $vuetify.lang.t('$vuetify.sale.state.accepted') }}
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
      <new-discount v-if="this.$store.state.discount.showNewModal" />
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import ExtraData from '../buy/ExtraData'
import NewDiscount from '../discount/NewDiscount'
import utils from '../../util'
import DetailArticleCost from '../buy/DetailArticleCost'

export default {
	name: 'ManagerBuy',
	components: {
		DetailArticleCost,
		NewDiscount,
		ExtraData
	},
	data () {
		return {
			showDiscount: false,
			inventory: {},
			articleSelected: {},
			loadingData: false,
			editedIndex: -1,
			localArticles: [],
			localDiscounts: [],
			update: false,
			panel: [0, 1, 2],

			totalTax: 0,
			totalDisc: 0,
			subTotal: 0,
			totalCost: 0,
			formValid: false,
			showInfoAdd: false,
			vBindOption: {
				itemKey: 'name',
				singleExpand: false,
				showExpand: true
			},
			formRule: this.$rules
		}
	},
	computed: {
		...mapState('inventory', ['managerInventory', 'newInventory', 'editInventory', 'isActionInProgress']),
		...mapState('article', [
			'showNewModal',
			'showEditModal',
			'showShowModal',
			'articles',
			'isTableLoading'
		]),
		...mapState('shop', ['shops', 'isShopLoading']),
		...mapState('discount', ['discounts']),
		...mapState('inventory', ['inventories']),
		...mapState('inventory', ['inventories']),
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
					with: '15%',
					value: 'name',
					select_filter: true
				},
				{
					text: this.$vuetify.lang.t('$vuetify.articles.inventory'),
					value: 'inventory',
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
		},
		getDifference () {
			let totalCalcP = 0.00
			this.inventory.pays.forEach(v => {
				totalCalcP += parseFloat(v.cant)
			})
			return parseFloat(this.totalCost) - parseFloat(totalCalcP)
		}
	},
	watch: {
		discounts: function () {
			this.getLocalDiscounts()
		},
		'inventory.no_facture': function () {
			if (this.inventories.filter(art => art.no_facture === this.inventory.no_facture).length > 0 || this.inventories.filter(art => art.no_facture === this.inventory.no_facture).length > 0) {
				this.inventory.no_facture = this.generateNF()
			}
		},
		'inventory.taxes' () {
			this.calcTotalSale()
		},
		'inventory.articles' () {
			this.calcTotalSale()
		},
		'inventory.discounts' () {
			this.calcTotalSale()
		}
	},
	async created () {
		this.loadingData = true
		this.inventory = !this.managerInventory ? this.newInventory : this.editInventory
		console.log(this.inventory)
		if (this.managerInventory) {
			this.calcTotalSale()
		}
		await this.getArticles()
		await this.getShops().then((s) => {
			if (!this.managerInventory) {
				this.inventory.shop = this.shops[0]
			}
		})
		await this.getDiscounts().then(() => {
			this.getLocalDiscounts()
		})
		this.loadingData = false
		await this.updateDataArticle()
		this.inventory.no_facture = this.generateNF()
		this.loadingData = false
	},
	methods: {
		...mapActions('inventory', ['getInventories']),
		...mapActions('article', ['getArticles']),
		...mapActions('shop', ['getShops']),
		...mapActions('inventory', ['getInventories', 'createInventory', 'updateInventory']),
		...mapActions('discount', ['getDiscounts']),
		generateNF () {
			const seqer = utils.serialMaker()
			seqer.set_prefix('C' + new Date().getFullYear() + '-')
			seqer.set_seq(1000000)
			return seqer.gensym()
		},
		async updateDataArticle () {
			this.localArticles = []
			if (this.inventory.shop) {
				await this.articles.forEach((value) => {
					if (value.variant_values.length > 0) {
						value.variant_values.forEach((v) => {
							const artS = v.articles_shops.filter(artS => artS.shop_id === this.inventory.shop.id)
							if (artS.length > 0) {
								this.validAddToLocalArticle(v, value, artS)
							}
						})
					} else {
						const artS = value.articles_shops.filter(artS => artS.shop_id === this.inventory.shop.id)
						if (artS.length > 0) {
							this.validAddToLocalArticle(value, value, artS)
						}
					}
				})
			}
		},
		validAddToLocalArticle (v, value, artS) {
			let inventory = 0
			if (!value.track_inventory) {
				this.addToLocalArticle(v, value, 0, artS[0])
			} else {
				if (artS.length > 0) {
					inventory = artS[0].stock
				}
				this.addToLocalArticle(v, value, inventory, artS[0])
			}
		},
		addToLocalArticle (v, value, inventory, artS) {
			this.localArticles.push({
				ref: value.ref,
				name: value.name + '(' + v.name + ')',
				category: !value.category ? '' : value.category.name,
				path: value.path,
				images: value.images,
				taxes: v.tax,
				discount: [],
				color: value.color,
				price: artS.price,
				cost: v.cost ? v.cost : 0,
				inventory: inventory || 0,
				cant: 1,
				totalCant: (inventory || 0) + 1,
				totalCost: v.cost,
				totalPrice: v.price,
				article_id: v.id
			})
		},
		getLocalDiscounts () {
			this.discounts.forEach((v) => {
				this.localDiscounts.push({
					id: v.id,
					name: v.percent ? v.name + '(' + v.value + '%)' : v.name + '(' + this.user.company.currency + v.value + ')',
					value: v.value,
					percent: v.percent
				})
			})
		},
		selectArticle (item) {
			if (item) {
				if (this.inventory.articles.filter(art => art.article_id === item.article_id).length === 0) {
					this.inventory.articles.push(item)
					this.calcTotalSale()
				} else {
					this.showInfoAdd = true
				}
			}
		},
		deleteItem (item) {
			this.inventory.articles.splice(this.inventory.articles.indexOf(item), 1)
			this.calcTotalSale()
		},
		closeInfoAdd () {
			this.showInfoAdd = false
		},
		async inventoryHandler () {
			if (this.getDifference !== 0) {
				this.loading = false
				this.shopMessageError(this.$vuetify.lang.t(
					'$vuetify.messages.warning_difference_price', [(this.getDifference + ' ' + this.user.company.currency).toString()]
				))
			} else {
				if (this.inventory.articles.length > 0) {
					if (this.$refs.form.validate()) {
						this.loading = true
						if (!this.managerInventory) {
							await this.createInventory(this.inventory).then(() => {
								this.$router.push({ name: 'supply_product' })
							})
						} else {
							await this.updateInventory(this.inventory).then(() => {
								this.$router.push({ name: 'supply_product' })
							})
						}
					}
				} else {
					this.loading = false
					this.shopMessageError(this.$vuetify.lang.t(
						'$vuetify.messages.warning_cant_article'
					))
				}
			}
		},
		shopMessageError (message) {
			this.$Swal.fire({
				title: this.$vuetify.lang.t('$vuetify.titles.newF', [
					this.$vuetify.lang.t('$vuetify.supply.name')
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
			this.inventory.articles = []
			this.$router.push({ name: 'supply_product' })
		},
		calcTotalArticle: function (item) {
			this.editedIndex = this.inventory.articles.indexOf(item)
			this.inventory.articles[this.editedIndex].totalCost = parseFloat(this.inventory.articles[this.editedIndex].cost *
              this.inventory.articles[this.editedIndex].cant).toFixed(2)
			this.inventory.articles[this.editedIndex].totalCant = parseFloat(parseFloat(this.inventory.articles[this.editedIndex].inventory) -
              parseFloat(this.inventory.articles[this.editedIndex].cant) || 0).toFixed(2)
			item.totalCost = item.cant * item.cost + this.articleTotalCost(item) - this.articleTotalDiscount(item)
			this.calcTotalSale()
		},
		articleTotalCost (item) {
			let tax = 0
			if (item.taxes.length > 0) {
				item.taxes.forEach((v) => {
					tax += v.percent ? item.cant * item.cost * v.value / 100 : v.value
				})
			}
			return tax
		},
		articleTotalDiscount (item) {
			let disc = 0
			if (item.discount.length > 0) {
				item.discount.forEach((v) => {
					disc += v.percent ? item.cant * item.cost * v.value / 100 : v.value
				})
			}
			return disc
		},
		calcTotalSale () {
			this.totalTax = 0
			this.totalDisc = 0
			this.totalCost = 0
			this.subTotal = 0
			this.inventory.articles.forEach((v) => {
				this.subTotal = parseFloat(v.cost) * parseFloat(v.cant) + this.subTotal
			})
			this.inventory.taxes.forEach((v) => {
				this.totalTax += v.percent === 'true' ? this.subTotal * v.value / 100 : v.value
			})
			this.inventory.discounts.forEach((v) => {
				this.totalDisc += v.percent === 'true' ? this.subTotal * v.value / 100 : v.value
			})
			this.totalCost = (this.subTotal + parseFloat(this.totalTax) - parseFloat(this.totalDisc)).toFixed(2)
			this.totalCost = parseFloat(this.totalCost).toFixed(2)
		},
		showDiscountArticle ($event) {
			this.showDiscount = true
			this.articleSelected = $event
		},
		closeDiscount () {
			this.showDiscount = false
		},
		saveDiscount () {
			this.calcTotalArticle(this.articleSelected)
			this.showDiscount = false
		}
	}
}
</script>

  <style scoped />
