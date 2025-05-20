
import { createRouter, createWebHistory } from 'vue-router'
import Login from '../components/Login.vue'
import Catalogo from '../components/Catalogo.vue'

const routes = [{ 
    path: '/', component: Login 
  },
  {
    path: '/catalogo', component: Catalogo ,
    meta: { requiresAuth: true }
  }
]

const router =  createRouter({
  history: createWebHistory(),
  routes
})

//confirmar autenticacao
router.beforeEach((to, from, next) => {
  const autenticado = !!localStorage.getItem('token')

  if (to.meta.requiresAuth && !autenticado) {
    next('/') // redireciona pro login
  } else {
    next()
  }
})

export default router;