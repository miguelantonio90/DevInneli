<template>
  <v-container>
    <v-row>
      <v-col
        class="py-0"
        cols="12"
      >
        <new-exchange-rate v-if="showNewModal" />
        <edit-exchange-rate v-if="showEditModal" />
        <app-data-table
          :title="$vuetify.lang.t('$vuetify.menu.exchange_rate_list')"
          csv-filename="ExchangeRate"
          :headers="getTableColumns"
          :is-loading="isTableLoading"
          :items="changes"
          :manager="'exchange_rate'"
          :sort-by="['country']"
          :sort-desc="[false, true]"
          multi-sort
          @create-row="toogleNewModal(true)"
          @edit-row="openEditModal($event)"
          @delete-row="deleteHandler($event)"
        >
          <template slot="subtitle">
            <span style="color: darkred">
              {{
                `${$vuetify.lang.t('$vuetify.messages.info_exchange_rate')} ${user.company.currency}`
              }}
            </span>
          </template>
        </app-data-table>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex'
import NewExchangeRate from './New'
import EditExchangeRate from './Edit'

export default {
  name: 'ListExchangeRate',
  components: {
    NewExchangeRate,
    EditExchangeRate
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    ...mapState('exchangeRate', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'changes',
      'isTableLoading'
    ]),
    ...mapGetters('auth', ['user']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'country',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.currency'),
          value: 'currency'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.change'),
          value: 'change'
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
    this.getChanges()
  },
  methods: {
    ...mapActions('exchangeRate', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getChanges',
      'deleteChange'
    ]),
    deleteHandler (id) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.exchange_rate')
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
          if (result.value) this.deleteChange(id)
        })
    }
  }
}
</script>

<style scoped></style>
