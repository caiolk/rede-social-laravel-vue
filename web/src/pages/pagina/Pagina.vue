<template>

    <site-template>
      
      <span slot="menu-esquerdo">
        <div class="row valign-wrapper">
          <grid-vue tamanho="4">
            <router-link :to="'/pagina/'+owner.id+'/'+$slug(owner.name,{lower:true})">
              <img :src="owner.image" :alt="owner.name" class="circle responsive-img"> <!-- notice the "circle" class -->
            </router-link>
          </grid-vue >
          <grid-vue tamanho="8">
            <span class="black-text">
              <router-link :to="'/pagina/'+owner.id+'/'+$slug(owner.name,{lower:true})">
                <h5>{{owner.name}}</h5>
              </router-link>
                  <button v-if="blBtnSeguir" @click="amigo(owner.id);" class="btn blue">{{textoBotao}}</button>
            </span>
            
          </grid-vue>
        </div>
        <div class="row valign-wrapper center-align">
          <grid-vue tamanho="6">
            <router-link :to="'/seguindo/'+owner.id">
              <small>Seguindo ({{seguindo}})</small>
            </router-link> 
          </grid-vue>
          <grid-vue tamanho="6">
            <router-link :to="'/seguidores/'+owner.id">
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
  name: 'Pagina',
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
      usuario: false,
      urlProximaPagina:null,
      stopScroll: false,
      owner: {imagem:'',name: ''},
      blBtnSeguir: false,
      amigos: [],
      seguindo: 0,
      seguidores: 0,
      amigoslogado:[],
      textoBotao: 'Seguir'
    }
  },
  watch:{
    '$route':'atualizaPagina'
  },
  methods:{
    atualizaPagina(){

      let usuarioAux = this.$store.getters.getUsuario;
      if(usuarioAux){
        this.usuario = this.$store.getters.getUsuario;
        
        this.$http.get(this.$urlAPI+`conteudo/pagina/listar/`+this.$route.params.id,{"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response =>{
      
          this.$store.commit('setConteudosLinhaTempo',response.data.conteudos.data)
          this.urlProximaPagina = response.data.conteudos.next_page_url;
          this.owner = response.data.owner;
      
          if(this.owner.id != this.usuario.id){
            this.blBtnSeguir = true;
            
          }else{
            this.blBtnSeguir = false;
        
          }
         
          this.$http.get(this.$urlAPI+`usuario/listaamigospagina/`+this.owner.id,{"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
          .then(response =>{
            if(response.data.status){
            
              this.amigos = response.data.amigos;
              this.seguindo = response.data.seguindo;
              this.seguidores = response.data.seguidores;
              this.amigoslogado = response.data.amigoslogado;
          
              this.verificaAmigo();
            }else{
              alert(response.data.erro);
            }
            
            
          })
          .catch(e=>{
        
            alert('Serviço indisponível! Tente mais tarde novamente')
          })


        })
        .catch(e=>{
      
          alert('Serviço indisponível! Tente mais tarde novamente')
        });
  
      };

    },
    verificaAmigo(){
     
      for(let amigo of this.amigoslogado){
        if(amigo.id == this.owner.id){
          this.textoBotao = "Remover";
          return;
        }
      }
      this.textoBotao = "Seguir";
    },
    amigo(id){
      
      this.$http.post(this.$urlAPI+`usuario/amigo`,
        {id:id},
        {"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response => {
          if(response.data.status){
            
            this.amigoslogado = response.data.amigos;
            this.seguidores = response.data.seguidores;
            this.verificaAmigo();

          }else{;
            alert(response.data.erro);
          }
        })
        .catch(e => {
          console.log(e)
          alert('Erro! Tente novamente mais tarde!')
        });
      
    },
    handleScroll() {
       if(this.stopScroll){
         return;
       }
      if (window.scrollY >= document.body.clientHeight - 1160) {
        this.stopScroll = true;
        this.carregaPaginacao();
      }
     
    },
    carregaPaginacao(){
      if(!this.urlProximaPagina){
        return;
      }
      this.$http.get(this.urlProximaPagina, {"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response => {
        //console.log(response);
        if(response.data.status && this.$route.name == "Pagina" ){
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
