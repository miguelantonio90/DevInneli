<template>
  <v-container>
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
        v-show="newInventory.articles.length > 0"
        cols="12"
        md="6"
      >
        <detail-supplier
          :supply-selected="newInventory"
          @updateSupplyData="updateSupplyData"
        />
      </v-col>
      <v-col
        v-show="newInventory.articles.length > 0 "
        cols="12"
        md="6"
      >
        <resume-supply :supply-selected="newInventory" />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import DetailSupplier from './DatailSupplier'
import ResumeSupply from './ResumeSupply'

export default {
  name: 'NewInventory',
  components: { ResumeSupply, DetailSupplier },
  data () {
    return {
      loadingData: false,
      editedIndex: -1,
      localArticles: []
    }
  },
  computed: {
    ...mapState('inventory', ['newInventory']),
    ...mapState('article', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'articles',
      'isTableLoading'
    ]),
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
    ...mapActions('article', ['getArticles']),
    selectArticle (item) {
      this.newInventory.articles.push(item)
    },
    updateSupplyData (supply) {
      this.$store.state.newInventory = supply
      console.log(this.newInventory)
    },
    deleteItem (item) {
      this.newInventory.articles.splice(this.newInventory.articles.indexOf(item), 1)
    },
    calcTotal: function (item) {
      console.log(item)
      this.editedIndex = this.newInventory.articles.indexOf(item)
      this.newInventory.articles[this.editedIndex].totalPrice = parseFloat(this.newInventory.articles[this.editedIndex].price * this.newInventory.articles[this.editedIndex].cant).toFixed(2)
      this.newInventory.articles[this.editedIndex].totalCost = parseFloat(this.newInventory.articles[this.editedIndex].cost * this.newInventory.articles[this.editedIndex].cant).toFixed(2)
    }
  }

}
</script>

<style scoped>

</style>
