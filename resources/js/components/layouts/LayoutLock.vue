<template>
  <v-app
    id="inspire"
    class="app dashboard"
  >
    <app-toolbar
      :show-nav-icon="false"
      :show-menu-user="false"
      :show-lock-icon="false"
      class="app--toolbar"
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
import AppFab from '../AppFab'
import AppToolbar from '../AppToolbar'

export default {
  name: 'LayoutLock',
  components: {
    AppToolbar,
    AppFab
  }
}
</script>

<style scoped></style>
