<template>
    
    <div v-if="id" >
        <div class="divider"></div>
        <div class="section">
          <div class="row valign-wrapper">
              <grid-vue tamanho="2">
                <router-link :to="'/pagina/'+usuarioid+'/'+$slug(nome,{lower:true})">
                  <img :src=" perfil || 'Imagem' " :alt="nome" class="circle responsive-img">
                </router-link>
              </grid-vue >
              <grid-vue tamanho="10">
                <span>
                  <strong><router-link :to="'/pagina/'+usuarioid+'/'+$slug(nome,{lower:true})">{{nome}}</router-link></strong><br>
                  <small v-if="data_member" >(Membro desde: {{ data_member.slice(0,10)}} )</small>
                </span>
              </grid-vue>
          </div>
        </div>
  </div>
    
</template>

<script>
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'CardConteudoVue',
  components:{
    GridVue
  },
  props:['id','perfil','nome','data','usuarioid','data_member'],
  data(){
      return{
     
      }
  },
 
  methods:{

  }
}
</script>

<style scoped>

</style>
