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
    meta: {
      title: 'Not Found'
    },
    component: () => import('../views/error/NotFound')
  },
  {
    path: '/500',
    name: '500',
    meta: {
      title: 'Server Error'
    },
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
        meta: {
          title: 'dashboard',
          group: 'apps',
          icon: 'mdi-view-dashboard',
          requiresAuth: true
        },
        component: () => import('../views/Dashboard')
      },
      {
        path: '/403',
        name: 'Forbidden',
        meta: {
          title: 'access_denied',
          hiddenInMenu: true,
          requiresAuth: true
        },
        component: () => import('../views/error/Deny')
      },
      {
        path: '/user/profile',
        name: 'Profile',
        meta: {
          title: 'profile',
          hiddenInMenu: true,
          requiresAuth: true
        },
        component: () => import('../views/auth/Profile')
      },
      {
        path: '/sales',
        component: RouteWrapper,
        redirect: '/sales/vending.list',
        meta: {
          title: 'vending',
          icon: 'mdi-cart',
          group: 'sales'
        },
        children: [
          {
            path: '/sales/vending.list',
            name: 'vending',
            meta: {
              title: 'vending',
              requiresAuth: true
            },
            component: () => import('../views/sales/ListSale')
          },
          {
            path: '/sales/vending/new',
            name: 'vending_new',
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
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/error/Deny')
          },
          {
            path: '/articles/product.add',
            name: 'product_add',
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
            meta: {
              title: 'product_edit',
              icon: 'mdi-database-plus',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/article/EditArticle')
          }
        ]
      },
      {
        path: '/finance',
        component: RouteWrapper,
        redirect: '/finance/supplier.list',
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
            meta: {
              title: 'supply_edit',
              icon: 'mdi-database-plus',
              hiddenInMenu: true,
              requiresAuth: true
            },
            component: () => import('../views/inventory/EditInventory')
          }
        ]
      },
      {
        path: '/resume',
        component: RouteWrapper,
        redirect: '/resume',
        meta: {
          title: 'resume',
          icon: 'mdi-chart-bar',
          group: 'resume'
        },
        children: [
          {
            path: '/resume/sell_product.list',
            name: 'sell_product',
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
            meta: {
              title: 'sell_category',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/error/Deny')
          },
          {
            path: '/resume/sell_user.list',
            name: 'sell_user',
            meta: {
              title: 'sell_user',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/error/Deny')
          },
          {
            path: '/resume/sell_types_payment.list',
            name: 'sell_types_payment',
            meta: {
              title: 'sell_types_payment',
              icon: 'mdi-database-plus',
              requiresAuth: true
            },
            component: () => import('../views/error/Deny')
          }
        ]
      },
      {
        path: '/users',
        component: RouteWrapper,
        redirect: '/users/employer.list',
        meta: {
          title: 'user',
          icon: 'mdi-account-star',
          group: 'user'
        },
        children: [
          {
            path: '/users/employer.list',
            name: 'employer_list',
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
            meta: {
              title: 'access',
              icon: 'mdi-account-key',
              requiresAuth: true
            },
            component: () => import('../views/access/ListAccess')
          },
          {
            path: '/users/assistance.list',
            name: 'assistance',
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
        meta: {
          title: 'setting',
          icon: 'mdi-cog',
          requiresAuth: true
        },
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
  }
]
