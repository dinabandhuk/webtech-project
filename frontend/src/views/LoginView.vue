<template>
    <div class="pt-16">
      <h1 class="text-3xl font-semibold mb-4">Enter your phone number</h1>
      <form @submit.prevent="handleSubmit">
        <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left">
          <div class="bg-white px-4 py-5 sm:p-6">
            <div>
              <input
                v-model="phone"
                type="text"
                name="phone"
                id="phone"
                placeholder="+977-1234567890"
                class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300"
              />
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
            <button
              type="submit"
              class="inline-flex justify-center rounded-md border border-transparent bg-black px-4 py-2 text-white"
            >
              Submit
            </button>
          </div>
        </div>
      </form>
    </div>
  </template>
  
  <script setup>
 
  </script>
  