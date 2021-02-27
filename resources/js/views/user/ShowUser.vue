<template>
  <v-dialog
    v-model="toogleShowModal"
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.show', [
            $vuetify.lang.t('$vuetify.menu.user')
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
          <v-row>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.firstName"
                :label="$vuetify.lang.t('$vuetify.firstName')"
                readonly
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.lastName"
                :label="$vuetify.lang.t('$vuetify.lastName')"
                readonly
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.company"
                :label="$vuetify.lang.t('$vuetify.company')"
                readonly
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="editUser.email"
                :label="$vuetify.lang.t('$vuetify.email')"
                readonly
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="editUser.username"
                :label="$vuetify.lang.t('$vuetify.username')"
                readonly
                required
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="6"
            >
              <v-select
                v-model="editUser.country"
                :items="arrayCountry"
                :label="$vuetify.lang.t('$vuetify.country')"
                color="pink"
                item-text="name"
                item-value="id"
                readonly
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="editUser.phone"
                :label="$vuetify.lang.t('$vuetify.phone')"
                autocomplete="off"
                class="hiddenSpinner"
                name="phone"
                readonly
                required
              />
            </v-col>
          </v-row>
          <v-row>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editUser.address"
                :label="$vuetify.lang.t('$vuetify.address')"
                readonly
              />
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-text-field
                v-model="editUser.aboutMe"
                :label="$vuetify.lang.t('$vuetify.about_me')"
                readonly
                required
              />
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          @click="toogleShowModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.close') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'ShowUser',
  data () {
    return {
      formValid: false
    }
  },
  computed: {
    ...mapState('user', ['saved', 'editUser']),
    ...mapState('statics', ['arrayCountry'])
  },
  methods: {
    ...mapActions('user', ['updateUser', 'toogleShowModal'])
  }
}
</script>

<style scoped></style>
