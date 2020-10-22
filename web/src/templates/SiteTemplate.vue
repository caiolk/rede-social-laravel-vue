<template>
  <span>
    <header>
      <nav-bar logo="Social" url="/" cor="green darken-1" >
        <li><router-link to="/">Home</router-link></li>
        <li v-if="!usuario" ><router-link to="/login">Login</router-link></li>
        <li v-if="!usuario" ><router-link to="/cadastro">Cadastre-se</router-link></li>
        <li v-if="usuario" ><router-link to="/perfil">{{usuario.name}}</router-link></li>
        <li v-if="usuario" ><a v-on:click="sair()">Sair</a></li>
      </nav-bar>
    </header>
    <main>
      <div class="container">
        <div class="row">
          <grid-vue tamanho="4">
              <card-menu-vue>
                <slot name="menu-esquerdo" />
              </card-menu-vue>
              <!--
              <card-menu-vue>
                <slot name="menu-esquerdo-amigos" />
              </card-menu-vue>
              -->
          </grid-vue>
          <grid-vue tamanho="8">
              <slot name="principal"></slot>
          </grid-vue> 
        </div>
       
      </div>
    </main>
    <footer>
      <footer-vue cor="green darken-1" logo="Social" descricao="Teste de Descricao" ano="2019">
          <li><a class="grey-text text-lighten-3" href="#!">Home</a></li>
          <li><a class="grey-text text-lighten-3" href="#!">Link 2</a></li>
          <li><a class="grey-text text-lighten-3" href="#!">Link 3</a></li>
          <li><a class="grey-text text-lighten-3" href="#!">Link 4</a></li>
      </footer-vue>
    </footer>
  </span>
</template>

<script>
import NavBar from '@/components/layouts/NavBarVue'
import FooterVue from '@/components/layouts/FooterVue'
import GridVue from '@/components/layouts/GridVue'
import CardMenuVue from '@/components/layouts/CardMenuVue'
export default {
  name: 'SiteTemplate',
  components:{
    NavBar,
    FooterVue,
    GridVue,
    CardMenuVue
  },
  data(){
    return {
      usuario: false,

    }
  },
  created(){
    
    let usuarioAux = this.$store.getters.getUsuario;
    if(usuarioAux){
      this.usuario = this.$store.getters.getUsuario
    }else{
      this.$router.push('/login')
    }
  },
  methods:{
    sair(){
      this.$store.commit('setUsuario',null);
      localStorage.clear();
      this.usuario = false;
      this.$router.push('/login')
    }
  }
}
</script>

<style>

</style>
