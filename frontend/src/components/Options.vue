<template>
    <div class="flex flex-wrap -mx-3 mb-2 bg:indigo-600">
        <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="namespace">
                Namespace
            </label>
            <input v-model="options.namespace" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="namespace" type="text">
        </div>
        <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                Class Name
            </label>
            <input v-model="options.name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" placeholder="JsonDataTransferObject" type="text">
        </div>
        <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="typed">
                Language Level
            </label>
            <div class="relative">
                <select v-model="options.version" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="typed">
                    <option value="v2_doc">PHP < 7.4 (Docblock Types)</option>
                    <option value="v2_typed">PHP >= 7.4 (Typed Properties)</option>
                    <option value="v3">PHP >= 8.0 (Typed + Attributes)</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
        </div>
        <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">
                DTO Type
            </label>
            <div>
                <label class="custom-label flex">
                    <div class="bg-white shadow w-6 h-6 p-1 flex justify-center items-center mr-2">
                        <input v-model="options.flexible" type="checkbox" class="hidden">
                        <svg class="hidden w-4 h-4 text-green-600 pointer-events-none" viewBox="0 0 172 172"><g fill="none" stroke-width="none" stroke-miterlimit="10" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode:normal"><path d="M0 172V0h172v172z"/><path d="M145.433 37.933L64.5 118.8658 33.7337 88.0996l-10.134 10.1341L64.5 139.1341l91.067-91.067z" fill="currentColor" stroke-width="1"/></g></svg>
                    </div>
                    <span class="select-none"> Flexible</span>
                </label>
                <p class="text-xs mt-1">See <a target="_blank" class="text-indigo-500 hover:text-indigo-700" href="https://github.com/spatie/data-transfer-object/issues/61">this issue</a> for information</p>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'Options',
    props: {
      value: Object,
    },
    data() {
      return {
        options: this.value
      }
    },
    watch: {
      value(newOpts) {
        this.options = newOpts
      },
      options: {
        handler: function(opts) {
          this.$emit('input', opts)
        },
        deep: true
      }
    }
  }
</script>

<style scoped>
    .custom-label input:checked + svg {
        display: block !important;
    }
</style>
