<template>
  <v-card
    max-width="450"
    class="mx-auto"
  >
    <v-list class="pa-0">
      <v-list-item
        v-for="(item, i) in notifications"
        :key="i"
      >
        <v-list-item-content>
          <v-alert
            dense
            dismissible
            type="info"
            @input="closeNotification(item)"
          >
            <template
              v-if="item.params.toString().split(',').length > 0"
            >
              {{
                $vuetify.lang.t(
                  "$vuetify.notifications." + item.msg,
                  item.params.toString()
                )
              }}
            </template>
            <template v-else>
              {{
                $vuetify.lang.t(
                  "$vuetify.notifications." + item.msg
                )
              }}
            </template>
          </v-alert>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-card>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  name: 'ListNotifications',
  computed: {
    ...mapGetters('auth', ['notifications'])
  },
  methods: {
    ...mapActions('auth', ['readNotification']),
    closeNotification (item) {
      this.readNotification(item.id)
    },
    notificate (arrayN) {
      const result = []
      arrayN.forEach(ele => {
        const data = []
        data.push(ele)
        result.push(data)
      })
      return result
    }
  }
}
</script>

<style scoped></style>
