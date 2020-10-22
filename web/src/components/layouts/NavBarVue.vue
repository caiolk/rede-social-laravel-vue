<template>
  <nav :class="cor || 'blue'">
    <div class="nav-wrapper container">
      <router-link  :to="url || '/'" class="brand-logo">{{logo || 'Site'}}</router-link>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <slot></slot>
      </ul>
    </div>
  </nav>
</template>

<script>
  export default {
    name: 'NavBarVue',
    props:['logo','url','cor'],
    data () {
      return {

      }
    }
  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
