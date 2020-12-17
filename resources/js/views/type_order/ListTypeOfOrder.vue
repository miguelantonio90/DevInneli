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
          :manager="'type_of_order'"
          :sort-by="['name']"
          :sort-desc="[false, true]"
          :options="bindProps"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="openEditModal($event)"
          @delete-row="deleteHandler($event)"
        >
          <template v-slot:[`group.header`]="{items, isOpen, toggle}">
            <th colspan="100%">
              <v-icon
                @click="toggle"
              >
                {{ isOpen ? 'mdi-minus' : 'mdi-plus' }}
              </v-icon>
              {{ $vuetify.lang.t('$vuetify.panel.shop').toUpperCase()+': '+ items[0].shopName }}
            </th>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'
import NewTypeOrder from './New'
import EditTypeOrder from './Edit'

export default {
  name: 'ListTypeOfOrder',
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
    bindProps () {
      return {
        itemKey: Math.random().toString(),
        groupBy: 'shopName'
      }
    },
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.name'),
          value: 'name',
          select_filter: true,
          groupable: false
        },
        {
          text: this.$vuetify.lang.t('$vuetify.menu.shop'),
          value: 'shopName',
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
