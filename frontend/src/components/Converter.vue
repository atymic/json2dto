<template>
    <div class="mb-10">
        <div class="max-w-6xl mx-auto mt-8">
            <p class="text-gray-800">Json 2 DTO generates <a class="text-indigo-500 hover:text-indigo-700"
                                                             target="_blank"
                                                             href="https://github.com/spatie/data-transfer-object">spatie/data-transfer-object</a>
                objects automatically from json snippets.<br>Your DTOs then allow you statically type check code that
                interacts with them</p>
            <div class="flex justify-center my-6">
                <button @click="generate" :disabled="loading"
                        class="bg-indigo-500 text-white active:bg-indigo-600 disabled:opacity-75 font-bold uppercase text-sm px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1"
                        type="button" style="transition: all .15s ease">
                    Generate DTO
                </button>
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
                    <dto-output :loading="loading" :value="dto" class="w-full md:w-1/2"></dto-output>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Axios from 'axios'
  import saveState from 'vue-save-state'
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
        options: {
          namespace: 'App\\DTO',
          name: null,
          typed: false,
          flexible: false,
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
          namespace: this.options.namespace,
          name: this.options.name,
          typed: this.options.typed,
          flexible: this.options.flexible,
          source: JSON.parse(this.json),
        }).then(res => {
          this.dto = res.data
          this.loading = false
        }).catch(err => {
          this.error = 'Failed to generate DTO'
          this.loading = false
        })
      },
      getSaveStateConfig () {
        return {
          'cacheKey': 'json2dto',
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
</style>
