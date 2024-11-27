<template>
    <div>
      <!-- Results Section -->
      <div id="translateResultsInfo" class="w-full h-screen flex justify-center items-center bg-[#ff6666]">
        <div class="text-center w-2/5 flex flex-col items-center">
          <p class="text-6xl mb-10 font-opensans text-white font-thin">
            There are 12 results in English and Spanish
          </p>
  
          <button class="nextButton" @click="scrollToTranslateMeaningInfo">
            <i class="fas fa-angle-down"></i>
            <span>View</span>
          </button>
        </div>
      </div>
  
      <!-- Meaning Section -->
      <div id="translateMeaningInfo" class="w-full h-screen flex justify-center items-center bg-[#ff6666] relative">
        <div class="text-center w-2/5 flex flex-col items-center">
          <p class="text-6xl mb-10 font-opensans text-white font-thin">
            We have the meaning of your original text
          </p>
          <button class="nextButton">
            <i class="fas fa-angle-down"></i>
            <span>View</span>
          </button>
        </div>
  
        <!-- Restart Button -->
        <div
          id="restartContainer"
          class="restart-container flex flex-col items-center justify-center w-16 h-16 bg-gray-300 text-gray-500 cursor-pointer transition-all duration-300 ease-in-out absolute right-10 top-1/2"
          @click="scrollToTopAndEmitRestart"
        >
          <i class="fas fa-redo-alt fa-lg mb-1"></i>
          <span class="text-sm">Restart</span>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    methods: {
      scrollToTranslateMeaningInfo() {
        const meaningSection = document.getElementById('translateMeaningInfo');
        if (meaningSection) {
          meaningSection.scrollIntoView({ behavior: 'smooth' });
        }
      },
      
      scrollToTopAndEmitRestart() { 
        window.scrollTo({ top: 0, behavior: 'smooth' });
        setTimeout(() => {
          this.$emit('restart');
        }, 600); 
      },
    },
  };
   </script>
  
  <style scoped>
  .nextButton {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff;
    color: #1b1b1b;
    font-size: 1.25rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
  }
  
  .nextButton i {
    margin-right: 0.5rem;
    font-size: 1.5rem;
    transition: transform 0.3s ease-in-out;
  }
  
  .nextButton:hover {
    background-color: #ffffff;
    color: #9d9c9c;
  }
  
  .nextButton:hover i {
    transform: translateY(5px);
  }
  
  .restart-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background-color: #d0c8c8;
    color: #333;
    border-radius: 15%;
    cursor: pointer;
    transition: transform 0.3s ease-in-out;
  }
  
  .restart-container:hover {
    background-color: #ffffff;
  }
</style>
  