<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <app-data-table
          :title="
            $vuetify.lang.t('$vuetify.titles.list', [
              $vuetify.lang.t('$vuetify.menu.articles')
            ])
          "
          :headers="getTableColumns"
          :view-transfer-button="true"
          csv-filename="Articles"
          has-csv-import
          :manager="'article'"
          :items="articles"
          :options="vBindOption"
          :sort-by="['ref']"
          :sort-desc="[false]"
          multi-sort
          :is-loading="isTableLoading"
          @create-row="createArticleHandler"
          @edit-row="editArticleHandler($event)"
          @delete-row="deleteArticleHandler($event)"
        >
          <template v-slot:item.name="{ item }">
            <v-chip
              :key="JSON.stringify(item)"
              style="max-width: 20%"
            >
              <v-avatar
                v-if="item.color && item.images.length === 0"
                class="white--text"
                style="margin: 3px"
                :color="item.color"
                left
                v-text="item.name.slice(0, 1).toUpperCase()"
              />
              <v-img :src="item.path" />
            </v-chip>
            {{ item.name }}
          </template>
          <template v-slot:item.percent="{ item }">
            <template v-if="item.variant_values.length === 0">
              {{ item.percent }} %
            </template>
          </template>
          <template v-slot:item.price="{ item }">
            <template v-if="item.variant_values.length === 0">
              {{ `${user.company.currency + " " + item.price}` }}
            </template>
          </template>
          <template v-slot:item.cost="{ item }">
            <template v-if="item.variant_values.length === 0">
              {{ `${user.company.currency + " " + item.cost}` }}
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
          <template
            v-slot:[`item.data-table-expand`]="{
              item,
              expand,
              isExpanded
            }"
          >
            <v-btn
              v-if="item.variant_values.length > 0"
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
            <v-btn
              v-else
              fab
              x-small
              disabled
            >
              <v-icon>
                mdi-check
              </v-icon>
            </v-btn>
          </template>
          <template v-slot:expanded-item="{ headers, item }">
            <td
              :colspan="headers.length"
              style="padding: 0 0 0 0"
            >
              <v-simple-table dense>
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th class="text-left">
                        {{
                          $vuetify.lang.t(
                            "$vuetify.articles.ref"
                          )
                        }}
                      </th>
                      <th class="text-left">
                        {{
                          $vuetify.lang.t(
                            "$vuetify.firstName"
                          )
                        }}
                      </th>
                      <th class="text-left">
                        {{
                          $vuetify.lang.t(
                            "$vuetify.menu.shop"
                          )
                        }}
                      </th>
                      <th class="text-left">
                        {{
                          $vuetify.lang.t(
                            "$vuetify.articles.price"
                          )
                        }}
                      </th>
                      <th class="text-left">
                        {{
                          $vuetify.lang.t(
                            "$vuetify.articles.cost"
                          )
                        }}
                      </th>
                      <th class="text-left">
                        {{
                          $vuetify.lang.t(
                            "$vuetify.articles.percent"
                          )
                        }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="dessert in item.variant_values"
                      :key="dessert.ref"
                    >
                      <td>{{ dessert.ref }}</td>
                      <td>{{ dessert.name }}</td>
                      <td>
                        <v-chip
                          v-for="(shop,
                                  i) of dessert.shopsNames"
                          :key="i + shop"
                          small
                        >
                          {{ shop }}
                        </v-chip>
                      </td>
                      <td>
                        {{
                          `${user.company.currency +
                            " " +
                            dessert.price}`
                        }}
                      </td>
                      <td>
                        {{
                          `${user.company.currency +
                            " " +
                            dessert.cost}`
                        }}
                      </td>
                      <td>
                        {{ dessert.percent + " %" }}
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

export default {
  name: 'ListArticle',
  data () {
    return {
      localArticles: [],
      search: '',
      vBindOption: {
        singleExpand: false,
        showExpand: true
      }
    }
  },
  computed: {
    ...mapState('article', [
      'managerArticle',
      'showNewModal',
      'showTransfer',
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
          text: this.$vuetify.lang.t('$vuetify.articles.ref'),
          value: 'ref',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          style: "width: '10%'",
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shopsNames',
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
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.percent'),
          value: 'percent',
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
    this.getArticles()
  },
  methods: {
    ...mapActions('article', [
      'toogleNewModal',
      'toogleTransferModal',
      'openEditModal',
      'openTransferModal',
      'openShowModal',
      'getArticles',
      'deleteArticle'
    ]),
    ...mapActions('category', ['getCategories']),
    ...mapActions('shop', ['getShops']),
    createArticleHandler () {
      this.$store.state.article.managerArticle = false
      this.$router.push({ name: 'product_add' })
    },
    transferHandler ($event) {
      this.openTransferModal($event)
      this.toogleTransferModal(true)
    },
    editArticleHandler ($event) {
      this.$store.state.article.managerArticle = true
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
        .then(result => {
          if (result.isConfirmed) this.deleteArticle(articleId)
        })
    }
  }
}
</script>

<style scoped></style>
