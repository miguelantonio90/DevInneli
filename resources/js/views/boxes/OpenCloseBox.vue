<template>
  <v-dialog
    v-model="toogleOpenCloseModal"
    max-width="450"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">
          {{ openClose.box.state === 'open'?$vuetify.lang.t('$vuetify.actions.close')
            :$vuetify.lang.t('$vuetify.actions.open') }} {{ openClose.box.name }}
        </span>
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
              v-if="openClose.box.state === 'close'"
              class="py-0"
              cols="12"
              md="6"
            >
              <v-select
                v-model="openClose.openTo"
                style="margin-top: 15px"
                :clearable="users.length > 1"
                :items="users"
                :label="$vuetify.lang.t('$vuetify.to') +':'"
                item-text="firstName"
                return-object
                required
                :rules="formRule.country"
              />
            </v-col>
            <v-col
              v-if="openClose.box.state !== 'open'"
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field-money
                v-model="openClose.cashOpen"
                :label="$vuetify.lang.t('$vuetify.variants.cant')"
                :rules="formRule.required"
                required
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00,
                }"
              />
            </v-col>
          </v-row>
          <v-row v-if="openClose.box.state === 'open'">
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="openClose.openTo.firstName"
                :label="$vuetify.lang.t('$vuetify.actions.open_to') +':'"
                readonly
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="openClose.cashOpen"
                :label="$vuetify.lang.t('$vuetify.boxes.init')"
                :rules="formRule.required"
                required
                readonly
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00,
                }"
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="openClose.cashOpen"
                :label="$vuetify.lang.t('$vuetify.payment.counted')"
                readonly
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
            >
              <v-text-field
                v-model="openClose.cashOpen"
                :label="$vuetify.lang.t('$vuetify.payment.cash')"
                :rules="formRule.required"
                required
                readonly
                :properties="{
                  clearable: true
                }"
                :options="{
                  length: 15,
                  precision: 2,
                  empty: 0.00,
                }"
              />
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
              :disabled="true"
            >
              <template :disabled="true">
                <v-text-field-money
                  v-model="openClose.cashClose"
                  :label="$vuetify.lang.t('$vuetify.payment.credit')"
                  :rules="formRule.required"
                  required
                  :properties="{
                    clearable: true
                  }"
                  :options="{
                    length: 15,
                    precision: 2,
                    empty: 0.00,
                  }"
                />
              </template>
            </v-col>
            <v-col
              class="py-0"
              cols="12"
              md="6"
              :disabled="true"
            >
              <template :disabled="true">
                <v-text-field-money
                  v-model="openClose.cashClose"
                  :label="$vuetify.lang.t('$vuetify.boxes.final')"
                  :rules="formRule.required"
                  required
                  :properties="{
                    clearable: true
                  }"
                  :options="{
                    length: 15,
                    precision: 2,
                    empty: 0.00,
                  }"
                />
              </template>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          :disabled="isActionInProgress"
          @click="toogleOpenCloseModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="openCloseBoxHandler"
        >
          <v-icon>mdi-content-save</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.save') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
  name: 'OpenCloseBox',
  data () {
    return {
      formValid: false,
      formRule: this.$rules,
      totalCount: 0,
      totalCredit: 0,
      totalCash: 0
    }
  },
  computed: {
    ...mapState('boxes', ['saved', 'openClose', 'isActionInProgress']),
    ...mapState('sale', ['sales']),
    ...mapState('user', ['users'])
  },
  async created () {
    console.log(this.openClose)
    this.formValid = false
    if (this.openClose.box.state !== 'open') {
      await this.getUsers().then((s) => {
        this.openClose.openTo = this.users[0]
      })
    }
  },
  methods: {
    ...mapActions('boxes', ['openCloseBox', 'toogleOpenCloseModal']),
    ...mapActions('user', ['getUsers']),
    async openCloseBoxHandler () {
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.openCloseBox(this.openClose)
      }
    }
  }
}
</script>

<style scoped>
</style>
