<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Here's your trip</h1>
        <div>
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto">
                <div class="bg-white px-4 py-5 sm:p-6">
                    <div>
                        <GMapMap :zoom="11" :center="location.destination.geometry"
                            style="width: 100%; height: 256px;">
                            <GMapMarker :position="location.destination.geometry" />
                        </GMapMap>
                    </div>
                    <div class="mt-2">
                        <p class="text-xl">Going to <strong>{{ location.destination.name }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { useLocationStore } from '@/stores/location'
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'

const location = useLocationStore()
const router = useRouter()

onMounted(() => {
    if(location.destination.name == ""){
        router.push({
            name: 'location'
        })
    }

    navigator.geolocation.getCurrentPosition((success) => {
        console.log(success);
        
    }, (error) => {
        console.error(error)
    })
})

</script>