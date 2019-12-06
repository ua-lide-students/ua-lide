<!--
File view in drawer.
TODO : change the list view to a tree view
 -->
<template>
  <div>
    <v-list
    data-cy="files-list"
      dense>
      <v-subheader>Files</v-subheader>
        <v-list-tile
            v-for="(file, index) in filesArray"
            :key="index"
            @click="selectActiveFile(index)"
            @contextmenu="showContextMenu($event, index)"
            data-cy="files-list-item"
        >
          <v-list-tile-content>
            <v-list-tile-title>{{ file.path + file.name }}</v-list-tile-title>
          </v-list-tile-content>
          <v-menu
              offset-y
          >
            <v-btn
                slot="activator"
                fab
                flat
                small
            ><v-icon>more_vert</v-icon></v-btn>
            <v-list dense>
              <v-list-tile
                  @click="removeFileAtIndex(index)"
              >
                <v-list-tile-title>Delete</v-list-tile-title>
              </v-list-tile>
              <v-list-tile
                  @click="duplicateFileAtIndex(index)"
              >
                <v-list-tile-title>Duplicate</v-list-tile-title>
              </v-list-tile>
              <!--
                <v-list-tile
                  @click="saveFileAtIndex(index)"
              >
                <v-list-tile-title>Save</v-list-tile-title>
              </v-list-tile>
              -->
            </v-list>
          </v-menu>
        </v-list-tile>
    </v-list>
    <v-menu
      v-model="showMenu"
      :position-x="menuX"
      :position-y="menuY"
      absolute
      offset-y
    >
      <v-list dense>
        <v-list-tile
          @click="removeFileAtIndex(contextMenuFileId)"
        >
          <v-list-tile-title>Delete</v-list-tile-title>
        </v-list-tile>
        <v-list-tile
          @click="duplicateFileAtIndex(contextMenuFileId)"
        >
          <v-list-tile-title>Duplicate</v-list-tile-title>
        </v-list-tile>
        <v-list-tile
          @click="saveFileAtIndex(contextMenuFileId)"
        >
          <v-list-tile-title>Save</v-list-tile-title>
        </v-list-tile>
      </v-list>
    </v-menu>
  </div>
</template>

<script>
import {EventBus, events} from "../../event-bus";

export default {
  name: 'LideAppDrawerPanelFiles',
  props: {
    files: {
      type: Array,
      required: true,
    },
    loadingProject: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      menuX: 0,
      menuY: 0,
      showMenu: false,
      contextMenuFileId: 0,
    };
  },
  computed: {
    filesArray() {
      return this.files;
    },
  },
  methods: {
    selectActiveFile(index) {
      EventBus.$emit(events.OPEN_FILE, this.files[index]);
    },
    removeFileAtIndex(index) {
      EventBus.$emit(events.REMOVE_FILE, this.files[index]);
    },
    duplicateFileAtIndex(index) {
      EventBus.$emit(events.DUPLICATE_FILE, index)
      // this.$emit('duplicate-file', index);
    },
    onSaveButtonClick() {
      // TODO start file download
    },
    saveFileAtIndex(index) {
      console.log(`save ${index}`);
      // TODO
    },
    showContextMenu(event, fileId) {
      this.contextMenuFileId = fileId;
      event.preventDefault();
      this.showMenu = false;
      this.menuX = event.clientX;
      this.menuY = event.clientY;
      this.$nextTick(() => {
        this.showMenu = true;
      });
    },
  },
};
</script>

<style scoped>
    .drawer-active-file .v-list__tile__title {
        font-weight: bold;
    }
</style>
