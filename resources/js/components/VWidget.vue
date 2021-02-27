<template>
  <v-card tile>
    <v-toolbar
      v-if="enableHeader"
      color="transparent"
      dense
      flat
      text
    >
      <v-toolbar-title>{{ title }}</v-toolbar-title>
      <v-spacer />
      <slot name="widget-header-action" />
    </v-toolbar>
    <v-divider v-if="enableHeader" />
    <v-card-text :class="contentBg">
      <slot name="widget-content" />
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'VWidget',
  props: {
    title: {
      type: String,
      default: ''
    },
    enableHeader: {
      type: Boolean,
      default: true
    },
    contentBg: {
      type: String,
      default: 'white'
    }
  },

  data () {
    return {}
  },
  computed: {}
}
</script>
