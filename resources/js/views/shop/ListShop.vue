<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-shop v-if="showNewModal" />
        <edit-shop v-if="showEditModal" />
        <show-shop v-if="showShowModal" />
        <v-card>
          <v-card-title>
            {{
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.shop'),
              ])
            }}
          </v-card-title>
          <v-data-table
            :headers="getTableColumns"
            :items="shops"
            :loading="isTableLoading"
            :search="search"
            class="elevation-1"
            sort-by="name"
          >
            <template v-slot:top>
              <v-toolbar flat>
                <v-text-field
                  v-model="search"
                  :label="
                    $vuetify.lang.t('$vuetify.actions.search')
                  "
                  append-icon="mdi-magnify"
                  hide-details
                  single-line
                />
                <v-spacer />
                <v-btn
                  class="mb-2"
                  color="primary"
                  @click="toogleNewModal(true)"
                >
                  <v-icon>mdi-plus</v-icon>
                  {{ $vuetify.lang.t('$vuetify.actions.new') }}
                </v-btn>
              </v-toolbar>
            </template>
            <template v-slot:item.actions="{ item }">
              <v-icon
                class="mr-2"
                small
                @click="openShowModal(item.id)"
              >
                mdi-eye
              </v-icon>
              <v-icon
                class="mr-2"
                small
                @click="openEditModal(item.id)"
              >
                mdi-pencil
              </v-icon>
              <v-icon
                small
                @click="deleteShopHandler(item.id)"
              >
                mdi-delete
              </v-icon>
            </template>
            <template v-slot:item.name="{ item }">
              <v-icon>mdi-shopping</v-icon>
              {{ item.name }}
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewShop from './NewShop'
import EditShop from './EditShop'
import ShowShop from './ShowShop'

export default {
  components: {
    ShowShop,
    NewShop,
    EditShop
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('shop', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'shops',
      'isTableLoading'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.name'),
          value: 'name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'country'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.phone'),
          value: 'phone'
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
    this.getShops()
  },
  methods: {
    ...mapActions('shop', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getShops',
      'deleteShop'
    ]),
    deleteShopHandler (shopId) {
      this.shops.length > 1
        ? this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.delete', [
              this.$vuetify.lang.t('$vuetify.menu.shop')
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
            if (result.value) this.deleteShop(shopId)
          })
        : this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.no_action', [
              this.$vuetify.lang.t('$vuetify.actions.delete')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.error_delete_shop'
            ),
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.cancel'
            ),
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'red'
          })
    }
  }
}
</script>

<style scoped>

</style>
