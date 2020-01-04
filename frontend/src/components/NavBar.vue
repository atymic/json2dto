<template>
    <nav class="relative flex flex-wrap items-center justify-between px-2 py-3 navbar-expand-lg bg-indigo-800 mb-3">
        <div class="container px-4 mx-auto flex flex-wrap items-center justify-between">
            <div class="w-full relative flex justify-between lg:w-auto  px-4 lg:static lg:block lg:justify-start">
                <a class="text-sm font-bold leading-relaxed inline-block mr-4 py-2 whitespace-no-wrap uppercase text-white" href="/">
                    Json 2 DTO
                </a>
                <button class="text-white cursor-pointer text-xl leading-none px-3 py-1 border border-solid border-transparent rounded bg-transparent block lg:hidden outline-none focus:outline-none" type="button" v-on:click="toggleNavbar()">
                    <span class="block relative w-6 h-px rounded-sm bg-white"></span>
                    <span class="block relative w-6 h-px rounded-sm bg-white mt-1"></span>
                    <span class="block relative w-6 h-px rounded-sm bg-white mt-1"></span>
                </button>
            </div>
            <div v-bind:class="{'hidden': !showMenu, 'flex': showMenu}" class="lg:flex lg:flex-grow items-center">
                <ul class="flex flex-col lg:flex-row list-none ml-auto">
                    <li class="nav-item">
                        <a class="px-3 py-2 flex items-center text-xs uppercase font-bold leading-snug text-white hover:opacity-75" href="#pablo">
                            Github
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
  export default {
    name: 'NavBar',
    data() {
      return {
        showMenu: false
      }
    },
    methods: {
      toggleNavbar: function(){
        this.showMenu = !this.showMenu;
      }
    }
  }
</script>

<style scoped>

</style>
