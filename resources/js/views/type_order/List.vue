<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-type-order v-if="showNewModal" />
        <edit-type-order v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.menu.type_of_order')"
          csv-filename="TypeOrder"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="typeOrders"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="openEditModal($event)"
          @delete-row="deleteHandler($event)"
        >
          <template v-slot:item.shopsNames="{ item }">
            <v-chip
              v-for="(shop, i) of item.shopsNames"
              :key="i"
            >
              {{ shop }}
            </v-chip>
          </template>
          <template v-slot:item.principal="{ item }">
            <v-switch
              v-model="item.principal"
              inset
              @change="setPrincipal(item)"
            />
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex'
import NewTypeOrder from './New'
import EditTypeOrder from './Edit'

export default {
  components: {
    NewTypeOrder,
    EditTypeOrder
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('typeOrder', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'typeOrders',
      'isTableLoading'
    ]),
    ...mapGetters('auth', ['user']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.name'),
          value: 'name',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shopsNames',
          select_filter_many: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.principal'),
          value: 'principal',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.description'),
          value: 'description'
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
    this.getTypeOfOrders()
  },
  methods: {
    ...mapActions('typeOrder', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getTypeOfOrders',
      'setPrincipalTypeOrder',
      'deleteTypeOrder'
    ]),
    deleteHandler (id) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.type_of_order')
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
          if (result.value) this.deleteTypeOrder(id)
        })
    },
    setPrincipal (item) {
      if (item.principal) {
        this.setPrincipalTypeOrder(item)
      }
    }
  }
}
</script>

<style scoped></style>
