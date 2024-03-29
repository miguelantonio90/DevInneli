<template>
  <v-dialog
    v-model="toogleEditModal"
    max-width="600px"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t("$vuetify.titles.edit", [
            $vuetify.lang.t("$vuetify.menu.assistance")
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
              md="12"
            >
              <v-autocomplete
                v-model="editAssistance.user"
                :items="users"
                chips
                :label="
                  $vuetify.lang.t('$vuetify.menu.employee')
                "
                item-text="firstName"
                item-value="id"
                :loading="isUserTableLoading"
                :disabled="!!isUserTableLoading"
                clearable
                :rules="formRule.firstName"
                required
              >
                <template v-slot:selection="data">
                  <v-chip
                    v-bind="data.attrs"
                    :input-value="data.selected"
                    @click="data.select"
                  >
                    <v-avatar left>
                      <v-img
                        :src="
                          data.item.avatar ||
                            '/assets/avatar/avatar-undefined.jpg'
                        "
                      />
                    </v-avatar>
                    {{
                      data.item.firstName +
                        " " +
                        `${
                          data.item.lastName !== null
                            ? data.item.lastName
                            : ""
                        }`
                    }}
                  </v-chip>
                </template>
                <template v-slot:item="data">
                  <template
                    v-if="typeof data.item !== 'object'"
                  >
                    <v-list-item-content
                      v-text="data.item"
                    />
                  </template>
                  <template v-else>
                    <v-list-item-avatar>
                      <img
                        :src="
                          data.item.avatar ||
                            '/assets/avatar/avatar-undefined.jpg'
                        "
                      >
                    </v-list-item-avatar>
                    <v-list-item-content>
                      <v-list-item-title>
                        {{
                          data.item.firstName +
                            " " +
                            `${
                              data.item
                                .lastName !==
                              null
                                ? data.item
                                  .lastName
                                : ""
                            }`
                        }}
                      </v-list-item-title>
                    </v-list-item-content>
                  </template>
                </template>
              </v-autocomplete>
            </v-col>
            <v-col
              cols="12"
              md="12"
            >
              <v-select
                v-model="editAssistance.shop"
                :disabled="!!isShopLoading"
                :items="shops"
                :label="$vuetify.lang.t('$vuetify.menu.shop')"
                :loading="isShopLoading"
                item-text="name"
                required
                :rules="formRule.country"
                return-object
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <app-datetime-picker
                v-model="datetimeEntry"
                :min-date="datetimeEntry"
                :label="
                  $vuetify.lang.t('$vuetify.assistance.entry')
                "
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <app-datetime-picker
                v-model="datetimeExit"
                :min-date="datetimeEntry"
                :label="
                  $vuetify.lang.t('$vuetify.assistance.exit')
                "
              />
            </v-col>
          </v-row>
        </v-form>
        <v-chip class="mr-2">
          <v-icon left>
            mdi-alarm-check
          </v-icon>
          {{ $vuetify.lang.t("$vuetify.assistance.total_hours") }} :
          {{ getTotalHours }}
        </v-chip>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          :disabled="isActionInProgress"
          @click="toogleEditModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.cancel") }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          class="mb-2"
          color="primary"
          :loading="isActionInProgress"
          @click="updateAssistanceHandler"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t("$vuetify.actions.save") }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'
import { differenceInHours } from 'date-fns'

export default {
  data () {
    return {
      formValid: false,
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('assistance', [
      'saved',
      'editAssistance',
      'isActionInProgress'
    ]),
    ...mapState('user', ['users', 'isUserTableLoading']),
    ...mapState('shop', ['shops', 'isShopLoading']),
    datetimeEntry: {
      get () {
        return new Date(this.editAssistance.datetimeEntry)
      },
      set (val) {
        this.editAssistance.datetimeEntry = val
      }
    },
    datetimeExit: {
      get () {
        return new Date(this.editAssistance.datetimeExit)
      },
      set (val) {
        this.editAssistance.datetimeExit = val
      }
    },
    getTotalHours () {
      return `${
                this.editAssistance.datetimeEntry === '' ||
                this.editAssistance.datetimeExit === ''
                    ? 0
                    : differenceInHours(
                          new Date(this.editAssistance.datetimeExit),
                          new Date(this.editAssistance.datetimeEntry)
                      )
            }`
    }
  },
  created () {
    this.getUsers()
    this.getShops()
  },
  mounted () {
    this.formValid = false
  },
  methods: {
    ...mapActions('assistance', ['updateAssistance', 'toogleEditModal']),
    ...mapActions('shop', ['getShops']),
    ...mapActions('user', ['getUsers']),

    async updateAssistanceHandler () {
      if (this.$refs.form.validate()) {
        this.editAssistance.totalHours = this.getTotalHours
        await this.updateAssistance(this.editAssistance)
      }
    }
  }
}
</script>

<style scoped></style>
