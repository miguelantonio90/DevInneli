<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-article v-if="showNewModal" />
        <edit-article v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.titles.list',
                                  [$vuetify.lang.t('$vuetify.menu.articles'),])"
          :columns="geArticleTableColumns"
          :rows="articles"
          :is-loading="isTableLoading"
          sort-options="firstName"
          show-avatar
          @create-row="createArticleHandler"
          @edit-row="editArticleHandler($event)"
          @delete-row="deleteArticleHandler($event)"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewArticle from './NewArticle'
import EditArticle from './EditArticle'

export default {
  name: 'ListArticle',
  components: {
    EditArticle,
    NewArticle
  },
  data () {
    return {
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
    geArticleTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.price'),
          value: 'price'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.category'),
          value: 'category.name'
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
    this.getArticles()
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
      this.toogleNewModal(true)
    },
    editArticleHandler ($event) {
      this.openEditModal($event)
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
          if (result.value) this.deleteArticle(articleId)
        })
    }
  }
}
</script>

<style scoped></style>
