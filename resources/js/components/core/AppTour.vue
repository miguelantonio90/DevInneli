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
          target: '#drawer-toolbar',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step1')
          /* params: {
            placement: 'left'
          } */
        }, {
          target: '.app--drawer',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step2'),
          params: {
            placement: 'right'
          }
        }, {
          target: '#setting',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step3')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#articles',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step4')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#vending',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step5')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#user',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step6')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#client',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step7')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#finance',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step8')
          /* params: {
            placement: 'top'
          } */
        },
        {
          target: '#resume',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step9')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#navIcon',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step10')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#mdi_lock',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step11')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#mdi_fullscreen',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step12')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#mdi_currency_usd',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step13')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#mdi_cart',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step14')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#menuLang',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step15')
          /* params: {
            placement: 'left'
          } */
        }, {
          target: '#menuUser',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step16'),
          params: {
            placement: 'left'
          }
        }, {
          target: '.setting-fab',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step17')
          /* params: {
            placement: 'top'
          } */
        }, {
          target: '#statistic_dashboard',
          content: this.$vuetify.lang.t('$vuetify.tourMsg.step18')
        }
      ]
    }
  }
}
</script>

<style scoped>

</style>
