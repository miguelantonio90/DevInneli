<template>
  <v-app :dark="true">
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
      <modal-idle
        v-if="isIdle"
        :dialog="!!isIdle"
      />
    </v-navigation-drawer>
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
