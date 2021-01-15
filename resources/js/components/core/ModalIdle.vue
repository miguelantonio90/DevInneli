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
import { mapState } from 'vuex'
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
        // Your lock app function should be over here
        console.log('lock user....')
        this.$router.push('/lock/pin')
      }
    }, 1000)
  }
}
</script>

<style scoped>

</style>
