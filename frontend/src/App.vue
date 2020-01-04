<template>
    <div id="app">
        <nav-bar></nav-bar>
        <converter></converter>
    </div>
</template>

<script>
  import Loader from './components/Loader'
  import Converter from './components/Converter'
  import NavBar from './components/NavBar'

  export default {
    name: 'app',
    components: { NavBar, Converter, Loader },
    data () {
      return {
        quote: null,
        quoteNum: null,
        quotes: [],
      }
    },
  }
</script>

<style>
    html {
        font-family: "Lucida Grande", Verdana, sans-serif;
    }
</style>
