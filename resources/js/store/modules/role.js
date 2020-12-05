import role from '../../api/role'

const FETCHING_ACCESS = 'FETCHING_ACCESS'
const SWITCH_ACCESS_NEW_MODAL = 'SWITCH_ACCESS_NEW_MODAL'
const SWITCH_ACCESS_EDIT_MODAL = 'SWITCH_ACCESS_EDIT_MODAL'
const SWITCH_ACCESS_SHOW_MODAL = 'SWITCH_ACCESS_SHOW_MODAL'
const ACCESS_CREATED = 'ACCESS_CREATED'
const ACCESS_EDIT = 'ACCESS_EDIT'
const ACCESS_UPDATED = 'ACCESS_UPDATED'
const ACCESS_DELETE = 'ACCESS_DELETE'
const ACCESS_TABLE_LOADING = 'ACCESS_TABLE_LOADING'
const FAILED_ACCESS = 'FAILED_ACCESS'
const ENV_DATA_PROCESS = 'ENV_DATA_PROCESS'
const LOAD_KEY_CONST = 'LOAD_KEY_CONST'

const state = {
  showNewModal: false,
  showEditModal: false,
  showShowModal: false,
  roles: [],
  rolesToSelect: [],
  avatar: '',
  loading: false,
  saved: false,
  keys: [
  ],
  newAccess: {
    key: '',
    name: '',
    accessPin: false,
    accessEmail: false,
    description: '',
    access_permit: [

    ]
  },
  editAccess: {
    id: '',
    key: '',
    name: '',
    accessPin: false,
    accessEmail: false,
    description: ''
  },
  isAccessLoading: false,
  isActionInProgress: false,
  isTableLoading: false
}

const mutations = {
  [LOAD_KEY_CONST] (state) {
    state.keys = [
      {
        name: this._vm.$language.t('$vuetify.access.keys.supervisor'),
        value: 'supervisor',
        access_permit: [
          {
            title: {
              name: 'manager_article',
              value: false
            },
            actions: [
              {
                name: 'article_list',
                value: false
              },
              {
                name: 'article_add',
                value: false
              },
              {
                name: 'article_edit',
                value: false
              },
              {
                name: 'article_delete',
                value: false
              }
            ]

          },
          {
            title: {
              name: 'manager_vending',
              value: false
            },
            actions: [{
              name: 'vending_list',
              value: false
            },
            {
              name: 'vending_add',
              value: false
            }, {
              name: 'vending_edit',
              value: false
            }, {
              name: 'vending_delete',
              value: false
            }
            ]
          },
          {
            title: {
              name: 'manager_category',
              value: false
            },
            actions: [{
              name: 'category_list',
              value: false
            }, {
              name: 'category_add',
              value: false
            }, {
              name: 'category_edit',
              value: false
            }, {
              name: 'category_delete',
              value: false
            }
            ]
          },
          {
            title: {
              name: 'manager_mod',
              value: false
            },
            actions: [{
              name: 'mod_list',
              value: false
            }, {
              name: 'mod_add',
              value: false
            }, {
              name: 'mod_edit',
              value: false
            }, {
              name: 'mod_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_supplier',
              value: false
            },
            actions: [{
              name: 'supplier_list',
              value: false
            }, {
              name: 'supplier_add',
              value: false
            }, {
              name: 'supplier_edit',
              value: false
            }, {
              name: 'supplier_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_buy',
              value: false
            },
            actions: [{
              name: 'buy_list',
              value: false
            }, {
              name: 'buy_add',
              value: false
            }, {
              name: 'buy_edit',
              value: false
            }, {
              name: 'buy_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_sell',
              value: false
            },
            actions: [{
              name: 'sell_by_product',
              value: false
            }, {
              name: 'sell_by_category',
              value: false
            }, {
              name: 'sell_by_employer',
              value: false
            }, {
              name: 'sell_by_payments',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_emplyer',
              value: false
            },
            actions: [{
              name: 'employer_list',
              value: false
            }, {
              name: 'employer_add',
              value: false
            }, {
              name: 'employer_edit',
              value: false
            }, {
              name: 'employer_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_assistance',
              value: false
            },
            actions: [{
              name: 'assistance_list',
              value: false
            }, {
              name: 'assistance_add',
              value: false
            }, {
              name: 'assistance_edit',
              value: false
            }, {
              name: 'assistance_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_client',
              value: false
            },
            actions: [{
              name: 'client_list',
              value: false
            }, {
              name: 'client_add',
              value: false
            }, {
              name: 'client_edit',
              value: false
            }, {
              name: 'client_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_shop',
              value: false
            },
            actions: [{
              name: 'shop_list',
              value: false
            }, {
              name: 'shop_add',
              value: false
            }, {
              name: 'shop_edit',
              value: false
            }, {
              name: 'shop_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_access',
              value: false
            },
            actions: [{
              name: 'access_list',
              value: false
            }, {
              name: 'access_add',
              value: false
            }, {
              name: 'access_edit',
              value: false
            }, {
              name: 'access_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_payment',
              value: false
            },
            actions: [{
              name: 'payment_list',
              value: false
            }, {
              name: 'payment_add',
              value: false
            }, {
              name: 'payment_edit',
              value: false
            }, {
              name: 'payment_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_exchange_rate',
              value: false
            },
            actions: [{
              name: 'exchange_rate_list',
              value: false
            }, {
              name: 'exchange_rate_add',
              value: false
            }, {
              name: 'exchange_rate_edit',
              value: false
            }, {
              name: 'exchange_rate_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_type_of_order',
              value: false
            },
            actions: [{
              name: 'type_of_order_list',
              value: false
            }, {
              name: 'type_of_order_add',
              value: false
            }, {
              name: 'type_of_order_edit',
              value: false
            }, {
              name: 'type_of_order_delete',
              value: false
            }]
          }
        ]
      },
      {
        name: this._vm.$language.t('$vuetify.access.keys.atm'),
        value: 'atm',
        access_permit: [
          {
            title: {
              name: 'manager_article',
              value: false
            },
            actions: [
              {
                name: 'article_list',
                value: false
              },
              {
                name: 'article_add',
                value: false
              },
              {
                name: 'article_edit',
                value: false
              },
              {
                name: 'article_delete',
                value: false
              }
            ]

          },
          {
            title: {
              name: 'manager_vending',
              value: false
            },
            actions: [{
              name: 'vending_list',
              value: false
            },
            {
              name: 'vending_add',
              value: false
            }, {
              name: 'vending_edit',
              value: false
            }, {
              name: 'vending_delete',
              value: false
            }
            ]
          },
          {
            title: {
              name: 'manager_category',
              value: false
            },
            actions: [{
              name: 'category_list',
              value: false
            }, {
              name: 'category_add',
              value: false
            }, {
              name: 'category_edit',
              value: false
            }, {
              name: 'category_delete',
              value: false
            }
            ]
          },
          {
            title: {
              name: 'manager_mod',
              value: false
            },
            actions: [{
              name: 'mod_list',
              value: false
            }, {
              name: 'mod_add',
              value: false
            }, {
              name: 'mod_edit',
              value: false
            }, {
              name: 'mod_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_supplier',
              value: false
            },
            actions: [{
              name: 'supplier_list',
              value: false
            }, {
              name: 'supplier_add',
              value: false
            }, {
              name: 'supplier_edit',
              value: false
            }, {
              name: 'supplier_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_buy',
              value: false
            },
            actions: [{
              name: 'buy_list',
              value: false
            }, {
              name: 'buy_add',
              value: false
            }, {
              name: 'buy_edit',
              value: false
            }, {
              name: 'buy_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_sell',
              value: false
            },
            actions: [{
              name: 'sell_by_product',
              value: false
            }, {
              name: 'sell_by_category',
              value: false
            }, {
              name: 'sell_by_employer',
              value: false
            }, {
              name: 'sell_by_payments',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_emplyer',
              value: false
            },
            actions: [{
              name: 'employer_list',
              value: false
            }, {
              name: 'employer_add',
              value: false
            }, {
              name: 'employer_edit',
              value: false
            }, {
              name: 'employer_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_assistance',
              value: false
            },
            actions: [{
              name: 'assistance_list',
              value: false
            }, {
              name: 'assistance_add',
              value: false
            }, {
              name: 'assistance_edit',
              value: false
            }, {
              name: 'assistance_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_client',
              value: false
            },
            actions: [{
              name: 'client_list',
              value: false
            }, {
              name: 'client_add',
              value: false
            }, {
              name: 'client_edit',
              value: false
            }, {
              name: 'client_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_shop',
              value: false
            },
            actions: [{
              name: 'shop_list',
              value: false
            }, {
              name: 'shop_add',
              value: false
            }, {
              name: 'shop_edit',
              value: false
            }, {
              name: 'shop_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_access',
              value: false
            },
            actions: [{
              name: 'access_list',
              value: false
            }, {
              name: 'access_add',
              value: false
            }, {
              name: 'access_edit',
              value: false
            }, {
              name: 'access_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_payment',
              value: false
            },
            actions: [{
              name: 'payment_list',
              value: false
            }, {
              name: 'payment_add',
              value: false
            }, {
              name: 'payment_edit',
              value: false
            }, {
              name: 'payment_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_exchange_rate',
              value: false
            },
            actions: [{
              name: 'exchange_rate_list',
              value: false
            }, {
              name: 'exchange_rate_add',
              value: false
            }, {
              name: 'exchange_rate_edit',
              value: false
            }, {
              name: 'exchange_rate_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_type_of_order',
              value: false
            },
            actions: [{
              name: 'type_of_order_list',
              value: false
            }, {
              name: 'type_of_order_add',
              value: false
            }, {
              name: 'type_of_order_edit',
              value: false
            }, {
              name: 'type_of_order_delete',
              value: false
            }]
          }
        ]
      },
      {
        name: this._vm.$language.t('$vuetify.access.keys.waiter'),
        value: 'waiter',
        access_permit: [
          {
            title: {
              name: 'manager_article',
              value: false
            },
            actions: [
              {
                name: 'article_list',
                value: false
              },
              {
                name: 'article_add',
                value: false
              },
              {
                name: 'article_edit',
                value: false
              },
              {
                name: 'article_delete',
                value: false
              }
            ]

          },
          {
            title: {
              name: 'manager_vending',
              value: false
            },
            actions: [{
              name: 'vending_list',
              value: false
            },
            {
              name: 'vending_add',
              value: false
            }, {
              name: 'vending_edit',
              value: false
            }, {
              name: 'vending_delete',
              value: false
            }
            ]
          },
          {
            title: {
              name: 'manager_category',
              value: false
            },
            actions: [{
              name: 'category_list',
              value: false
            }, {
              name: 'category_add',
              value: false
            }, {
              name: 'category_edit',
              value: false
            }, {
              name: 'category_delete',
              value: false
            }
            ]
          },
          {
            title: {
              name: 'manager_mod',
              value: false
            },
            actions: [{
              name: 'mod_list',
              value: false
            }, {
              name: 'mod_add',
              value: false
            }, {
              name: 'mod_edit',
              value: false
            }, {
              name: 'mod_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_supplier',
              value: false
            },
            actions: [{
              name: 'supplier_list',
              value: false
            }, {
              name: 'supplier_add',
              value: false
            }, {
              name: 'supplier_edit',
              value: false
            }, {
              name: 'supplier_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_buy',
              value: false
            },
            actions: [{
              name: 'buy_list',
              value: false
            }, {
              name: 'buy_add',
              value: false
            }, {
              name: 'buy_edit',
              value: false
            }, {
              name: 'buy_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_sell',
              value: false
            },
            actions: [{
              name: 'sell_by_product',
              value: false
            }, {
              name: 'sell_by_category',
              value: false
            }, {
              name: 'sell_by_employer',
              value: false
            }, {
              name: 'sell_by_payments',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_emplyer',
              value: false
            },
            actions: [{
              name: 'employer_list',
              value: false
            }, {
              name: 'employer_add',
              value: false
            }, {
              name: 'employer_edit',
              value: false
            }, {
              name: 'employer_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_assistance',
              value: false
            },
            actions: [{
              name: 'assistance_list',
              value: false
            }, {
              name: 'assistance_add',
              value: false
            }, {
              name: 'assistance_edit',
              value: false
            }, {
              name: 'assistance_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_client',
              value: false
            },
            actions: [{
              name: 'client_list',
              value: false
            }, {
              name: 'client_add',
              value: false
            }, {
              name: 'client_edit',
              value: false
            }, {
              name: 'client_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_shop',
              value: false
            },
            actions: [{
              name: 'shop_list',
              value: false
            }, {
              name: 'shop_add',
              value: false
            }, {
              name: 'shop_edit',
              value: false
            }, {
              name: 'shop_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_access',
              value: false
            },
            actions: [{
              name: 'access_list',
              value: false
            }, {
              name: 'access_add',
              value: false
            }, {
              name: 'access_edit',
              value: false
            }, {
              name: 'access_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_payment',
              value: false
            },
            actions: [{
              name: 'payment_list',
              value: false
            }, {
              name: 'payment_add',
              value: false
            }, {
              name: 'payment_edit',
              value: false
            }, {
              name: 'payment_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_exchange_rate',
              value: false
            },
            actions: [{
              name: 'exchange_rate_list',
              value: false
            }, {
              name: 'exchange_rate_add',
              value: false
            }, {
              name: 'exchange_rate_edit',
              value: false
            }, {
              name: 'exchange_rate_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_type_of_order',
              value: false
            },
            actions: [{
              name: 'type_of_order_list',
              value: false
            }, {
              name: 'type_of_order_add',
              value: false
            }, {
              name: 'type_of_order_edit',
              value: false
            }, {
              name: 'type_of_order_delete',
              value: false
            }]
          }
        ]
      },
      {
        name: this._vm.$language.t('$vuetify.access.keys.seller'),
        value: 'seller',
        access_permit: [
          {
            title: {
              name: 'manager_article',
              value: false
            },
            actions: [
              {
                name: 'article_list',
                value: false
              },
              {
                name: 'article_add',
                value: false
              },
              {
                name: 'article_edit',
                value: false
              },
              {
                name: 'article_delete',
                value: false
              }
            ]

          },
          {
            title: {
              name: 'manager_vending',
              value: false
            },
            actions: [{
              name: 'vending_list',
              value: false
            },
            {
              name: 'vending_add',
              value: false
            }, {
              name: 'vending_edit',
              value: false
            }, {
              name: 'vending_delete',
              value: false
            }
            ]
          },
          {
            title: {
              name: 'manager_category',
              value: false
            },
            actions: [{
              name: 'category_list',
              value: false
            }, {
              name: 'category_add',
              value: false
            }, {
              name: 'category_edit',
              value: false
            }, {
              name: 'category_delete',
              value: false
            }
            ]
          },
          {
            title: {
              name: 'manager_mod',
              value: false
            },
            actions: [{
              name: 'mod_list',
              value: false
            }, {
              name: 'mod_add',
              value: false
            }, {
              name: 'mod_edit',
              value: false
            }, {
              name: 'mod_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_supplier',
              value: false
            },
            actions: [{
              name: 'supplier_list',
              value: false
            }, {
              name: 'supplier_add',
              value: false
            }, {
              name: 'supplier_edit',
              value: false
            }, {
              name: 'supplier_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_buy',
              value: false
            },
            actions: [{
              name: 'buy_list',
              value: false
            }, {
              name: 'buy_add',
              value: false
            }, {
              name: 'buy_edit',
              value: false
            }, {
              name: 'buy_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_sell',
              value: false
            },
            actions: [{
              name: 'sell_by_product',
              value: false
            }, {
              name: 'sell_by_category',
              value: false
            }, {
              name: 'sell_by_employer',
              value: false
            }, {
              name: 'sell_by_payments',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_emplyer',
              value: false
            },
            actions: [{
              name: 'employer_list',
              value: false
            }, {
              name: 'employer_add',
              value: false
            }, {
              name: 'employer_edit',
              value: false
            }, {
              name: 'employer_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_assistance',
              value: false
            },
            actions: [{
              name: 'assistance_list',
              value: false
            }, {
              name: 'assistance_add',
              value: false
            }, {
              name: 'assistance_edit',
              value: false
            }, {
              name: 'assistance_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_client',
              value: false
            },
            actions: [{
              name: 'client_list',
              value: false
            }, {
              name: 'client_add',
              value: false
            }, {
              name: 'client_edit',
              value: false
            }, {
              name: 'client_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_shop',
              value: false
            },
            actions: [{
              name: 'shop_list',
              value: false
            }, {
              name: 'shop_add',
              value: false
            }, {
              name: 'shop_edit',
              value: false
            }, {
              name: 'shop_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_access',
              value: false
            },
            actions: [{
              name: 'access_list',
              value: false
            }, {
              name: 'access_add',
              value: false
            }, {
              name: 'access_edit',
              value: false
            }, {
              name: 'access_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_payment',
              value: false
            },
            actions: [{
              name: 'payment_list',
              value: false
            }, {
              name: 'payment_add',
              value: false
            }, {
              name: 'payment_edit',
              value: false
            }, {
              name: 'payment_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_exchange_rate',
              value: false
            },
            actions: [{
              name: 'exchange_rate_list',
              value: false
            }, {
              name: 'exchange_rate_add',
              value: false
            }, {
              name: 'exchange_rate_edit',
              value: false
            }, {
              name: 'exchange_rate_delete',
              value: false
            }]
          },
          {
            title: {
              name: 'manager_type_of_order',
              value: false
            },
            actions: [{
              name: 'type_of_order_list',
              value: false
            }, {
              name: 'type_of_order_add',
              value: false
            }, {
              name: 'type_of_order_edit',
              value: false
            }, {
              name: 'type_of_order_delete',
              value: false
            }]
          }
        ]
      }
    ]
  },
  [SWITCH_ACCESS_NEW_MODAL] (state, showModal) {
    state.showNewModal = showModal
  },
  [SWITCH_ACCESS_EDIT_MODAL] (state, showModal) {
    state.showEditModal = showModal
  },
  [SWITCH_ACCESS_SHOW_MODAL] (state, showModal) {
    state.showShowModal = showModal
  },
  [ACCESS_TABLE_LOADING] (state, isLoading) {
    state.isTableLoading = isLoading
    state.isAccessLoading = isLoading
  },
  [FETCHING_ACCESS] (state, roles) {
    state.roles = roles
  },
  [ENV_DATA_PROCESS] (state, isActionInProgress) {
    state.isActionInProgress = isActionInProgress
  },
  [ACCESS_CREATED] (state) {
    state.showNewModal = false
    state.newAccess = {
      key: '',
      name: '',
      accessPin: false,
      accessEmail: false,
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_add', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [ACCESS_EDIT] (state, roleId) {
    state.editAccess = Object.assign({}, state.roles
      .filter(node => node.id === roleId)
      .shift()
    )
  },
  [ACCESS_UPDATED] (state) {
    state.showEditModal = false
    state.editAccess = {
      id: '',
      key: '',
      name: '',
      accessPin: false,
      accessEmail: false,
      description: ''
    }
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_up', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [ACCESS_DELETE] (state) {
    state.saved = true
    this._vm.$Toast.fire({
      icon: 'success',
      title: this._vm.$language.t(
        '$vuetify.messages.success_del', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  },
  [FAILED_ACCESS] (state, error) {
    state.saved = false
    state.error = error
    state.isAccessLoading = false
    state.isActionInProgress = false
    state.isTableLoading = false
    this._vm.$Toast.fire({
      icon: 'error',
      title: this._vm.$language.t(
        '$vuetify.messages.failed_catch', [this._vm.$language.t('$vuetify.menu.access')]
      )
    })
  }
}

const getters = {}

const actions = {
  toogleNewModal ({ commit }, showModal) {
    commit(LOAD_KEY_CONST)
    commit(SWITCH_ACCESS_NEW_MODAL, showModal)
  },
  toogleEditModal ({ commit }, showModal) {
    commit(LOAD_KEY_CONST)
    commit(SWITCH_ACCESS_EDIT_MODAL, showModal)
  },
  toogleShowModal ({ commit }, showModal) {
    commit(SWITCH_ACCESS_SHOW_MODAL, showModal)
  },
  openEditModal ({ commit }, roleId) {
    commit(SWITCH_ACCESS_EDIT_MODAL, true)
    commit(ACCESS_EDIT, roleId)
  },
  openShowModal ({ commit }, roleId) {
    commit(SWITCH_ACCESS_SHOW_MODAL, true)
    commit(ACCESS_EDIT, roleId)
  },
  async getRoles ({ commit }) {
    commit(ACCESS_TABLE_LOADING, true)
    // noinspection JSUnresolvedVariable
    await role
      .fetchRoles()
      .then(({ data }) => {
        commit(FETCHING_ACCESS, data.data)
        commit(ACCESS_TABLE_LOADING, false)
      })
      .catch(error => commit(FAILED_ACCESS, error))
  },
  loadKeysPermitConst ({ commit }) {
    commit(LOAD_KEY_CONST)
  },
  async createRole ({ commit, dispatch }, newAccess) {
    commit(ENV_DATA_PROCESS, true)

    await role
      .sendCreateRequest(newAccess)
      .then(() => {
        commit(ACCESS_CREATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch(error => commit(FAILED_ACCESS, error))
  },
  async updateRole ({ commit, dispatch }, editAccess) {
    commit(ENV_DATA_PROCESS, true)

    await role
      .sendUpdateRequest(editAccess)
      .then(() => {
        commit(ACCESS_UPDATED)
        commit(ENV_DATA_PROCESS, false)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch(error => commit(FAILED_ACCESS, error))
  },
  async deleteRole ({ commit, dispatch }, roleId) {
    await role
      .sendDeleteRequest(roleId)
      .then(() => {
        commit(ACCESS_DELETE)
        dispatch('role/getRoles', null, { root: true })
      })
      .catch(error => commit(FAILED_ACCESS, error))
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
