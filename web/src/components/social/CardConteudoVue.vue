<template>
  <div class="row">
    <div class="card ">
      <div class="card-content">
        <div class="row valign-wrapper">
            <grid-vue tamanho="1">
              <router-link :to="'/pagina/'+usuarioid+'/'+$slug(nome,{lower:true})">
                <img :src=" perfil || 'Imagem' " :alt="nome" class="circle responsive-img"> <!-- notice the "circle" class -->
              </router-link>
            </grid-vue >
            <grid-vue tamanho="11">
              <span>
                <strong><router-link :to="'/pagina/'+usuarioid+'/'+$slug(nome,{lower:true})">{{nome}}</router-link></strong> - <small>{{data}}</small>
              </span>
            </grid-vue>
          </div>
      </div>
      <slot></slot>
      <div class="card-action">
        <p>
          <a style="cursor:pointer" @click="curtida(id)"><i class="material-icons">{{curtiu}}</i>{{intTotalCurtidas}}</a>
          <a style="cursor:pointer" @click="abreComentario()"><i class="material-icons">insert_comment</i>{{listaComentario.length}}</a>
        </p>
        <p v-if="blComentario" class="right-align">
          <input type="text" placeholder="Comentar" v-model="textoComentario">
          <button v-if="textoComentario" @click="comentar(id)" class="btn btn-sm waves-effect waves-ligth orange"><i class="material-icons">send</i></button>
        </p>
        <p v-if="blComentario" >
          <ul class="collection">
            <li class="collection-item avatar" v-for=" item in comentarios " :key="item.id">
              <img :src="item.user.image" alt="" class="circle" />
              <span class="title">{{item.user.name}}</span> - 
              <span class="right-align" ><small >  {{item.data}}  </small></span>
              <p >
                {{item.texto}}
              </p>
            </li>
          </ul>
        </p>
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
  props:['id','perfil','nome','data','total_curtidas','comentarios','curtiuConteudo','blCurtir','usuarioid'],
  data(){
      return{
        curtiu: this.blCurtir ? 'favorite' : "favorite_border",
        intTotalCurtidas: this.total_curtidas,
        blComentario: false,
        textoComentario: '',
        listaComentario: this.comentarios || []
      }
  },
 
  methods:{
    curtida(id){
        let strUrl = '';
        if(this.$route.name == "Home"){
          strUrl = 'conteudo/curtir/'
        }else{
          strUrl = 'conteudo/curtirpagina/'
        }
        this.$http.put(this.$urlAPI+strUrl+id,{},
        {"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response => {
          if(response.data.status){
            console.log(response.data)
            this.intTotalCurtidas = response.data.curtidas;
            this.$store.commit('setConteudosLinhaTempo',response.data.lista.conteudos.data)

            if(this.curtiu == 'favorite_border'){
              this.curtiu = "favorite";
            }else{
              this.curtiu = 'favorite_border'
            }
          }else{
            alert(response.data.erro);
          }
        })
        .catch(e => {
          console.log(e)
          alert('Erro! Tente novamente mais tarde!')
        });
    },
    abreComentario(){
      this.blComentario = !this.blComentario;
    },
    comentar(id){
        if(!this.textoComentario){
          return;
        }
        let strUrl = '';
        if(this.$route.name == "Home"){
          strUrl = 'conteudo/comentar/'
        }else{
          strUrl = 'conteudo/comentarpagina/'
        }

        this.$http.put(this.$urlAPI+strUrl+id,{texto:this.textoComentario},
        {"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
        .then(response => {
          if(response.status){
            this.textoComentario = ''
            this.$store.commit('setConteudosLinhaTempo',response.data.lista.conteudos.data)
            
          }else{
            alert(response.data.erro);
          }
          
        })
        .catch(e => {
          console.log(e)
          alert('Erro! Tente novamente mais tarde!')
        })
    },

  }
}
</script>

<style scoped>

</style>
