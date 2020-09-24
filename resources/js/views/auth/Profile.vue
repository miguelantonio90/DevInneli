<template>
  <v-container
    fill-height
    fluid
    grid-list-xl
  >
    <v-layout
      justify-center
      wrap
    >
      <v-flex
        md4
        style="margin-top: 50px"
        xs12
      >
        <material-card class="v-card-profile">
          <v-row
            align="end"
            class="fill-height"
          >
            <v-col
              align-self="start"
              class="pa-0"
              cols="12"
            >
              <avatar-picker
                :image-src="getAvatar"
                :image-style="{ 'border-radius': '50%' }"
                class="profile mx-auto d-block"
                @change="onChangeImage($event)"
              />
              <v-slide-x-transition>
                <div v-if="saving === true && saved === false">
                  <v-btn
                    :loading="saving"
                    class="mx-auto d-block"
                    icon
                  >
                    <v-icon>mdi-content-save</v-icon>
                  </v-btn>
                </div>
              </v-slide-x-transition>
            </v-col>
          </v-row>
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
                  v-text="getFullName"
                />
                <v-list-item-subtitle
                  v-text="
                    $vuetify.lang.t('$vuetify.firstName')
                  "
                />
              </v-list-item-content>
            </v-list-item>
            <v-divider inset />
            <v-list-item href="#">
              <v-list-item-action>
                <v-icon color="indigo">
                  mdi-mail
                </v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title
                  v-text="userData.email"
                />
                <v-list-item-subtitle
                  v-text="$vuetify.lang.t('$vuetify.email')"
                />
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </material-card>
      </v-flex>
      <v-flex
        md8
        xs12
      >
        <material-card
          :text="$vuetify.lang.t('$vuetify.profile.sub_profile')"
          :title="$vuetify.lang.t('$vuetify.profile.edit_profile')"
          color="color"
        >
          <v-form
            ref="form"
            v-model="formValid"
            class="my-10"
            lazy-validation
          >
            <v-container py-0>
              <v-layout wrap>
                <v-flex
                  md4
                  xs12
                >
                  <v-text-field
                    v-model="userData.company"
                    :label="
                      $vuetify.lang.t('$vuetify.company')
                    "
                    :rules="formRule.company"
                  />
                </v-flex>
                <v-flex
                  md4
                  xs12
                >
                  <v-text-field
                    v-model="userData.username"
                    :label="
                      $vuetify.lang.t('$vuetify.username')
                    "
                    :rules="formRule.username"
                  />
                </v-flex>
                <v-flex
                  md4
                  xs12
                >
                  <v-text-field
                    v-model="userData.email"
                    :label="
                      $vuetify.lang.t('$vuetify.email')
                    "
                    :rules="formRule.email"
                  />
                </v-flex>
                <v-flex
                  md3
                  xs12
                >
                  <v-text-field
                    v-model="userData.firstName"
                    :label="
                      $vuetify.lang.t(
                        '$vuetify.first_name'
                      )
                    "
                    :rules="formRule.firstName"
                  />
                </v-flex>
                <v-flex
                  md4
                  xs12
                >
                  <v-text-field
                    v-model="userData.lastName"
                    :label="
                      $vuetify.lang.t(
                        '$vuetify.last_name'
                      )
                    "
                    :rules="formRule.lastName"
                  />
                </v-flex>
                <v-flex
                  md5
                  xs12
                >
                  <vue-tel-input-vuetify
                    v-model="userData.phone"
                    :placeholder="$vuetify.lang.t('$vuetify.phone_holder')"
                    :label="
                      $vuetify.lang.t('$vuetify.phone')
                    "
                    :select-label="$vuetify.lang.t('$vuetify.country')"
                    v-bind="bindProps"
                    :error-messages="errorPhone"
                    @input="onInput"
                  >
                    <template #message="{ key, message }">
                      <slot
                        name="label"
                        v-bind="{ key, message }"
                      />
                      {{ message }}
                    </template>
                  </vue-tel-input-vuetify>
                </v-flex>
                <v-flex
                  md5
                  xs12
                >
                  <v-autocomplete
                    v-model="userData.country"
                    :items="arrayCountry"
                    :label="
                      $vuetify.lang.t('$vuetify.country')
                    "
                    :rules="formRule.country"
                    clearable
                    item-text="name"
                    item-value="id"
                    required
                  >
                    <template
                      slot="item"
                      slot-scope="data"
                    >
                      <template
                        v-if="
                          typeof data.item !==
                            'object'
                        "
                      >
                        <v-list-item-content
                          v-text="data.item"
                        />
                      </template>
                      <template v-else>
                        <v-list-item-avatar>
                          {{
                            data.item.emoji
                          }}
                        </v-list-item-avatar>
                        <v-list-item-content>
                          <v-list-item-title>{{ data.item.name }}</v-list-item-title>
                        </v-list-item-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-flex>
                <v-flex
                  md7
                  xs12
                >
                  <v-text-field
                    v-model="userData.address"
                    :label="
                      $vuetify.lang.t('$vuetify.address')
                    "
                  />
                </v-flex>
                <v-flex
                  text-xs-right
                  xs12
                >
                  <v-btn
                    :disabled="!formValid"
                    :loading="loading"
                    color="primary"
                    @click="updateProfile"
                  >
                    <v-icon>mdi-account-edit</v-icon>
                    {{
                      $vuetify.lang.t(
                        '$vuetify.profile.btn_edit',
                      )
                    }}
                  </v-btn>
                </v-flex>
              </v-layout>
            </v-container>
          </v-form>
        </material-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'Profile',
  data () {
    return {
      color: 'primary',
      formValid: false,
      loading: false,
      saving: false,
      errorPhone: null,
      formRule: {
        company: [
          (v) =>
            !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
              this.$vuetify.lang.t('$vuetify.company')
            ])
        ],
        country: [
          (v) =>
            !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
              this.$vuetify.lang.t('$vuetify.country')
            ])
        ],
        firstName: [
          (v) =>
            !!v || this.$vuetify.lang.t('$vuetify.rule.required', [
              this.$vuetify.lang.t('$vuetify.name')
            ])
        ],
        username: [
          (v) =>
            !!v ||
                        this.$vuetify.lang.t('$vuetify.rule.required', [
                          this.$vuetify.lang.t('$vuetify.username')
                        ])
        ],
        lastName: [
          (v) =>
            !!v ||
                        this.$vuetify.lang.t('$vuetify.rule.required', [
                          this.$vuetify.lang.t('$vuetify.lastName')
                        ])
        ],
        email: [
          (v) =>
            !!v ||
                        this.$vuetify.lang.t('$vuetify.rule.required', [
                          this.$vuetify.lang.t('$vuetify.email')
                        ]),
          (v) =>
            /.+@.+\..+/.test(v) ||
                        this.$vuetify.lang.t('$vuetify.rule.bad_email', [
                          this.$vuetify.lang.t('$vuetify.email')
                        ])
        ]
      }
    }
  },
  computed: {
    ...mapState('auth', ['userData', 'pending']),
    ...mapState('user', ['saved', 'users']),
    ...mapState('statics', ['arrayCountry']),
    getFullName () {
      return `${this.userData.firstName} ${this.userData.lastName || ''}`
    },
    getAvatar () {
      return `${this.userData.avatar ||
            '/assets/avatar/avatar-undefined.jpg'}`
    },
    bindProps () {
      return {
        mode: 'international',
        defaultCountry: 'US',
        disabledFetchingCountry: false,
        autocomplete: 'off',
        dropdownOptions: {
          disabledDialCode: false
        },
        inputOptions: {
          showDialCode: false
        }
      }
    }
  },
  created () {
    this.validateData()
  },
  beforeCreate () {
    this.$nextTick(function () {
      this.getUserData()
    })
  },
  methods: {
    ...mapActions('auth', ['getUserData']),
    ...mapActions('user', ['updateUser', 'updateAvatar']),
    async updateProfile () {
      this.loading = true
      await this.updateUser(this.userData).then(() => {
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
    },
    async onChangeImage (file) {
      this.saving = true
      const id = this.userData.id
      await this.updateAvatar({
        id,
        file
      }).then(() => {
        if (this.saved) {
          const msg = this.$vuetify.lang.t(
            '$vuetify.messages.success_avatar'
          )
          this.$Toast.fire({
            icon: 'success',
            title: msg
          })
        }
      })
    },
    validateData () {
      if (this.userData.username === '') {
        this.color = 'warning'
      } else {
        this.color = 'primary'
      }
    },
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.userData.phone = number
        this.errorPhone = null
      } else {
        this.errorPhone = lang.t('$vuetify.rule.bad_phone', [
          lang.t('$vuetify.phone')
        ])
      }
    }
  }
}
</script>
<style scoped>
.profile {
    width: 150px;
    height: 150px;
    border-radius: 50%;
}

.hiddenSpinner input[type='number'] {
    -moz-appearance: textfield;
}

.hiddenSpinner input::-webkit-outer-spin-button,
.hiddenSpinner input::-webkit-inner-spin-button {
    -webkit-appearance: none;
}
</style>
