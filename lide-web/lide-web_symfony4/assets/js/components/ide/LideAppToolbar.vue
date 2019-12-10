<template>
    <v-toolbar
            color="primary"
            dark
            fixed
            app
            clipped-left
    >
        <!-- Drawer toggle button -->
        <v-toolbar-side-icon @click="toggleDrawer" data-cy="drawer-toggle" />
        <v-toolbar-title>
          <v-btn :href="lideBaseUrl" flat class="v-toolbar__title">LIDE - Université d'Angers</v-btn>
        </v-toolbar-title>
        <!-- File menu -->
        <v-toolbar-items class="ml-2">
            <lide-app-toolbar-menu-file
                @submit-new-file="submitNewFile($event)"
            />
        </v-toolbar-items>
        <v-spacer/>
        <!-- Environment selector -->
        <v-toolbar-items>
            <v-menu offset-y>
                <v-btn
                        slot="activator"
                        flat
                >
                    Environment : {{ selectedEnvironment.name }}
                    <v-icon>arrow_drop_down</v-icon>
                </v-btn>
                <v-list>
                    <v-list-tile
                            v-for="availableEnvironment in availableEnvironments"
                            :key="availableEnvironment.id"
                            v-if="availableEnvironment.id !== selectedEnvironment.id"
                            @click="selectEnvironment(availableEnvironment)"
                    >
                        <v-list-tile-title>
                            <v-tooltip bottom>
                          <span slot="activator">
                            {{ availableEnvironment.name }}
                          </span>
                                <span>{{ availableEnvironment.description }}</span>
                            </v-tooltip>
                        </v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-btn :href="lideBaseUrl" flat>
              Home
            </v-btn>
        </v-toolbar-items>
    </v-toolbar>
</template>

<script>
  import LideAppToolbarMenuFile from './LideAppToolbarMenuFile';
  import {EventBus, events} from "../../event-bus";

  export default {
    name: 'lide-app-toolbar',
    components: {LideAppToolbarMenuFile},
    props: {
      availableEnvironments: {
        type: Array,
        required: true,
        default() {
          return [];
        }
      },
      selectedEnvironment: {
        type: Object,
        required: true,
      },
      lideBaseUrl: {
        type: String,
        required: true,
      }
    },
    methods: {
      toggleDrawer() {
        EventBus.$emit(events.TOGGLE_DRAWER);
      },
      selectEnvironment(environment) {
        this.$emit('select-env', environment);
      },
      submitNewFile(event){
        this.$emit('submit-new-file', event);
      }
    },
  }
</script>

<style scoped>

</style>
