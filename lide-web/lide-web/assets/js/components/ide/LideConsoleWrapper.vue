<template>
  <v-layout
    column
    fill-height
  >
    <lide-console-toolbar
      :program-running="program.isRunning"
      :program-running-loading="program.isRunningLoading"
      :program-form="programForm"
      @run="run"
      @run-debug="runDebug"
      @stop="stop"
      @program-form-change="onProgramFormChange"
    />
    <div class="h-full w-full relative">
      <lide-console
        style="position: absolute; top : 0; bottom: 0; left: 0; overflow: none;"
        :show-prompt="showConsolePrompt"
        :buffer="program.consoleBuffer"
        :input-loading="program.inputLoading"
        @input="handleConsoleInput"
      />
    </div>
  </v-layout>
</template>
<script>
import LideConsole from './LideConsole.vue';
import LideConsoleToolbar from "./LideConsoleToolbar";
import WsConnector from "../../ws_connector/WsConnector";
import { notify } from "../../event-bus";

export default {
  name: 'LideConsoleWrapper',
  components: {
    LideConsole,
    LideConsoleToolbar,
  },
  props: {
    wsAdress: {
      type: String,
      required: true,
    },
    jwt: {
      type: String,
      required: true,
    },
    projectId: {
      type: Number,
      required: true
    },
    selectedEnvironment: {
        type: Object,
        required: true
    }
  },
  data () {
    return {
      program: {
        isRunning: false,
        isRunningLoading: false,
        stopLoading: false,
        inputLoading: false,
        consoleBuffer: ""
      },
      programForm: {
        compile_options: [],
        launch_options: ""
      },
      wsConnector: null
    }
  },
  computed: {
    showConsolePrompt () {
      return this.program.isRunning;
    }
  },
  mounted () {
    this.wsConnector = new WsConnector(
      this.wsAdress,
      event => {
        console.log("Ws connection opened", event);
        this.wsConnector.send("auth", {
          jwt: this.jwt,
          project_id: this.projectId
        });
      },
      true,
      () => {
        notify("error", `Failed to connect to execution server`, 2000);
        //TODO set timeout/interval for retry
      }
    );

    this.wsConnector.setCallbackForType("end", data => this.onProgramEnd(data));
    this.wsConnector.setCallbackForType("out", data => this.onProgramOutputMessage(data));
    this.wsConnector.setCallbackForType("status", data => this.onProgramStatusMessage(data));
  },
  methods: {
    run () {
      console.log("RUN");
      this.program.consoleBuffer = "";
      this.program.isRunningLoading = true;
      this.wsConnector.send("execute", {
        compile_options: this.programForm.compile_options.join(" "),
        launch_options: this.programForm.launch_options,
        image: this.selectedEnvironment.image
      });
      // TODO API
    },
    runDebug () {
      // TODO
    },
    stop () {
      this.stopLoading = true;
      this.wsConnector.send("force_stop", {});
    },
    appendLineToBuffer (append) {
      this.program.consoleBuffer = this.program.consoleBuffer.concat(append);
    },
    handleConsoleInput (input) {
      this.appendLineToBuffer(input);
      this.wsConnector.send("input", {
        input
      });
    },
    onProgramFormChange (form) {
      this.programForm = {
        launch_options: form.launch_options,
        compile_options: form.compile_options.slice(0)
      };
    },
    
    /*******************************************************************************
     * Callbacks form Websockets messages
     */ 
    onProgramOutputMessage(data) {
      if (data.hasOwnProperty("stdout")) {
        this.program.consoleBuffer = this.program.consoleBuffer.concat(
          data.stdout
        );
      }
      if (data.hasOwnProperty("stderr")) {
        this.program.consoleBuffer = this.program.consoleBuffer.concat(
          data.stderr
        );
      }
    },
    onProgramStatusMessage(data) {
      this.program.isRunningLoading = false;
      this.program.isRunning = data.is_running;
      notify("info", "Program started", 1500);
    },
    onProgramEnd (data) {
      notify("info", "Program ended", 1500);
      this.program.isRunningLoading = false;
      this.program.isRunning = false;
      this.stopLoading = false;
      console.log("Program ended", data.return);
    },
  }
}
</script>
