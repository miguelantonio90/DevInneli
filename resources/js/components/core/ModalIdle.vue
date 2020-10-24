<template>
  <v-row justify="center">
    <v-dialog
      v-model="dialog"
      persistent
      max-width="500"
    >
      <v-card>
        <v-card-title class="headline">
          {{ $vuetify.lang.t('$vuetify.messages.idle_title') }}
        </v-card-title>
        <v-card-text>
          <div class="p-3">
            <p>{{ $vuetify.lang.t('$vuetify.messages.idle_info') }}</p>
            <p>{{ $vuetify.lang.t('$vuetify.messages.idle_counter',[second]) }}</p>
          </div>
        </v-card-text>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import { mapState, mapActions } from 'vuex'
export default {
  props: {
    dialog: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      time: 10000
    }
  },
  computed: {
    ...mapState('auth', ['userData']),
    second () {
      return this.time / 1000
    }
  },
  created () {
    const timerId = setInterval(() => {
      this.time -= 1000
      if (!this.$store.state.idleVue.isIdle) clearInterval(timerId)

      if (this.time < 1) {
        clearInterval(timerId)
        // Your logout function should be over here
        console.log('logout user....')
        this.sendLogoutRequest().then(() => {
          this.$router.push('/auth/login')
        })
      }
    }, 1000)
  },
  methods: {
    ...mapActions('auth', ['sendLogoutRequest'])
  }
}
</script>

<style scoped>

</style>
