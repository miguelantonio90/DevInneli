<template>
  <v-card>
    <v-card-title>
      {{ title }}
    </v-card-title>
    <v-data-table
      :headers="getColumns"
      :items="rows"
      :loading="isLoading"
      :search="search"
      :class="getClassStyle"
      :sort-by="sortOptions"
    >
      <template v-slot:top>
        <v-toolbar flat>
          <v-text-field
            v-model="search"
            :label="
              $vuetify.lang.t('$vuetify.actions.search')
            "
            append-icon="mdi-magnify"
            hide-details
            single-line
          />
          <v-spacer />
          <v-btn
            class="mb-2"
            color="primary"
            @click="createButtonClicked(true)"
          >
            <v-icon>mdi-plus</v-icon>
            {{ $vuetify.lang.t('$vuetify.actions.new') }}
          </v-btn>
        </v-toolbar>
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon
          v-if="viewShowButton"
          class="mr-2"
          small
          @click="showButtonClicked(item.id)"
        >
          mdi-eye
        </v-icon>
        <v-icon
          v-if="viewEditButton"
          class="mr-2"
          small
          @click="editButtonClicked(item.id)"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          v-if="viewDeleteButton"
          small
          @click="deleteButtonClicked(item.id)"
        >
          mdi-delete
        </v-icon>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
export default {
  name: 'AppDataTable',
  props: {
    title: {
      type: String,
      default: '',
      required: false
    },
    hideHeaders: {
      type: Boolean,
      default: false,
      required: false
    },
    headersLength: {
      type: Number,
      default: null,
      required: false
    },
    isLoading: {
      type: Boolean,
      default: false,
      required: false
    },
    refreshAction: {
      type: Boolean,
      default: true,
      required: false
    },
    createAction: {
      type: Boolean,
      default: true,
      required: false
    },
    createButtonTitle: {
      type: String,
      default: '',
      required: false
    },
    columns: {
      type: Array,
      required: true
    },
    sortOptions: {
      type: String,
      default: '',
      required: false
    },
    rows: {
      type: Array,
      required: true
    },
    viewShowButton: {
      type: Boolean,
      default: false
    },
    viewEditButton: {
      type: Boolean,
      default: true
    },
    viewDeleteButton: {
      type: Boolean,
      default: true
    }
  },
  data () {
    return {
      search: ''
    }
  },
  computed: {
    // Done to get the ordered headers
    getColumns () {
      return Object.values(this.columns)
    }
  },
  methods: {
    createButtonClicked () {
      this.$emit('create-row')
    },
    refreshButtonClicked () {
      this.$emit('refresh-table')
    },
    editButtonClicked (clickedRowId) {
      this.$emit('edit-row', clickedRowId)
    },
    showButtonClicked (clickedRowId) {
      this.$emit('show-row', clickedRowId)
    },
    deleteButtonClicked (clickedRowId) {
      this.$emit('delete-row', clickedRowId)
    },
    getClassStyle () {
      return 'elevation-1'
    }
  }
}
</script>

<style scoped>

</style>
