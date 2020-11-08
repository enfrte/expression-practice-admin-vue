<template>
  <div id="app">
    <!--<img alt="Vue logo" src="./assets/logo.png">-->
    <!--<HelloWorld msg="Welcome to Your Vue.js App"/>-->
    <Admin />
  </div>
</template>

<script>
//import HelloWorld from './components/HelloWorld.vue'
import Admin from './components/Admin.vue'

export default {
  name: 'App',
  components: {
    //HelloWorld, 
    Admin,
  }
}
</script>

<style>

</style>
