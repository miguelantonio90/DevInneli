<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600px"
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.edit', [
            $vuetify.lang.t('$vuetify.menu.user'),
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
          <avatar-picker
            :image-src="getAvatar"
            :image-style="{ 'border-radius': '50%' }"
            class="profile mx-auto d-block"
            @change="onChangeImage($event)"
          />
          <v-row>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.firstName"

                :label="$vuetify.lang.t('$vuetify.firstName')"
                :rules="formRule.firstName"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.lastName"
                :label="$vuetify.lang.t('$vuetify.lastName')"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.email"
                :label="$vuetify.lang.t('$vuetify.email')"
                :rules="formRule.email"
                autocomplete="off"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.username"
                :label="$vuetify.lang.t('$vuetify.username')"
                autocomplete="off"
                required
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.pinCode"
                :append-icon=" hidePinCode1 ? 'mdi-eye' : 'mdi-eye-off'"
                :label="$vuetify.lang.t('$vuetify.pinCode')"
                :rules="formRule.pinCode"
                :type="hidePinCode1 ? 'password' : 'number'"
                autocomplete="off"
                name="pinCode"
                required
                @keypress="numbers"
                @click:append="hidePinCode1 = !hidePinCode1"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-text-field
                v-model="editUser.confirm_pinCode"
                :append-icon="hidePinCode2 ? 'mdi-eye' : 'mdi-eye-off'"
                :label="$vuetify.lang.t('$vuetify.confirm_pinCode')"
                :rules="formRule.confirm_pinCode"
                :type="hidePinCode2 ? 'password' : 'number'"
                autocomplete="off"
                name="confirm_pinCode"
                required
                @keypress="numbers"
                @click:append="hidePinCode2 = !hidePinCode2"
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
          @click="updateUserHandler"
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
      hidePinCode1: true,
      hidePinCode2: true,
      formRule: {
        firstName: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.name')
          ])
        ],
        email: [
          (v) =>
            !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
              this.$vuetify.lang.t('$vuetify.email')
            ]),
          (v) => /.+@.+\..+/.test(v) ||
                        this.$vuetify.lang.t('$vuetify.rule.bad_email', [
                          this.$vuetify.lang.t('$vuetify.email')
                        ])
        ],
        pinCode: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.pinCode')
          ]),
          (v) => (v && v.length >= 4) || this.$vuetify.lang.t('$vuetify.rule.pin.min', ['4']),
          (v) => (v && v.length <= 6) || this.$vuetify.lang.t('$vuetify.rule.pin.max', ['6'])
        ],
        confirm_pinCode: [
          (v) => !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
            this.$vuetify.lang.t('$vuetify.confirm_pinCode')
          ]),
          (v) => (!!v && v) === this.editUser.pinCode ||
                        this.$vuetify.lang.t(
                          '$vuetify.rule.match',
                          [this.$vuetify.lang.t('$vuetify.pinCode')],
                          [this.$vuetify.lang.t('$vuetify.confirm_pinCode')]
                        )
        ]
      }
    }
  },
  computed: {
    ...mapState('user', ['saved', 'editUser']),
    ...mapState('statics', ['arrayCountry']),
    getAvatar () {
      return `${this.editUser.avatar ||
            '/assets/avatar/avatar-undefined.jpg'}`
    }
  },
  methods: {
    ...mapActions('user', ['updateUser', 'toogleEditModal']),
    numbers (event) {
      const regex = new RegExp('^[0-9]+$')
      const key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
      )
      if (!regex.test(key)) {
        event.preventDefault()
        return false
      }
    },
    onChangeImage (file) {
      this.editUser.avatar = `data:${file.file.type};base64,${file.file.base64}`
    },
    async updateUserHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.updateUser(this.editUser).then(() => {
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
