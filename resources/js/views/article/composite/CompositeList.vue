<template>
  <v-container>
    <v-col
      v-show="article.composite"
      cols="12"
      md="3"
    >
      <v-select
        ref="selectArticle"
        :items="localArticles"
        :label="$vuetify.lang.t('$vuetify.rule.select')"
        item-text="name"
        chips
        :loading="loading"
        :disabled="!!loading"
        return-object
        @input="selectArticle"
      >
        <template v-slot:selection="data">
          <v-chip
            :key="JSON.stringify(data.item)"
            v-bind="data.attrs"
            :input-value="data.selected"
            :disabled="data.disabled"
            @click:close="data.parent.selectItem(data.item)"
          >
            <v-avatar
              v-if="data.item.color"
              class="white&#45;&#45;text"
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
      </v-select>
    </v-col>
    <v-col
      cols="12"
      md="12"
    >
      <v-data-table
        :headers="getHeader"
        :items="article.composites"
        sort-by="name"
        class="elevation-1"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <h4> {{ $vuetify.lang.t('$vuetify.variants.total_cost') }} {{ `${user.company.currency + ' ' + parseFloat(article.cost).toFixed(2)}` }}</h4>
            <v-spacer />
            <h4> {{ $vuetify.lang.t('$vuetify.variants.total_price') }} {{ `${user.company.currency + ' ' + parseFloat(article.price).toFixed(2)}` }}</h4>
          </v-toolbar>
        </template>
        <template v-slot:[`item.name`]="{ item }">
          <v-chip
            :key="JSON.stringify(item)"
          >
            <v-avatar
              v-if="item.color"
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
            @save="updateTotalCost"
          >
            <div>{{ item.cant }}</div>
            <template v-slot:input>
              <div class="mt-4 title">
                {{ $vuetify.lang.t('$vuetify.actions.edit') }}
              </div>
              <v-text-field
                v-model="item.cant"
                :label="$vuetify.lang.t('$vuetify.actions.edit')"
                single-line
                counter
                autofocus
              />
            </template>
          </v-edit-dialog>
        </template>
        <template v-slot:[`item.cost`]="{ item }">
          {{ `${user.company.currency +' '+item.cost}` }}
        </template>
        <template v-slot:[`item.price`]="{ item }">
          {{ `${user.company.currency +' '+item.price}` }}
        </template>
        <template v-slot:[`item.actions`]="{ item }">
          <v-icon
            small
            @click="deleteItem(item)"
          >
            mdi-delete
          </v-icon>
        </template>
      </v-data-table>
    </v-col>
  </v-container>
</template>
<script>
/* eslint-disable vue/require-default-prop */

import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'CompositeList',
  props: {
    article: {
      type: Object,
      default: function () {
        return {}
      }
    }
  },
  data: () => ({
    localArticles: [],
    loading: false,
    totalCost: 0.00,
    totalPrice: 0.00,
    dialog: false,
    headers: [],
    editedIndex: -1,
    editedItem: {
      name: '',
      cant: 0,
      cost: 0,
      price: 0
    },
    defaultItem: {
      name: '',
      cant: 0,
      cost: 0,
      price: 0
    }
  }),
  computed: {
    ...mapState('article', ['articles']),
    ...mapGetters('auth', ['user']),
    getHeader () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.variants.name'),
          value: 'name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cant'),
          value: 'cant'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cost'),
          value: 'cost'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.price'),
          value: 'price'
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
    dialog (val) {
      val || this.close()
    }
  },
  async created () {
    this.loading = true
    await this.getArticles().then(() => {
      this.articles.forEach((value) => {
        this.ref = parseFloat(value.ref) > parseFloat(this.ref) ? value.ref : this.ref
        if (!value.article_id) {
          if (value.variant_values.length > 0) {
            value.variant_values.forEach((v) => {
              this.localArticles.push({
                name: value.name + '(' + v.name + ')',
                price: v.price,
                cost: v.cost,
                cant: '1',
                composite_id: v.id
              })
            })
          } else {
            this.localArticles.push({
              name: value.name,
              price: value.price,
              cost: value.cost,
              cant: '1',
              composite_id: value.id
            })
          }
        }
      })
    })
    this.updateTotalCost()
    this.loading = false
  },
  methods: {
    ...mapActions('article', ['getArticles']),
    updateTotalCost () {
      if (this.article.composite) {
        this.article.cost = 0.00
        this.article.price = 0.00
        this.article.composites.forEach((comp) => {
          this.article.cost += comp.cant * comp.cost
          this.article.price += comp.cant * comp.price
        })
      }
    },
    deleteItem (item) {
      this.editedIndex = this.article.composites.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.articles.name')
          ]),
          text: this.$vuetify.lang.t('$vuetify.messages.sure_delete'),
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
          if (result.value) {
            this.article.composites.splice(this.editedIndex, 1)
            this.updateTotalCost()
          }
        })
    },
    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    save () {
      if (this.editedIndex > -1) {
        Object.assign(this.article.composite[this.editedIndex], this.editedItem)
      } else {
        this.article.composites.push(this.editedItem)
      }
      this.close()
    },
    selectArticle (item) {
      if (this.article.composites.filter(art => art.composite_id === item.composite_id).length === 0) {
        this.article.composites.push({
          name: item.name,
          price: item.price,
          cost: item.cost,
          cant: '1',
          composite_id: item.composite_id
        })
      } else {
        this.showInfoAdd = true
      }
      this.selected = null
      this.updateTotalCost()
    }
  }
}
</script>

<style scoped>

</style>
