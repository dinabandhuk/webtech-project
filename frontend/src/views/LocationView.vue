<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">कृपया आफ्नो गन्तव्य प्रदान गर्नुहोस्।</h1>
    <form action="#">
      <div class="overflow-hidden shadow sm:rounded-md max-w-125 mx-auto text-left">
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <GMapAutocomplete placeholder="Enter your destination here" @place_changed="handleLocationChanged"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-b-blue-900 shadow-sm"></GMapAutocomplete>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button @click.prevent="handleSelectLocation" type="button"
            class="inline-flex justify-center rounded-md border border-transparent bg-black px-4 py-2 text-white">
            Find a ride
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>

import { ref, isProxy } from "vue";
import { useLocationStore } from '@/stores/location'
import { useRouter } from "vue-router";

const router = useRouter()
const search = ref("");

const location = useLocationStore();
const handleLocationChanged = (e) => {
  console.log('handle location changed', e)
  location.$patch({
    destination: {
      name: e.name,
      address: e.formatted_address,
      geometry: {
        lat: e.geometry.location.lat(),
        lng: e.geometry.location.lng()
      }
    }
  })
}

const handleSelectLocation = () => {
  if (location.destination.name != '') {
    router.push({
      name: 'map'
    })
  }
}

</script>
