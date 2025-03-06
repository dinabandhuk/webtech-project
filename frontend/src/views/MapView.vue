<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Here's your trip</h1>
        <div>
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto">
                <div class="bg-white px-4 py-5 sm:p-6">
                    <div>
                        <GMapMap v-if="location.destination.name !== ''" :zoom="16" :center="location.destination.geometry"
                            ref="gMap"
                            style="width: 100%; height: 500px;">
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
import { ref } from 'vue'

const location = useLocationStore()
const router = useRouter()
const gMap = ref(null)

onMounted( async () => {
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
    await location.updateCurrentLocation()

    gMap.value.$mapPromise.then((mapObject) => {
        let currentPoint = new google.maps.LatLng(location.current.geometry),
            destinationPoint = new google.maps.LatLng(location.destination.geometry),
            directionsService = new google.maps.DirectionsService,
            directionsDisplay = new google.maps.DirectionsRenderer({
                map: mapObject
            });
   
    directionsService.route({
        origin: currentPoint,
        destination: destinationPoint,
        avoidTolls: false,
        avoidHighways: false,
        travelMode: google.maps.TravelMode.DRIVING
    }, (res, status) => {
        if(status === google.maps.DirectionsStatus.OK){
            directionsDisplay.setDirections(res)
        }
        else{
            console.error(status)
        }
    })
})
})


</script>