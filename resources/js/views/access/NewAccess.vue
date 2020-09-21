<template>
  <v-dialog
    v-model="toogleNewModal"
    max-width="450px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.new', [
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
                v-model="newAccess.key"
                :items="keys"
                :label="$vuetify.lang.t('$vuetify.access.key')"
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newAccess.name"
                :counter="10"
                :label="$vuetify.lang.t('$vuetify.access.name')"
                required
              />
            </v-col>
            <v-checkbox
              v-model="newAccess.accessEmail"
              class="md-6"
              :label="$vuetify.lang.t('$vuetify.access.accessEmail')"
            />
            <v-checkbox
              v-model="newAccess.accessPin"
              class="md-6"
              :label="$vuetify.lang.t('$vuetify.access.accessPin')"
            />
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="newAccess.description"
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
          @click="toogleNewModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid"
          class="mb-2"
          color="primary"
          :loading="isActionInProgress"
          @click="createNewRole"
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
      formRule: {
      }
    }
  },
  computed: {
    ...mapState('role', ['saved', 'newAccess', 'keys', 'isActionInProgress'])
  },
  mounted () {
    this.formValid = false
  },
  methods: {
    ...mapActions('role', ['createRole', 'toogleNewModal']),

    async createNewRole () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.createRole(this.newAccess).then(() => {
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

<style scoped>
</style>
