<template>
  <v-container>
    <new-account-category
      v-if="showNewModal"
      :parent-id="parent_id"
    />
    <edit-account-category v-if="showEditModal" />
    <new-accounting-account
      v-if="showNewAccountModal"
      :category-id="parent_id"
    />
    <edit-accounting-account v-if="showEditAccountModal" />
    <new-move
      v-if="showNewMoveModal"
      :account-parent="parent_id"
    />
    <v-card>
      <v-card-title>
        <v-list-item two-line>
          <v-list-item>
            <v-list-item-title>
              {{ $vuetify.lang.t('$vuetify.menu.accounting_category_list') }}
            </v-list-item-title>
            <v-list-item-icon>
              <v-btn
                class="mb-2"
                color="primary"
                @click="toogleNewModal(true); parent_id=''"
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
          cols="12"
          md="12"
        >
          <category-accounting
            :account="tree"
            @addSubCategory="addSubCategory"
            @editCategoryHandler="editCategoryHandler"
            @deleteCategoryHandler="deleteCategoryHandler"
            @addAccountingAccount="addAccountingAccount"
            @editAccountHandler="editAccountHandler"
            @deleteAccountHandler="deleteAccountHandler"
            @addAccountingMove="addAccountingMove"
            @listMoveByCategory="listMoveByCategory"
          />
        </v-col>
      </v-row>
    </v-card>
  </v-container>
</template>
<script>
import { mapActions, mapState } from 'vuex'
import CategoryAccounting from './CategoryAccounting'
import NewAccountCategory from './NewAccountCategory'
import EditAccountCategory from './EditAccountCategory'
import NewAccountingAccount from '../accounting/NewAccountingAccount'
import NewMove from '../move/NewMove'
import EditAccountingAccount from '../accounting/EditAccountingAccount'

export default {
  name: 'ListAccountCategory',
  components: { EditAccountingAccount, NewMove, NewAccountingAccount, EditAccountCategory, NewAccountCategory, CategoryAccounting },
  data () {
    return {
      parent_id: ''
    }
  },
  computed: {
    ...mapState('accountCategory', [
      'showNewModal',
      'showEditModal',
      'showShowModal',
      'categories',
      'tree',
      'isTableLoading'
    ]),
    ...mapState('account', [
      'showNewAccountModal',
      'showEditAccountModal',
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
  async created () {
    await this.getTree()
  },
  methods: {
    ...mapActions('accountCategory', [
      'toogleNewModal',
      'openEditModal',
      'openShowModal',
      'getTree',
      'deleteCategory'
    ]),
    ...mapActions('account', [
      'toogleNewAccountsModal',
      'openEditAccountsModal',
      'deleteAccount'
    ]),
    ...mapActions('accountMove', [
      'toogleNewMovesModal'
    ]),
    showArticlesByCategory (accountId) {
      this.$router.push({ name: 'product_by_account', params: { accountId } })
    },
    addAccountingAccount (category) {
      this.parent_id = category
      this.toogleNewAccountsModal(true)
    },
    addAccountingMove (parent) {
      this.parent_id = parent
      console.log(parent)
      this.toogleNewMovesModal(true)
    },
    listMoveByCategory (parent) {
      this.$router.push({ name: 'account_move_by_category', params: { account: parent } })
    },
    addSubCategory (parent) {
      this.parent_id = parent.id
      this.toogleNewModal(true)
    },
    editCategoryHandler ($event) {
      this.openEditModal($event)
    },
    editAccountHandler ($event) {
      console.log($event)
      this.openEditAccountsModal($event)
    },
    deleteCategoryHandler (categoryId) {
      this.categories.filter(cat => cat.id === categoryId)[0].default_category
        ? this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.delete', [
              this.$vuetify.lang.t('$vuetify.menu.account_classify')
            ]),
            text: this.$vuetify.lang.t(
              '$vuetify.messages.no_delete_default'
            ),
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: this.$vuetify.lang.t(
              '$vuetify.actions.accept'
            ),
            confirmButtonColor: 'primary'
          })
        : this.$Swal
          .fire({
            title: this.$vuetify.lang.t('$vuetify.titles.delete', [
              this.$vuetify.lang.t('$vuetify.menu.account_classify')
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
          })
    },
    deleteAccountHandler (accountId) {
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.menu.accounting')
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
          if (result.value) this.deleteAccount(accountId)
        })
    }
  }
}
</script>

<style scoped>

</style>
