<template>
  <div data-cy="editor" class="w-full h-full" :style="{'font-size' : fontSize + 'pt'}"/>
</template>

<script>
import ace,{ EditSession, edit } from 'brace';
// Language Mode
import 'brace/mode/c_cpp';
import 'brace/mode/javascript';

import 'brace/ext/language_tools';

import 'brace/snippets/c_cpp';

// Themes
import 'brace/theme/twilight';
import 'brace/theme/clouds';
import 'brace/theme/clouds_midnight';
import 'brace/theme/cobalt';
import 'brace/theme/dracula';
import 'brace/theme/tomorrow_night';
import 'brace/theme/crimson_editor';
import 'brace/theme/ambiance';
import 'brace/theme/merbivore';


export default {
  props: {
    session: {
      type: Object,
      required: false,
      default () {
        return null;
      }
    },
    theme: {
      type: String,
      required: false,
      default() {
        return null;
      },
    },
    lineHighlight: {
      type: Boolean,
      required: false,
      default() {
        return true;
      },
    },
    fontSize: {
      type: Number,
      required: false,
      default() {
        return 12
      }
    },
    readonly: {
      type: Boolean,
      required: false,
      default () {
        return false;
      }
    }
  },
  data() {
    return {
      editor: Object,
      beforeContent: '',
    };
  },
  watch: {
    session(value) {
      if(value === null | value === undefined){
        const session = new EditSession("", "ace/mode/text");
        this.editor.setSession(session);
        this.editor.setReadOnly(true);
      }else{
        this.editor.setReadOnly(this.readonly);
        //remove listner on old session
        this.editor.session.on('change', (delta) => {})
        this.editor.setSession(value);
        this.editor.session.on('change', (delta) => {
          console.log('change')
          this.$emit('change')
        });
      }
    },
    lineHighlight(value) {
      this.editor.setHighlightActiveLine(value);
    },
    theme(value) {
      this.editor.setTheme(`ace/theme/${value}`);
    },
    readonly(value) {
      this.editor.setReadOnly(value);
    }
  },
  mounted() {
    this.editor = ace.edit(this.$el, {
      enableBasicAutocompletion: true,
      enableLiveAutocompletion: false,
      enableSnippets: true,
    });
    this.editor.setReadOnly(this.readonly);

    this.editor.commands.addCommand({
      name: 'save',
      bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
      exec: (editor) => {
        this.$emit('save')
      },
      readOnly: true // false if this command should not apply in readOnly mode
    });
    if(this.session != undefined){
      this.editor.setSession(this.session);
      this.editor.session.on('change', (delta) => {
        console.log('change')
        this.$emit('change')
      });
    }
    if (this.theme) {
      this.editor.setTheme(`ace/theme/${this.theme}`);
    }
  },
  name: 'LideEditor',
};
</script>

<style scoped>

</style>
