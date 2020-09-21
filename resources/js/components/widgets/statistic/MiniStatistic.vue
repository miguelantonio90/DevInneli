<template>
  <v-card tile>
    <v-card-text class="pa-0">
      <div class="d-flex">
        <div class="flex pa-3">
          <div class="layout justify-center align-center">
            <v-icon
              :color="color"
              size="56px"
            >
              {{ icon }}
            </v-icon>
          </div>
        </div>
        <div
          :class="color"
          class="flex pa-3"
        >
          <div class="white--text">
            {{ title }}
          </div>
          <span class="white--text">{{ subTitle }}</span>
        </div>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      default: ''
    },
    title: {
      type: String,
      default: ''
    },
    subTitle: {
      type: String,
      default: ''
    },
    color: {
      type: String,
      default: ''
    }
  }
}
</script>
