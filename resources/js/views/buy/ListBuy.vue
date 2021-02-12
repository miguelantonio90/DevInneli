<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-refound
          v-if="showNewModal"
        />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.supply.name'),])"
          :headers="getTableColumns"
          csv-filename="BuyProducts"
          :manager="'vending'"
          :items="localInventories"
          :options="vBindOption"
          :sort-by="['no_facture']"
          :sort-desc="[false, true]"
          multi-sort
          :is-loading="isTableLoading"
          @create-row="createBuyHandler"
          @edit-row="editBuyHandler($event)"
          @delete-row="deleteBuyHandler($event)"
        >
          <template v-slot:[`item.shop.name`]="{ item }">
            {{ item.shop.name }}
          </template>
          <template v-slot:[`item.totalCost`]="{ item }">
            <template>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <b><v-icon
                    :style="item.totalRefund > 0?'color: red': 'color:primary'"
                    class="mr-2"
                    small
                    v-bind="attrs"
                    v-on="on"
                  >
                    mdi-information
                  </v-icon></b>
                </template>
                <template>
                  <list-pay
                    :show="false"
                    :sale="item"
                    :total-price="parseFloat(item.totalCost).toFixed(2).toString()"
                    :total-tax="parseFloat(item.totalTax).toFixed(2)"
                    :total-discount="parseFloat(item.totalDisc).toFixed(2)"
                    :sub-total="parseFloat(item.subTotal).toFixed(2)"
                    :currency="user.company.currency"
                  />
                </template>
                <span
                  v-if="item.totalRefund > 0"
                >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + item.totalRefund}` }}</span>
              </v-tooltip>
            </template>
            {{ `${user.company.currency + ' ' + item.totalCost}` }}
          </template>
          <template v-slot:[`item.data-table-expand`]="{item, expand, isExpanded }">
            <v-btn
              v-if="item.articles.length > 0"
              color="primary"
              fab
              x-small
              dark
              @click="expand(!isExpanded)"
            >
              <v-icon v-if="isExpanded">
                mdi-chevron-up
              </v-icon>
              <v-icon v-else>
                mdi-chevron-down
              </v-icon>
            </v-btn>
          </template>
          <template v-slot:expanded-item="{ headers,item }">
            <td
              :colspan="headers.length"
              style="padding: 0 0 0 0"
            >
              <v-simple-table dense>
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.articles.ref') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.firstName') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.variants.cant') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.articles.cost') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.variants.total_cost') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.articles.new_inventory') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.actions.actions') }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="article in item.articles"
                      :key="article.id"
                    >
                      <td>
                        <template v-if="article.refounds.length > 0">
                          <v-tooltip right>
                            <template v-slot:activator="{ on, attrs }">
                              <b><v-icon
                                v-if="article.cant > 0"
                                style="color: red"
                                class="mr-2"
                                small
                                v-bind="attrs"
                                v-on="on"
                              >
                                mdi-information
                              </v-icon></b>
                            </template>
                            <template>
                              <detail-refund
                                :article="article"
                                :currency="`${user.company.currency}`"
                              />
                            </template>
                          </v-tooltip>
                        </template>
                        {{ article.ref }}
                      </td>
                      <td>{{ article.name }}</td>
                      <td>
                        <v-tooltip top>
                          <template v-slot:activator="{ on, attrs }">
                            <b><v-icon
                              v-if="article.cantRefund > 0"
                              style="color: red"
                              class="mr-2"
                              small
                              v-bind="attrs"
                              v-on="on"
                            >
                              mdi-information
                            </v-icon></b>
                          </template>
                          <span>{{ $vuetify.lang.t('$vuetify.menu.refund')+': ' + article.cantRefund + ' ' + $vuetify.lang.t('$vuetify.menu.articles') }}</span>
                        </v-tooltip>
                        {{ article.cant }}
                      </td>
                      <td>{{ `${user.company.currency + ' ' + article.cost}` }}</td>
                      <td>
                        <template
                          v-if="article.taxes.length > 0 || article.discount.length > 0"
                        >
                          <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                              <b><v-icon
                                :color="article.moneyRefund > 0 ? 'red':'primary'"
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
                                :article="article"
                                :currency="user.company.currency"
                              />
                              <span
                                v-if="article.moneyRefund > 0"
                              >{{ $vuetify.lang.t('$vuetify.menu.refund')+': '+ `${user.company.currency + ' ' + article.moneyRefund}` }}</span>
                            </template>
                          </v-tooltip>
                        </template>
                        <span>{{ `${user.company.currency + ' ' + parseFloat(article.totalCost).toFixed(2)}` }}</span>
                      </td>
                      <td>
                        <template v-if="article.inventory > 0">
                          {{ article.inventory }}
                        </template>
                        <template v-else>
                          <i style="color: red">
                            <v-icon style="color: red">
                              mdi-arrow-down-bold-circle
                            </v-icon>
                            {{ article.inventory }}
                          </i>
                        </template>
                      </td>
                      <td>
                        <template>
                          <v-tooltip top>
                            <template v-slot:activator="{ on, attrs }">
                              <b><v-icon
                                style="color: #ff752b"
                                class="mr-2"
                                small
                                v-bind="attrs"
                                v-on="on"
                                @click="refundArticle(item, article)"
                              >
                                mdi-undo
                              </v-icon></b>
                            </template>
                            <span>{{ $vuetify.lang.t('$vuetify.actions.refund') }}</span>
                          </v-tooltip>
                        </template>
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </td>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import NewRefound from '../refund/NewRefound'
import DetailRefund from '../refund/DetailRefund'
import ListPay from '../pay/ListPay'
import DetailArticleCost from './DetailArticleCost'
export default {
	name: 'ListBuy',
	components: { DetailRefund, NewRefound, ListPay, DetailArticleCost },
	data () {
		return {
			menu: false,
			fav: true,
			message: false,
			hints: true,
			localInventories: [],
			search: '',
			localAccess: {},
			vBindOption: {
				itemKey: 'no_facture',
				singleExpand: false,
				showExpand: true
			}
		}
	},
	computed: {
		...mapState('inventory', [
			'showNewModal',
			'showEditModal',
			'loading',
			'showShowModal',
			'inventories',
			'isTableLoading'
		]),
		...mapState('article', ['articles']),
		...mapState('refund', ['showNewModal']),
		...mapGetters('auth', ['user', 'access_permit']),
		getTableColumns () {
			return [
				{
					text: this.$vuetify.lang.t('$vuetify.tax.noFacture'),
					value: 'no_facture',
					select_filter: true
				},
				{
					text: this.$vuetify.lang.t('$vuetify.variants.total_cost'),
					value: 'totalCost',
					select_filter: true
				},
				{
					text: this.$vuetify.lang.t('$vuetify.menu.shop'),
					value: 'shop.name',
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
	watch: {
		inventories: function () {
			this.localInventories = []
			this.inventories.forEach((value) => {
				const inventory = value
				value.articles.forEach((v, i) => {
					if (v.parent_id) { inventory.articles[i].name = this.articles.filter(art => art.id === v.parent_id)[0].name + '(' + v.name + ')' }
				})
				this.localInventories.push(inventory)
			})
		},
		loadData: function () {
			if (this.loadData === true) { this.loadInitData() }
		}
	},
	created () {
		this.loadInitData()
	},
	methods: {
		...mapActions('inventory', [
			'toogleNewModal',
			'openEditModal',
			'openShowModal',
			'getInventories',
			'updateInventory',
			'deleteInventory'
		]),
		...mapActions('article', ['getArticles']),
		...mapActions('refund', ['openNewModal']),
		async loadInitData () {
			this.localInventories = []
			await this.getArticles()
			await this.getInventories()
		},
		refundArticle (sale, article) {
			if (article.cant === article.cantRefund && this.total_pay(article) === article.moneyRefund) {
				this.$Swal.fire({
					title: this.$vuetify.lang.t('$vuetify.actions.refund', [
						this.$vuetify.lang.t('$vuetify.menu.articles')
					]),
					text: this.$vuetify.lang.t('$vuetify.messages.warning_refund_all'),
					icon: 'warning',
					showCancelButton: false,
					confirmButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.accept'
					),
					confirmButtonColor: 'red'
				})
			} else {
				this.openNewModal({ sale, article })
			}
		},
		cancelProductPreform (item, art) {
		},
		total_pay (item) {
			let sum = 0
			item.taxes.forEach((v) => {
				sum += v.percent ? item.cant * item.cost * v.value / 100 : v.value
			})
			let discount = 0
			item.discount.forEach((v) => {
				discount += v.percent ? item.cant * item.cost * v.value / 100 : v.value
			})
			return item.cant * item.cost + sum - discount - item.moneyRefund
		},
		createBuyHandler () {
			if (this.articles.length === 0) {
				this.$Swal
					.fire({
						title: this.$vuetify.lang.t('$vuetify.titles.newF', [
							this.$vuetify.lang.t('$vuetify.supply.name')
						]),
						text: this.$vuetify.lang.t(
							'$vuetify.messages.warning_no_article'
						),
						icon: 'warning',
						showCancelButton: false,
						confirmButtonText: this.$vuetify.lang.t(
							'$vuetify.actions.accept'
						),
						confirmButtonColor: 'red'
					})
			} else {
				this.$store.state.inventory.managerInventory = false
				this.$router.push({ name: 'supply_add' })
			}
		},
		editBuyHandler ($event) {
			this.openEditModal($event)
			this.$store.state.inventory.managerInventory = true
			this.$router.push({ name: 'supply_edit' })
		},
		deleteBuyHandler (articleId) {
			this.$Swal
				.fire({
					title: this.$vuetify.lang.t('$vuetify.titles.delete', [
						this.$vuetify.lang.t('$vuetify.supply.name')
					]),
					text: this.$vuetify.lang.t(
						'$vuetify.messages.warning_delete'
					),
					icon: 'warning',
					showCancelButton: true,
					cancelButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.cancel'
					),
					confirmButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.delete'
					),
					confirmButtonColor: 'red'
				})
				.then((result) => {
					if (result.isConfirmed) this.deleteInventory(articleId)
				})
		}
	}
}
</script>

<style scoped></style>
