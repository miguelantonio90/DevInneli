<template>
  <div class="page-welcome">
    <v-alert
      color="orange"
      icon="mdi-alert"
      prominent
      style="margin: 20px"
    >
      <v-row align="center">
        <v-col class="grow">
          {{ $vuetify.lang.t('$vuetify.activeAccount') }}
        </v-col>
      </v-row>
    </v-alert>
    <v-card
      class="pa-3 page-login__card"
      tile
    >
      <v-img
        height="300"
        src="/assets/avatar/avatar-undefined.jpg"
      />
      <v-list
        class="pa-0"
        two-line
      >
        <v-list-item href="#">
          <v-list-item-action>
            <v-icon color="indigo">
              mdi-account
            </v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title
              v-text="
                userData.firstName
                  ? userData.firstName
                  : $vuetify.lang.t('$vuetify.noDefined')
              "
            />
            <v-list-item-subtitle
              v-text="$vuetify.lang.t('$vuetify.firstName')"
            />
          </v-list-item-content>
        </v-list-item>
        <v-divider inset />
        <v-list-item href="#">
          <v-list-item-action>
            <v-icon color="indigo">
              mdi-email
            </v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title
              v-text="
                userData.email
                  ? userData.email
                  : $vuetify.lang.t('$vuetify.noDefined')
              "
            />
            <v-list-item-subtitle
              v-text="$vuetify.lang.t('$vuetify.email')"
            />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
	name: 'Welcome',
	computed: {
		...mapState('auth', ['isLoggedIn', 'userData']),
		...mapGetters(['errors'])
	},
	mounted () {
		this.getUserData()
	},
	created () {
		this.getUserData()
	},
	methods: {
		...mapActions('auth', ['getUserData'])
	}
}
</script>

<style lang="sass" scoped>
.page-login
  &__card
    max-width: 600px
    margin: 0 auto
</style>
