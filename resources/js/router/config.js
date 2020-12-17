import {
  LayoutAuth,
  LayoutDefault,
  RouteWrapper,
  LayoutVerify,
  LayoutLock/*,
  LayoutSales */
} from '../components/layouts'

export const publicRoute = [
  {
    path: '*',
    children: [],
    component: () => import('../views/error/NotFound')
  },
  {
    path: '/auth',
    component: LayoutAuth,
    meta: {
      title: 'Register'
    },
    redirect: '/auth/register',
    hidden: true,
    children: [
      {
        path: 'register',
        name: 'register',
        meta: {
          title: 'Register'
        },
        component: () => import('../views/auth/Register')
      }
    ]
  },
  {
    path: '/auth',
    component: LayoutAuth,
    meta: {
      title: 'Login'
    },
    redirect: '/auth/login',
    hidden: true,
    children: [
      {
        path: 'login',
        name: 'login',
        meta: {
          title: 'Login'
        },
        component: () => import('../views/auth/Login')
      }
    ]
  },
  {
    path: '/auth',
    component: LayoutAuth,
    meta: {
      title: 'Forgot'
    },
    redirect: '/auth/forgot',
    hidden: true,
    children: [
      {
        path: 'forgot',
        name: 'forgot',
        meta: {
          title: 'Forgot Password'
        },
        component: () => import('../views/auth/Forgot')
      }
    ]
  },
  {
    path: '/404',
    name: '404',
    hidden: true,
    meta: {
      title: 'Not Found'
    },

    children: [],
    component: () => import('../views/error/NotFound')
  },
  {
    path: '/500',
    name: '500',
    hidden: true,
    meta: {
      title: 'Server Error'
    },
    children: [],
    component: () => import('../views/error/Error')
  },
  {
    path: '/password/reset/:hash',
    component: LayoutAuth,
    hidden: true,
    children: [
      {
        path: '/password/reset/:hash',
        name: 'reset_password',
        meta: {
          title: 'Reset Password'
        },
        component: () => import('../views/auth/ResetPassword')
      }
    ]
  }
]

export const protectedRoute = [
  {
    path: '/',
    component: LayoutDefault,
    meta: {
      title: 'home',
      group: 'apps',
      icon: ''
    },
    redirect: '/dashboard',
    children: [
      {
        path: '/dashboard',
        name: 'dashboard',
        access: ['dashboard'],
        meta: {
          title: 'dashboard',
          group: 'apps',
          icon: 'mdi-view-dashboard',
          requiresAuth: true
        },
        children: [],
        component: () => import('../views/Dashboard')
      },
      {
        path: '/403',
        name: 'Forbidden',
        access: ['Forbidden'],
        meta: {
          title: 'access_denied',
          hiddenInMenu: true,
          requiresAuth: true
        },
        children: [],
        component: () => import('../views/error/Deny')
      },
      {
        path: '/user/profile',
        name: 'Profile',
        access: ['profile'],
        meta: {
          title: 'profile',
          hiddenInMenu: true,
          requiresAuth: true
        },
        children: [],
        component: () => import('../views/auth/Profile')
      },
      {
        path: '/sales',
        component: RouteWrapper,
        access: ['manager_vending'],
        redirect: '/sales/vending.list',
        meta: {
          title: 'vending',
          icon: 'mdi-cash-usd',
          group: 'sales'
        },
        children: [
          {
            path: '/sales/vending.list',
            name: 'vending',
            access: 'vending_list',
            meta: {
              title: 'vending',
              hiddenInMenu: false,
              requiresAuth: true
            },
            component: () => import('../views/sales/ListSale.vue')
          },
          {
            path: '/sales/vending/new',
            name: 'vending_new',
            access: 'vending_add',
            meta: {
              title: 'vending_new',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/sales/NewSale')
          },
          {
            path: '/sales/vending/edit',
            name: 'vending_edit',
            access: 'vending_edit',
            meta: {
              title: 'vending_edit',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/sales/EditSale')
          }
        ]
      },
      {
        path: '/articles',
        component: RouteWrapper,
        access: ['manager_article', 'manager_category', 'manager_mod'],
        redirect: '/articles/product.list',
        meta: {
          title: 'articles',
          icon: 'mdi-shopping',
          requiresAuth: true,
          group: 'articles'
        },
        children: [
          {
            path: '/articles/product.list',
            name: 'product_list',
            access: 'article_list',
            meta: {
              title: 'product_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/article/ListArticle.vue')
          },
          {
            path: '/articles/category.list',
            name: 'category_list',
            access: 'category_list',
            meta: {
              title: 'category_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/category/ListCategory')
          },
          {
            path: '/articles/modifiers.list',
            name: 'modifiers_list',
            access: 'mod_list',
            meta: {
              title: 'modifiers_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/error/Deny')
          },
          {
            path: '/articles/discounts.list',
            name: 'discounts_list',
            meta: {
              title: 'discounts_list',
              hiddenInMenu: true,
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/error/Deny')
          },
          {
            path: '/articles/product.add',
            name: 'product_add',
            access: 'article_add',
            meta: {
              title: 'product_add',
              icon: 'mdi-database-plus',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/article/NewArticle')
          },
          {
            path: '/articles/product.edit',
            name: 'product_edit',
            access: 'article_edit',
            meta: {
              title: 'product_edit',
              icon: 'mdi-database-plus',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/article/EditArticle')
          },
          {
            path: '/article/type_of_order.list',
            name: 'type_of_order',
            access: 'type_of_order',
            meta: {
              title: 'type_of_order',
              icon: 'mdi-car',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/type_order/ListTypeOfOrder')
          }
        ]
      },
      {
        path: '/finance',
        component: RouteWrapper,
        redirect: '/finance/supplier.list',
        access: ['manager_supplier', 'manager_buy'],
        meta: {
          title: 'finance',
          icon: 'mdi-podium',
          requiresAuth: true,
          group: 'finance'
        },
        children: [
          {
            path: '/finance/supplier.list',
            name: 'supplier_list',
            access: 'supplier_list',
            meta: {
              title: 'supplier_list',
              icon: 'mdi-car',
              requiresAuth: true
            },
            component: () => import('../views/supplier/ListSupplier')
          },
          {
            path: '/finance/buy_list',
            name: 'supply_product',
            access: 'buy_list',
            meta: {
              title: 'supply_product',
              icon: 'mdi-database-plus',
              hiddenInMenu: false,
              requiresAuth: true
            },
            component: () => import('../views/inventory/ListInventory')
          },
          {
            path: '/finance/buy.add',
            name: 'supply_add',
            access: 'supplier_add',
            meta: {
              title: 'supply_add',
              icon: 'mdi-database-plus',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/inventory/NewInventory')
          },
          {
            path: '/finance/supply.edit',
            name: 'supply_edit',
            access: 'supplier_edit',
            meta: {
              title: 'supply_edit',
              icon: 'mdi-database-plus',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/inventory/EditInventory')
          },
          {
            path: '/finance/tax.list',
            name: 'tax_list',
            access: 'tax_list',
            meta: {
              title: 'tax_list',
              icon: 'mdi-car',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/tax/ListTax')
          },
          {
            path: '/finance/discount.list',
            name: 'discount',
            access: 'discount_list',
            meta: {
              title: 'discount',
              icon: 'mdi-car',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/discount/ListDiscount')
          },
          {
            path: '/finance/pay.list',
            name: 'pay',
            access: 'pay_list',
            meta: {
              title: 'pay',
              icon: 'mdi-car',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/discount/ListDiscount')
          },
          {
            path: '/finance/exchange_rate.list',
            name: 'exchange_rate',
            access: 'exchange_rate_list',
            meta: {
              title: 'exchange_rate',
              icon: 'mdi-car',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/exchange_rate/ListExchangeRate')
          },
          {
            path: '/finance/expense_category.list',
            name: 'expense_category',
            access: 'expense_category_list',
            meta: {
              title: 'expense_category',
              icon: 'mdi-car',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/expense_category/ListExpenseCategory')
          }
        ]
      },
      {
        path: '/resume',
        component: RouteWrapper,
        redirect: '/resume/sell_product.list',
        access: ['manager_sell'],
        meta: {
          title: 'resume',
          icon: 'mdi-chart-bar',
          group: 'resume'
        },
        children: [
          {
            path: '/resume/sell_product.list',
            name: 'sell_product',
            access: 'sell_by_product',
            meta: {
              title: 'sell_product',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/sales_by/SalesProduct')
          },
          {
            path: '/resume/sell_category.list',
            name: 'sell_category',
            access: 'sell_by_category',
            meta: {
              title: 'sell_category',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/sales_by/SalesCategory')
          },
          {
            path: '/resume/sell_user.list',
            name: 'sell_user',
            access: 'sell_by_employer',
            meta: {
              title: 'sell_user',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/sales_by/SalesEmployer')
          },
          {
            path: '/resume/sell_types_payment.list',
            name: 'sell_types_payment',
            access: 'sell_by_payments',
            meta: {
              title: 'sell_types_payment',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/sales_by/SalesPayment')
          }
        ]
      },
      {
        path: '/users',
        component: RouteWrapper,
        redirect: '/users/employer.list',
        access: ['manager_employer', 'manager_access', 'manager_assistence'],
        meta: {
          title: 'user',
          icon: 'mdi-account-star',
          group: 'user'
        },
        children: [
          {
            path: '/users/employer.list',
            name: 'employer_list',
            access: 'employer_list',
            meta: {
              title: 'employer_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/user/ListUser')
          },
          {
            path: '/users/access.list',
            name: 'access',
            access: 'access_list',
            meta: {
              title: 'access',
              hiddenInMenu: true,
              icon: 'mdi-account-key',
              requiresAuth: true
            },
            component: () => import('../views/access/ListAccess')
          },
          {
            path: '/users/access.add',
            name: 'access_new',
            access: 'access_add',
            meta: {
              title: 'access_new',
              hiddenInMenu: true,
              icon: 'mdi-account-key',
              requiresAuth: true
            },
            component: () => import('../views/access/NewAccess')
          },
          {
            path: '/users/assistance.list',
            name: 'assistance',
            access: 'assistance_list',
            meta: {
              title: 'assistance',
              icon: 'mdi-clock',
              requiresAuth: true
            },
            component: () => import('../views/assistance/ListAssistance')
          }
        ]
      },
      {
        path: '/clients',
        component: RouteWrapper,
        redirect: '/clients/client.list',
        access: ['manager_client'],
        meta: {
          title: 'client',
          icon: 'mdi-account-multiple',
          group: 'clients',
          requiresAuth: true
        },
        children: [
          {
            path: '/clients/client.list',
            name: 'clients_list',
            access: 'client_list',
            meta: {
              title: 'client_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/client/ListClient')
          }
        ]
      },
      {
        path: '/setting',
        access: ['manager_shop', 'manager_key', 'manager_access', 'manager_payment', 'manager_expense_category', 'manager_exchange_rate', 'manager_type_of_order', 'manager_tax', 'manager_discount'],
        name: 'setting',
        meta: {
          title: 'setting',
          icon: 'mdi-cog',
          requiresAuth: true
        },
        children: [],
        component: () => import('../views/general/General')
      }
    ]
  },
  {
    path: '/hi',
    component: LayoutVerify,
    meta: {
      title: 'verify'
    },
    redirect: '/hi/welcome',
    hidden: true,
    children: [
      {
        path: 'welcome',
        name: 'welcome',
        meta: {
          title: 'welcome',
          hiddenInMenu: true,
          requiresAuth: true
        },
        component: () => import('../views/Welcome')
      },
      {
        path: 'verify/:hash',
        name: 'verify',
        props: true,
        meta: {
          title: 'verify',
          hiddenInMenu: true,
          requiresAuth: true
        },
        component: () => import('../views/auth/Verify')
      }
    ]
  },
  {
    path: '/lock',
    component: LayoutLock,
    meta: {
      title: 'pinlogin'
    },
    redirect: '/lock/pin',
    hidden: true,
    children: [
      {
        path: 'pin',
        name: 'pinlogin',
        meta: {
          title: 'pinlogin',
          hiddenInMenu: true,
          requiresAuth: true
        },
        component: () => import('../views/AppLock')
      }
    ]
  },
  {
    path: '/',
    component: LayoutDefault,
    meta: {
      title: 'home',
      group: 'apps',
      icon: ''
    },
    redirect: '/admin/dashboard',
    children: [
      {
        path: '/admin/dashboard',
        name: 'admin_dashboard',
        access: ['dashboard'],
        meta: {
          title: 'dashboard',
          group: 'apps',
          icon: 'mdi-view-dashboard',
          requiresAuth: true
        },
        children: [],
        component: () => import('../views/AdminDashboard')
      },
      {
        path: '/admin/403',
        name: 'Forbidden',
        access: ['Forbidden'],
        meta: {
          title: 'access_denied',
          hiddenInMenu: true,
          requiresAuth: true
        },
        children: [],
        component: () => import('../views/error/Deny')
      },
      {
        path: '/admin/user/profile',
        name: 'Profile',
        access: ['profile'],
        meta: {
          title: 'profile',
          hiddenInMenu: true,
          requiresAuth: true
        },
        children: [],
        component: () => import('../views/auth/Profile')
      },
      {
        path: '/admin/sales',
        component: RouteWrapper,
        access: ['manager_vending'],
        redirect: '/sales/vending.list',
        meta: {
          title: 'vending',
          icon: 'mdi-cash-usd',
          group: 'sales'
        },
        children: [
          {
            path: '/admin/sales/vending.list',
            name: 'vending',
            access: 'vending_list',
            meta: {
              title: 'vending',
              hiddenInMenu: false,
              requiresAuth: true
            },
            component: () => import('../views/sales/ListSale.vue')
          }
        ]
      },
      {
        path: '/admin/articles',
        component: RouteWrapper,
        access: ['manager_article', 'manager_category', 'manager_mod'],
        redirect: '/admin/articles/product.list',
        meta: {
          title: 'articles',
          icon: 'mdi-shopping',
          requiresAuth: true,
          group: 'articles'
        },
        children: [
          {
            path: '/admin/articles/product.list',
            name: 'product_list',
            access: 'article_list',
            meta: {
              title: 'product_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/article/AdminArticle.vue')
          },
          {
            path: '/admin/articles/category.list',
            name: 'category_list',
            access: 'category_list',
            meta: {
              title: 'category_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/category/ListCategoryAdmin')
          }
        ]
      },
      {
        path: '/admin/finance',
        component: RouteWrapper,
        redirect: '/admin/finance/supplier.list',
        access: ['manager_supplier', 'manager_buy'],
        meta: {
          title: 'finance',
          icon: 'mdi-podium',
          requiresAuth: true,
          group: 'finance'
        },
        children: [
          {
            path: '/admin/finance/supplier.list',
            name: 'supplier_list',
            access: 'supplier_list',
            meta: {
              title: 'supplier_list',
              icon: 'mdi-car',
              requiresAuth: true
            },
            component: () => import('../views/supplier/ListSupplierAdmin')
          },
          {
            path: '/admin/finance/buy_list',
            name: 'supply_product',
            access: 'buy_list',
            meta: {
              title: 'supply_product',
              icon: 'mdi-database-plus',
              hiddenInMenu: false,
              requiresAuth: true
            },
            component: () => import('../views/inventory/ListInventoryAdmin')
          }
        ]
      },
      {
        path: '/admin/resume',
        component: RouteWrapper,
        redirect: '/resume/sell_product.list',
        access: ['manager_sell'],
        meta: {
          title: 'resume',
          icon: 'mdi-chart-bar',
          group: 'resume'
        },
        children: [
          {
            path: '/admin/resume/sell_product.list',
            name: 'sell_product',
            access: 'sell_by_product',
            meta: {
              title: 'sell_product',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/sales_by/SalesProduct')
          },
          {
            path: '/admin/resume/sell_category.list',
            name: 'sell_category',
            access: 'sell_by_category',
            meta: {
              title: 'sell_category',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/sales_by/SalesCategory')
          },
          {
            path: '/admin/resume/sell_user.list',
            name: 'sell_user',
            access: 'sell_by_employer',
            meta: {
              title: 'sell_user',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/sales_by/SalesEmployer')
          },
          {
            path: '/admin/resume/sell_types_payment.list',
            name: 'sell_types_payment',
            access: 'sell_by_payments',
            meta: {
              title: 'sell_types_payment',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/sales_by/SalesPayment')
          }
        ]
      },
      {
        path: '/admin/users',
        component: RouteWrapper,
        redirect: '/admin/users/employer.list',
        access: ['manager_employer', 'manager_access', 'manager_assistence'],
        meta: {
          title: 'user',
          icon: 'mdi-account-star',
          group: 'user'
        },
        children: [
          {
            path: '/admin/users/employer.list',
            name: 'employer_list',
            access: 'employer_list',
            meta: {
              title: 'employer_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/user/ListUserAdmin')
          }
        ]
      },
      {
        path: '/admin/clients',
        component: RouteWrapper,
        redirect: '/clients/client.list',
        access: ['manager_client'],
        meta: {
          title: 'client',
          icon: 'mdi-account-multiple',
          group: 'clients',
          requiresAuth: true
        },
        children: [
          {
            path: '/admin/clients/client.list',
            name: 'clients_list',
            access: 'client_list',
            meta: {
              title: 'client_list',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/client/ListClientAdmin')
          }
        ]
      },
      {
        path: '/admin/setting',
        access: ['manager_shop', 'manager_key', 'manager_access', 'manager_payment', 'manager_expense_category', 'manager_exchange_rate', 'manager_type_of_order', 'manager_tax', 'manager_discount'],
        meta: {
          title: 'setting',
          icon: 'mdi-cog',
          requiresAuth: true
        },
        children: [],
        component: () => import('../views/general/General')
      }
    ]
  }
]
