<template>
    <site-template>
        <span slot="menu-esquerdo">
            <img class="responsive-img" :src="usuario.image">
        </span>
        <span slot="principal">
          <h2>Perfil</h2>
            <input type="text" placeholder="Nome" v-model="name">
            <input type="text" placeholder="E-mail" v-model="email">
            <div class="file-field input-field">
              <div class="btn">
                <span>Imagem</span>
                <input type="file" v-on:change="uploadImagem">
              </div>
              <div class="file-path-wrapper">
                <input class="file-path validate" type="text" >
              </div>
            </div>
            <input type="password" placeholder="Senha" v-model="password">
            <input type="password" placeholder="Confirme sua Senha" v-model="password_confirmation">
            <button type="button" class="btn" v-on:click="perfil()">Atualizar</button>
        </span>
    </site-template>
    
</template>

<script>
import SiteTemplate from '@/templates/SiteTemplate';
import axios from 'axios';

export default {
  name: 'Perfil',
  components:{
    SiteTemplate
  },
  data () {
    return {
      usuario: false,
      name : '',
      email: '',
      password : '',
      password_confirmation: '',
      image: ''    
    }
  },
  created(){
    let usuarioAux = this.$store.getters.getUsuario;
    if(usuarioAux){
      this.usuario = this.$store.getters.getUsuario;
      this.name = this.usuario.name;
      this.email = this.usuario.email;
 
    }
  },
  methods:{
   uploadImagem(e){

      let arquivo = e.target.files || e.dataTransfer.files;
      if(!arquivo.length){
        return;
      }
  
      let reader = new FileReader();
      reader.onloadend = (e) => {
        this.image =  e.target.result;
       
      };
       reader.readAsDataURL(arquivo[0]);
      
    },
    perfil(){
      
      this.$http.put(this.$urlAPI+`perfil`,{
        name:                   this.name,
        email:                  this.email,
        password:               this.password,
        image:                  this.image,
        password_confirmation:  this.password_confirmation
      },{"headers":{"Authorization":"Bearer "+this.$store.getters.getToken}})
      .then(response =>{

        if(response.data.status){
          
          this.usuario = response.data.usuario;
          this.$store.commit('setUsuario',response.data.usuario)
          localStorage.setItem('usuario',JSON.stringify(response.data.usuario));
          alert('Perfil atualizado com sucesso!');
          
        }else if(response.data.status == false && response.data.validacao){
          let erros = '';
          for(let erro of Object.values(response.data.erros)){
            erros += '- '+erro+"\n";
          }
            alert(erros);
        }
       
      })
      .catch(e=>{
        alert('Serviço indisponível! Tente mais tarde novamente.')
      })
    }
    
  }
 
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

</style>
