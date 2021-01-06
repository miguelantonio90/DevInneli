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
    },
    steps () {
      return [
        {
          target: '.app--drawer',
          content: '<strong>Drawer</strong>!<br> Funcionalidades generales por modulos',
          params: {
            placement: 'right'
          }
        }, {
          target: '#setting',
          content: '<strong>Setting</strong>!',
          params: {
            placement: 'top'
          }
        },
        {
          target: '#articles',
          content: '<strong>Articles</strong>!',
          params: {
            placement: 'top'
          }
        },
        {
          target: '#vending',
          content: '<strong>Vending</strong>!',
          params: {
            placement: 'top'
          }
        },
        {
          target: '#user',
          content: '<strong>Employer</strong>!',
          params: {
            placement: 'top'
          }
        },
        {
          target: '#client',
          content: '<strong>Client</strong>!',
          params: {
            placement: 'top'
          }
        },
        {
          target: '#finance',
          content: '<strong>Finance</strong>!',
          params: {
            placement: 'top'
          }
        },
        {
          target: '#resume',
          content: '<strong>Resume</strong>!',
          params: {
            placement: 'top'
          }
        },
        {
          target: '#dashboard',
          content: '<strong>Dashboard</strong>!'
        }
      ]
    }
  }
}
</script>

<style scoped>

</style>
