<template>
  <v-app class="exception">
    <v-main
      fill-height
      fluid
    >
      <v-layout
        align-center
        justify-center
      >
        <div class="text-md-center">
          <v-alert
            v-if="error"
            type="error"
          >
            {{ error }}
          </v-alert>
          <v-container
            v-show="!error"
            style="height: 400px;"
          >
            <v-row
              align-content="center"
              class="fill-height"
              justify="center"
            >
              <v-col
                class="subtitle-1 text-center"
                cols="12"
              >
                {{ this.$vuetify.lang.t('$vuetify.wait') }}
              </v-col>
              <v-col cols="12">
                <v-progress-linear
                  color="deep-purple accent-4"
                  height="6"
                  indeterminate
                  rounded
                />
              </v-col>
            </v-row>
          </v-container>
        </div>
      </v-layout>
    </v-main>
  </v-app>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import router from '../../router'

export default {
  props: {
    hash: {
      type: String,
      default: ''
    }
  },
  data () {
    return {
      error: null
    }
  },

  computed: {
    ...mapGetters('auth', ['user'])
  },

  mounted () {
    this.sendVerifyRequest(this.hash)
      .then(() => {
        this.$router.push({ name: 'pinlogin' })
      })
      .catch(() => {
        this.error = 'Error verifying email'
      })
  },

  methods: {
    ...mapActions('auth', ['sendVerifyRequest']),
    goLogin () {
      router.push('/auth/login')
    }
  }
}
</script>
