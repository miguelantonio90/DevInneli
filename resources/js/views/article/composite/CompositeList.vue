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
      </v-toolbar>
    </template>
    <template v-slot:item.name="{ item }">
      <v-chip
        :key="JSON.stringify(item)"
      >
        <v-avatar
          v-if="item.color"
          class="white--text"
          :color="item.color"
          left
          v-text="item.name.slice(0, 1).toUpperCase()"
        />
        <v-avatar
          v-else
          left
        >
          <v-img :src="item.path" />
        </v-avatar>
        {{ item.name }}
      </v-chip>
    </template>
    <template v-slot:item.cant="{ item }">
      <v-edit-dialog
        :return-value.sync="item.cant"
        large
        persistent
        :cancel-text="$vuetify.lang.t('$vuetify.actions.cancel')"
        :save-text="$vuetify.lang.t('$vuetify.actions.edit')"
        @save="updateData(item)"
      >
        <div>{{ item.cant }}</div>
        <template v-slot:input>
          <div class="mt-4 title">
            {{ $vuetify.lang.t('$vuetify.actions.edit') }}
          </div>
        </template>
        <template v-slot:input>
          <v-text-field
            v-model="item.cant"
            :label="$vuetify.lang.t('$vuetify.actions.edit')"
            single-line
            counter
            autofocus
          />
        </template>
      </v-edit-dialog>
    </template>
    <template v-slot:item.cost="{ item }">
      {{ `${user.company.currency +' '+item.cost}` }}
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

import { mapGetters } from 'vuex'

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
    ...mapGetters('auth', ['user']),
    formTitle () {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    }
  },
  watch: {
    dialog (val) {
      val || this.close()
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
      this.$Swal
        .fire({
          title: this.$vuetify.lang.t('$vuetify.titles.delete', [
            this.$vuetify.lang.t('$vuetify.articles.name')
          ]),
          text: this.$vuetify.lang.t('$vuetify.messages.sure_delete'),
          icon: 'warning',
          showCancelButton: true,
          cancelButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.cancel'
          ),
          confirmButtonText: this.$vuetify.lang.t(
            '$vuetify.actions.delete'
          ),
          confirmButtonColor: 'red'
        })
        .then((result) => {
          if (result.value) {
            this.deleteItemConfirm()
          }
        })
    },
    deleteItemConfirm () {
      this.composite.splice(this.editedIndex, 1)
      this.updateComposite()
    },
    close () {
      this.dialog = false
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
