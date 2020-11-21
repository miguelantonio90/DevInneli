<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.supply_product'),])"
          :headers="getTableColumns"
          csv-filename="ProductBuys"
          :items="inventories"
          :options="vBindOption"
          :sort-by="['no_facture']"
          :sort-desc="[false, true]"
          multi-sort
          :is-loading="isTableLoading"
          @create-row="createInventoryHandler"
          @edit-row="editInventoryHandler($event)"
          @delete-row="deleteInventoryHandler($event)"
        >
          <template v-slot:[`item.pay`]="{ item }">
            {{
              item.pay === 'counted' ? $vuetify.lang.t('$vuetify.pay.counted') : $vuetify.lang.t('$vuetify.pay.credit')
            }}
          </template>
          <template v-slot:[`item.payments.name`]="{ item }">
            <template v-if="item.payments">
              {{ item.payments.name }}
            </template>
            <template v-else>
              <i style="color: red">{{ $vuetify.lang.t('$vuetify.no_defined') }}</i>
            </template>
          </template>
          <template v-slot:[`item.shop.name`]="{ item }">
            <v-chip>
              {{ item.shop.name }}
            </v-chip>
          </template>
          <template v-slot:[`item.totalPrice`]="{ item }">
            {{ `${user.company.currency + ' ' + item.totalPrice}` }}
          </template>
          <template v-slot:[`item.totalCost`]="{ item }">
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
                        {{ $vuetify.lang.t('$vuetify.articles.inventory') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.articles.price') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.articles.cost') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.variants.cant') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.variants.total_price') }}
                      </th>
                      <th class="text-left">
                        {{ $vuetify.lang.t('$vuetify.variants.total_cost') }}
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
                      <td>{{ article.inventory }}</td>
                      <td>{{ `${user.company.currency + ' ' + article.price}` }}</td>
                      <td>{{ `${user.company.currency + ' ' + article.cost}` }}</td>
                      <td>{{ `${article.cant}` }}</td>
                      <td>{{ `${user.company.currency + ' ' + article.price * article.cant}` }}</td>
                      <td>{{ `${user.company.currency + ' ' + article.cost * article.cant}` }}</td>
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
  name: 'ListInventory',
  data () {
    return {
      localInventories: [],
      search: '',
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
      'showShowModal',
      'inventories',
      'isTableLoading'
    ]),
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
          text: this.$vuetify.lang.t('$vuetify.supplier.name'),
          value: 'supplier.name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.total_cost'),
          value: 'totalCost',
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
    this.getInventories()
  },
  methods: {
    ...mapActions('inventory', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getInventories',
      'deleteInventory'
    ]),
    createInventoryHandler () {
      this.$router.push({ name: 'supply_add' })
    },
    editInventoryHandler ($event) {
      this.openEditModal($event)
      this.$router.push({ name: 'supply_edit' })
    },
    deleteInventoryHandler (articleId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.supply_productS')
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
