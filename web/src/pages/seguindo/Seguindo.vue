<template>
    <site-template>
      <span slot="menu-esquerdo">
        <div class="row valign-wrapper">
          <grid-vue tamanho="4">
            <router-link :to="'/pagina/'+this.$route.params.id+'/'+$slug(usuario.name,{lower:true})">
              <img :src="usuario.image" :alt="usuario.name" class="circle responsive-img"> <!-- notice the "circle" class -->
            </router-link>
          </grid-vue >
          <grid-vue tamanho="8">
            <span class="black-text">
              <router-link :to="'/pagina/'+this.$route.params.id+'/'+$slug(usuario.name,{lower:true})">
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
        <grid-vue tamanho="12" class="center-align">
          <h4>Seguindo</h4>
        </grid-vue>
        <div class="divider"></div>
        <grid-vue v-if="listaSeguindo" tamanho="12">
          <card-amigo-vue  v-for="item in listaSeguindo" :key="item.id"
          
            :id="item.id"
            :usuarioid="item.id"
            :perfil="item.image" 
            :nome="item.name"
            :data_member="item.created_at" 
            >
            
          </card-amigo-vue>
          
          <div v-if="!listaSeguindo" class="divider"></div>
          <div v-scroll="handleScroll">

          </div>
        </grid-vue>
      </span>
    </site-template>
    
</template>

<script>
import SiteTemplate from '@/templates/SiteTemplate'
import CardAmigoVue from '@/components/social/CardAmigoVue'
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'Seguindo',
  components:{
    SiteTemplate,
    CardAmigoVue,
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
      seguidores: 0,
      listaSeguindo: null
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
    buscaDetalhesUsuario(id){
 
      this.$http.get(this.$urlAPI+`usuario/detalheusuario/`+id,{"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response =>{
          if(response.data.status){
           this.usuario = response.data.detalhesUsuario;
           
          }else{
            alert(response.data.erro);
          }
          
          
        })
        .catch(e=>{
      
          alert('Serviço indisponível! Tente mais tarde novamente.')
        })
  
    },
    atualizaPagina(){
  
      let usuarioAux = this.$store.getters.getUsuario;
    
      if(this.$route.params.id && this.$route.params.id!=usuarioAux.id){
        this.buscaDetalhesUsuario(this.$route.params.id);
      }
      
      if(usuarioAux){
        this.usuario = this.$store.getters.getUsuario;
        
        this.$http.get(this.$urlAPI+`usuario/listaseguindo/`+this.$route.params.id,{"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response =>{
          
          
          this.listaSeguindo = (response.data.seguindo!=null ? response.data.seguindo : null);
          this.seguindo = response.data.intSeguindo;
          this.seguidores = response.data.intSeguidores;
        

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
  },
  watch:{
    '$route': '$route.name'
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
