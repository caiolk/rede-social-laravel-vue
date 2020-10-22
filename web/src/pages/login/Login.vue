<template>
    <login-template>
        <span slot="menu-esquerdo">
            <img class="responsive-img" src="http://www.postdigital.cc/admin/admin/assets/uploads/conteudo/intranet-x-rede-social-corporativa-qual-a-melhor-solucao-para-a-sua-empresa-.jpg">
        </span>
        <span slot="principal">
          <h2>Login</h2>
            <input type="text" placeholder="E-mail" v-model="email">
            <input type="password" placeholder="Senha" v-model="password">
            <button type="button" class="btn" v-on:click="login()">Entrar</button>
            <router-link to="/cadastro" class="btn orange" >Cadastre-se</router-link> 
        </span>
    </login-template>
    
</template>

<script>
import LoginTemplate from '@/templates/LoginTemplate'
import axios from 'axios';

export default {
  name: 'Login',
  components:{
    LoginTemplate
  },
  data () {
    return {
      email:'',
      password:''
    }
  },
  methods:{
    login(){
      axios.post(`http://localhost:8000/api/login`,{
        email:    this.email,
        password: this.password
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
          alert('Login inválido!');
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
