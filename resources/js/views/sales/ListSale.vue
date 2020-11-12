<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.vending'),])"
          :headers="getTableColumns"
          csv-filename="ProductBuys"
          :items="localSales"
          :options="vBindOption"
          :sort-by="['no_facture']"
          :sort-desc="[false, true]"
          multi-sort
          :is-loading="isTableLoading"
          @create-row="createSaleHandler"
          @edit-row="editSaleHandler($event)"
          @delete-row="deleteSaleHandler($event)"
        >
          <template v-slot:item.pay="{ item }">
            {{
              item.pay === 'counted' ? $vuetify.lang.t('$vuetify.pay.counted') : $vuetify.lang.t('$vuetify.pay.credit')
            }}
          </template>
          <template v-slot:item.payments.name="{ item }">
            <template v-if="item.payments">
              {{ item.payments.name }}
            </template>
            <template v-else>
              <i style="color: red">{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
            </template>
          </template>
          <template v-slot:item.shop.name="{ item }">
            <v-chip>
              {{ item.shop.name }}
            </v-chip>
          </template>
          <template v-slot:item.totalPrice="{ item }">
            {{ `${user.company.currency + ' ' + item.totalPrice}` }}
          </template>
          <template v-slot:item.totalCost="{ item }">
            {{ `${user.company.currency + ' ' + item.totalCost}` }}
          </template>
          <template v-slot:item.data-table-expand="{item, expand, isExpanded }">
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
                        {{ $vuetify.lang.t('$vuetify.articles.price') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.tax.name') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.variants.total_price') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.articles.new_inventory') }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="article in item.articles"
                      :key="article.name"
                    >
                      <td>{{ article.ref }}</td>
                      <td>{{ article.name }}</td>
                      <td>{{ article.cant }}</td>
                      <td>{{ `${user.company.currency + ' ' + article.price}` }}</td>
                      <td>
                        <template>
                          <v-chip
                            v-for="(lTax, i) of article.tax"
                            :key="i"
                          >
                            {{ lTax.name }}{{ lTax.percent ? '('+lTax.value +'%)':'' }} +{{ `${user.company.currency}` }} {{ lTax.percent ? lTax.value*article.cant*article.price/100 : lTax.value }}
                          </v-chip>
                        </template>
                      </td>
                      <td>{{ `${user.company.currency + ' ' + total_pay(article)}` }}</td>
                      <td>{{ article.inventory }}</td>
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

export default {
  name: 'ListSale',
  data () {
    return {
      localSales: [],
      search: '',
      vBindOption: {
        itemKey: 'no_facture',
        singleExpand: false,
        showExpand: true
      }
    }
  },
  computed: {
    ...mapState('sale', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'sales',
      'isTableLoading'
    ]),
    ...mapState('article', ['articles']),
    ...mapGetters('auth', ['user']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.tax.noFacture'),
          value: 'no_facture',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.pay.pay'),
          value: 'pay',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.payment.name'),
          value: 'payments.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.client'),
          value: 'client.firstName',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.total_price'),
          value: 'totalPrice',
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
  created () {
    this.getArticles().then(() => {
      this.getSales().then(() => {
        this.sales.forEach((value) => {
          const sale = value
          value.articles.forEach((v, i) => {
            if (v.parent_id) { sale.articles[i].name = this.articles.filter(art => art.id === v.parent_id)[0].name + '(' + v.name + ')' }
          })
          this.localSales.push(sale)
        })
      })
    })
  },
  methods: {
    ...mapActions('sale', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getSales',
      'deleteSale'
    ]),
    ...mapActions('article', ['getArticles']),
    total_pay (item) {
      let suma = 0
      item.tax.forEach((v) => {
        suma += v.percent ? item.cant * item.price * v.value / 100 : v.value
      })
      return item.cant * item.price + suma
    },
    createSaleHandler () {
      this.$router.push({ name: 'vending_new' })
    },
    editSaleHandler ($event) {
      this.openEditModal($event)
      this.$router.push({ name: 'vending_edit' })
    },
    deleteSaleHandler (articleId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.sale.sale')
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
          if (result.isConfirmed) this.deleteSale(articleId)
        })
    }
  }
}
</script>

<style scoped></style>
