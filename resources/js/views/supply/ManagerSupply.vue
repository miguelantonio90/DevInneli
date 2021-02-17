<template>
  <div class="page-add-supplys">
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
            $vuetify.lang.t(managerSupply? '$vuetify.titles.edit' : '$vuetify.titles.new',
                            [$vuetify.lang.t('$vuetify.menu.supply_productS')])
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
                        v-model="supply.shop"
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
                        :disabled="!supply.shop"
                        :hint="!supply.shop ? $vuetify.lang.t('$vuetify.sale.selectShop') : localArticles.length > 0 ? $vuetify.lang.t('$vuetify.sale.selectArticle') : $vuetify.lang.t('$vuetify.sale.emptyArticle')"
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
                        :items="supply.articles"
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
                              <!--                              <v-text-field-money
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
                              />-->
                              <v-text-field-integer
                                v-model="item.cant"
                                :label="$vuetify.lang.t('$vuetify.actions.save') "
                                :properties="{
                                  readonly: false,
                                  disabled: false,
                                  outlined: false,
                                  clearable: true,
                                  placeholder: '',
                                }"
                                :options="{
                                  inputMask: '#########',
                                  outputMask: '#########',
                                  empty: 0,
                                  applyAfter: false,
                                }"
                                :focus="focus"
                                @focus="focus = false"
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
                      v-show="supply.articles.length > 0 "
                      cols="12"
                      md="6"
                    />
                  </v-row>
                </v-expansion-panel-content>
              </v-expansion-panel>
              <v-col
                v-show="supply.articles.length > 0"
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
                      :edit="managerSupply"
                      :sale="supply"
                      :total-cost="parseFloat(totalCost).toFixed(2)"
                      :total-tax="parseFloat(totalTax).toFixed(2)"
                      :total-discount="parseFloat(totalDisc).toFixed(2)"
                      :sub-total="parseFloat(subTotal).toFixed(2)"
                      @updateData="calcTotalSupply"
                      @generateNF="generateNF"
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
            @click="supplyHandler()"
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
import ExtraData from '../supply/ExtraData'
import NewDiscount from '../discount/NewDiscount'
import utils from '../../util'
import DetailArticleCost from '../buy/DetailArticleCost'

export default {
	name: 'ManagerSupply',
	components: {
		DetailArticleCost,
		NewDiscount,
		ExtraData
	},
	data () {
		return {
			focus: false,
			showDiscount: false,
			supply: {},
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
		...mapState('supply', ['managerSupply', 'newSupply', 'editSupply', 'isActionInProgress', 'supplies']),
		...mapState('article', [
			'showNewModal',
			'showEditModal',
			'showShowModal',
			'articles',
			'isTableLoading'
		]),
		...mapState('shop', ['shops', 'isShopLoading']),
		...mapState('discount', ['discounts']),
		...mapGetters('auth', ['user']),
		...mapGetters('supply', ['getNumberFacture']),
		factureNumber: {
			get () {
				return this.getNumberFacture
			},
			set (newNumber) {
				return newNumber
			}
		},
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
			this.supply.pays.forEach(v => {
				totalCalcP += parseFloat(v.cant)
			})
			return parseFloat(this.totalCost) - parseFloat(totalCalcP)
		}
	},
	watch: {
		discounts: function () {
			this.getLocalDiscounts()
		},
		'supply.taxes' () {
			this.calcTotalSupply()
		},
		'supply.articles' () {
			this.calcTotalSupply()
		},
		'supply.discounts' () {
			this.calcTotalSupply()
		}
	},
	async created () {
		this.loadingData = true
		this.supply = !this.managerSupply ? this.newSupply : this.editSupply
		if (this.managerSupply) {
			this.calcTotalSupply()
		}
		await this.getArticles()
		await this.getShops().then((s) => {
			if (!this.managerSupply) {
				this.supply.shop = this.shops[0]
			}
		})
		await this.getDiscounts().then(() => {
			this.getLocalDiscounts()
		})
		await this.updateDataArticle()
		if (!this.managerSupply) {
			await this.fetchSupplyNumber().then(() => {
				this.supply.no_facture = this.generateNF()
			})
		}
		this.loadingData = false
	},
	methods: {
		...mapActions('supply', ['getSupplies']),
		...mapActions('article', ['getArticles']),
		...mapActions('shop', ['getShops']),
		...mapActions('supply', ['getSupplies', 'createSupply', 'updateSupply', 'fetchSupplyNumber']),
		...mapActions('discount', ['getDiscounts']),
		generateNF () {
			const seqer = utils.serialMaker()
			seqer.set_prefix('C' + new Date().getFullYear() + '-')
			seqer.set_seq(this.factureNumber)
			return seqer.gensym()
		},
		async updateDataArticle () {
			this.localArticles = []
			if (this.supply.shop) {
				await this.articles.forEach((value) => {
					if (value.variant_values.length > 0) {
						value.variant_values.forEach((v) => {
							const artS = v.articles_shops.filter(artS => artS.shop_id === this.supply.shop.id)
							if (artS.length > 0) {
								this.validAddToLocalArticle(v, value, artS)
							}
						})
					} else {
						const artS = value.articles_shops.filter(artS => artS.shop_id === this.supply.shop.id)
						if (artS.length > 0) {
							this.validAddToLocalArticle(value, value, artS)
						}
					}
				})
			}
		},
		validAddToLocalArticle (v, value, artS) {
			let supply = 0
			if (!value.track_inventory) {
				this.addToLocalArticle(v, value, 0, artS[0])
			} else {
				if (artS.length > 0) {
					supply = artS[0].stock
				}
				this.addToLocalArticle(v, value, supply, artS[0])
			}
		},
		addToLocalArticle (v, value, supply, artS) {
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
				inventory: supply || 0,
				cant: 1,
				totalCant: (supply || 0) + 1,
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
				if (this.supply.articles.filter(art => art.article_id === item.article_id).length === 0) {
					this.supply.articles.push(item)
					this.calcTotalSupply()
				} else {
					this.showInfoAdd = true
				}
			}
		},
		deleteItem (item) {
			this.supply.articles.splice(this.supply.articles.indexOf(item), 1)
			this.calcTotalSupply()
		},
		closeInfoAdd () {
			this.showInfoAdd = false
		},
		async supplyHandler () {
			if (this.getDifference !== 0) {
				this.loading = false
				this.shopMessageError(this.$vuetify.lang.t(
					'$vuetify.messages.warning_difference_price', [(this.getDifference + ' ' + this.user.company.currency).toString()]
				))
			} else {
				if (this.supply.articles.length > 0) {
					if (this.$refs.form.validate()) {
						this.loading = true
						if (!this.managerSupply) {
							await this.createSupply(this.supply).then(() => {
								this.$router.push({ name: 'supply_product' })
							})
						} else {
							await this.updateInventory(this.supply).then(() => {
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
			this.supply.articles = []
			this.$router.push({ name: 'supply_product' })
		},
		calcTotalArticle: function (item) {
			this.editedIndex = this.supply.articles.indexOf(item)
			this.supply.articles[this.editedIndex].totalCost = parseFloat(this.supply.articles[this.editedIndex].cost *
              this.supply.articles[this.editedIndex].cant).toFixed(2)
			this.supply.articles[this.editedIndex].totalCant = parseFloat(parseFloat(this.supply.articles[this.editedIndex].supply) -
              parseFloat(this.supply.articles[this.editedIndex].cant) || 0).toFixed(2)
			item.totalCost = item.cant * item.cost + this.articleTotalCost(item) - this.articleTotalDiscount(item)
			this.calcTotalSupply()
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
		calcTotalSupply () {
			this.totalTax = 0
			this.totalDisc = 0
			this.totalCost = 0
			this.subTotal = 0
			this.supply.articles.forEach((v) => {
				this.subTotal = parseFloat(v.cost) * parseFloat(v.cant) + this.subTotal
			})
			this.supply.taxes.forEach((v) => {
				this.totalTax += v.percent === 'true' ? this.subTotal * v.value / 100 : v.value
			})
			this.supply.discounts.forEach((v) => {
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
