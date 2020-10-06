import {
  LayoutAuth,
  LayoutDefault,
  RouteWrapper,
  LayoutVerify,
  LayoutLock,
  LayoutSales
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
    path: '/auth',
    component: LayoutAuth,
    meta: {
      title: 'PinCode'
    },
    redirect: '/auth/pincode',
    hidden: true,
    props: true,
    children: [
      {
        path: 'pincode',
        name: 'pincode',
        meta: {
          title: 'Pin Code'
        },
        component: () => import('../views/auth/PinCode')
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
            component: () => import('../views/error/Deny')
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
          icon: 'mdi-wrench',
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
  },
  {
    path: '/employee',
    component: LayoutSales,
    meta: {
      title: 'vending'
    },
    redirect: '/employee/vending',
    hidden: true,
    children: [
      {
        path: 'vending',
        name: 'vending',
        meta: {
          title: 'vending',
          hiddenInMenu: true,
          requiresAuth: true
        },
        component: () => import('../views/sales/Sales')
      }
    ]
  }
]
