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
    <v-list class="pa-0">
      <template v-for="(item, key) in computeMenu">
        <template v-if="item.children && item.children.length > 0">
          <v-list-group
            :key="key"
            :prepend-icon="item.meta.icon"
            :to="item.path"
            no-action
          >
            <template v-slot:prepend-icon>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    v-bind="attrs"
                    v-on="on"
                    v-text="item.meta.icon"
                  />
                </template>
                <span>
                  {{
                    $vuetify.lang.t(
                      '$vuetify.menu.' + item.meta.title,
                    )
                  }}
                </span>
              </v-tooltip>
            </template>
            <template v-slot:activator>
              <v-list-item-content>
                <v-list-item-title
                  v-text="
                    $vuetify.lang.t(
                      '$vuetify.menu.' + item.meta.title
                    )
                  "
                />
              </v-list-item-content>
            </template>
            <v-list-item
              v-for="subItem in item.children"
              v-show="!subItem.meta.hiddenInMenu"
              :key="subItem.name"
              :class="drawerWidth === 64 ? 'pl-4' : ''"
              :to="subItem.path"
            >
              <template v-if="drawerWidth === 64">
                <v-list-item-icon>
                  <v-tooltip bottom>
                    <template
                      v-slot:activator="{ on, attrs }"
                    >
                      <v-icon
                        v-bind="attrs"
                        v-on="on"
                        v-text="subItem.meta.icon"
                      />
                    </template>
                    <span>{{
                      $vuetify.lang.t(
                        '$vuetify.menu.' +
                          subItem.meta.title,
                      )
                    }}</span>
                  </v-tooltip>
                </v-list-item-icon>
              </template>
              <template v-else>
                <v-list-item-content>
                  <v-list-item-title
                    v-text="
                      $vuetify.lang.t(
                        '$vuetify.menu.' +
                          subItem.meta.title
                      )
                    "
                  />
                </v-list-item-content>
              </template>
            </v-list-item>
          </v-list-group>
        </template>
        <template v-else>
          <v-list-item
            v-show="!item.meta.hiddenInMenu"
            :key="key"
            :to="item.path"
          >
            <v-list-item-icon>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-icon
                    v-bind="attrs"
                    v-on="on"
                    v-text="item.meta.icon"
                  />
                </template>
                <span>{{
                  $vuetify.lang.t(
                    '$vuetify.menu.' + item.meta.title,
                  )
                }}</span>
              </v-tooltip>
            </v-list-item-icon>
            <v-list-item-content v-if="drawerWidth !== 64">
              <v-list-item-title
                v-text="
                  $vuetify.lang.t(
                    '$vuetify.menu.' + item.meta.title
                  )
                "
              />
            </v-list-item-content>
          </v-list-item>
        </template>
      </template>
    </v-list>
  </v-navigation-drawer>
</template>
<script>
import { protectedRoute as routes } from '../router/config'

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
    },
    computeMenu () {
      return routes[0].children
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
