<template>
  <v-layout
    align-center
    justify-center
  >
    <div class="text-md-center">
      <v-container>
        <v-row
          align-content="center"
          class="fill-height"
          justify="center"
        >
          <v-col
            class="subtitle-1 text-center"
            cols="12"
          >
            {{ this.$vuetify.lang.t('$vuetify.wait') }}
          </v-col>
          <v-col cols="6">
            <v-progress-circular
              indeterminate
              color="primary"
            />
          </v-col>
        </v-row>
      </v-container>
    </div>
  </v-layout>
</template>

<script>
export default {
	name: 'AppLoading'
}
</script>

<style scoped>
.v-progress-circular {
    margin: 1rem;
}
</style>
