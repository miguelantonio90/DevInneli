<template>
  <v-navigation-drawer
    v-model="drawer"
    :dark="$vuetify.dark"
    :width="drawerWidth"
    app
    class="app--drawer"
    expand-on-hover
  >
    <v-toolbar
      color="primary darken-1"
      dark
    >
      <img
        :src="computeLogo"
        alt="Vue Material Admin Template"
        height="36"
      >
      <v-toolbar-title class="ml-0 pl-3">
        <span class="hidden-sm-and-down">INNELI APP</span>
      </v-toolbar-title>
    </v-toolbar>
    <v-list class="pa-0" />
  </v-navigation-drawer>
</template>
<script>

export default {
  name: 'AppDrawer',
  components: {},
  props: {
    expanded: {
      type: Boolean,
      default: true
    },
    showDrawer: Boolean
  },
  data () {
    return {
      mini: false,
      drawerWidth: 256,
      drawer: true,
      scrollSettings: {
        maxScrollbarLength: 160
      }
    }
  },

  computed: {
    computeLogo () {
      return '/assets/m.png'
    }
  },
  watch: {
    showDrawer: {
      handler (val) {
        this.drawer = val
      },
      immediate: true
    }
  },
  created () {
  },

  methods: {
    handleDrawerCollapse () {
      this.drawerWidth = this.drawerWidth === 256 ? 64 : 256
    }
  }
}
</script>

<style lang="sass" scoped>
.app--drawer
  overflow: hidden !important

  .drawer-menu--scroll
    height: calc(100vh - 48px)
    overflow: auto
</style>
