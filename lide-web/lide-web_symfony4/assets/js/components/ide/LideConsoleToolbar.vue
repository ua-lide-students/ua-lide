<!-- Console Toolbar -->
<template>
  <v-toolbar
      dense
      dark
      color="consoleToolbar"
  >
    <v-toolbar-items>
      <v-tooltip bottom>
        <v-btn
            slot="activator"
            flat
            small
            :disabled="programRunning"
            :loading="programRunningLoading"
            @click="run"
        >
          <v-icon>fa-play</v-icon>
          <span class="ml-1"             data-cy="run">Run</span>
        </v-btn>
        <span class="text-lg">Compile & Run</span>
      </v-tooltip>
      <v-tooltip bottom>
        <v-btn
          slot="activator"
          flat
          small
          @click="openRunForm"
        >
        <v-icon>fa-cog</v-icon>
        </v-btn>
        <span class="text-lg">Run options</span>
      </v-tooltip>
      <!-- DEBUGGER BUTTON - UNUSED FOR NOW -->
      <!--
      <v-tooltip bottom>
        <v-btn
            slot="activator"
            flat
            small
            :disabled="programRunning"
            @click="runDebug"
        >
          <v-icon>fas fa-bug</v-icon>
          <span
              class="ml-1">Debug</span>
        </v-btn>
        <span class="text-lg">Run In Debugger</span>
      </v-tooltip> -->
      <v-tooltip
          bottom
          :disabled="!programRunning"
      >
        <v-btn
            slot="activator"
            flat
            small
            :disabled="!programRunning"
            @click="stop"
        >
          <v-icon>fa-stop</v-icon>
          <span class="ml-1">Stop</span>
        </v-btn>
        <span class="text-lg">Stop</span>
      </v-tooltip>
    </v-toolbar-items>
    <v-dialog 
      v-model="showProgramForm"
      max-width="480px"
    >
      <v-card>
        <v-card-title primary-title><h3 class="headline">Run options</h3></v-card-title>
        <v-card-text>
          <v-select 
            v-model="form.compile_options"
            multiple
            clearable
            label="Compile options"
            :items="availableCompileOptions"
            @change="onFormChange"
          />
          <v-text-field
            v-model="form.launch_options"
            clearable
            label="Launch options"
            @change="onFormChange"
          />
        </v-card-text>
        <v-card-actions>
          <v-layout columns>
            <v-btn @click="closeProgramForm" block>Close</v-btn>
            <v-btn @click="closeProgramFormAndRun" block color="primary">Run</v-btn>
          </v-layout>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-toolbar>
</template>

<script>
  export default {
    props: {
      programRunning: {
        type: Boolean,
        required: true
      },
      programRunningLoading: {
        type: Boolean,
        required: true
      },
      programForm: {
        type: Object,
        required: true,
      },
      availableCompileOptions: {
        type: Array,
        required: false,
        default () {
          return ["-Wall", "-O2"]
        }
      }
    },
    data(){
      return {
        showProgramForm: false,
        form: {
          launch_options : this.programForm.launch_options || "",
          compile_options : this.programForm.compile_options.slice(0) || [],
        }
      }
    },
    methods: {
      run() {
        this.$emit('run')
      },
      runDebug() {
        this.$emit('run-debug')
      },
      stop() {
        this.$emit('stop')
      },
      openRunForm(){
        this.showProgramForm = true;
      },
      closeProgramForm(){
        this.showProgramForm = false;
      },
      closeProgramFormAndRun() {
        this.$emit("program-form-change", this.form);
        this.$nextTick(() => {
          this.closeProgramForm();
          this.run();
        })
      },
      onFormChange(){
        this.$emit("program-form-change", this.form)
      }
    },
    name: "LideConsoleToolbar"
  }
</script>

<style scoped>

</style>