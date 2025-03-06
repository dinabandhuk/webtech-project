import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import VueGoogleMaps from '@fawmi/vue-google-maps'

const app = createApp(App)

const apikey = import.meta.env.VITE_GOOGLE_MAP_API_KEY

app.use(createPinia())
app.use(router)

app.use(VueGoogleMaps, {
    load: {
        key: apikey,
        libraries: "places"
    }
})

app.mount('#app')
