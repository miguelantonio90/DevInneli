<template>
  <div class="page-dashboard">
    <v-container>
      <v-row>
        <!-- mini statistic start -->
        <v-col cols="3">
          <mini-statistic
            color="indigo"
            icon="mdi-facebook"
            sub-title="Likes"
            title="100+"
          />
        </v-col>
        <v-col cols="3">
          <mini-statistic
            color="red"
            icon="mdi-google"
            sub-title="Connections"
            title="150+"
          />
        </v-col>
        <v-col cols="3">
          <mini-statistic
            color="light-blue"
            icon="mdi-twitter"
            sub-title="Followers"
            title="200+"
          />
        </v-col>
        <v-col cols="3">
          <mini-statistic
            color="purple"
            icon="mdi-instagram"
            sub-title="Shots"
            title="50+"
          />
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import API from '../api'
import MiniStatistic from '../components/widgets/statistic/MiniStatistic'
import Material from 'vuetify/es5/util/colors'

export default {
  name: 'PageDashboard',
  components: {
    MiniStatistic
  },
  data: () => ({
    color: Material
  }),
  computed: {
    siteTrafficData () {
      return API.getMonthVisit
    },
    locationData () {
      return API.getLocation
    }
  }
}
</script>
