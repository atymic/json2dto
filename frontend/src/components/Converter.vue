<template>
    <div class="mb-10">
        <div class="max-w-6xl mx-auto mt-8">
            <p class="text-gray-800">Json 2 DTO generates <a class="text-indigo-500 hover:text-indigo-700"
                                                             target="_blank"
                                                             href="https://github.com/spatie/data-transfer-object">spatie/data-transfer-object</a>
                objects automatically from json snippets.
                <br>Your DTOs then allow you statically type check code that interacts with them.
            </p>
            <div class="flex justify-center items-center my-6">
                <button @click="generate" :disabled="loading"
                        class="bg-indigo-500 text-white active:bg-indigo-600 disabled:opacity-75 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1"
                        type="button" style="transition: all .15s ease">
                    Generate DTO
                </button>
                <div class="ml-3">
                    <label class="custom-label flex">
                        <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                            <input v-model="options.nested" type="checkbox" class="hidden">
                            <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                        </div>
                        <span class="select-none"> Generate Nested DTOs</span>
                    </label>
                    <p class="text-xs mt-1">A Zip file containing the DTOs will be generated</p>
                </div>
            </div>

            <div v-if="error" class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-red-500">
                <span class="inline-block align-middle mr-8">
                    <b class="capitalize">Error</b> {{ error }}
                </span>
            </div>

            <div class="px-4 md:p-0">
                <options v-model="options"></options>
                <div class="md:flex container border flex-wrap">
                    <json-input-editor ref="input" v-model="json" class="w-full md:w-1/2"></json-input-editor>
                    <dto-output :nested="options.nested" :loading="loading" :value="dto" class="w-full md:w-1/2"></dto-output>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Axios from 'axios'
  import saveState from 'vue-save-state'
  import { saveAs } from 'file-saver';
  import defaultDto from '../dto.default.js'

  import JsonInputEditor from './JsonInputEditor'
  import DtoOutput from './DtoOutput'
  import Options from './Options'

  export default {
    name: 'Converter',
    components: { Options, DtoOutput, JsonInputEditor },
    mixins: [saveState],
    data () {
      return {
        json: JSON.stringify({ 'id': 45, 'name': 'hello world', 'price': 44.5, 'enabled': true }, null, 2),
        version: null,
        options: {
          namespace: 'App\\DTO',
          name: null,
          version: 'v2_typed',
          flexible: false,
          nested: false,
        },
        loading: false,
        error: null,
        dto: defaultDto,
      }
    },
    methods: {
      generate () {
        this.error = null

        if (!isJsonString(this.json)) {
          this.error = 'Invalid JSON Input'
          return
        }

        this.$refs.input.tidy()
        this.loading = true


        Axios.post(process.env.VUE_APP_ENDPOINT || 'http://localhost:8081', {
          namespace: this.options.namespace || null,
          name: this.options.name || null,
          typed: this.options.version === 'v3' || this.options.version === 'v2_typed',
          v3: this.options.version === 'v3',
          nested: this.options.nested,
          flexible: this.options.flexible,
          source: JSON.parse(this.json),
        }, {
          responseType: this.options.nested ? 'blob' : 'text'
        }).then(res => {
          if (this.options.nested) {
            saveAs(res.data, `${this.options.name || 'NewDTO'}_dto.zip`)
          }

          this.dto = res.data
          this.loading = false
        }).catch(err => {
          this.error = 'Failed to generate DTO'
          this.loading = false
        })
      },
      getSaveStateConfig () {
        return {
          'cacheKey': 'json2dto_v2',
          'ignoreProperties': ['loading', 'error'],
        }
      },
    },
  }
</script>

<style scoped>
    .container {
        height: 65vh;
    }

    .custom-label input:checked + svg {
        display: block !important;
    }
</style>
