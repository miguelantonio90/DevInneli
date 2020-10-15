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
                item-text="name"
                item-value="value"
                :label="$vuetify.lang.t('$vuetify.access.key')"
                requiered
                :rules="formRule.key"
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="newAccess.name"
                :label="$vuetify.lang.t('$vuetify.access.name')"
                required
                :rules="formRule.position"
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
      formRule: this.$rules
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
        await this.createRole(this.newAccess)
      }
    }
  }
}
</script>

<style scoped>
</style>
