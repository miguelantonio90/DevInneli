<template>
  <v-tour
    :name="name"
    :steps="steps"
    :options="stepsOptions"
  />
</template>

<script>
export default {
	name: 'AppTour',
	props: {
		name: {
			type: String,
			default: 'MyTour'
		},
		steps: {
			type: Array,
			default: () => { return [] }
		}
	},
	computed: {
		stepsOptions () {
			return {
				useKeyboardNavigation: true,
				labels: {
					buttonSkip: this.$vuetify.lang.t('$vuetify.tour.buttonSkip'),
					buttonPrevious: this.$vuetify.lang.t('$vuetify.tour.buttonPrevious'),
					buttonNext: this.$vuetify.lang.t('$vuetify.tour.buttonNext'),
					buttonStop: this.$vuetify.lang.t('$vuetify.tour.buttonStop')
				}
			}
		}
	}
}
</script>

<style scoped>

</style>
