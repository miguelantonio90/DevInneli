<template>
  <v-snackbar
    v-model="snackbar"
    :bottom="y === 'bottom'"
    :color="color"
    :left="x === 'left'"
    :multi-line="mode === 'multi-line'"
    :right="x === 'right'"
    :timeout="timeout"
    :top="y === 'top'"
    :vertical="mode === 'vertical'"
  >
    {{ text }}

    <template v-slot:action="{ attrs }">
      <v-btn
        v-bind="attrs"
        dark
        text
        @click="handleSnackbar"
      >
        Close
      </v-btn>
    </template>
  </v-snackbar>
</template>

<script>
export default {
	name: 'AppSnackbar',
	props: {
		color: {
			type: String,
			default: ''
		},
		mode: {
			type: String,
			default: ''
		},
		showSnackbar: Boolean,
		text: {
			type: String,
			default: ''
		},
		timeout: {
			type: Number,
			default: -1
		},
		x: {
			type: String,
			default: null
		},
		y: {
			type: String,
			default: 'top'
		}
	},
	data () {
		return {
			snackbar: false
		}
	},
	computed: {},
	watch: {
		showSnackbar: {
			handler (val) {
				this.snackbar = val
			},
			immediate: true
		}
	},
	methods: {
		handleSnackbar () {
			this.snackbar = !this.snackbar
		}
	}
}
</script>

<style scoped></style>
