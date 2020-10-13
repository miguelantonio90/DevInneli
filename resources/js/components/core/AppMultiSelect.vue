<template>
  <v-select
    v-model="getSelected"
    :items="items"
    :label="$vuetify.lang.t('$vuetify.menu.shop')"
    :item-text="itemText"
    :item-value="itemValue"
    :loading="loading"
    :disabled="!!loading"
    multiple
    :rules="rules"
    :required="required"
    :return-object="returnObject"
  >
    <template v-slot:prepend-item>
      <v-list-item
        ripple
        @click="toggle"
      >
        <v-list-item-action>
          <v-icon :color="getSelected.length > 0 ? 'indigo darken-4' : ''">
            {{ icon }}
          </v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>
            {{ $vuetify.lang.t('$vuetify.component.select_all') }}
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-divider class="mt-2" />
    </template>
  </v-select>
</template>

<script>
const DEFAULT_ARRAY = []
export default {
  name: 'AppMultiSelect',
  model: {
    prop: 'selected',
    event: 'input'
  },
  props: {
    items: {
      type: Array,
      default: DEFAULT_ARRAY
    },
    selected: {
      type: Array,
      default: DEFAULT_ARRAY
    },
    label: {
      type: String,
      default: ''
    },
    itemText: {
      type: String,
      default: null
    },
    itemValue: {
      type: String,
      default: null
    },
    loading: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    },
    rules: {
      type: Array,
      default: null
    },
    required: {
      type: Boolean,
      default: false
    },
    returnObject: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      select: this.selected
    }
  },

  computed: {
    getSelected: {
      get () {
        return this.select
      },
      set (val) {
        this.select = val
      }
    },
    likesAll () {
      return this.getSelected.length === this.items.length
    },
    likesSome () {
      return this.getSelected.length > 0 && !this.likesAll
    },
    icon () {
      if (this.likesAll) return 'mdi-close-box'
      if (this.likesSome) return 'mdi-minus-box'
      return 'mdi-checkbox-blank-outline'
    }
  },

  methods: {
    toggle () {
      this.$nextTick(() => {
        if (this.likesAll) {
          this.getSelected = []
        } else {
          this.getSelected = this.items.slice()
        }
      })
    }
  }
}
</script>

<style scoped>

</style>
