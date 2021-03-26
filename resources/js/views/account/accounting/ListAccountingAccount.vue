<template>
  <div>
    <app-loading v-if="loading" />
    <new-accounting-account v-if="showNewAccountModal" />
    <edit-accounting-account v-if="showEditAccountModal" />
    <new-move
      v-if="showNewMoveModal"
      :account-parent="parent_id"
    />
    <v-container v-if="!loading">
      <v-row>
        <v-col
          class="py-0"
          cols="12"
        >
          <app-data-table
            :title="
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.accountings')
              ])
            "
            csv-filename="Categories"
            :headers="getTableColumns"
            :items="accounts"
            :manager="'client'"
            :sort-by="['firstName']"
            :sort-desc="[false, true]"
            multi-sort
            @create-row="toogleNewAccountsModal(true)"
            @edit-row="editMoveHandler($event)"
            @delete-row="deleteMoveHandler($event)"
          >
            <template v-slot:[`item.name`]="{ item }">
              <template>
                <v-tooltip
                  top
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon
                      class="mr-2"
                      small
                      color="primary"
                      v-bind="attrs"
                      v-on="on"
                      @click="addAccountingMove(item)"
                    >
                      mdi-tag-plus
                    </v-icon>
                  </template>
                  <span>{{ $vuetify.lang.t('$vuetify.actions.created', [$vuetify.lang.t('$vuetify.menu.account_move')]) }}</span>
                </v-tooltip>
                <v-tooltip
                  top
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon
                      class="mr-2"
                      small
                      color="primary"
                      v-bind="attrs"
                      v-on="on"
                      @click="listMoveByCategory(item.id)"
                    >
                      mdi-tag-multiple
                    </v-icon>
                  </template>
                  <span>{{ $vuetify.lang.t('$vuetify.actions.show_p', [$vuetify.lang.t('$vuetify.menu.account_moves')]) }}</span>
                </v-tooltip>
              </template>
              {{ item.name }}
            </template>
            <template v-slot:[`item.category.nature`]="{ item }">
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <span
                    v-bind="attrs"
                    v-on="on"
                  >
                    {{ $vuetify.lang.t('$vuetify.accounting_category.' + item.category.nature) }}</span>
                </template>
                <span style="max-width: 200px">
                  <h4>{{ $vuetify.lang.t('$vuetify.accounting_category.' + item.category.nature) }}</h4>
                  <p>{{ $vuetify.lang.t('$vuetify.accounting_category.description_nature.' + item.category.nature) }}</p>
                </span>
              </v-tooltip>
            </template>
          </app-data-table>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewAccountingAccount from './NewAccountingAccount'
import EditAccountingAccount from './EditAccountingAccount'
import NewMove from '../move/NewMove'

export default {
  name: 'ListAccountingAccount',
  components: { NewAccountingAccount, EditAccountingAccount, NewMove },
  data () {
    return {
      parent_id: '',
      loading: false
    }
  },
  computed: {
    ...mapState('account', [
      'showNewAccountModal',
      'showEditAccountModal',
      'accounts',
      'isTableLoading'
    ]),
    ...mapState('accountMove', [
      'showNewMoveModal'
    ]),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          value: 'name',
          select_filter: true
        }, {
          text: this.$vuetify.lang.t('$vuetify.accounting_category.nature'),
          value: 'category.nature',
          select_filter: true
        }, {
          text: this.$vuetify.lang.t('$vuetify.boxes.init'),
          value: 'init_balance',
          select_filter: true
        }, {
          text: this.$vuetify.lang.t('$vuetify.boxes.current'),
          value: 'current_balance',
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
  async created () {
    this.loading = true
    await this.getAccounts()
    this.loading = false
  },
  methods: {
    ...mapActions('account', ['getAccounts', 'toogleNewAccountsModal', 'openEditAccountsModal', 'deleteAccount']),
    ...mapActions('accountMove', [
      'toogleNewMovesModal'
    ]),
    addAccountingMove (item) {
      this.parent_id = item
      console.log(item)
      this.toogleNewMovesModal(true)
    },
    listMoveByCategory ($id) {
      this.$router.push({ name: 'account_move_by_category', params: { account: $id } })
    },
    editMoveHandler ($id) {
      this.openEditAccountsModal(this.accounts.filter(acc => acc.id === $id)[0])
    },
    deleteMoveHandler ($id) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.accountings')
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
          if (result.value) {
            this.deleteAccount($id).then(() => {
              this.getAccounts()
            })
          }
        })
    }
  }
}
</script>

<style scoped>

</style>
