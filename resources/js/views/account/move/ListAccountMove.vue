<template>
  <div>
    <app-loading v-if="loading" />
    <v-container v-if="!loading">
      <new-move
        v-if="showNewMoveModal"
        :account-parent="editAccount"
      />
      <v-app-bar
        v-if="editAccount.id"
        flat
        dense
        color="rgba(0, 0, 0, 0)"
      >
        <h3>{{ $vuetify.lang.t('$vuetify.menu.accounting') + ': ' + editAccount.name }}</h3>
        <v-spacer />
        <h3>{{ $vuetify.lang.t('$vuetify.boxes.init') + ': ' + parseFloat(editAccount.init_balance).toFixed(2) }}</h3>
        <v-spacer />
        <h3>{{ $vuetify.lang.t('$vuetify.boxes.current') + ': ' + parseFloat(editAccount.current_balance).toFixed(2) }}</h3>
      </v-app-bar>
      <v-row>
        <v-col
          class="py-0"
          cols="12"
        >
          <app-data-table
            :title="
              $vuetify.lang.t('$vuetify.titles.list', [
                $vuetify.lang.t('$vuetify.menu.account_moves')
              ])
            "
            csv-filename="Categories"
            :headers="getTableColumns"
            :items="moves"
            :manager="'client'"
            :sort-by="['firstName']"
            :sort-desc="[false, true]"
            multi-sort
            @create-row="toogleNewMovesModal(true)"
            @edit-row="editMoveHandler($event)"
            @delete-row="deleteMoveHandler($event)"
          >
            <template v-slot:[`item.firstName`]="{ item }">
              <v-avatar>
                <v-img
                  :src="
                    item.avatar ||
                      `/assets/avatar/avatar-undefined.jpg`
                  "
                />
              </v-avatar>
              {{ item.firstName }}
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
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import NewMove from './NewMove'

export default {
  name: 'ListAccountMove',
  components: { NewMove },
  data () {
    return {
      loading: false
    }
  },
  computed: {
    ...mapState('accountMove', [
      'showNewMoveModal',
      'showEditMoveModal',
      'moves',
      'isTableLoading'
    ]),
    ...mapState('account', ['editAccount']),
    getTableColumns () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.date'),
          value: 'date',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.accounting_category.assets'),
          value: 'credit',
          select_filter: true
        },
        {
          text: this.$vuetify.lang.t('$vuetify.accounting_category.debit'),
          value: 'debit'
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
    await this.getEditAccount(this.$route.params.account)
    await this.getMovesByAccount(this.$route.params)
    this.loading = false
  },
  methods: {
    ...mapActions('accountMove', ['getMovesByAccount', '', 'toogleNewMovesModal', 'openEditMovesModal', 'deleteMove']),
    ...mapActions('account', ['getEditAccount']),
    editMoveHandler ($id) {
      console.log('editMoveHandler')
    },
    deleteMoveHandler ($id) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.account_move')
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
            this.deleteMove($id).then(() => {
              this.getEditAccount(this.$route.params.account).then((ac) => {
                this.editAccount = ac
              })
              this.getMovesByAccount(this.$route.params)
            })
          }
        })
    }
  }
}
</script>

<style scoped>

</style>
