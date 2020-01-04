<template>
    <div id="app">
        <nav-bar></nav-bar>
        <converter></converter>
        <foot></foot>
    </div>
</template>

<script>
  import Loader from './components/Loader'
  import Converter from './components/Converter'
  import NavBar from './components/NavBar'
  import Footer from './components/Footer'

  export default {
    name: 'app',
    components: { foot: Footer, NavBar, Converter, Loader },
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

    html, body {
        margin: 0;
    }

</style>
