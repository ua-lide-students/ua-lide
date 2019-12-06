import { EventBus, events } from "../../../event-bus";
import FileHelper from "./file-helper";
import {
  repositoriesContainer,
  initFileRepository,
  initProjectRepository
} from "../../../api/index";
const { EditSession } = require("brace");

export default {
  mixins: [FileHelper],
  props: {
    filesOpenOnStart: {
      type: Array,
      required: false,
      default() {
        return [];
      }
    },
    environments: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      project: {},
      projectLoadingError: null,
      files: [],
      templateStringFunctions: [
        content => content.replace("${USER_NAME}", this.user.username),
        content =>
          content.replace("${CURRENT_DATE}", new Date().toLocaleDateString())
      ],
      selectedEnvironment: {},
      idsFilesBeingRemoved: new Set()
    };
  },
  mounted() {
    this.loadingProject = true;

    initProjectRepository(this.lideProjectManagerUrl, this.jwt);
    repositoriesContainer.projectRepository
      .readProject(this.projectId)
      .then(project => {
        this.project = project;
        this.selectedEnvironment =
          this.environments.find(env => env.id === project.environnementId) ||
          this.environments[0]; //TODO notify user if env not available
        initFileRepository(this.lideProjectManagerUrl, this.jwt, this.project);
        console.log("Project ok");
        repositoriesContainer.fileRepository
          .readProjectFiles()
          .then(files => {
            // eslint-disable-next-line no-console
            console.log(files);
            this.files = files;
            EventBus.$emit(events.NOTIFICATION, {
              message: "Files loaded",
              length: 1000,
              type: "info"
            });
            this.loadingProject = false;
          })
          .catch(err => {
            console.log(err);
            const message = `Unable to load project (${err.message})`;
            this.loadingProject = false;
            this.projectLoadingError = message;
          });
      })
      .catch(err => {
        console.log(err);
        const message = `Unable to load project (${err.message})`;
        this.loadingProject = false;
        this.projectLoadingError = message;
      });
    EventBus.$on(events.DUPLICATE_FILE, this.duplicateFileAtIndex);
    EventBus.$on(events.REMOVE_FILE, this.removeFile);
  },
  computed: {
    activeFile() {
      return this.openedFiles[this.activeFileId];
    },
    activeFileEditSession() {
      return this.activeFile ? this.activeFile.session : undefined;
    }
  },
  methods: {
    /**
     *
     * @param {Number} index in the files array
     */
    submitNewFile({ name, content }) {
      this.addFile(name, content);
    },
    onImportFileButtonClick() {
      this.$refs.importFileInput.click();
    },
    /**
     * Add a file with the given name and content
     * @param {String} name
     * @param {String} content
     */
    addFile(name, content) {
      content = this.templateStringFunctions.reduce(
        (acc, fn) => fn(acc),
        content
      );

      // TODO split name to get path
      const file = {
        path: "/",
        name,
        content
      };

      repositoriesContainer.fileRepository
        .createFile(file)
        .then(createdFile => {
          createdFile.session = this.generateSession(
            content,
            this.modeForFile(name)
          );
          this.files.push(createdFile);
          EventBus.$emit(events.NOTIFICATION, {
            type: "success",
            message: `File ${createdFile.name} successfully created`,
            length: 1500
          });
        })
        .catch(err => {
          // TODO proper error handling with notification to user
          // eslint-disable-next-line no-console
          console.error(err);
          EventBus.$emit(events.NOTIFICATION, {
            type: "error",
            message: `Failed to create file - ${err.message}`, //TODO get message from response
            length: 2000
          });
        });
    },
    /**
     * @param {index} index of the file to duplicate
     */
    duplicateFileAtIndex(id) {
      const file = this.files[id];
      if (file) {
        const { baseName, ext } = this.splitFileName(file.name);
        const name = `${this.generateNewFileName(baseName)}.${ext}`;
        this.submitNewFile({ name, content: file.content });
      } else {
        // console.error("Duplicate file : id not in range", id);
      }
    },
    removeFileAtIndex(index) {
      const file = this.files[index];
      this.removeFile(file);
    },
    removeFile(file) {
      if (this.idsFilesBeingRemoved.has(file.id)) {
        return;
      }
      this.idsFilesBeingRemoved.add(file.id);
      repositoriesContainer.fileRepository
        .deleteFile(file)
        .then(() => {
          const index = this.files.findIndex(f => f.id === file.id);
          this.files.splice(index, 1);
          if (this.activeFileId == index) {
            this.activeFileId = 0;
          }
          EventBus.$emit(events.NOTIFICATION, {
            type: "success",
            message: `File ${file.name} deleted`,
            length: 1500
          });
          EventBus.$emit(events.REMOVE_FILE, file);
        })
        .catch(err => {
          console.error(err);
          EventBus.$emit(events.NOTIFICATION, {
            type: "error",
            message: `Failed to create file - ${err.message}`, //TODO get message from response
            length: 2000
          });
        })
        .then(() => {
          this.idsFilesBeingRemoved.delete(file.id);
        });
    },

    /**
     * Generate the  editor session object for the given file content and mode
     * @param content
     * @param mode
     * @returns {AceAjax.EditSession}
     */
    generateSession(content, mode) {
      const session = new EditSession(content, mode);
      session.setUseSoftTabs(this.userOptions.useSoftTabs);
      session.setUseWrapMode(this.userOptions.useWrapMode);
      return session;
    },

    // Helpers --------------------------------------------------------------------------
    generateNewFileName(base = "New File") {
      let tryCount = 1;
      const callback = file => base + tryCount === file.name;
      while (Array.from(this.files.values()).find(callback)) {
        tryCount += 1;
      }
      return base + tryCount;
    },
    splitFileName(fileName) {
      const regex = /(.*)\.([0-9a-z]+)$/gi;
      const matches = regex.exec(fileName);
      let baseName = fileName;
      let ext = "";
      if (matches !== null) {
        // eslint-disable-next-line prefer-destructuring
        baseName = matches[1];
        // eslint-disable-next-line prefer-destructuring
        ext = matches[2];
      }
      return { baseName, ext };
    }
  }
};
