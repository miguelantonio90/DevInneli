<template>
  <v-card>
    <v-card-text>
      <v-treeview
        :active.sync="active"
        :items="accounts"
        :open.sync="open"
        activatable
        open-on-click
        transition
      >
        <template v-slot:label="{ item }">
          <v-list-item two-line>
            <v-list-item-title>
              <span
                v-if="item.id"
                class="subtitle-2 font-weight-light"
              >
                {{ item.category_id? item.code +'- ' + item.name: item.default_category ?
                  $vuetify.lang.t('$vuetify.accounting_category.' + item.name) + '('+ $vuetify.lang.t('$vuetify.accounting_category.' + item.nature ) +')':
                  item.name + '('+ $vuetify.lang.t('$vuetify.accounting_category.' + item.nature) +')' }}</span>
              <span v-else>{{ item.name }}</span>
            </v-list-item-title>

            <v-list-item-icon v-if="item.id !== ''">
              <v-spacer />
              <v-divider />
              <v-tooltip
                v-if="!item.category_id"
                top
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="mr-2"
                    small
                    color="primary"
                    v-bind="attrs"
                    v-on="on"
                    @click="$emit('addSubCategory', item)"
                  >
                    mdi-folder-plus
                  </v-icon>
                </template>
                <span>{{ $vuetify.lang.t('$vuetify.actions.created', [$vuetify.lang.t('$vuetify.menu.sub_file')]) }}</span>
              </v-tooltip>
              <v-tooltip
                v-if="!item.category_id"
                top
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="mr-2"
                    small
                    color="primary"
                    v-bind="attrs"
                    v-on="on"
                    @click="$emit('addAccountingAccount', item.id)"
                  >
                    mdi-bookmark-plus-outline
                  </v-icon>
                </template>
                <span>{{ $vuetify.lang.t('$vuetify.actions.created', [$vuetify.lang.t('$vuetify.menu.accounting')]) }}</span>
              </v-tooltip>
              <v-tooltip
                v-if="item.category_id"
                top
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="mr-2"
                    small
                    color="primary"
                    v-bind="attrs"
                    v-on="on"
                    @click="$emit('addAccountingMove', item)"
                  >
                    mdi-tag-plus
                  </v-icon>
                </template>
                <span>{{ $vuetify.lang.t('$vuetify.actions.created', [$vuetify.lang.t('$vuetify.menu.account_move')]) }}</span>
              </v-tooltip>
              <v-tooltip
                v-if="item.category_id"
                top
              >
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="mr-2"
                    small
                    color="primary"
                    v-bind="attrs"
                    v-on="on"
                    @click="$emit('listMoveByCategory', item.id)"
                  >
                    mdi-tag-multiple
                  </v-icon>
                </template>
                <span>{{ $vuetify.lang.t('$vuetify.actions.show', [$vuetify.lang.t('$vuetify.menu.account_moves')]) }}</span>
              </v-tooltip>
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="mr-2"
                    small
                    color="warning"
                    v-bind="attrs"
                    v-on="on"

                    @click="!item.category_id ? $emit('editCategoryHandler', item.id):$emit('editAccountHandler', item)"
                  >
                    mdi-pencil
                  </v-icon>
                </template>
                <span>{{ $vuetify.lang.t('$vuetify.actions.edit', item.category_id?[$vuetify.lang.t('$vuetify.menu.account_move')]:[$vuetify.lang.t('$vuetify.menu.account_classify')]) }}</span>
              </v-tooltip>
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    class="mr-2"
                    color="error"
                    small
                    v-bind="attrs"
                    v-on="on"
                    @click="!item.category_id ? $emit('deleteCategoryHandler', item.id):$emit('deleteAccountHandler', item.id)"
                  >
                    mdi-delete
                  </v-icon>
                </template>
                <span>{{ $vuetify.lang.t('$vuetify.actions.delete', item.category_id?[$vuetify.lang.t('$vuetify.menu.account_move')]:[$vuetify.lang.t('$vuetify.menu.account_classify')]) }}</span>
              </v-tooltip>
            </v-list-item-icon>
          </v-list-item>
        </template>
        <template v-slot:prepend="{ item, open }">
          <v-icon v-if="!item.file">
            {{ open ? 'mdi-folder-open' : item.category_id ?
              'mdi-bookmark-outline':'mdi-folder' }}
          </v-icon>
          <v-icon
            v-else
          >
            {{ files[item.file] }}
          </v-icon>
        </template>
      </v-treeview>
    </v-card-text>
  </v-card>
</template>
<script>
import { mapState, mapActions } from 'vuex'

const pause = ms => new Promise(resolve => setTimeout(resolve, ms))

export default {
  name: 'CategoryAccounting',
  props: {
    account: {
      type: Array,
      default: function () {
        return []
      }
    }
  },
  data: () => ({
    active: [],
    avatar: null,
    open: [1]
  }),
  computed: {
    accounts () {
      const result = {
        name: this.$vuetify.lang.t('$vuetify.menu.account_classify'),
        children: this.account,
        id: ''
      }
      return [
        result
      ]
    },
    selected () {
      if (!this.active.length) return undefined
      const id = this.active[0]
      return this.account.accounts.find(act => act.id === id)
    }
  }
}
</script>

<style scoped>

</style>
