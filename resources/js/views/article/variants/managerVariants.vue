<template>
  <v-dialog
    v-model="dialog2"
    max-width="500px"
  >
    <v-card>
      <v-card-title>
        Dialog 2
      </v-card-title>
      <v-card-text />
      <v-card-actions>
        <v-btn
          color="primary"
          text
          @click="dialog2 = false"
        >
          Close
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: 'ManagerVariants'
}
</script>

<style scoped>

</style>
