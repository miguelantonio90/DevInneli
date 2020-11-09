<template>
  <v-app-bar
    app
    color="primary"
    dark
  >
    <v-app-bar-nav-icon
      v-if="showNavIcon"
      @click="handleDrawerToggle"
    />
    <v-spacer />
    <v-toolbar-items>
      <v-tooltip
        v-if="showLockIcon"
        bottom
      >
        <template v-slot:activator="{ on, attrs }">
          <v-icon
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
        icon
        @click="handleFullScreen()"
      >
        <v-icon>mdi-fullscreen</v-icon>
      </v-btn>
      <v-btn
        icon
        @click="handleSales"
      >
        <v-icon>mdi-cart</v-icon>
      </v-btn>
      <v-menu
        v-if="showMenuLang"
        class="elelvation-1"
        offset-y
        origin="center center"
        transition="scale-transition"
      />
      <v-select
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
          v-if="isManagerIn && isLoggedIn"
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
          v-if="!isManagerIn && isLoggedIn"
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
    return {}
  },
  computed: {
    ...mapGetters('auth', ['isLoggedIn', 'user', 'isManagerIn']),
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
        const to =
            index === matched.length - 1
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
    }
  },
  created () {
    this.getUserData()
  },
  methods: {
    ...mapActions('auth', ['sendLogoutRequest', 'getUserData']),
    handleDrawerToggle () {
      this.$emit('side-icon-click')
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
    handlePinLogin () {
      localStorage.removeTokenManager()
      this.$router.push({ name: 'pinlogin', params: { email: this.user.email } })
    },
    handleSales () {
      this.$router.push({ name: 'vending' }).catch(() => {})
    }
  }
}
</script>

<style lang="sass" scoped></style>
