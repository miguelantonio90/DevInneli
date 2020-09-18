<template>
  <div class="themeSetting">
    <v-toolbar color="primary">
      <v-toolbar-title class="white--text">
        {{
          $vuetify.lang.t('$vuetify.settings.title')
        }}
      </v-toolbar-title>
    </v-toolbar>
    <v-container>
      <v-row column>
        <v-col>
          <v-select
            v-model="$vuetify.lang.current"
            :items="availableLanguages"
            :label="$vuetify.lang.t('$vuetify.settings.lang')"
            placeholder="Language"
            @change="changeLocale"
          />
          <v-subheader class="px-1 my-2">
            {{
              $vuetify.lang.t('$vuetify.settings.color')
            }}
          </v-subheader>
          <div class="color-option">
            <v-layout wrap>
              <label
                v-for="(option, index) in themeColorOptions"
                :key="index"
                class="color-option--label flex xs6 pa-1"
              >
                <input
                  v-model="themeColor"
                  :value="option.key"
                  name="color"
                  type="radio"
                >
                <span class="color-option--item bg">
                  <span class="overlay">
                    <span
                      class="material-icons"
                    ><v-icon>mdi-check</v-icon></span>
                  </span>
                  <span
                    :class="option.value.sideNav"
                    class="color-option--item--header sideNav"
                  />
                  <span
                    :class="option.value.mainNav"
                    class="color-option--item--header mainNav"
                  />
                  <span
                    :class="option.value.sideMenu"
                    class="sideMenu"
                  />
                </span>
              </label>
            </v-layout>
          </div>
          <div class="theme-options">
            <v-subheader class="px-1 my-2">
              {{
                $vuetify.lang.t('$vuetify.settings.sidebar')
              }}
            </v-subheader>
            <v-divider />
            <div class="my-3">
              <v-btn-toggle v-model="sideBarOption">
                <v-btn
                  text
                  value="dark"
                >
                  Dark
                </v-btn>
                <v-btn
                  text
                  value="light"
                >
                  Light
                </v-btn>
              </v-btn-toggle>
            </div>
          </div>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
import colors from 'vuetify/es5/util/colors'
import localStorage from '../config/localStorage'

export default {
  data () {
    return {
      themeColor: localStorage.getTheme() || 'blue',
      sideBarOption: 'light',
      colors: colors
    }
  },
  computed: {
    themeColorOptions () {
      return [
        {
          key: 'blue',
          value: {
            sideNav: 'blue',
            mainNav: 'blue',
            sideMenu: 'white'
          }
        },
        {
          key: 'teal',
          value: {
            sideNav: 'teal',
            mainNav: 'teal',
            sideMenu: 'white'
          }
        },
        {
          key: 'red',
          value: {
            sideNav: 'red',
            mainNav: 'red',
            sideMenu: 'white'
          }
        },
        {
          key: 'indigo',
          value: {
            sideNav: 'indigo',
            mainNav: 'indigo',
            sideMenu: 'white'
          }
        }
      ]
    },
    availableLanguages () {
      const { locales } = this.$vuetify.lang
      return Object.keys(locales).map((lang) => {
        return {
          text: locales[lang].label,
          value: lang
        }
      })
    }
  },
  watch: {
    themeColor: {
      handler (val) {
        this.$vuetify.theme.themes.light.primary = this.colors[val].base
        localStorage.setTheme(val)
      },
      immediate: true
    },
    sideBarOption: {
      handler (val) {
        this.$vuetify.theme.dark = val === 'dark'
      },
      immediate: true
    },
    updateTheme () {
    }
  },
  methods: {
    changeLocale (lang) {
      this.$vuetify.lang.current = lang
      localStorage.setLanguage(lang)
    }
  }
}
</script>
<style lang="sass" scoped>
.color-option
  &--label
    position: relative
    display: block
    cursor: pointer

    & input[type='radio']
      display: none

      & + span
        position: relative

        & > .overlay
          display: none
          position: absolute
          top: 0
          bottom: 0
          right: 0
          left: 0
          width: 100%
          height: 100%
          background-color: rgba(0, 0, 0, 0.3)
          text-align: center
          line-height: 30px
          color: #fff

      &:checked + span > .overlay
        display: block

    & .bg
      background-color: #f1f1f1

  &--item
    overflow: hidden
    display: block
    box-shadow: 0 0 2px rgba(0, 0, 0, 0.1)
    margin-bottom: 15px

    &--header
      height: 10px

    & > span
      display: block
      float: left
      width: 50%
      height: 20px
</style>
