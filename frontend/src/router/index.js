import { createRouter, createWebHistory } from 'vue-router'
import LandingView from '@/views/LandingView.vue'
import axios from 'axios'
import LoginView from '@/views/LoginView.vue'
import LocationView from '@/views/LocationView.vue'
import MapView from '@/views/MapView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/landing',
      name: 'landing',
      component: LandingView,
    },
    {
      path: '/location',
      name: 'location',
      component: LocationView,
    },
    {
      path: '/map',
      name: 'map',
      component: MapView
    }
  ]
})

router.beforeEach((to, from) =>{
  if(to.name == 'login'){
    return true
  }
  if(!localStorage.getItem('token')) {
    return {
      name: 'login'
    }
  }
  checkTokenAuthenticity()
})

const checkTokenAuthenticity = () => {
  axios.get('http://localhost:8000/api/user', {
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`
    }
  })
    .then((response) => {
      console.log(response.data)
    })
    .catch((error) => {
      localStorage.removeItem('token')
      router.push({
        name: 'login'
      })
      console.error(error)
    })
}




export default router
