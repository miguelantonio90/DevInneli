<template>
  <v-app
    class="layout-lock"
  >
    <v-main>
      <router-view :key="$route.path" />
    </v-main>
    <v-footer
      height="auto"
      style="background-color: transparent"
    >
      <span style="color: white">&copy; {{ new Date().getFullYear() }} INNELI Team</span>
    </v-footer>
  </v-app>
</template>

<script>

export default {
	name: 'LayoutLock'
}
</script>

<style lang="sass" scoped>
.layout-lock
  width: 100%
  position: absolute
  top: 0
  left: 0
  content: ""
  z-index: 0
  background-size: cover
  background-repeat: no-repeat
  background-position: center top
  background-image: url('/assets/background/lock.jpg')
</style>
