<template>
  <v-dialog
    v-model="display"
    :width="dialogWidth"
  >
    <template v-slot:activator="{ on }">
      <v-text-field
        v-bind="textFieldProps"
        prepend-icon="mdi-calendar"
        :disabled="disabled"
        :loading="loading"
        :label="label"
        :value="formattedDatetime"
        readonly
        v-on="on"
      >
        <template v-slot:progress>
          <slot name="progress">
            <v-progress-linear
              color="primary"
              indeterminate
              absolute
              height="2"
            />
          </slot>
        </template>
      </v-text-field>
    </template>

    <v-card>
      <v-card-text class="px-0 py-0">
        <v-tabs
          v-model="activeTab"
          fixed-tabs
        >
          <v-tab key="calendar">
            <slot name="dateIcon">
              <v-icon>mdi-calendar</v-icon>
            </slot>
          </v-tab>
          <v-tab
            key="timer"
            :disabled="dateSelected"
          >
            <slot name="timeIcon">
              <v-icon>mdi-clock</v-icon>
            </slot>
          </v-tab>
          <v-tab-item key="calendar">
            <v-date-picker
              v-model="date"
              :min="min"
              v-bind="datePickerProps"
              full-width
              @input="showTimePicker"
            />
          </v-tab-item>
          <v-tab-item key="timer">
            <v-time-picker
              ref="timer"
              v-model="time"
              class="v-time-picker-custom"
              v-bind="timePickerProps"
              full-width
            />
          </v-tab-item>
        </v-tabs>
      </v-card-text>
      <v-card-actions>
        <v-spacer />
        <slot
          name="actions"
          :parent="this"
        >
          <v-btn
            color="grey lighten-1"
            text
            @click.native="clearHandler"
          >
            {{ clearText }}
          </v-btn>
          <v-btn
            color="green darken-1"
            text
            @click="okHandler"
          >
            {{ okText }}
          </v-btn>
        </slot>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { format, parse } from 'date-fns'
const DEFAULT_DATE = ''
const DEFAULT_TIME = '00:00:00'
const DEFAULT_DATE_FORMAT = 'yyyy-MM-dd'
const DEFAULT_TIME_FORMAT = 'HH:mm:ss'
const DEFAULT_DIALOG_WIDTH = 340
const DEFAULT_CLEAR_TEXT = 'CLEAR'
const DEFAULT_OK_TEXT = 'OK'
export default {
  name: 'AppDatetimePicker',
  model: {
    prop: 'datetime',
    event: 'input'
  },
  props: {
    datetime: {
      type: [Date, String],
      default: null
    },
    minDate: {
      type: [Date, String],
      default: null
    },
    disabled: {
      type: Boolean
    },
    loading: {
      type: Boolean
    },
    label: {
      type: String,
      default: ''
    },
    dialogWidth: {
      type: Number,
      default: DEFAULT_DIALOG_WIDTH
    },
    dateFormat: {
      type: String,
      default: DEFAULT_DATE_FORMAT
    },
    timeFormat: {
      type: String,
      default: 'HH:mm'
    },
    clearText: {
      type: String,
      default: DEFAULT_CLEAR_TEXT
    },
    okText: {
      type: String,
      default: DEFAULT_OK_TEXT
    },
    textFieldProps: {
      type: Object,
      default: null
    },
    datePickerProps: {
      type: Object,
      default: null
    },
    timePickerProps: {
      type: Object,
      default: null
    }
  },
  data () {
    return {
      display: false,
      activeTab: 0,
      date: DEFAULT_DATE,
      min: DEFAULT_DATE,
      time: DEFAULT_TIME
    }
  },
  computed: {
    dateTimeFormat () {
      return this.dateFormat + ' ' + this.timeFormat
    },
    defaultDateTimeFormat () {
      return DEFAULT_DATE_FORMAT + ' ' + DEFAULT_TIME_FORMAT
    },
    formattedDatetime () {
      return this.selectedDatetime ? format(this.selectedDatetime, this.dateTimeFormat) : ''
    },
    selectedDatetime () {
      if (this.date && this.time) {
        let datetimeString = this.date + ' ' + this.time
        if (this.time.length === 5) {
          datetimeString += ':00'
        }
        return parse(datetimeString, this.defaultDateTimeFormat, new Date())
      } else {
        return null
      }
    },
    dateSelected () {
      return !this.date
    }
  },
  watch: {
    datetime: function () {
      this.init()
    },
    minDate: function () {
      this.initMinDate()
    }
  },
  mounted () {
    this.init()
    this.initMinDate()
  },
  methods: {
    init () {
      if (!this.datetime) {
        return
      }
      let initDateTime
      if (this.datetime instanceof Date) {
        initDateTime = this.datetime
      } else if (typeof this.datetime === 'string' || this.datetime instanceof String) {
        // see https://stackoverflow.com/a/9436948
        initDateTime = parse(this.datetime, this.dateTimeFormat, new Date())
      }
      this.date = format(initDateTime, DEFAULT_DATE_FORMAT)
      this.time = format(initDateTime, DEFAULT_TIME_FORMAT)
    },
    initMinDate () {
      if (!this.minDate) {
        return
      }
      let initMinDateTime
      if (this.minDate instanceof Date) {
        initMinDateTime = this.minDate
      } else if (typeof this.minDate === 'string' || this.minDate instanceof String) {
        // see https://stackoverflow.com/a/9436948
        initMinDateTime = parse(this.minDate, this.dateTimeFormat, new Date())
      }
      this.min = format(initMinDateTime, DEFAULT_DATE_FORMAT)
    },
    okHandler () {
      this.resetPicker()
      this.$emit('input', this.selectedDatetime)
    },
    clearHandler () {
      this.resetPicker()
      this.date = DEFAULT_DATE
      this.time = DEFAULT_TIME
      this.$emit('input', null)
    },
    resetPicker () {
      this.display = false
      this.activeTab = 0
      if (this.$refs.timer) {
        this.$refs.timer.selectingHour = true
      }
    },
    showTimePicker () {
      this.activeTab = 1
    }
  }
}
</script>
