<template>
  <v-app :dark="true">
    <app-tour
      name="App"
      :steps="steps"
    />
    <router-view />
    <!-- theme setting -->
    <v-btn
      class="setting-fab"
      color="red"
      dark
      fab
      fixed
      right="right"
      small
      top="top"
      @click="openThemeSettings"
    >
      <v-icon>mdi-monitor</v-icon>
    </v-btn>
    <!-- setting drawer -->
    <v-navigation-drawer
      v-model="rightDrawer"
      class="setting-drawer"
      fixed
      hide-overlay
      right
      temporary
    >
      <theme-settings />
    </v-navigation-drawer>
    <modal-idle
      v-if="isIdle"
      :dialog="!!isIdle"
    />
  </v-app>
</template>

<script>
import ThemeSettings from './components/ThemeSettings'
import ModalIdle from './components/core/ModalIdle'
import { mapGetters } from 'vuex'

export default {
  components: {
    ThemeSettings, ModalIdle
  },
  data () {
    return {
      rightDrawer: false
    }
  },
  computed: {
    ...mapGetters(['errors']),
    ...mapGetters('auth', ['isLoggedIn']),
    isIdle () {
      return this.isLoggedIn ? this.$store.state.idleVue.isIdle : null
    },
    steps () {
      return [
        {
          target: '#drawer-toolbar',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step1')
          /* params: {
            placement: 'left'
          } */
        }, {
          target: '.app--drawer',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step2'),
          params: {
            placement: 'right'
          }
        }, {
          target: '#setting',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step3')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#articles',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step4')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#vending',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step5')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#user',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step6')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#client',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step7')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#finance',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step8')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#resume',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step9')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#navIcon',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step10')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#mdi_lock',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step11')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#mdi_fullscreen',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step12')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#mdi_currency_usd',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step13')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#mdi_cart',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step14')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#menuLang',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step15')
          /* params: {
            placement: 'left'
          } */
        }, {
          target: '#menuUser',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step16'),
          params: {
            placement: 'left'
          }
        }, {
          target: '.setting-fab',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step17')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#statistic_dashboard',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step18')
        }
      ]
    }
  },
  created () {
    // add app events
  },
  methods: {
    openThemeSettings () {
      this.$vuetify.goTo(0)
      this.rightDrawer = !this.rightDrawer
    }
  }
}
</script>

<style lang="sass" scoped>
.setting-fab
  top: 50% !important
  right: 0
  border-radius: 0
/* custom scrollbar

\::-webkit-scrollbar
  width: 20px

\::-webkit-scrollbar-track
  background-color: transparent

\::-webkit-scrollbar-thumb
  background-color: #d6dee1
  border-radius: 20px
  border: 6px solid transparent
  background-clip: content-box

  &:hover
    background-color: #a8bbbf

</style>
