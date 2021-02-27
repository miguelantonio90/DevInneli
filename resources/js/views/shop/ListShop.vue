<template>
  <v-container>
    <v-row>
      <v-col
          class="py-0"
          cols="12"
      >
        <new-shop v-if="showNewModal"/>
        <edit-shop v-if="showEditModal"/>
        <show-shop v-if="showShowModal"/>
        <v-card>
          <app-data-table
              :headers="getTableColumns"
              :is-loading="isTableLoading"
              :items="shops"
              :manager="'shop'"
              :sort-by="['name']"
              :sort-desc="[false, true]"
              :title="
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.shop')
              ])
            "
              csv-filename="Shops"
              multi-sort
              @create-row="toogleNewModal(true)"
              @edit-row="openEditModal($event)"
              @delete-row="deleteShopHandler($event)"
          >
            <template v-slot:[`item.name`]="{ item }">
              <v-icon>mdi-shopping</v-icon>
              {{ item.name }}
            </template>
            <template v-slot:[`item.nameCountry`]="{ item }">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-chip
                      v-bind="attrs"
                      v-on="on"
                  >
                    <v-avatar left>
                      {{ item.countryFlag }}
                    </v-avatar>
                    {{ item.country }}
                  </v-chip>
                </template>
                <span>{{ item.nameCountry }}</span>
              </v-tooltip>
            </template>
          </app-data-table>
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
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'nameCountry',
          select_filter: true
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
      this.shops.length === 1
          ? this.$Swal.fire({
            title: this.$vuetify.lang.t('$vuetify.titles.no_action', [
              this.$vuetify.lang.t('$vuetify.actions.delete')
            ]),
            text: this.$vuetify.lang.t(
                '$vuetify.messages.error_delete_shop'
            ),
            icon: 'warning',
            confirmButtonText: this.$vuetify.lang.t(
                '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'red'
          })
          : this.$Swal
          .fire({
            title: this.$vuetify.lang.t(
                '$vuetify.titles.delete',
                [this.$vuetify.lang.t('$vuetify.menu.shop')]
            ),
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
            if (result.value) this.deleteShop(shopId)
          })
    }
  }
}
</script>

<style scoped></style>
