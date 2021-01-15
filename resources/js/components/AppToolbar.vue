<template>
  <v-app-bar
    app
    color="primary"
    dark
  >
    <v-app-bar-nav-icon
      v-if="showNavIcon"
      id="navIcon"
      @click="handleDrawerToggle"
    />
    <v-spacer />
    <v-toolbar-items>
      <v-tooltip
        bottom
      >
        <template v-slot:activator="{ on, attrs }">
          <v-icon
            v-if="showLockIcon"
            id="mdi_lock"
            v-bind="attrs"
            class="mr-3"
            v-on="on"
            @click="handlePinLogin"
            v-text="'mdi-lock'"
          />
        </template>
        <span>{{ $vuetify.lang.t('$vuetify.have_pin') }}</span>
      </v-tooltip>
      <v-btn
        v-if="!isMobile"
        id="mdi_fullscreen"
        icon
        @click="handleFullScreen()"
      >
        <v-icon>mdi-fullscreen</v-icon>
      </v-btn>
      <v-tooltip
        v-if="!isAdminIn"
        bottom
      >
        <template v-slot:activator="{ on, attrs }">
          <v-icon
            v-if="showSalesIcon"
            id="mdi_currency_usd"
            v-bind="attrs"
            class="mr-3"
            v-on="on"
            @click="handleSales"
            v-text="'mdi-currency-usd'"
          />
        </template>
        <span>{{ $vuetify.lang.t('$vuetify.titles.newF', [
          $vuetify.lang.t('$vuetify.sale.sale'),
        ]) }}</span>
      </v-tooltip>
      <v-tooltip
        v-if="!isAdminIn"
        bottom
      >
        <template v-slot:activator="{ on, attrs }">
          <v-icon
            v-if="showBuyIcon"
            id="mdi_cart"
            v-bind="attrs"
            class="mr-3"
            v-on="on"
            @click="handleBuy"
            v-text="'mdi-cart'"
          />
        </template>
        <span>{{ $vuetify.lang.t('$vuetify.titles.newF', [$vuetify.lang.t('$vuetify.supply.name')]) }}</span>
      </v-tooltip>
      <v-menu
        v-if="showMenuLang"
        class="elelvation-1"
        offset-y
        origin="center center"
        transition="scale-transition"
      />
      <v-select
        id="menuLang"
        v-model="$vuetify.lang.current"
        :items="availableLanguages"
        class="mt-3"
        item-text="text"
        item-value="value"
        menu-props="auto"
        prepend-icon="mdi-translate"
        style="width:8em"
        @change="handleChangeLocale($event)"
      />
      <v-menu
        v-if="showMenuUser"
        offset-y
        origin="center center"
        transition="scale-transition"
      >
        <template v-slot:activator="{ on }">
          <v-btn
            id="menuUser"
            slot="activator"
            icon
            large
            text
            v-on="on"
          >
            <v-avatar size="30px">
              <v-icon>mdi-account-circle</v-icon>
            </v-avatar>
          </v-btn>
        </template>
        <v-list
          v-if="!isManagerIn && isAdminIn && isLoggedIn"
          class="pa-0"
        >
          <v-list-item
            v-for="(item, index) in adminMenus"
            :key="index"
            :disabled="item.disabled"
            :href="item.href"
            :target="item.target"
            :to="!item.href ? { name: item.name } : null"
            rel="noopener"
            ripple="ripple"
            @click="item.click"
          >
            <v-list-item-action v-if="item.icon">
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{
                  item.title
                }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
        <v-list
          v-if="isManagerIn && !isAdminIn && isLoggedIn"
          class="pa-0"
        >
          <v-list-item
            v-for="(item, index) in profileMenus"
            :key="index"
            :disabled="item.disabled"
            :href="item.href"
            :target="item.target"
            :to="!item.href ? { name: item.name } : null"
            rel="noopener"
            ripple="ripple"
            @click="item.click"
          >
            <v-list-item-action v-if="item.icon">
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{
                  item.title
                }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
        <v-list
          v-if="!isManagerIn && !isAdminIn && isLoggedIn"
          class="pa-0"
        >
          <v-list-item
            v-for="(item, index) in employeeMenus"
            :key="index"
            :disabled="item.disabled"
            :href="item.href"
            :target="item.target"
            :to="!item.href ? { name: item.name } : null"
            rel="noopener"
            ripple="ripple"
            @click="item.click"
          >
            <v-list-item-action v-if="item.icon">
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>
                {{
                  item.title
                }}
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-toolbar-items>
    <v-toolbar
      slot="extension"
      color="white"
      dense
      light
      tag="div"
    >
      <v-icon>mdi-home</v-icon>
      <v-breadcrumbs
        :items="breadcrumbs"
        class="pa-3"
      />
    </v-toolbar>
  </v-app-bar>
</template>
<script>
import Util from '../util'
import { mapActions, mapGetters } from 'vuex'
import localStorage from '../config/localStorage'

export default {
  name: 'AppToolbar',
  props: {
    showNavIcon: {
      type: Boolean,
      default: true
    },
    showLockIcon: {
      type: Boolean,
      default: true
    },
    showSalesIcon: {
      type: Boolean,
      default: true
    },
    showBuyIcon: {
      type: Boolean,
      default: true
    },
    showMenuLang: {
      type: Boolean,
      default: true
    },
    showMenuUser: {
      type: Boolean,
      default: true
    }
  },
  data () {
    return {
      showSale: false,
      showBuy: false,
      isMobile: false
    }
  },
  computed: {
    ...mapGetters('auth', ['isLoggedIn', 'isAdminIn', 'user', 'access_permit', 'isManagerIn']),
    toolbarColor () {
      return this.$vuetify.options.extra.mainNav
    },
    availableLanguages () {
      const { locales } = this.$vuetify.lang
      return Object.keys(locales).map((lang) => {
        return {
          text: locales[lang].label,
          value: lang
        }
      })
    },
    localeText () {
      const find = this.availableLanguages.find(
        (item) => item.value === this.$vuetify.lang.current
      )
      return find.text
    },
    breadcrumbs () {
      const { matched } = this.$route
      return matched.map((route, index) => {
        const to = index === matched.length - 1
          ? this.$route.path
          : route.path || route.redirect
        const text = this.$vuetify.lang.t(
          '$vuetify.menu.' + route.meta.title
        )
        return {
          text: text,
          to: to,
          exact: true,
          disabled: false
        }
      })
    },
    profileMenus () {
      return [
        {
          icon: 'mdi-face',
          href: '#',
          title: this.$vuetify.lang.t('$vuetify.menu.profile'),
          click: this.handleProfile
        },
        {
          icon: 'mdi-logout',
          href: '#',
          title: this.$vuetify.lang.t('$vuetify.menu.logout'),
          click: this.handleLogout
        }
      ]
    },
    employeeMenus () {
      return [
        {
          icon: 'mdi-face',
          href: '#',
          title: this.$vuetify.lang.t('$vuetify.menu.profile'),
          click: this.handleProfileEmployee
        }, {
          icon: 'mdi-timer',
          href: '#',
          title: this.$vuetify.lang.t('$vuetify.menu.turnOn'),
          click: this.handleProfileEmployee
        }
      ]
    },
    adminMenus () {
      return [
        {
          icon: 'mdi-face',
          href: '#',
          title: this.$vuetify.lang.t('$vuetify.menu.profile'),
          click: this.handleProfileAdmin
        },
        {
          icon: 'mdi-logout',
          href: '#',
          title: this.$vuetify.lang.t('$vuetify.menu.logout'),
          click: this.handleLogout
        }
      ]
    }
  },
  watch: {
    access_permit () {
      this.showSale = this.access_permit.filter(a => a.title.name === 'manager_vending')[0].actions.vending_add
        ? this.access_permit.filter(a => a.title.name === 'manager_vending')[0].actions.vending_add : this.showSalesIcon
      this.showBuy = this.access_permit.filter(a => a.title.name === 'manager_buy')[0].actions.buy_add
        ? this.access_permit.filter(a => a.title.name === 'manager_buy')[0].actions.buy_add : this.showBuyIcon
    }
  },
  created () {
    this.getUserData().then((v) => {
      this.showSale = this.access_permit.filter(a => a.title.name === 'manager_vending')[0].actions.vending_add
        ? this.access_permit.filter(a => a.title.name === 'manager_vending')[0].actions.vending_add : this.showSalesIcon
      this.showBuy = this.access_permit.filter(a => a.title.name === 'manager_buy')[0].actions.buy_add
        ? this.access_permit.filter(a => a.title.name === 'manager_buy')[0].actions.buy_add : this.showBuyIcon
    })
  },
  mounted () {
    this.onResize()
    window.addEventListener('resize', this.onResize, { passive: true })
  },
  beforeDestroy () {
    if (typeof window !== 'undefined') {
      window.removeEventListener('resize', this.onResize, { passive: true })
    }
  },
  methods: {
    ...mapActions('auth', ['sendLogoutRequest', 'getUserData']),
    handleDrawerToggle () {
      this.$emit('side-icon-click')
    },
    onResize () {
      this.isMobile = window.innerWidth < 600
    },
    handleFullScreen () {
      Util.toggleFullScreen()
    },
    handleLogout () {
      this.sendLogoutRequest().then(() => {
        this.$router.push('/auth/login')
      })
    },
    handleChangeLocale (value) {
      this.$vuetify.lang.current = value
      localStorage.setLanguage(value)
      window.location.reload()
    },
    handleProfileEmployee () {
      alert('Profile employee')
    },
    handleProfile () {
      this.$router.push({ name: 'Profile' })
    },
    handleProfileAdmin () {
      this.$router.push({ name: 'admin_profile' })
    },
    handlePinLogin () {
      localStorage.removeTokenManager()
      this.$router.push({ name: 'pinlogin', params: { email: this.user.email } })
    },
    handleSales () {
      this.$store.state.sale.managerSale = false
      this.$router.push({ name: 'vending_new' }).catch(() => {})
    },
    handleBuy () {
      this.$store.state.inventory.managerInventory = false
      this.$router.push({ name: 'supply_add' }).catch(() => {})
    }
  }
}
</script>

<style lang="sass" scoped></style>
