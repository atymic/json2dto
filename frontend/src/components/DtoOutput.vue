<template>
  <div class="flex-1 flex flex-col h-full">
    <div class="flex flex-wrap border-b border-l text-sm select-none">
      <span
        class="py-2 px-3 text-gray-500">
        Generated DTO
      </span>
      <button v-clipboard:copy="value"
              class="text-indigo-500 font-semibold ml-auto py-2 px-3 focus:outline-none border-b-3 transition border-transparent hover:bg-gray-200 focus:bg-gray-400">
        Copy To Clipboard
      </button>
    </div>
    <div class="h-full border-l">
      <div v-if="loading" class="flex-1 flex flex-col justify-center align-center">
        <spinner class="py-10" size="large"></spinner>
      </div>
      <codemirror v-else :value="value" :options="editorOptions"></codemirror>
    </div>
  </div>
</template>

<script>
  import { codemirror } from 'vue-codemirror'
  import 'codemirror/mode/php/php'
  import Spinner from 'vue-simple-spinner'

  export default {
    name: 'DtoOutput',
    components: {
      codemirror,
      Spinner,
    },
    props: {
      value: String,
      loading: Boolean,
    },
    data () {
      return {
        editorOptions: {
          readOnly: true,
          styleActiveLine: true,
          smartIndent: true,
          indentUnit: 4,
          lineNumbers: true,
          line: true,
          mode: 'text/x-php',
          lineWrapping: true,
        },
      }
    },
  }
</script>

<style scoped>
  .vue-codemirror {
    width: 100% !important;
    height: 100% !important;
  }
</style>


<style>
  .CodeMirror {
    height: 100% !important;
    width: 100% !important;
  }
</style>
