import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/pages/home/Home'
import Login from '@/pages/login/Login'
import Cadastro from '@/pages/cadastro/Cadastro'

import Perfil from '@/pages/perfil/Perfil'
import Pagina from '@/pages/pagina/Pagina'
import Seguidores from '@/pages/seguidores/Seguidores'
import Seguindo from '@/pages/seguindo/Seguindo'

Vue.use(Router)

export default new Router({
  mode:'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: Home
    },
    {
      path: '/pagina/:id/:nome?',
      name: 'Pagina',
      component: Pagina
    },
    {
      path: '/login',
      name: 'Login',
      component: Login
    },
    {
      path: '/cadastro',
      name: 'Cadastro',
      component: Cadastro
    }
    ,
    {
      path: '/perfil',
      name: 'Perfil',
      component: Perfil
    },
    {
      path: '/seguidores/:id',
      name: 'Seguidores',
      component: Seguidores
    },
    {
      path: '/seguindo/:id',
      name: 'Seguindo',
      component: Seguindo
    }
  ]
})
