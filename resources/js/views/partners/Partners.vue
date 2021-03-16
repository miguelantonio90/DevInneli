<template>
  <div>
    <app-loading v-show="loadingData" />
    <v-container
      fill-height
      fluid
      grid-list-xl
    >
      <v-layout
        v-if="!loadingData"
        justify-center
        wrap
      >
        <v-col cols="12">
          <v-card
            color="#385F73"
            dark
          >
            <v-card-title class="headline">
              {{ $vuetify.lang.t('$vuetify.partners.activate') }}
            </v-card-title>

            <v-card-subtitle>{{ $vuetify.lang.t('$vuetify.partners.description') }}</v-card-subtitle>

            <v-card-actions>
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <div
                    v-bind="attrs"
                    v-on="on"
                  >
                    <v-switch
                      v-model="affiliateOn"
                      class="ml-2 mt-5"
                      inset
                    />
                  </div>
                </template>
                <span>{{
                  !affiliateOn ?
                    $vuetify.lang.t(
                      '$vuetify.tips.referrals_on'
                    ):$vuetify.lang.t(
                      '$vuetify.tips.referrals_off'
                    )
                }}</span>
              </v-tooltip>
            </v-card-actions>
          </v-card>
        </v-col>
        <v-flex
          md4
          xs12
          style="margin-top: 15px"
        >
          <material-card class="v-card-profile">
            <v-list
              class="pa-0"
              two-line
            >
              <v-list-item href="#">
                <v-list-item-action>
                  <v-icon color="indigo">
                    mdi-city
                  </v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title
                    v-text="getCompanyName"
                  />
                  <v-list-item-subtitle
                    v-text="
                      $vuetify.lang.t(
                        '$vuetify.profile.company'
                      )
                    "
                  />
                </v-list-item-content>
              </v-list-item>
              <v-divider inset />
              <v-list-item href="#">
                <v-list-item-action>
                  <v-icon color="indigo">
                    mdi-account
                  </v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title v-text="getFullName" />
                  <v-list-item-subtitle
                    v-text="
                      $vuetify.lang.t(
                        '$vuetify.firstName'
                      )
                    "
                  />
                </v-list-item-content>
              </v-list-item>
              <v-divider inset />
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-list-item
                    v-clipboard:copy="getRefferalLink"
                    v-clipboard:success="onCopy"
                    v-clipboard:error="onError"
                    v-bind="attrs"
                    href="#"
                    v-on="on"
                  >
                    <v-list-item-action>
                      <v-icon color="indigo">
                        mdi-link
                      </v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                      <v-list-item-title
                        v-text="getRefferalLink"
                      />
                      <v-list-item-subtitle
                        v-text="
                          $vuetify.lang.t('$vuetify.refferalLink')
                        "
                      />
                    </v-list-item-content>
                  </v-list-item>
                </template>
                <span>{{
                  $vuetify.lang.t(
                    '$vuetify.tips.copy_link'
                  )
                }}</span>
              </v-tooltip>
            </v-list>
          </material-card>
        </v-flex>
        <v-flex
          md8
          xs12
        >
          <v-row
            cols="12"
          >
            <v-col
              cols="6"
              md="6"
            >
              <linear-statistic
                class="my-4"
                :title="$vuetify.lang.t('$vuetify.partners.referrals')"
                :sub-title="
                  $vuetify.lang.t('$vuetify.partners.referralsSub')
                "
                :quantity="userData.referrals.length > 0 ? userData.referrals.length.toString() : '0'"
                icon="mdi-trending-up"
                color="success"
                :value="100"
              />
            </v-col>
            <v-col
              cols="6"
              md="6"
            >
              <linear-statistic
                class="my-4"
                :title="$vuetify.lang.t('$vuetify.partners.referrer')"
                :sub-title="
                  $vuetify.lang.t('$vuetify.partners.referrerSub')
                "
                :quantity="userData.referrer ? userData.referrer.company.name : $vuetify.lang.t('$vuetify.partners.not_specified')"
                icon="mdi-trending-up"
                color="info"
                :value="100"
              />
            </v-col>
            <v-col cols="12">
              <v-card v-if="userData.referrals.length > 0">
                <v-card-title>
                  {{ $vuetify.lang.t(
                    '$vuetify.partners.referralsList'
                  ) }}
                  <v-spacer />
                  <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                  />
                </v-card-title>
                <v-data-table
                  :headers="columnHeaders"
                  :items="userData.referrals"
                  :search="search"
                />
              </v-card>
            </v-col>
          </v-row>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>
<script>
import { mapState, mapActions, mapGetters } from 'vuex'
export default {
  data () {
    return {
      loadingData: false,
      affiliateOn: false,
      search: ''
    }
  },
  computed: {
    ...mapState('auth', ['userData', 'pending']),
    getFullName () {
      return `${this.userData.firstName} ${this.userData.lastName || ''}`
    },
    getCompanyName () {
      return `${this.userData.company.name}`
    },
    getRefferalLink () {
      return `${process.env.MIX_APP_URL_AFFILIATE + this.userData.affiliate_id}`
    },
    columnHeaders () {
      return [
        {
          text: this.$vuetify.lang.t('$vuetify.firstName'),
          align: 'left',
          value: 'firstName'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.email'),
          value: 'email'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.company'),
          value: 'company.name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.phone'),
          value: 'company.phone'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.country'),
          value: 'company.country'
        }
      ]
    }
  },
  created () {
    this.loadingData = true
    this.getUserData().then(() => {
      this.loadingData = false
    })
  },
  methods: {
    ...mapActions('auth', ['getUserData']),
    onCopy: function (e) {
      this.$Toast.fire({
        icon: 'success',
        title: this.$vuetify.lang.t(
		  '$vuetify.partners.confirm_copied'
        ) + e.text
	  })
    },
    onError: function (e) {
      this.$Toast.fire({
        icon: 'error',
        title: this.$vuetify.lang.t(
		  '$vuetify.partners.error_copied'
        )
	  })
      console.log(e)
    }
  }
}
</script>
<style  scoped>

</style>
