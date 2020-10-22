<template>
    <div class="row">
        <grid-vue class="input-field" tamanho="12">
            <input type="text" v-model="conteudo.titulo">
            <textarea v-if="conteudo.titulo" placeholder="Conteudo" v-model="conteudo.texto" class="materialize-textarea"></textarea>
            <input v-if="conteudo.titulo && conteudo.texto" type="text" placeholder="Link" v-model="conteudo.link">
            <input v-if="conteudo.titulo && conteudo.texto" type="text" placeholder="Url da Imagem" v-model="conteudo.imagem">
            <label>O que está aconntecendo?</label>
        </grid-vue>
        
        <p class="right-align" v-if="conteudo.titulo && conteudo.texto" >
          <button @click="addConteudo()" class="btn waves-effect waves-dark">Publicar</button>
        </p>
    </div>
</template>

<script>
import GridVue from '@/components/layouts/GridVue'

export default {
  name: 'PublicarConteudoVue',
  props:[],
  components:{
    GridVue
  },
  data(){
      return{
        conteudo: {titulo:'',texto:'',link:'',imagem:''}
      }
  },
  methods:{
    addConteudo(){
        this.$http.post(this.$urlAPI+'conteudo/adicionar',{
          titulo: this.conteudo.titulo,
          texto: this.conteudo.texto,
          link: this.conteudo.link,
          imagem: this.conteudo.imagem
        },
        {"headers":{"authorization":"Bearer "+this.$store.getters.getToken}})
        .then(
          response => {
           if(response.data.status){

              this.conteudo = {titulo:'',texto:'', link:'', imagem:''};
              this.$store.commit('setConteudosLinhaTempo',response.data.conteudos.data);
           }else if(response.data.status == false && response.data.validacao){
              let erros = '';
              for(let erro of Object.values(response.data.erros)){
                erros += '- '+erro+"\n";
              }
                alert(erros);
            }else{

              
            }
          }).catch(
            e => {
              console.log(e);
              alert('Erro! Tente mais tarde!');
            }

          )
    }
  }
}
</script>

<style scoped>

</style>
