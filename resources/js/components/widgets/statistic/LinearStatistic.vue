<template>
  <v-card tile>
    <v-card-title>
      <div class="layout row ma-0 justify-space-between pb-1">
        <div class="subheading">
          {{ title }}
        </div>
        <div class="icon">
          <v-icon
            :color="color"
            mini
          >
            {{ icon }}
          </v-icon>
        </div>
      </div>
    </v-card-title>
    <v-card-text class="pt-2">
      <h3 class="headline">
        {{ subTitle }}
      </h3>
      <v-progress-linear
        :color="color"
        :value="value"
        height="5"
      />
      <span class="caption">{{ caption }}</span>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: {
    icon: String,
    title: String,
    subTitle: String,
    value: Number,
    color: String
  },
  computed: {
    caption () {
      return this.value + '% ' + this.subTitle
    }
  }
}
</script>

<style></style>
