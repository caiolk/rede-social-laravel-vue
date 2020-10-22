<template>
    <site-template>
      <span slot="menu-esquerdo">
        <div class="row valign-wrapper">
          <grid-vue tamanho="4">
            <router-link :to="'/pagina/'+usuario.id+'/'+$slug(usuario.name,{lower:true})">
              <img :src="usuario.image" :alt="usuario.name" class="circle responsive-img"> <!-- notice the "circle" class -->
            </router-link>
          </grid-vue >
          <grid-vue tamanho="8">
            <span class="black-text">
              <router-link :to="'/pagina/'+usuario.id+'/'+$slug(usuario.name,{lower:true})">
                <h5>{{usuario.name}}</h5>
              </router-link>
              
            </span>
          </grid-vue>
        </div>
        <div class="row valign-wrapper center-align">
          <grid-vue tamanho="6">
            <router-link :to="'/seguindo/'+usuario.id">
              <small>Seguindo ({{seguindo}})</small>
            </router-link>
          </grid-vue>
          <grid-vue tamanho="6">
            <router-link :to="'/seguidores/'+usuario.id">
              <small>Seguidores ({{seguidores}})</small>
            </router-link>
          </grid-vue>
          
        </div>
      </span>

      <span slot="principal">
        <publicar-conteudo-vue />

        <card-conteudo-vue v-for="item in listaConteudos" :key="item.id"
        
          :id="item.id"
          :total_curtidas="item.total_curtidas"
          :comentarios="item.comentarios"
          :blCurtir="item.blCurtir"
          :usuarioid="item.user.id"
          :perfil="item.user.image" 
          :nome="item.user.name" 
          :data="item.data">
            <card-detalhe-vue 
              :img="item.imagem" 
              :titulo="item.titulo"
              :txt="item.texto"
              :link="item.link" />
        </card-conteudo-vue>
        <div v-scroll="handleScroll">

        </div>
      </span>
    </site-template>
    
</template>

<script>
import SiteTemplate from '@/templates/SiteTemplate'
import CardConteudoVue from '@/components/social/CardConteudoVue'
import CardDetalheVue from '@/components/social/CardDetalheVue'
import PublicarConteudoVue from '@/components/social/PublicarConteudoVue'
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'Home',
  components:{
    SiteTemplate,
    CardConteudoVue,
    CardDetalheVue,
    PublicarConteudoVue,
    GridVue
  },
  created(){
    this.atualizaPagina();
  },
  data () {
    return {
      usuario: {imagem:'',name: ''},
      urlProximaPagina:null,
      stopScroll: false,
      seguindo: 0,
      seguidores: 0
    }
  },
  methods:{
     handleScroll() {
       if(this.stopScroll){
         return;
       }
      if (window.scrollY >= document.body.clientHeight - 1160) {
        this.stopScroll = true;
        this.carregaPaginacao();
      }
     
    },
    atualizaPagina(){
      let usuarioAux = this.$store.getters.getUsuario;
      if(usuarioAux){
        this.usuario = this.$store.getters.getUsuario;
    
        this.$http.get(this.$urlAPI+`conteudo/listar`,{"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response =>{
      
          this.$store.commit('setConteudosLinhaTempo',response.data.conteudos.data)
          this.urlProximaPagina = response.data.conteudos.next_page_url;

          this.$http.get(this.$urlAPI+`usuario/listaamigos/`,{"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
          .then(response =>{
            if(response.data.status){
              
              this.seguindo = response.data.seguindo;
              this.seguidores = response.data.seguidores;
            }else{
              alert(response.data.erro);
            }
            
            
          })
          .catch(e=>{
        
            alert('Serviço indisponível! Tente mais tarde novamente.')
          })

        })
        .catch(e=>{
      
          alert('Serviço indisponível! Tente mais tarde novamente.')
        });
  
      }

    },
    carregaPaginacao(){
      if(!this.urlProximaPagina){
        return;
      }
      this.$http.get(this.urlProximaPagina, {"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
        //console.log(response);
        if(response.data.status && this.$route.name == "Home"){
          this.$store.commit('setPaginacaoConteudosLinhaTempo',response.data.conteudos.data);
          this.urlProximaPagina = response.data.conteudos.next_page_url;
          this.stopScroll = false;
        }

      })
      .catch(e => {
        console.log(e)
        alert("Erro! Tente novamente mais tarde!");
      })
    }

  },
  computed:{ // Executa quando esta carregando/atualizando
    listaConteudos(){
      return this.$store.getters.getConteudosLinhaTempo;
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
