<template>
  <v-menu
    offset-y
  >
    <v-btn
      slot="activator"
      flat
      data-cy="file-menu"
    >
      <span>Files</span>
      <v-icon>arrow_drop_down</v-icon>
    </v-btn>
    <v-list>
      <v-list-tile @click="openFileForm" data-cy="file-menu-new">
        <v-list-tile-title>New File</v-list-tile-title>
      </v-list-tile>
      <v-list-tile @click="onImportFileButtonClick">
        <v-list-tile-title>Import</v-list-tile-title>
        <input
          class="absolute"
          style="top: -999999px"
          type="file"
          accept="*"
          :multiple="false"
          ref="importFileInput"
          @change="onImportFileChange">
      </v-list-tile>
      <!-- Export project - UNUSED FOR NOW -->
      <!--
      <v-list-tile>
        <v-list-tile-title @click="onExportFileClick">Export</v-list-tile-title>
      </v-list-tile>
      --> 
    </v-list>
    <v-dialog
      v-model="newFileForm.show"
      max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">New File</span>
        </v-card-title>
        <v-card-text>
          <v-form
            v-model="newFileForm.formValid"
            @submit.prevent="submitCreateNewFile"
          >
            <v-text-field
              data-cy="new-file-name"
              v-model="newFileForm.name"
              label="Name"
              required
              :suffix="selectedModelExtension"
            />
            <v-select
              clearable
              label="Model"
              v-model="newFileForm.model"
              :items="fileModels"
              item-text="name"
              return-object
            />
            <v-textarea
              v-if="newFileForm.model"
              readonly
              :value="newFileForm.model.content"
              />
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer/>
          <v-btn
            color="blue darken-1"
            flat
            @click.native="newFileForm.show = false">Cancel</v-btn>
          <v-btn
            color="blue darken-1"
            flat
            data-cy="new-file-submit" 
            @click.native="submitCreateNewFile">Create</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-menu>
</template>

<script>
export default {
  props: {
    fileModels: {
      type: Array,
      required: false,
      default() {
        return [
          {
            name: 'Main File',
            ext: 'cpp',
            content: '//\n' +
              // eslint-disable-next-line
              '// Created by ${USER_NAME} on ${CURRENT_DATE}\n' +
              '//\n' +
              '\n' +
              '#include <iostream>\n' +
              'using namespace std;\n' +
              '\n' +
              'int main() \n' +
              '{\n' +
              '    return 0;\n' +
              '}',
          },
          {
            name: 'Cpp File',
            ext: 'cpp',
            content: '//\n' +
              // eslint-disable-next-line
              '// Created by ${USER_NAME} on ${CURRENT_DATE}\n' +
              '//\n' +
              '\n',
          },
          {
            name: 'Header File',
            ext: 'h',
            // eslint-disable-next-line
            content: '//\n' +
              // eslint-disable-next-line
              '// Created by ${USER_NAME} on ${CURRENT_DATE}\n' +
              '//\n' +
              '\n' +
              // eslint-disable-next-line
              '#IFNDEF ${FILE_NAME}_H\n' +
              // eslint-disable-next-line
              '#DEFINE ${FILE_NAME}_H\n' +
              '\n' +
              '#ENDIF',
          },
        ];
      },
    },
  },

  data() {
    return {
      newFileForm: {
        show: false,
        name: '',
        formValid: false,
        model: null,
      },
    };
  },
  computed: {
    selectedModelExtension() {
      return this.newFileForm.model ? `.${this.newFileForm.model.ext}` || '' : '';
    },
    selectedModelContent() {
      return this.newFileForm.model ? this.newFileForm.model.content || '' : '';
    },
  },
  methods: {
    openFileForm() {
      this.newFileForm.show = true;
    },
    resetNewFileForm() {
      this.newFileForm.name = '';
    },
    submitCreateNewFile() {
      // TODO validation
      this.$emit('submit-new-file', { name: this.newFileForm.name + this.selectedModelExtension, content: this.selectedModelContent });
      this.newFileForm.show = false;
      this.resetNewFileForm();
    },
    onImportFileButtonClick() {
      this.$refs.importFileInput.click();
    },
    onImportFileChange($event) {
      // TODO multi file selection
      // TODO handle zip archive
      const files = $event.target.files || $event.dataTransfer.files;
      if (files) {
        if (files.length > 0) {
          const file = files[0];
          const reader = new FileReader();
          reader.onload = () => {
            this.$emit('submit-new-file', { name: file.name, content: reader.result });
          };
          reader.readAsText(file);
        }
      }
    },
    onExportFileClick() {
      this.$emit('export-file');
    },
  },
  name: 'FileMenus',
};
</script>

<style scoped>

</style>
