<template>
  <div id="app" class="app-container flex justify-center items-center h-screen bg-gray-100">
    <div class="text-center mt-10">
      <!-- <h1 class="text-3xl font-semibold mb-6 text-gray-800">Let's translate the world's food</h1> -->
      <!-- Translation Input Component -->
      <TranslationInput />
    </div>
  </div>
</template>

<script>
import TranslationInput from './components/TranslationInput.vue';

export default {
  components: {
    TranslationInput,
  },
};
</script>
