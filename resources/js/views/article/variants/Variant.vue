<template>
  <v-data-table
    :headers="headers"
    :items="variantsValues"
    sort-by="calories"
    class="elevation-1"
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-chip
          v-for="tag in variants"
          close
          close-icon="mdi-delete"
          color="success"
          text-color="white"
          @click:close="removeChips(tag)"
        >
          <v-icon
            left
            @click="editChips(tag)"
          >
            mdi-pencil
          </v-icon>
          {{ tag.name }}
        </v-chip>
        <v-spacer />
        <v-dialog
          v-model="dialog"
          max-width="500px"
        >
          <template v-slot:activator="{ on, attrs }">
            <v-btn
              color="primary"
              dark
              class="mb-2"
              v-bind="attrs"
              v-on="on"
            >
              {{ $vuetify.lang.t('$vuetify.actions.newF') }}
            </v-btn>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                    <v-text-field
                      v-model="editedItem.name"
                      style="margin-top: 14px"
                      :label="$vuetify.lang.t('$vuetify.variants.name')"
                    />
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="8"
                  >
                    <v-combobox
                      v-model="select"
                      multiple
                      :label="$vuetify.lang.t('$vuetify.variants.options')"
                      append-icon
                      chips
                      deletable-chips
                      class="tag-input"
                      @keyup.tab="updateTags"
                      @paste="updateTags"
                    />
                  </v-col>
                </v-row>
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer />
              <v-btn
                class="mb-2"
                color="error"
                @click="close"
              >
                <v-icon>mdi-close</v-icon>
                {{ $vuetify.lang.t('$vuetify.actions.cancel') }}
              </v-btn>
              <v-btn
                class="mb-2"
                color="primary"
                @click="save"
              >
                <v-icon>mdi-check</v-icon>
                {{ $vuetify.lang.t('$vuetify.actions.accept') }}
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog
          v-model="dialogDelete"
          max-width="500px"
        >
          <v-card>
            <v-card-title class="headline">
              Are you sure you want to delete this item?
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
export default {
  name: 'Variant',
  data: () => ({
    dialog: false,
    dialogDelete: false,
    headers: [],
    variants: [],
    variantsValues: [],
    editedIndex: -1,
    editedItem: {
      name: '',
      valueVariant: ''
    },
    defaultItem: {
      name: '',
      valueVariant: ''
    },
    select: [],
    items: [],
    search: '' // sync search
  }),
  computed: {
    formTitle () {
      return this.editedIndex === -1
        ? this.$vuetify.lang.t('$vuetify.titles.newF', [
          this.$vuetify.lang.t('$vuetify.variants.variant')
        ])
        : this.$vuetify.lang.t('$vuetify.titles.edit', [
          this.$vuetify.lang.t('$vuetify.variants.variant')
        ])
    }
  },
  watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    }
  },
  created () {
    this.initialize()
  },
  methods: {
    initialize () {
      this.variantsValues = []
      this.headers = [
        {
          text: this.$vuetify.lang.t('$vuetify.variants.name'),
          value: 'name'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.price'),
          value: 'price'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.cost'),
          value: 'cost'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.ref'),
          value: 'ref'
        },
        {
          text: this.$vuetify.lang.t('$vuetify.variants.barCode'),
          value: 'barCode'
        }
      ]
    },
    editChips (tag) {
      this.editedItem.name = tag.name
      this.select = tag.values
      this.dialog = true
    },
    removeChips (tag) {
      this.variants.splice(tag, 1)
      this.myComb()
    },
    updateTags () {
      this.$nextTick(() => {
        this.select.push(...this.search.split(','))
        this.$nextTick(() => {
          this.search = ''
        })
      })
    },
    editItem (item) {
      this.editedIndex = this.variantsValues.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },
    deleteItem (item) {
      this.editedIndex = this.variantsValues.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },
    deleteItemConfirm () {
      this.variantsValues.splice(this.editedIndex, 1)
      this.closeDelete()
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
        Object.assign(this.variantsValues[this.editedIndex], {
          name: this.editedItem.name,
          values: this.select
        })
        this.select = []
      } else {
        this.variants.push({
          name: this.editedItem.name,
          values: this.select
        })
        this.select = []
      }
      this.myComb()
      this.close()
    },
    myComb () {
      const data = this.variants.reverse()
      let result = []
      let localResult = []
      data.forEach((value, index) => {
        if (index === 0) {
          value.values.forEach((localValue, localIndex) => {
            if (localValue) {
              result.push({
                name: localValue.toString(),
                price: '',
                cost: '',
                ref: '',
                barCode: ''
              })
            }
          })
        } else {
          value.values.forEach((localValue, localIndex) => {
            localResult.forEach((v, i) => {
              if (localValue) {
                result.push({
                  name: localValue.toString() + '/' + v.name.toString(),
                  price: '',
                  cost: '',
                  ref: '',
                  barCode: ''
                })
              }
            })
          })
        }
        localResult = result
        index + 1 !== data.length ? result = [] : ''
      })
      this.variantsValues = result
      this.updateVariants()
    },
    updateVariants () {
      this.$emit('updateVariants', this.variants, this.variantsValues)
    }
  }
}
</script>

<style scoped>
.tag-input span.chip {
    background-color: #1976d2;
    color: #fff;
    font-size: 1em;
}

.tag-input span.v-chip {
    background-color: #1976d2;
    color: #fff;
    font-size: 1em;
    padding-left: 7px;
}

.tag-input span.v-chip::before {
    content: "label";
    font-family: 'Material Icons';
    font-weight: normal;
    font-style: normal;
    font-size: 20px;
    line-height: 1;
    letter-spacing: normal;
    text-transform: none;
    display: inline-block;
    white-space: nowrap;
    word-wrap: normal;
    direction: ltr;
    -webkit-font-feature-settings: 'liga';
    -webkit-font-smoothing: antialiased;
}
</style>
