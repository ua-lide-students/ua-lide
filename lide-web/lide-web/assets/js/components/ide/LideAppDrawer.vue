<!-- Drawer of the application.
Is shown when "drawerOpen" is set to "true"

Catch the OPEN_DRAWER, CLOSE_DRAWER AND TOGGLE_DRAWER events on the EventBus, and change the value of "drawerOpen" accordingly.
Other components should not change this variables, and emit event if a state change is required.
-->

<template>
  <v-navigation-drawer
    v-model="drawerOpen"
    app
    fixed
    clipped
    data-cy="app-drawer"
  >
    <v-layout
      row
      fill-height
    >
      <!-- Lateral nav tab to change between files and settings vue -->
      <!--suppress RequiredAttributes -->
      <lide-app-drawer-tabs
        :open="drawerOpen"
        :dark="options.useDarkTheme"
        v-model="drawerSideActive"
      />
      <!-- Drawer content : switch between files and settings according to the selected tab-->
      <v-layout
        column
        class="w-3/4"
      >
        <template v-if="drawerSideActive === 'files'">
          <lide-app-drawer-panel-files
            :loading-project="loadingProject"
            :files="files"
          />
        </template>
        <template v-if="drawerSideActive === 'settings'">
          <lide-app-drawer-panel-settings
            :value="options"
            :loading="configSaveLoading"
            @save="saveConfiguration"
          />
        </template>
        <!-- If needed supplementary tabs content goes here -->
      </v-layout>
    </v-layout>
  </v-navigation-drawer>
</template>

<script>

import LideAppDrawerTabs from "./LideAppDrawerTabs";
import LideAppDrawerPanelFiles from './LideAppDrawerPanelFiles';
import LideAppDrawerPanelSettings from './LideAppDrawerPanelSettings';

import { Api } from '../../api';
import { EventBus, events } from "../../event-bus";

export default {
  name: 'LideAppDrawer',
  components: { LideAppDrawerTabs, LideAppDrawerPanelFiles, LideAppDrawerPanelSettings },
  props: {
    files: {
      type: Array,
      required: true,
    },
    loadingProject: {
      type: Boolean,
      required: true
    },
    options: {
      type: Object,
      required: true
    },
    apiConnector: {
      type: Api,
      required: true,
    },
  },
  data () {
    return {
      drawerOpen: true,
      configSaveLoading: false,
      drawerSideActive: 'files',
    }
  },
  methods: {
    saveConfiguration () {
      //TODO
    }
  },
  mounted () {
    EventBus.$on(events.OPEN_DRAWER, () => {
      this.drawerOpen = true;
    });
    EventBus.$on(events.CLOSE_DRAWER, () => {
      this.drawerOpen = false;
    });
    EventBus.$on(events.TOGGLE_DRAWER, () => {
      this.drawerOpen = !this.drawerOpen;
    })
  },
}
</script>

