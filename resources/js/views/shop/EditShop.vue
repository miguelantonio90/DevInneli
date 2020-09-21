<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="450px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
            $vuetify.lang.t('$vuetify.menu.shop'),
          ])
        }}</span>
      </v-card-title>

      <v-card-text>
        Form
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          color="error"
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          class="mb-2"
          color="primary"
          :loading="isActionInProgress"
          @click="updateRoleHandler"
        >
          <v-icon>mdi-check</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  data () {
    return {
      formValid: false,
      formRule: {}
    }
  },
  computed: {
    ...mapState('shop', ['saved', 'editShop', 'isActionInProgress'])
  },
  methods: {
    ...mapActions('shop', ['updateShop', 'toogleEditModal']),

    async updateRoleHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateShop(this.editShop).then(() => {
          if (this.saved) {
            this.loading = false
            const msg = this.$vuetify.lang.t(
              '$vuetify.messages.success_profile'
            )
            this.$Toast.fire({
              icon: 'success',
              title: msg
            })
          }
        })
      }
    }
  }
}
</script>

<style scoped></style>
