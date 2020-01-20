<template>
    <div class="mb-10">
        <div>
            <div class="max-w-6xl mx-auto mt-8">
                <docs class="markdown"></docs>
            </div>
        </div>
    </div>
</template>

<script>
  import docs from '../docs/cli.md'


  export default {
    components: {
      docs
    },
    name: 'CliDocs',
  }
</script>
