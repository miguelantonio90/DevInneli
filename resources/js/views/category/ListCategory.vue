<template>
  <v-container>
    <new-category v-if="showNewModal" />
    <edit-category v-if="showEditModal" />
    <v-card>
      <v-card-title>
        <v-list-item two-line>
          <v-list-item>
            <v-list-item-title>
              {{ $vuetify.lang.t('$vuetify.menu.category_list') }}
            </v-list-item-title>
            <v-list-item-icon>
              <v-btn
                class="mb-2"
                color="primary"
                @click="toogleNewModal(true)"
              >
                <v-icon>mdi-plus</v-icon>
                {{ $vuetify.lang.t('$vuetify.actions.new') }}
              </v-btn>
            </v-list-item-icon>
          </v-list-item>
        </v-list-item>
      </v-card-title>
      <v-row>
        <v-col
          v-for="category in categories"
          :key="category.id"
          style="margin-top: 20px"
          class="py-0"
          cols="12"
          md="4"
        >
          <v-card
            class="mx-auto"
            :color="category.color? category.color :'#26c6da'"
            dark
            max-width="400"
          >
            <v-card-title>
              <v-list-item two-line>
                <v-list-item-title>
                  <span class="title font-weight-light">{{ category.name }}</span>
                </v-list-item-title>

                <v-list-item-icon>
                  <v-spacer />
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        class="mr-2"
                        small
                        color="withe"
                        v-bind="attrs"
                        v-on="on"
                        @click="editCategoryHandler(category.id)"
                      >
                        mdi-pencil
                      </v-icon>
                    </template>
                    <span>{{ $vuetify.lang.t('$vuetify.actions.edit') }}</span>
                  </v-tooltip>
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        class="mr-2"
                        color="withe"
                        small
                        v-bind="attrs"
                        v-on="on"
                        @click="deleteCategoryHandler(category.id)"
                      >
                        mdi-delete
                      </v-icon>
                    </template>
                    <span>{{ $vuetify.lang.t('$vuetify.actions.delete') }}</span>
                  </v-tooltip>
                </v-list-item-icon>
              </v-list-item>
            </v-card-title>
            <v-card-text class="headline font-weight-bold" />
            <v-card-actions>
              <v-list-item class="grow">
                <v-list-item-content>
                  <v-list-item-title>{{ $vuetify.lang.t('$vuetify.menu.articles') }}: {{ category.articles.length }}</v-list-item-title>
                </v-list-item-content>

                <v-row
                  align="center"
                  justify="end"
                >
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        class="mr-2"
                        small
                        color="withe"
                        v-bind="attrs"
                        v-on="on"
                        @click="showArticlesByCategory(category.id)"
                      >
                        mdi-eye
                      </v-icon>
                    </template>
                    <span>{{ $vuetify.lang.t('$vuetify.actions.eye') }}</span>
                  </v-tooltip>
                  <span class="mr-1" />
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-icon
                        class="mr-2"
                        small
                        color="withe"
                        v-bind="attrs"
                        v-on="on"
                        @click="editCategoryHandler(category.id)"
                      >
                        mdi-chart-bar
                      </v-icon>
                    </template>
                    <span>{{ $vuetify.lang.t('$vuetify.menu.sell_category') }}</span>
                  </v-tooltip>
                </v-row>
              </v-list-item>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-card>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewCategory from './NewCategory'
import EditCategory from './EditCategory'

export default {
  name: 'ListCategory',
  components: {
    NewCategory,
    EditCategory
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('category', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'categories',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.color'),
          value: 'color'
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
    this.getCategories()
  },
  methods: {
    ...mapActions('category', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getCategories',
      'deleteCategory'
    ]),
    showArticlesByCategory (categoryId) {
      this.$router.push({ name: 'product_by_category', params: { categoryId } })
    },
    editCategoryHandler ($event) {
      this.openEditModal($event)
    },
    deleteCategoryHandler (categoryId) {
      this.categories.filter(cat => cat.id === categoryId)[0].articles === 0
        ? this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.delete', [
              this.$vuetify.lang.t('$vuetify.menu.category')
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
            if (result.value) this.deleteCategory(categoryId)
          }) : this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.delete', [
              this.$vuetify.lang.t('$vuetify.menu.category')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.warning_exist_articles'
            ),
            icon: 'info',
            showCancelButton: false,
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'red'
          })
    }
  }
}
</script>

<style scoped></style>
