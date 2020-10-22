<template>
    <login-template>
        <span slot="menu-esquerdo">
            <img class="responsive-img" src="http://www.postdigital.cc/admin/admin/assets/uploads/conteudo/intranet-x-rede-social-corporativa-qual-a-melhor-solucao-para-a-sua-empresa-.jpg">
        </span>
        <span slot="principal">
          <h2>Cadastro</h2>
            <input type="text" placeholder="Nome" v-model="name">
            <input type="text" placeholder="E-mail" v-model="email">
            <input type="password" placeholder="Senha" v-model="password">
            <input type="password" placeholder="Confirme sua Senha" v-model="password_confirmation">
            <button type="button" class="btn" v-on:click="cadastro()">Enviar</button>
            <router-link to="/login" class="btn orange" >Já tenho conta</router-link> 
        </span>
    </login-template>
    
</template>

<script>
import LoginTemplate from '@/templates/LoginTemplate';
import axios from 'axios';

export default {
  name: 'Cadastro',
  components:{
    LoginTemplate
  },
  data () {
    return {
      name : '',
      email: '',
      password : '',
      password_confirmation: ''
    }
  },
  methods:{
    cadastro(){
      axios.post(`http://localhost:8000/api/cadastro`,{
        name:                   this.name,
        email:                  this.email,
        password:               this.password,
        password_confirmation:  this.password_confirmation
      })
      .then(response =>{
        if(response.data.status){
          this.$store.commit('setUsuario',response.data.usuario)
          localStorage.setItem('usuario',JSON.stringify(response.data.usuario));
          this.$router.push('/');
          
        }else if(response.data.status == false && response.data.validacao){

          let erros = '';
          for(let erro of Object.values(response.data.erros)){
            erros += '- '+erro+"\n";
          }
          alert(erros);
          
        }else{
          alert('Erro no cadastro! Tente novamente mais tarde.');
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
