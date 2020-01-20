<template>
    <div id="app">
        <nav-bar></nav-bar>
        <router-view></router-view>
        <foot></foot>
    </div>
</template>

<script>
  import NavBar from './components/NavBar'
  import Footer from './components/Footer'

  export default {
    name: 'app',
    components: { foot: Footer, NavBar },
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
