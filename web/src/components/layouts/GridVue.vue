<template>
    <div :class="'col s'+ tamanho + (' offset-s'+ offset || '' )">
        <slot></slot>
    </div>
</template>

<script>
export default {
  name: 'GridVue',
  props:['tamanho','offset'],
  data(){
      return{

      }
  }
}
</script>

<style scoped>

</style>
