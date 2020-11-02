<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="4"
      >
        <v-autocomplete
          chips
          clearable
          deletable-chips
          rounded
          solo
          :items="localArticles"
          item-text="name"
          return-object
          @input="selectArticle"
        />
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="8">
        <v-card
          elevation="2"
          outlined
          shaped
          tile
        />
        <v-card-text>
          <v-data-table
            :headers="getTableColumns"
            :items="supply"
            class="elevation-1"
          >
            <template v-slot:item.cost="{ item }">
              <v-edit-dialog
                :return-value.sync="item.cost"
                large
                persistent
                :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
              >
                <div>{{ item.cost }}</div>
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
            <template v-slot:item.cant="{ item }">
              <v-edit-dialog
                :return-value.sync="item.cant"
                large
                persistent
                :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
                :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
              >
                <div>{{ item.cant }}</div>
                <template v-slot:input>
                  <div class="mt-4 title">
                    {{ $vuetify.lang.t('$vuetify.actions.edit') }}
                  </div>
                </template>
              </v-edit-dialog>
            </template>
            <template v-slot:item.supplier="{ item }">
              <v-select
                v-model="item.supplier"
                :items="suppliers"
                :label="$vuetify.lang.t('$vuetify.menu.supplier')"
                item-text="name"
                :loading="isSupplierTableLoading"
                :disabled="!!isSupplierTableLoading"
                return-object
              >
                <template v-slot:append-outer>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        v-bind="attrs"
                        v-on="on"
                        @click="$store.dispatch('supplier/toogleNewModal',true)"
                      >
                        mdi-plus
                      </v-icon>
                    </template>
                    <span>{{ $vuetify.lang.t('$vuetify.titles.newAction') }}</span>
                  </v-tooltip>
                </template>
              </v-select>
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
          <new-supplier v-if="$store.state.supplier.showNewModal" />
        </v-card-text>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapActions, mapState } from 'vuex'
import NewSupplier from '../supplier/NewSupplier'

export default {
  name: 'Inventory',
  components: { NewSupplier },
  data () {
    return {
      supply: [],
      localArticles: []
    }
  },
  computed: {
    ...mapState('article', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'articles',
      'isTableLoading'
    ]),
    ...mapState('supplier', ['suppliers', 'isSupplierTableLoading']),
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
          text: this.$vuetify.lang.t('$vuetify.supplier.name'),
          value: 'supplier',
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
      this.articles.forEach((value, index) => {
        if (!value.parent_id) {
          let inventory = 0
          if (value.variant_values.length > 0) {
            value.variant_values.forEach((v, i) => {
              if (v.articles_shops.length > 0) {
                v.articles_shops.forEach((k, l) => {
                  inventory += k.under_inventory ? parseFloat(k.under_inventory) : 0
                })
              }
              this.localArticles.push({
                ref: value.ref,
                name: value.name + '(' + v.name + ')',
                price: v.price ? v.price : 0,
                cost: v.cost ? v.cost : 0,
                inventory: inventory || 0,
                cant: 1,
                supplier: '',
                totalCost: v.cost,
                totalPrice: v.price,
                article_id: v.id
              })
            })
          } else {
            if (value.articles_shops.length > 0) {
              value.articles_shops.forEach((k, l) => {
                inventory += k.under_inventory ? parseFloat(k.under_inventory) : 0
              })
            }
            this.localArticles.push({
              ref: value.ref,
              name: value.name,
              price: value.price ? value.price : 0,
              cost: value.cost ? value.cost : 0,
              cant: 1,
              inventory: inventory || 0,
              totalCost: value.cost,
              supplier: '',
              totalPrice: value.price,
              article_id: value.id
            })
          }
        }
      })
    })
  },
  methods: {
    ...mapActions('article', ['getArticles']),
    ...mapActions('article', ['getArticles']),
    selectArticle (item) {
      console.log(item)
      this.supply.push(item)
    },

    deleteItem (item) {
      this.supply.splice(this.supply.indexOf(item), 1)
    }
  }
}
</script>

<style scoped>

</style>
