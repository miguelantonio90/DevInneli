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
        >
          <template v-slot:[`item.company.country`]="{ item }">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-chip
                  v-bind="attrs"
                  v-on="on"
                >
                  <v-avatar left>
                    {{
                      arrayCountry.filter(
                        cou =>
                          cou.id ===
                          item.company.country
                      )[0].emoji
                    }}
                  </v-avatar>
                  {{ item.company.country }}
                </v-chip>
              </template>
              <span>{{
                arrayCountry.filter(
                  cou => cou.id === item.company.country
                )[0].name
              }}</span>
            </v-tooltip>
          </template>
          <template v-slot:item.name="{ item }">
            <v-chip :key="JSON.stringify(item)">
              <v-avatar
                v-if="item.color && item.images.length === 0"
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
                            "$vuetify.firstName"
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
                      <th class="text-left">
                        {{
                          $vuetify.lang.t(
                            "$vuetify.menu.shop"
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
                      <td>{{ dessert.name }}</td>
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
  name: 'AdminArticle',
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
    ...mapGetters('statics', ['arrayCountry']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'company.name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'company.country'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shopsNames',
          select_filter: true
        },
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
        },
        {
          text: this.$vuetify.lang.t('$vuetify.articles.percent'),
          value: 'percent',
          select_filter: true
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
    ...mapActions('shop', ['getShops'])
  }
}
</script>

<style scoped></style>
