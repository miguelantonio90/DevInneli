<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table-expand
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.articles'),])"
          :headers="getTableColumns"
          :items="localArticles"
          item-key="name"
          class="elevation-1"
          @create-row="createArticleHandler"
          @edit-row="editArticleHandler($event)"
          @delete-row="deleteArticleHandler($event)"
        >
          <template v-slot:item.name="{ item }">
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
          <template v-slot:item.percent="{ item }">
            <template v-if="item.variant_values.length===0">
              {{ item.percent }} %
            </template>
          </template>
          <template v-slot:item.price="{ item }">
            <template v-if="item.variant_values.length===0">
              {{ `${user.company.currency + ' ' + item.price}` }}
            </template>
          </template>
          <template
            v-slot:item.cost="{ item }"
          >
            <template v-if="item.variant_values.length===0">
              {{ `${user.company.currency + ' ' + item.cost}` }}
            </template>
          </template>
          <template v-slot:item.shopsNames="{ item }">
            <v-chip
              v-for="(shop, i) of item.shopsNames"
              :key="i"
            >
              {{ shop }}
            </v-chip>
          </template>
          <template v-slot:expanded-item="{ headers, item }">
            <td :colspan="headers.length">
              <v-data-table
                style="margin: 0"
                item-key="name"
                :headers="getTableColumns"
                :items="item.variant_values"
                hide-default-header
                hide-default-footer
              >
                <template v-slot:value.price="{ value }">
                  {{ `${user.company.currency + ' ' + value.price}` }}
                </template>
                <template v-slot:value.cost="{ value }">
                  {{ `${user.company.currency + ' ' + value.cost}` }}
                </template>
                <template v-slot:value.shopsNames="{ value }">
                  <v-chip
                    v-for="(article_shop, i) of value.articles_shops"
                    :key="i"
                  >
                    {{ article_shop.shops.name }}
                  </v-chip>
                </template>
              </v-data-table>
            </td>
          </template>
        </app-data-table-expand>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'ListArticle',
  data () {
    return {
      expanded: [],
      singleExpand: false,
      localArticles: [],
      search: ''
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
    ...mapState('category', ['categories', 'isActionInProgress']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    ...mapGetters('auth', ['user']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.category'),
          value: 'category.name',
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
        }, {
          text: this.$vuetify.lang.t('$vuetify.articles.percent'),
          value: 'percent',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shopsNames',
          select_filter_many: true
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
      this.articles.forEach((value) => {
        if (!value.parent_id) {
          this.localArticles.push(value)
        }
      })
      console.log(this.localArticles)
    })
  },
  methods: {
    ...mapActions('article', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getArticles',
      'deleteArticle'
    ]),
    ...mapActions('category', ['getCategories']),
    ...mapActions('shop', ['getShops']),
    createArticleHandler () {
      // this.toogleNewModal(true)
      this.$router.push({ name: 'product_add' })
    },
    editArticleHandler ($event) {
      this.openEditModal($event)
      this.$router.push({ name: 'product_edit' })
    },
    deleteArticleHandler (articleId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.articles')
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
          if (result.isConfirmed) this.deleteArticle(articleId)
        })
    }
  }
}
</script>

<style scoped></style>
