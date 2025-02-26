<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Dinabandhu Ride share</h1>
    <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
      <div class="bg-white px-4 py-5 sm:p-6">
        <div class="flex justify-between">
          <button
            class="inline-flex justify-center rounded-md border border-transparent bg-black px-4 py-2 text-white"
          >
            Start Driving
          </button>
          <button
            @click="handleFindARide"
            class="inline-flex justify-center rounded-md border border-transparent bg-black px-4 py-2 text-white"
          >
            Find a ride
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';


const router = useRouter()
const handleFindARide = () => {
  router.push({
    name: 'location'
  })
}

</script>