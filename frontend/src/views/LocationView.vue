<template>
    <div class="pt-50">
      <h1 class="text-3xl font-semibold mb-4">कृपया आफ्नो गन्तव्य प्रदान गर्नुहोस्।</h1>
      <form action="#">
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
            <VueLocationIQ
            api-key="pk.55711316afe9535ac02e93fcc2e5df56"  
            v-model="search"
            />
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button 
            type="button"
            class="inline-flex justify-center rounded-md border border-transparent bg-black px-4 py-2 text-white"
            >
              Find a driver
            </button>
          </div>
        </div>
      </form>
    </div>
</template>

<script setup>

import { VueLocationIQ } from "vue-location-iq";
import "vue-location-iq/dist/style.css";
import { ref } from "vue";

const search = ref("");
</script>
