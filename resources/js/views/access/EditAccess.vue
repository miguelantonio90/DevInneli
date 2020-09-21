<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="450px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
            $vuetify.lang.t('$vuetify.menu.access'),
          ])
        }}</span>
      </v-card-title>

      <v-card-text>
        <v-form
          ref="form"
          v-model="formValid"
          class="my-10"
          lazy-validation
        >
          <v-row justify="space-around">
            <v-col
              cols="12"
              md="6"
            >
              <v-select
                v-model="editAccess.key"
                disabled
                :items="keys"
                :label="$vuetify.lang.t('$vuetify.access.key')"
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="editAccess.name"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.access.name')"
                required
              />
            </v-col>
            <v-checkbox
              v-model="editAccess.accessEmail"
              class="md-6"
              :label="$vuetify.lang.t('$vuetify.access.accessEmail')"
            />
            <v-checkbox
              v-model="editAccess.accessPin"
              class="md-6"
              :label="$vuetify.lang.t('$vuetify.access.accessPin')"
            />
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editAccess.description"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.access.description')"
              />
            </v-col>
          </v-row>
        </v-form>
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
  name: 'EditUser',
  data () {
    return {
      formValid: false,
      formRule: {}
    }
  },
  computed: {
    ...mapState('role', ['saved', 'editAccess', 'keys', 'isActionInProgress'])
  },
  methods: {
    ...mapActions('role', ['updateRole', 'toogleEditModal']),

    async updateRoleHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateRole(this.editAccess).then(() => {
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
