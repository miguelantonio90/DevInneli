<template>
  <v-card>
    <v-toolbar
      text
      dense
      flat
    >
      <v-toolbar-title>
        {{ title }}
      </v-toolbar-title>
      <v-spacer />
      <v-btn icon>
        <v-icon>mdi-more-vert</v-icon>
      </v-btn>
    </v-toolbar>
    <v-divider />
    <v-card-text class="pa-0">
      <v-data-table
        :headers="headers"
        :items="items"
        hide-default-footer
      >
        <template v-slot:item.avatar="{ item }">
          <v-avatar class="ma-3">
            <img
              :src="item.avatar || `/assets/avatar/sign-no-photo-512.png`"
              alt="avatar"
            >
          </v-avatar>
        </template>
        <template v-slot:item.progress="{ item }">
          <v-progress-linear
            :value="item.progress"
            height="25"
            :color="item.color"
          >
            <template v-slot:default="{ value }">
              <strong>{{ Math.ceil(value) }}%</strong>
            </template>
          </v-progress-linear>
        </template>
      </v-data-table>
      <v-divider />
    </v-card-text>
  </v-card>
</template>

<script>
/* eslint-disable vue/require-default-prop */
export default {
  name: 'PlainTable',
  props: {
    title: String,
    headers: Array,
    items: Array
  }
}
</script>
