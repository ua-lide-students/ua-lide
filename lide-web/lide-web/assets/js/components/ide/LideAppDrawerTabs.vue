<template>
  <v-navigation-drawer
      dark
      :style="style"
      mini-variant
      color="secondary"
      stateless
      class="py-2"
      :value="open"
  >
    <v-list
        class="pt-0 h-full flex flex-col"
        dense>
      <v-divider/>
      <v-tooltip bottom>
        <v-list-tile
            @click="selectTab('files')"
            class="mb-2"
            slot="activator"
        >
          <v-list-tile-action
          >
            <v-btn
                :class="{drawerSideActive: value === 'files'}"
                title="Files"
                flat
            >
              <v-icon
              >far fa-folder
              </v-icon>
            </v-btn>

          </v-list-tile-action>

          <v-list-tile-content>
            <v-list-tile-title>File</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <span
            class="text-lg">
          Project Files
        </span>
      </v-tooltip>
      <v-tooltip bottom>
          <span class="text-lg">
            Settings
          </span>
        <v-list-tile
            @click="selectTab('settings')"
            slot="activator"
        >
          <v-list-tile-action
          >
            <v-btn
                :class="{'drawerSideActive': value === 'settings'}"
                flat
                file="Settings">
              <v-icon
              >fa-cog
              </v-icon>
            </v-btn>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>Settings</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-tooltip>
      <v-spacer />
      <v-list-tile @click="closeDrawer">
        <v-list-tile-action>
          <v-btn data-cy="close-drawer"
                  flat
          >
            <v-icon>fas fa-times</v-icon>
          </v-btn>
        </v-list-tile-action>
      </v-list-tile>
    </v-list>
  </v-navigation-drawer>

</template>

<script>
import { EventBus, events } from "../../event-bus";
  export default {
    name: "LideAppDrawerTabs",
    props: {
      dark: {
        type: Boolean,
        required: true,
      },
      open: {
        type: Boolean,
        required: true
      },
      value: {
        type: String,
        required: true
      }
    },
    computed: {
      style() {
        if(this.dark) {
          return {'background-color' : 'rgb(50,50,50)'};
        }
        else{
          return {};
        }
      }
    },
    methods: {
      selectTab(tab) {
        this.$emit('input', tab)
      },
      closeDrawer() {
        EventBus.$emit(events.CLOSE_DRAWER)
      }
    },
  }
</script>

<style scoped>

</style>