<template>
  <v-data-table
    :headers="headers"
    :items="composite"
    sort-by="name"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat>
        <h4> {{ $vuetify.lang.t('$vuetify.variants.total_cost') }} ${{ totalCost }}</h4>
        <v-dialog
          v-model="dialogDelete"
          max-width="500px"
        >
          <v-card>
            <v-card-title class="headline">
              {{ $vuetify.lang.t('$vuetify.messages.sure_delete') }}
            </v-card-title>
            <v-card-actions>
              <v-spacer />
              <v-btn
                class="mb-2"
                color="error"
                @click="closeDelete"
              >
                <v-icon>mdi-close</v-icon>
                {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
              </v-btn>
              <v-btn
                class="mb-2"
                color="primary"
                @click="deleteItemConfirm"
              >
                <v-icon>mdi-check</v-icon>
                {{ $vuetify.lang.t('$vuetify.actions.save') }}
              </v-btn>
              <v-spacer />
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.cant="props">
      <v-edit-dialog
        :return-value.sync="props.item.cant"
        large
        persistent
        :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
        :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
        @save="updateData(props.item)"
      >
        <div>{{ props.item.cant }}</div>
        <template v-slot:input>
          <div class="mt-4 title">
            {{ $vuetify.lang.t('$vuetify.actions.edit') }}
          </div>
        </template>
        <template v-slot:input>
          <v-text-field
            v-model="props.item.cant"
            :label="$vuetify.lang.t('$vuetify.actions.edit')"
            single-line
            counter
            autofocus
          />
        </template>
      </v-edit-dialog>
    </template>
    <template v-slot:item.actions="{ item }">
      <v-icon
        small
        @click="deleteItem(item)"
      >
        mdi-delete
      </v-icon>
    </template>
  </v-data-table>
</template>
<script>
/* eslint-disable vue/require-default-prop */

export default {
  name: 'CompositeList',
  props: {
    compositeList: {
      type: Array
    }
  },
  data: () => ({
    totalCost: 0.00,
    dialog: false,
    dialogDelete: false,
    headers: [],
    composite: [],
    editedIndex: -1,
    editedItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0
    },
    defaultItem: {
      name: '',
      calories: 0,
      fat: 0,
      carbs: 0,
      protein: 0
    }
  }),
  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    }
  },
  watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    },
    compositeList: function (val) {
      this.composite = val
      this.updateTotalCost()
    }
  },
  created () {
    this.initialize()
  },

  methods: {
    initialize () {
      this.composite = []
      this.headers = [
        {
          text: this.$vuetify.lang.t('$vuetify.variants.name'),
          value: 'name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cant'),
          value: 'cant'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cost'),
          value: 'cost'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.actions.actions'),
          value: 'actions',
          sortable: false
        }
      ]
    },
    updateData (item) {
      this.composite[this.composite.indexOf(item)].cost = item.price * item.cant
      this.updateTotalCost()
      this.updateComposite()
    },
    updateTotalCost () {
      this.totalCost = 0.00
      this.composite.forEach((comp) => {
        this.totalCost += comp.cant * comp.price
      })
    },
    editItem (item) {
      this.editedIndex = this.composite.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },
    deleteItem (item) {
      this.editedIndex = this.composite.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    deleteItemConfirm () {
      this.composite.splice(this.editedIndex, 1)
      this.closeDelete()
      this.updateComposite()
    },
    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    closeDelete () {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
      })
    },
    save () {
      if (this.editedIndex > -1) {
        Object.assign(this.composite[this.editedIndex], this.editedItem)
      } else {
        this.composite.push(this.editedItem)
      }
      this.close()
    },
    updateComposite () {
      this.$emit('updateComposite', this.composite)
    }
  }
}
</script>

<style scoped>

</style>
