<template>
  <v-card-text>
    <v-list-item
      v-if="showClose"
      two-line
    >
      <v-list-item-title>
        <h2> {{ $vuetify.lang.t('$vuetify.representation.representation') }} </h2>
      </v-list-item-title>

      <v-list-item-icon>
        <v-spacer />
        <v-divider />
        <v-icon
          @click="$emit('closeDialog')"
        >
          mdi-close
        </v-icon>
      </v-list-item-icon>
    </v-list-item>
    <v-row>
      <v-col
        cols="12"
        md="12"
      >
        <v-radio-group
          v-model="representation"
          row
        >
          <v-row>
            <v-col
              cols="12"
              md="8"
            >
              <v-radio
                :label="$vuetify.lang.t('$vuetify.representation.color_shape')"
                value="color"
              />
            </v-col>
            <v-col
              cols="12"
              md="4"
            >
              <v-radio
                :label="$vuetify.lang.t('$vuetify.representation.image')"
                value="image"
              />
            </v-col>
          </v-row>
        </v-radio-group>
      </v-col>
      <v-row>
        <v-col
          v-show="representation===`image`"
          cols="12"
          md="12"
        >
          <div
            id="multiple-image"
            style="display: flex; justify-content: center;"
          >
            <app-upload-multiple-image
              :data-images="articleImages"
              :upload-success="uploadImage"
            />
          </div>
        </v-col>
        <v-col
          v-show="representation===`color`"
          cols="12"
          md="9"
        >
          <app-color-picker
            :value="articleColor || ``"
            @input="inputColor"
          />
        </v-col>
      </v-row>
    </v-row>
  </v-card-text>
</template>
<script>
export default {
  name: 'Representation',
  props: {
    showClose: {
      type: Boolean,
      default: false
    },
    articleColor: {
      type: String,
      default: '#1FBC9C'
    },
    articleImages: {
      type: Array,
      default: function () {
        return []
      }
    }
  },
  data () {
    return {
      representation: 'color'
    }
  },
  created () {
    this.representation = this.articleImages.length > 0 ? 'image' : 'color'
  },
  methods: {
    inputColor (color) {
      this.$emit('inputColor', color)
    },
    uploadImage (formData, index, fileList) {
      this.$emit('uploadImage', fileList)
    }
  }
}
</script>

<style scoped>

</style>
