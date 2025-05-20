
import { createRouter, createWebHistory } from 'vue-router'
import Login from '../Login.vue'
import Catalogo from '../Catalogo.vue'

const routes = [
  { path: '/', component: Login },
  { path: '/catalogo', component: Catalogo }
]

const router =  createRouter({
  history: createWebHistory(),
  routes
})

export default router;