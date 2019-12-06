<!--
Main component of the IDE part of the application.
This handle the main layout
-->  
<template>
  <v-app
    class="h-screen"
    :dark="options.useDarkTheme"
  >
    <!-- Navigation Drawer on the side of the app -->
    <lide-app-drawer
      :files="files"
      :loading-project="loadingProject"
      :options="options"
      :api-connector="api"
    />
    <!-- Application Toolbar -->
    <lide-app-toolbar
      :available-environments="environments"
      :selected-environment="selectedEnvironment"
      :lideBaseUrl="lideWebBaseUrl"
      @toggle-drawer="toggleDrawer"
      @select-env="selectEnvironnement($event)"
      @submit-new-file="submitNewFile($event)"
    />
    <!-- On application start, load the project from the API. Show a spinner while waiting -->
    <v-content
      v-if="loadingProject"
      fill-height
      fluid
    >
      <v-layout
        column
        flex
        align-center
        justify-center
        fill-height
      >
        <lide-loading-spinner :color="vuetifyTheme.secondary"></lide-loading-spinner>
        <div class="title mt-2">Loading ...</div>
      </v-layout>
    </v-content>
    <!-- If error while loading project from the api -->
    <v-content v-else-if="projectLoadingError">
      <v-layout
        column
        flex
        align-center
        justify-center
        fill-height
      >
        <v-alert
          :value="true"
          type="error"
          outline
          data-cy="loading-error"
        >
           {{ projectLoadingError }}
         </v-alert>
      </v-layout>
    </v-content>
    <!-- Once application is started, layout with editor + console -->
    <v-content v-else>
      <v-container
        fill-height
        fluid
        class="pa-0"
      >
        <v-layout column>
          <v-layout
            :row="!options.layoutStacked"
            :column="options.layoutStacked"
            fill-height
            xs12
          >
            <!-- Editor -->
            <v-flex
              d-flex
              :style="{'height' : options.layoutStacked && showConsole? 'calc(50% - 3px)' : '100%'}"
            >
              <lide-editor-wrapper
                :files="files"
                :editor-options="options"
                :api-connector="api"
                :project-id="projectId"
              />
            </v-flex>
            <div
              v-if="showConsole"
              class="cursor-move bg-grey-light relative"
              style="min-height: 6px; min-width: 6px"
              :class="{'h-full' : !options.layoutStacked,
                         'w-full' : options.layoutStacked,}"
            >
              <div
                class="bg-black absolute"
                :style="{
                    'top': options.layoutStacked ? '50%' : 'calc(50% - 2rem)',
                    'left': options.layoutStacked ? 'calc(50% - 2 rem)' : '50%'
                  }"
                :class="{
                    'h-8' : !options.layoutStacked,
                    'w-8' : options.layoutStacked,
                    'h-px' : options.layoutStacked,
                    'w-px': !options.layoutStacked}"
              />
            </div>
            <v-flex
              v-if="showConsole"
              xs6
              d-flex
              :fill-height="!options.layoutStacked"
              :style="{'max-height' : options.layoutStacked ? 'calc(50% - 4px)' : '100%'}"
              class="relative"
              :class="{'h-full' : !options.layoutStacked}"
            >
              <lide-console-wrapper
                :jwt="jwt"
                :project-id="projectId"
                :ws-adress="wsUrl"
                :selected-environment="selectedEnvironment"
              />
            </v-flex>
          </v-layout>
        </v-layout>
        <div
          class="absolute m-2"
          :style="toggleTerminalStyle"
        >
          <v-tooltip left>
            <button
              @click="toggleConsole"
              class="text-xl text-grey "
              slot="activator"
            >
              <v-icon color="grey">{{ showConsoleIcon }}
              </v-icon>
            </button>
            <span>{{ showConsole ? 'Hide' : 'Show' }} Terminal</span>
          </v-tooltip>
        </div>

      </v-container>
    </v-content>
    <lide-notifications style="position: absolute; top : 0; right: 0; left: 0; margin: auto; max-width: 100%; width: 480px; z-index: 1150" />
  </v-app>
</template>
<script>

import LideAppToolbar from "./LideAppToolbar";
import LideAppDrawer from "./LideAppDrawer";
import LideEditorWrapper from './LideEditorWrapper.vue';
import LideLoadingSpinner from "../../components/LideLoadingSpinner";
import LideNotifications from "../LideNotifications";
import LideConsoleWrapper from './LideConsoleWrapper';

import fileManagement from './mixins/files-management';

import { Api } from "../../api/index"
import { EventBus, events } from '../../event-bus';

export default {
  name: 'App',
  mixins: [fileManagement],
  components: {
    LideLoadingSpinner,
    LideAppDrawer,
    LideAppToolbar,
    LideEditorWrapper,
    LideConsoleWrapper,
    LideNotifications,
  },
  props: {
    user: {
      type: Object,
      required: true,
    },
    projectId: {
      type: Number,
      required: true
    },
    userOptions: {
      type: Object,
      required: false,
      default () {
        return {
        };
      },
    },
    lideProjectManagerUrl: {
      type: String,
      required: true
    },
    lideWebBaseUrl: {
      type: String,
      required: false,
      default () {
        return '/app.php/'
      }
    },
    wsUrl: {
      type: String,
      required: true
    },
    jwt: {
      type: String,
      required: true
    },
  },
  data () {
    let options = {
      useSoftTabs: true,
      useWrapMode: true,
      lineHighlight: true,
      useDarkTheme: false,
      editorTheme: 'crimson_editor',
      editorTextSize: 12,
      layoutStacked: false,
    };

    Object.keys(options).forEach((key) => {
      if (this.userOptions.hasOwnProperty(key)) {
        options[key] = this.userOptions[key];
      }
    });

    return {
      api: new Api(this.lideWebBaseUrl),
      loadingProject: false,
      // Console
      showConsole: true,
      consoleSide: true,
      // Configuration
      options: options,
      ideConfigDialog: false,
      configSaveLoading: false,
    };
  },
  mounted () {
    EventBus.$on(events.CHANGE_USER_OPTIONS, (opt) => {
      console.log(events.CHANGE_USER_OPTIONS, opt);
      this.$set(this, 'options', opt);
    });
  },
  computed: {
    vuetifyTheme () {
      return this.$vuetify.theme;
    },
    showConsoleIcon () {
      if (this.options.layoutStacked) {
        if (this.showConsole) {
          // return 'fas fa-eye-slash';
          return 'fas fa-arrow-down';
        }
        return 'fas fa-arrow-up';
      }
      if (this.showConsole) {
        // return 'fas fa-eye-slash';
        return 'fas fa-arrow-right';
      }
      return 'fas fa-arrow-left';
    },
    toggleTerminalStyle () {
      if (this.options.layoutStacked) {
        if (this.showConsole) {
          return { right: '0', top: '50%' };
        }
        return { right: '0', bottom: '0' };
      }
      return { right: '0', top: '0' };
    },
  },
  methods: {
    toggleDrawer () {
      this.drawerOpen = !this.drawerOpen;
    },
    toggleConsole () {
      this.showConsole = !this.showConsole;
    },
    saveConfiguration () {
      console.log('coucou')
      this.configSaveLoading = true;
      this.api.saveUserConfiguration(this.options
      ).then((response) => {
        console.log(response);
        this.configSaveLoading = false;
      }).catch((err) => {
        console.error(err);
        this.configSaveLoading = false;
      })
    },

    selectEnvironnement (environement) {
      this.selectedEnvironnenemt = environement;
      // TODO necessary change on environnement change
    },
  },
  watch: {
    // activeFile(value) {
    // },
    // eslint-disable-next-line object-shorthand
    'options.useSoftTabs' (value) {
      this.files.forEach((file) => {
        file.session.setUseSoftTabs(value);
      });
    },
    // eslint-disable-next-line object-shorthand
    'options.useWrapMode' (value) {
      this.files.forEach((file) => {
        file.session.setUseWrapMode(value);
      });
    },
  },
};
</script>


<style scoped>
.container {
  padding: 0 0 0 0;
}

.v-tabs__div {
  text-transform: none;
}
</style>
