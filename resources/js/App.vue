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
    </v-navigation-drawer>
  </v-app>
</template>

<script>
import ThemeSettings from './components/ThemeSettings'
import { mapGetters } from 'vuex'

export default {
  components: {
    ThemeSettings
  },
  data () {
    return {
      rightDrawer: false
    }
  },
  computed: {
    ...mapGetters(['errors'])
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
</style>
