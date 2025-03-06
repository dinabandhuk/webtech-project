<template>
    <div class="pt-16">
        <h1 class="text-3xl font-semibold mb-4">Here's your trip</h1>
        <div>
            <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto">
                <div class="bg-white px-4 py-5 sm:p-6">
                    <div>
                        <GMapMap v-if="location.destination.name !== ''" :zoom="16"
                            :center="location.destination.geometry" ref="gMap" style="width: 100%; height: 500px;">
                            <GMapMarker :position="location.destination.geometry" />
                        </GMapMap>
                    </div>
                    <div class="mt-2">
                        <p class="text-xl">Going to <strong>{{ location.destination.name }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departure time label (No input, just current date/time will be used) -->
        <div class="mt-4">
            <p class="text-lg">Departure Time: <strong>{{ currentDateTime }}</strong></p>
        </div>
        <button @click="getRoute" class="mt-4 p-2 bg-blue-500 text-white rounded-md">Get Route</button>
    </div>
</template>

<script setup>

import { useLocationStore } from '@/stores/location'
import { onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import axios from 'axios'

const location = useLocationStore()
const router = useRouter()
const gMap = ref(null)

const API_KEY = import.meta.env.VITE_GOOGLE_MAP_API_KEY // Replace with your API key

// Set current date and time as departure time
const currentDateTime = new Date(new Date().getTime() + 50000000).toISOString(); // Adding 50000000 milliseconds
const getRoute = async () => {
    if (location.destination.name == "") {
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
        let currentPoint = location.current.geometry,
            destinationPoint = location.destination.geometry;

        // Replace the Directions API with the Routes API call
        axios.post('https://routes.googleapis.com/directions/v2:computeRoutes', {
            origin: {
                location: {
                    latLng: {
                        latitude: currentPoint.lat,
                        longitude: currentPoint.lng
                    }
                }
            },
            destination: {
                location: {
                    latLng: {
                        latitude: destinationPoint.lat,
                        longitude: destinationPoint.lng
                    }
                }
            },
            travelMode: "DRIVE",
            polylineQuality: "OVERVIEW",
            routingPreference: "TRAFFIC_AWARE",
            departureTime: currentDateTime // Use current date and time as departure time
        }, {
            headers: {
                'Content-Type': 'application/json; charset=utf-8',
                'X-Goog-Api-Key': API_KEY,
                'X-Goog-FieldMask': 'routes.distanceMeters,routes.duration,routes.polyline' // Include necessary fields
            }
        })
            .then((response) => {
                const directions = response.data.routes;
                const polyline = directions[0].polyline;
                const legs = directions[0].legs;

                // Display directions on the map
                const directionsDisplay = new google.maps.DirectionsRenderer({
                    map: mapObject,
                    directions: response.data
                });
            })
            .catch((error) => {
                console.error('Error fetching route:', error);
            });

    })
}
</script>
