<!-- author : dinabandhu khatiwada -->
<template>
    <div class="pt-16">
      <h1 class="text-3xl font-semibold mb-4">Enter your phone number</h1>
      <form v-if="!waitingOnVerification" action="#" @submit.prevent="handleLogin">
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
              <input
                v-model="credentials.phone"
                type="text"
                v-maska data-maska="(###) ## ### #####"
                name="phone"
                id="phone"
                placeholder="(977) 97 456 00000"
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300"
              />
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button @click="submit" @submit.prevent="handleLogin"
              class="inline-flex justify-center rounded-md border border-transparent bg-black px-4 py-2 text-white"
            >
              Submit
            </button>
          </div>
        </div>
      </form>
      <form v-else action="#" @submit.prevent="handleVerification">
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
              <input
                v-model="credentials.login_code"
                type="text"
                v-maska data-maska="#####"
                name="login_code"
                id="login_code"
                placeholder="(977) 97 456 00000"
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300"
              />
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button @click="submit" @submit.prevent="handleVerification"
              class="inline-flex justify-center rounded-md border border-transparent bg-black px-4 py-2 text-white"
            >
              Verify
            </button>
          </div>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
import {vMaska} from 'maska/vue'
import { onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const credentials = reactive({
  phone: null,
  login_code: null,
})

// checks if user is already logged in and redirects to landing page if token is found 
onMounted(()=>{
  if(localStorage.getItem('token')){
    router.push({
      name: 'landing'
    })
  }
})

const formattedCredentials = () => {
  return {
    phone: credentials.phone.replaceAll(' ', '').replace('(', '').replace(')', ''),
    login_code: credentials.login_code,
  }
}

const waitingOnVerification = ref(false)

const handleLogin = () =>{
  axios.post('http://localhost:8000/api/login', formattedCredentials())
  .then((response) =>{
    console.log(response.data)
    waitingOnVerification.value = true
  })
  .catch((error) =>{
    console.error(error)
    alert(error.response.data.message)
  })
}

const handleVerification =  () =>{
  axios.post('http://localhost:8000/api/login/verify', formattedCredentials())
  .then((response) => {
    console.log(response.data) // this should be the authentication token
    localStorage.setItem('token', response.data) // store the token
    router.push({
      name: 'landing'
    })
  })
  .catch((error) =>{
    console.error(error)
    alert(error.response.data.message)
  })
}
  </script>
  