<!--
  Wrapper for the editor and the files tab bar.
  Handle all operations relatives to files.
  For opening file, retrieve the event OPEN_FILE by the event bus
  On file removal, retrieve the event REMOVE_FILE to update the list of openend files.
!-->
<template>
  <v-layout column>
    <div
      class="font-mono"
      style="max-height: 48px;"
    >
      <v-chip
        v-for="(file, index) in openedFiles"
        :key="index"
        small
        class="file-chip"
        color="accent"
        :selected="index === activeFileId"
        close
        @input="closeOpenedFile(index)"
        label
        @click="selectOpenedFile(index)"
      >
        <v-icon
          v-if="file.isSaving"
          class="blink-1s"
          left
        >fa-save</v-icon>
        <!-- <v-icon 
          v-else-if="file.loading"
          left
        >fa-spinner</v-icon> -->
        {{ file.name + (file.modified ? '*' : '')}}
      </v-chip>
    </div>
    <div class="flex-grow flex-1 h-full">
      <div
        class="h-full w-full relative"
        v-if="activeFile"
      >
        <v-btn
          data-cy="save-current-file"
          :disabled="activeFile.loading"
          @click="saveCurrentFile"
          small
          absolute
          round
          fab
          style="top: 0; right:0;"
          class="m-2"
          :color="activeFile.modified ? 'warning' : 'success'"
          :loading="activeFile.isSaving"
        >
          <v-icon>save</v-icon>
        </v-btn>
        <lide-editor
          @save="saveCurrentFile"
          @change="onFileChange"
          ref="editor"
          class="text-lg"
          :session="activeFileEditSession"
          :theme="editorOptions.editorTheme"
          :line-highlight="editorOptions.lineHighlight"
          :font-size="editorOptions.editorTextSize"
          :readonly="activeFile.isSaving"
        />
        <div v-if="activeFile.loading || activeFile.isSaving" class="w-full h-full flex flex-col items-center justify-center absolute" style="top: 0; left: 0; z-index: 4000;">
            <lide-loading-spinner class="mx-auto mw-50" :color="vuetifyTheme.secondary" />
            <v-alert type="info" class="bg-white absolute" style="opacity: 1;" :value="true" outline>{{ activeFile.loading ? "Loading file..." : "Saving..."}}</v-alert>
        </div>

      </div>
      <div
        class="p-4"
        v-else
      >Click on file a in the <a @click="drawerOpen = true">sidebar</a> to open it</div>
    </div>
  </v-layout>
</template>
<script>
import { EventBus, events, notify } from '../../event-bus';

//Components
import LideEditor from './LideEditor.vue';
import LideLoadingSpinner from '../../components/LideLoadingSpinner.vue'

//Mixins
import FileHelper from './mixins/file-helper'
import { ExtensionAceModeMap } from "./extension-ace-mode";
import {
  repositoriesContainer,
} from "../../api";

const { EditSession } = require("brace");

export default {
  name: 'LideEditorWrapper',
  components: { LideEditor, LideLoadingSpinner },
  mixins: [FileHelper],
  props: {
    files: {
      type: Array,
      required: true,
    },
    editorOptions: {
      type: Object,
      required: true
    },
    projectId: {
      type: Number,
      required: true
    }
  },
  data () {
    return {
      activeFileId: 0,
      openedFiles: [],
    }
  },
  mounted () {
    EventBus.$off(events.OPEN_FILE); //Don't know why but component was susbcribed twice. This fix it.
    EventBus.$on(events.OPEN_FILE, this.onOpenFile)
    EventBus.$on(events.REMOVE_FILE, (file) => {
      console.log("editor-wrapper - " + events.REMOVE_FILE, file);
      const index = this.openedFiles.findIndex((f) => f.id === file.id);
      console.log(index); 
      if(index >= 0){
        this.openedFiles.splice(index, 1);
      }
    })
  },
  computed: {
    activeFile () {
      return this.openedFiles[this.activeFileId];
    },
    activeFileEditSession () {
      return this.activeFile ? this.activeFile.session : undefined;
    },
    vuetifyTheme() {
      return this.$vuetify.theme;
    },
  },
  methods: {
    onOpenFile (fileToOpen) {
      console.log("onOpenFile " + fileToOpen.id + " " + fileToOpen.loading);
      let indexInOpenedFile = this.openedFiles.findIndex(file => file.id === fileToOpen.id);
      const index = this.files.findIndex(file => file.id === fileToOpen.id);
      if (indexInOpenedFile !== -1) {
        console.log(indexInOpenedFile);
        this.activeFileId = indexInOpenedFile;
        return;
      }
      if (fileToOpen.loading === true) {
          console.log("file already loading");
        return; //File already loading, don't request it again
      }
      this.$set(fileToOpen, "loading", true);
      this.openedFiles.push(fileToOpen);
      const idx = this.openedFiles.length - 1;
      this.activeFileId = idx;
      
        repositoriesContainer.fileRepository
        .readFile(fileToOpen.id)
        .then(fetchedFile => {
          console.log("File fetched")
          if (!fetchedFile.hasOwnProperty("content")) {
            throw "No content key in api return"; //Defensive in case someone break the API connector
          }
          for (let key in fetchedFile) { //Copy the fetched properties into the original file object so we don't have to touch the files array (since it's passed as prop)
            this.$set(fileToOpen, key, fetchedFile[key]);
          }
          //Boolean representing states of the file in the IDE
          this.$set(fileToOpen, "modified", false); //File content has changed (value set to true on a change event from the editor)
          fileToOpen.loading = false;
          this.$set(fileToOpen, "isSaving", false); // Passed to true when request is made to save content of the file
          this.$set(fileToOpen, "session", this.generateSession( //Ace editor sessions. Store content, cursor position, etc...
            fetchedFile.content,
            this.modeForFile(fetchedFile.name)
          ));
        })
        .catch(err => {
          // eslint-disable-next-line no-console
          console.error(err);
          this.openedFiles.splice(
            this.openedFiles.find(f => f.id === file.id),
            1
          );
          file.loading = false;
          EventBus.$emit(events.NOTIFICATION, {
            type: "error",
            message: `Unable to load file ${file.name}`,
            length: 4000
          });
        });
    },
    closeOpenedFile (index) {
      let file = this.openedFiles[index];
      const fileObj = {
        id: file.id,
        content: file.session.getValue()
      };
      //Save before closing. May be we should ask for confirmation in case the user wants to discard the modifications ?
      this.saveFile(file).then(() => {
        delete file.content;
        delete file.session;
        this.activeFileId = index === 0 ? 0 : index - 1;
        this.openedFiles.splice(index, 1);
      } )
    },
    selectOpenedFile (index) {
      this.activeFileId = index;
    },

    saveFile(file) {
      if(file.isSaving){
        return;
      }
      file.isSaving = true;
      const fileObj = {
        id: file.id,
        content: file.session.getValue()
      };
      return repositoriesContainer.fileRepository
        .updateFile(fileObj)
        .then(() => {
          file.isSaving = false;
          file.modified = false;

          notify("success", `${file.name} successfully saved`, 2000);
        })
        .catch(err => {
          console.log(err);
          file.isSaving = false;
          notify("error", `Unable to save file ${file.name}`, 4000)
        });

    },

    saveCurrentFile () {
      this.saveFile(this.activeFile);
    },
    onFileChange () {
      this.$set(this.activeFile, 'modified', true);
    },

    /**
     * Generate the  editor session object for the given file content and mode
     * @param content
     * @param mode
     * @returns {AceAjax.EditSession}
     */
    generateSession (content, mode) {
      const session = new EditSession(content, mode);
      session.setUseSoftTabs(this.editorOptions.useSoftTabs);
      session.setUseWrapMode(this.editorOptions.useWrapMode);
      return session;
    },
  },
}
</script>
