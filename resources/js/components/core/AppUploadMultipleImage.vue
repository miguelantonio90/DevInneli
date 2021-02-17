<template>
  <div
    id="multiple-image"
    style="display: flex; justify-content: center;"
  >
    <vue-upload-multiple-image
      :data-images="dataImages"
      :primary-text="primaryText"
      :mark-is-primary-text="markIsPrimaryText"
      :drag-text="dragText"
      :browse-text="browseText"
      :popup-text="popupText"
      :drop-text="dragText"
      :multiple="multiple"
      :show-primary="showPrimary"
      :max-image="maxImage"
      :id-upload="idUpload"
      :id-edit="idEdit"
      :show-add="showAdd"
      :show-edit="showEdit"
      :show-delete="showDelete"
      :disabled="disabled"
      @upload-success="uploadSuccess"
      @before-remove="beforeRemove"
      @edit-image="editImage"
      @data-change="dataChange"
    />
  </div>
</template>

<script>
import VueUploadMultipleImage from 'vue-upload-multiple-image'
export default {
	name: 'AppUploadMultipleImage',
	components: { VueUploadMultipleImage },
	props: {
		accept: {
			type: String,
			default: 'image/gif,image/jpeg,image/png,image/bmp,image/jpg'
		},
		dataImages: {
			type: Array,
			default: () => {
				return []
			}
		},
		uploadSuccess: {
			type: Function,
			default: () => {}
		},
		multiple: {
			type: Boolean,
			default: true
		},
		showPrimary: {
			type: Boolean,
			default: true
		},
		maxImage: {
			type: Number,
			default: 5
		},
		idUpload: {
			type: String,
			default: 'image-upload'
		},
		idEdit: {
			type: String,
			default: 'image-edit'
		},
		showEdit: {
			type: Boolean,
			default: true
		},
		showDelete: {
			type: Boolean,
			default: true
		},
		showAdd: {
			type: Boolean,
			default: true
		},
		disabled: {
			type: Boolean,
			default: false
		}
	},
	data () {
		return {
			images: []
		}
	},
	computed: {
		dragText () {
			return this.$vuetify.lang.t('$vuetify.component.images.dragText')
		},
		primaryText () {
			return this.$vuetify.lang.t('$vuetify.component.images.primaryText')
		},
		markIsPrimaryText () {
			return this.$vuetify.lang.t('$vuetify.component.images.markIsPrimaryText')
		},
		popupText () {
			return this.$vuetify.lang.t('$vuetify.component.images.popupText')
		},
		dropText () {
			return this.$vuetify.lang.t('$vuetify.component.images.dropText')
		},
		browseText () {
			return this.$vuetify.lang.t('$vuetify.component.images.browseText')
		}
	},
	methods: {
		beforeRemove (index, done, fileList) {
			this.$Swal
				.fire({
					title: this.$vuetify.lang.t('$vuetify.titles.delete', [
						this.$vuetify.lang.t('$vuetify.component.image')
					]),
					text: this.$vuetify.lang.t(
						'$vuetify.messages.warning_delete'
					),
					icon: 'warning',
					showCancelButton: true,
					cancelButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.cancel'
					),
					confirmButtonText: this.$vuetify.lang.t(
						'$vuetify.actions.delete'
					),
					confirmButtonColor: 'red'
				})
				.then((result) => {
					if (result.isConfirmed) {
						done()
					}
					this.$emit('delete', fileList)
				})
		},
		editImage (formData, index, fileList) {
			this.$emit('edit-image', fileList)
		},
		dataChange (data) {
			this.$emit('change', data)
		}
	}

}
</script>

<style scoped>
#multiple-image {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 0px;
}
</style>
