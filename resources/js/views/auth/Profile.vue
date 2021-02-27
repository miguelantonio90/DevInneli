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
                  :image-style="{
                    'border-radius': '50%',
                    height: '10em'
                  }"
                  class="profile mx-auto d-block"
                  @input="onChangeImage($event)"
                />
                <v-slide-x-transition>
                  <div
                    v-if="
                      saving === true && saved === false
                    "
                  >
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
              <v-list-item href="#">
                <v-list-item-action>
                  <v-icon color="indigo">
                    mdi-mail
                  </v-icon>
                </v-list-item-action>
                <v-list-item-content>
                  <v-list-item-title
                    v-text="userData.company.email"
                  />
                  <v-list-item-subtitle
                    v-text="
                      $vuetify.lang.t('$vuetify.email')
                    "
                  />
                </v-list-item-content>
              </v-list-item>
            </v-list>
            <template v-slot:actions>
              <v-spacer />
              <v-tooltip top>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn icon>
                    <v-icon
                      v-bind="attrs"
                      color="red"
                      v-on="on"
                    >
                      mdi-delete
                    </v-icon>
                  </v-btn>
                </template>
                <span>{{
                  $vuetify.lang.t(
                    '$vuetify.tips.account_delete'
                  )
                }}</span>
              </v-tooltip>
            </template>
          </material-card>
        </v-flex>
        <v-flex
          md8
          xs12
        >
          <material-card
            :text="$vuetify.lang.t('$vuetify.profile.sub_profile')"
            :title="
              $vuetify.lang.t('$vuetify.profile.edit_profile')
            "
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
                    md6
                    xs12
                  >
                    <v-text-field
                      v-model="userData.company.name"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.company'
                        )
                      "
                      :rules="formRule.company"
                    />
                  </v-flex>
                  <v-flex
                    md6
                    xs12
                  >
                    <v-text-field
                      v-model="userData.company.email"
                      :disabled="!enableEmail"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.email'
                        )
                      "
                      :rules="formRule.email"
                    />
                    <v-btn
                      color="secondary"
                      @click="openConfirm"
                    >
                      {{
                        $vuetify.lang.t(
                          '$vuetify.actions.change'
                        )
                      }}
                    </v-btn>
                  </v-flex>

                  <v-flex
                    md6
                    xs12
                  >
                    <vue-tel-input-vuetify
                      v-model="userData.company.phone"
                      v-bind="bindProps"
                      :error-messages="errorPhone"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.phone'
                        )
                      "
                      :placeholder="
                        $vuetify.lang.t(
                          '$vuetify.phone_holder'
                        )
                      "
                      :prefix="
                        countrySelect
                          ? `+` +
                            countrySelect.dialCode
                          : ``
                      "
                      :rules="formRule.phone"
                      :select-label="
                        $vuetify.lang.t(
                          '$vuetify.country'
                        )
                      "
                      required
                      @input="onInput"
                      @country-changed="onCountry"
                    >
                      <template
                        #message="{ key, message }"
                      >
                        <slot
                          v-bind="{ key, message }"
                          name="label"
                        />
                        {{ message }}
                      </template>
                    </vue-tel-input-vuetify>
                  </v-flex>
                  <v-flex
                    md6
                    xs12
                  >
                    <v-autocomplete
                      v-model="userData.company.country"
                      :items="arrayCountry"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.country'
                        )
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
                            {{ data.item.emoji }}
                          </v-list-item-avatar>
                          <v-list-item-content>
                            <v-list-item-title>
                              {{
                                data.item.name
                              }}
                            </v-list-item-title>
                          </v-list-item-content>
                        </template>
                      </template>
                    </v-autocomplete>
                  </v-flex>
                  <v-flex
                    md12
                    xs12
                  >
                    <v-text-field
                      v-model="userData.company.address"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.address'
                        )
                      "
                      :rules="formRule.required"
                      required
                    />
                  </v-flex>
                  <v-flex
                    md12
                    xs12
                  >
                    <v-text-field
                      v-model="userData.company.slogan"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.slogan'
                        )
                      "
                    />
                  </v-flex>
                  <v-flex
                    md12
                    xs12
                  >
                    <v-text-field
                      v-model="userData.company.footer"
                      :label="
                        $vuetify.lang.t(
                          '$vuetify.footer'
                        )
                      "
                    />
                  </v-flex>
                  <v-flex
                    text-xs-right
                    xs12
                  >
                    <v-btn
                      :disabled="!formValid || loading"
                      :loading="loading"
                      color="primary"
                      @click="updateProfile"
                    >
                      <v-icon>
                        mdi-content-save-all
                      </v-icon>
                      {{
                        $vuetify.lang.t(
                          '$vuetify.profile.btn_edit'
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
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex'

export default {
  name: 'Profile',
  data () {
    return {
      color: 'primary',
      formValid: false,
      enableEmail: false,
      loading: false,
      saving: false,
      loadingData: false,
      errorPhone: null,
      formRule: this.$rules,
      countrySelect: null
    }
  },
  computed: {
    ...mapState('auth', ['userData', 'pending']),
    ...mapState('company', ['saved', 'companies']),
    ...mapState('statics', ['arrayCountry']),
    ...mapGetters('auth', ['user', 'isManagerIn', 'isLoggedIn']),
    getFullName () {
      return `${this.user.firstName} ${this.user.lastName || ''}`
    },
    getCompanyName () {
      return `${this.user.company.name}`
    },
    getAvatar () {
      return `${
          this.user.company.logo
              ? this.user.company.logo
              : '/assets/avatar/avatar-undefined.jpg'
      }`
    },
    bindProps () {
      return {
        mode: 'national',
        clearable: true,
        defaultCountry: this.user.company.country || 'US',
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
    this.loadingData = true
    this.getUserData().then(() => {
      this.loadingData = false
    })
  },
  methods: {
    ...mapActions('auth', ['getUserData']),
    ...mapActions('company', ['updateCompany', 'updateLogo']),
    onCountry (event) {
      this.userData.company.country = event.iso2
      this.countrySelect = event
    },
    async updateProfile () {
      this.loading = true
      await this.updateCompany(this.userData.company).then(() => {
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
      const id = this.userData.company.id
      await this.updateLogo({
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
    onInput (number, object) {
      const lang = this.$vuetify.lang
      if (object.valid) {
        this.userData.company.phone = number
        this.errorPhone = null
      } else {
        this.errorPhone = lang.t('$vuetify.rule.bad_phone', [
          lang.t('$vuetify.phone')
        ])
      }
    },
    openConfirm () {
      const lang = this.$vuetify.lang
      this.$Swal
        .fire({
          title: lang.t('$vuetify.find_password'),
          input: 'password',
          inputAttributes: {
            minlength: 10,
            autocapitalize: 'off',
            autocorrect: 'off'
          },
          confirmButtonText: lang.t('$vuetify.actions.accept'),
          showLoaderOnConfirm: true,
          preConfirm: login => {
          },
          allowOutsideClick: () => !this.$Swal.isLoading()
        })
        .then(result => {
          if (result.isConfirmed) {
            this.enableEmail = true
          }
        })
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
</style>
