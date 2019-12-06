<template>
  <div class="h-full w-full bg-grey">
    <div
      class="font-mono text-white bg-black h-full"
      @click="giveFocus">
      <div
        class="h-full px-2 py-4 max-h-full flex flex-col-reverse"
        style="max-height: calc(100% - 3px)"
        ref="wrapper">
        <div class="m-w-full">
          <input
            v-model="input"
            ref="consoleInput"
            type="text"
            style="opacity: 0;"
            @keyup.enter="validateInput"
            @focus="hasFocus=true"
            @blur="hasFocus=false"
            title="console-input">

          <div
            v-if="inputLoading"
            class="absolute mx-2"
            style="right: 0px; top:0px">
            <i class="fas fa-sync fa-spin"/>
          </div>
        </div> 
        <div class="relative overflow-auto flex-grow" data-cy="console-buffer">
          <div style="position: absolute; top: 0; left: 0;" class="w-full h-full">
            <div :class="{'console-focused' : hasFocus}" v-html="bufferHtml" />
          </div>
        </div>       
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LideConsole',
  data() {
    return {
      input: '',
      hasFocus: false,
    };
  },
  props: {
    prompt: {
      type: String,
      default() {
        return '>';
      },
    },
    buffer: {
      type: String,
      default() {
        return 'some data' +
          '\nsome other data';
      },
    },
    showPrompt: {
      type: Boolean,
      default() {
        return true;
      },
    },
    inputLoading: {
      type: Boolean,
      default() {
        return false;
      },
    },
  },
  computed: {
    bufferHtml(){
      //TODO better
      let bufferHtml = this.buffer.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br />').concat(this.input);
      if(this.showPrompt){
        bufferHtml = bufferHtml.concat('<span class="cursor">|</span>')
      }
      return bufferHtml;
    },
    lines() {
      return this.buffer.split(/\n/);
    },
  },
  methods: {
    giveFocus() {
      this.$refs.consoleInput.focus();
    },
    validateInput() {
      this.$emit('input', this.input + "\n");
      this.input = '';
    },
    scrollBottom() {
      this.$refs.wrapper.scrollTop =
        this.$refs.wrapper.scrollHeight - this.$refs.wrapper.clientHeight;
    },
  },
  watch: {
    buffer() {
      this.$nextTick(() => { this.scrollBottom(); });
    },
  },
};
</script>

<style scoped>
    .input-cursor {
        animation: colorblink 1s infinite;
    }
    @keyframes colorblink {
        0% {background-color: rgba(0,0,0,0)}
        50% {background-color: white}
    }
</style>
