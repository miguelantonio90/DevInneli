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
            <template v-slot:item.actions="{ item }">
              <v-icon
                small
                @click="deleteItem(item)"
              >
                mdi-delete
              </v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-col>
    </v-row>
  </v-container>
</template>
<script>
import { mapActions, mapState } from 'vuex'
export default {
  name: 'Inventory',
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
          value: 'name',
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
          if (value.variant_values.length > 0) {
            value.variant_values.forEach((v, i) => {
              this.localArticles.push({
                name: value.name + '(' + v.name + ')',
                price: v.price ? v.price : 0,
                cost: v.cost ? v.cost : 0,
                cant: '1',
                totalCost: v.cost,
                totalPrice: v.price,
                article_id: v.id
              })
            })
          } else {
            this.localArticles.push({
              name: value.name,
              price: value.price ? value.price : 0,
              cost: value.cost ? value.cost : 0,
              cant: '1',
              totalCost: value.cost,
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
