<template>
  <v-dialog
    v-model="showImportModal"
    max-width="650"
    persistent
  >
    <v-card>
      <v-card-title>
        <span class="headline">{{
          $vuetify.lang.t('$vuetify.titles.importData')
        }}</span>
      </v-card-title>
      <v-card-text>
        <v-row
          v-if="isActionInProgress"
          class="fill-height"
          align-content="center"
          justify="center"
        >
          <v-col
            class="subtitle-1 text-center"
            cols="12"
          >
            {{ $vuetify.lang.t('$vuetify.import_csv') }}
          </v-col>
          <v-col cols="6">
            <v-progress-linear
              color="deep-purple"
              indeterminate
              rounded
              height="6"
            />
          </v-col>
        </v-row>
        <v-form
          v-show="!isActionInProgress"
          ref="form"
          v-model="formValid"
          class="my-10"
          lazy-validation
        >
          <v-row>
            <v-col
              cols="12"
              md="6"
            >
              <v-file-input
                counter
                show-size
                small-chips
                :rules="formRule.required"
                accept="document/csv"
                :placeholder="$vuetify.lang.t('$vuetify.rule.select')"
                prepend-icon="mdi-file-document"
                :label="$vuetify.lang.t('$vuetify.file')"
                @change="onFileChange"
              />
            </v-col>
            <v-col
              cols="12"
              md="6"
            >
              <v-select
                v-model="importArticle.type"
                clearable
                :items="getSistemFrom"
                :label="$vuetify.lang.t('$vuetify.system')"
                item-text="text"
                item-value="value"
                required
                :rules="formRule.country"
              />
            </v-col>
            <v-col>
              <p><b>{{ $vuetify.lang.t('$vuetify.messages.observation') }}</b></p>
              <p>{{ $vuetify.lang.t('$vuetify.messages.info_import_category') }}</p>
              <p>{{ $vuetify.lang.t('$vuetify.messages.info_import_shop') }}</p>
              <p>{{ $vuetify.lang.t('$vuetify.messages.info_import_ref') }}</p>
            </v-col>
          </v-row>
        </v-form>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          :disabled="isActionInProgress"
          @click="toogleImportModal(false)"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          :disabled="!formValid || isActionInProgress"
          :loading="isActionInProgress"
          class="mb-2"
          color="primary"
          @click="createNewCategory"
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
  name: 'ImportArticle',
  data () {
    return {
      formValid: false,
      hidePinCode1: true,
      hidePinCode2: true,
      errorPhone: null,
      file: '',
      formRule: this.$rules
    }
  },
  computed: {
    ...mapState('article', ['saved', 'showImportModal', 'importArticle', 'isActionInProgress']),
    getSistemFrom () {
      return [
        {
          text: 'LOYVERSE',
          value: 'loyverse'
        },
        {
          text: 'ALEGRA',
          value: 'alegra'
        }
      ]
    }
  },
  created () {
    this.formValid = false
  },
  methods: {
    ...mapActions('article', ['importArticles', 'toogleImportModal']),
    onFileChange (e) {
      // this.file = e.target.files[0]
      this.file = e
    },
    async createNewCategory () {
      const formData = new FormData()
      formData.append('file', this.file)
      formData.append('type', this.importArticle.type)
      if (this.$refs.form.validate()) {
        this.loading = true
        await this.importArticles(formData)
      }
    }
  }
}
</script>

<style scoped>

</style>
