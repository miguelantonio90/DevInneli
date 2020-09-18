<template>
  <v-app
    id="inspire"
    class="app dashboard"
  >
    <app-drawer
      :show-drawer="showDrawer"
      class="app--drawer"
    />
    <app-toolbar
      class="app--toolbar"
      @side-icon-click="handleDrawerVisiable"
    />
    <v-main>
      <!-- Page Header -->
      <div class="page-wrapper">
        <v-slide-x-transition>
          <router-view />
        </v-slide-x-transition>
      </div>
      <!-- App Footer -->
      <v-footer
        class="pa-3 app--footer"
        height="auto"
      >
        <span>INNELI &copy; {{ new Date().getFullYear() }}</span>
      </v-footer>
    </v-main>
    <!-- Go to top -->
    <app-fab />
  </v-app>
</template>

<script>
import AppDrawer from '../AppDrawer'
import AppToolbar from '../AppToolbar'
import AppFab from '../AppFab'

export default {
  name: 'LayoutDefault',
  components: {
    AppDrawer,
    AppToolbar,
    AppFab
  },

  data () {
    return {
      showDrawer: true
    }
  },
  created () {
  },
  methods: {
    handleDrawerVisiable () {
      this.showDrawer = !this.showDrawer
    }
  }
}
</script>

<style lang="sass" scoped>
.page-wrapper
  min-height: calc(100vh - 112px - 48px)
</style>
