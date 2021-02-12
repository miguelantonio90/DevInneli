<template>
  <v-row
    justify="space-around"
    style="margin-left: 0"
  >
    <div class="form__field">
      <div class="form__label">
        <strong>{{ $vuetify.lang.t('$vuetify.component.fieldColor') }}:</strong>
        <v-swatches
          v-model="color"
          inline
          @input="input($event)"
        />
      </div>
    </div>
  </v-row>
</template>

<script>

import VSwatches from 'vue-swatches'
export default {
	name: 'AppColorPicker',
	components: { VSwatches },
	props: {
		value: {
			type: String,
			default: '#1FBC9C',
			required: true
		}
	},
	data () {
		return {
			color: this.value
		}
	},
	methods: {
		input ($event) {
			this.$emit('input', $event)
		}
	}
}
</script>

<style scoped>

</style>
