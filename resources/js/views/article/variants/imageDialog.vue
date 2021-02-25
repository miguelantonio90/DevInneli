<template>
  <v-dialog
    v-model="dialog"
    max-width="500px"
  >
    <v-card>
      <v-card-title>{{ this.$vuetify.lang.t('$vuetify.representation.image') }}</v-card-title>
      <v-card-text>
        <div
          id="multiple-image"
          style="display: flex; justify-content: center;"
        >
          <app-upload-multiple-image
            :data-images="images"
            :upload-success="uploadImage"
          />
        </div>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <v-btn
          class="mb-2"
          @click="$emit('closeDialog')"
        >
          <v-icon>mdi-close</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
        </v-btn>
        <v-btn
          class="mb-2"
          color="primary"
          @click="$emit('saveImage', images)"
        >
          <v-icon>mdi-check</v-icon>
          {{ $vuetify.lang.t('$vuetify.actions.accept') }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
	name: 'ImageDialog',
	props: {
		// images: {
		// 	type: Array,
		// 	default: function () {
		// 	    return []
		// 	}
		// },
		dialog: {
		    type: Boolean,
			default: false
		}
	},
	data () {
	  return {
	      images: []
		}
	},
	methods: {
		uploadImage (formData, index, fileList) {
		    console.log('adsdsdsdsadsadsadass')
			console.log(index, fileList, formData)
		    this.images = fileList
		}
	}
}
</script>

<style scoped>

</style>
