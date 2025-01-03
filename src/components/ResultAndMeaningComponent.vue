<template>
  <div>
    <!-- Results Section -->
    <div
      id="translateResultsInfo"
      class="w-full h-screen flex justify-center items-center bg-[#ff6666]"
    >
      <div class="text-center w-2/5 flex flex-col items-center">
        <p class="text-6xl mb-10 font-opensans text-white font-thin">
          {{ resultMessage }}
        </p>
        <button
          v-if="hasUnknownWords"
          class="fixButton"
          @click="scrollToTopAndEmitRestart"
        >
          <i class="fas fa-angle-up"></i>
          <span>Fix</span>
        </button>
        <button v-else class="nextButton" @click="scrollToTranslateMeaningInfo">
          <i class="fas fa-angle-down"></i>
          <span>View</span>
        </button>
      </div>
    </div>

    <!-- Meaning Section -->
    <div
      id="translateMeaningInfo"
      class="w-full h-screen flex justify-center items-center bg-[#ff6666] relative"
    >
      <div class="text-center w-2/5 flex flex-col items-center">
        <p class="text-6xl mb-10 font-opensans text-white font-thin">
          We have the meaning of your original text
        </p>
        <!-- Modified Button -->
        <button class="nextButton">
          <i class="fas fa-angle-down"></i>
          <span>View</span>
        </button>
      </div>
      <!-- Restart Button Positioned Right and Center -->
      <div
        id="restartContainer"
        class="restart-container flex flex-col items-center justify-center w-16 h-16 bg-gray-300 text-gray-500 cursor-pointer transition-all duration-300 ease-in-out absolute right-10 top-1/2"
        @click="scrollToTop"
      >
        <i class="fas fa-redo-alt fa-lg mb-1"></i>
        <!-- Refresh Icon -->
        <span class="text-sm">Restart</span>
        <!-- Text -->
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    hasUnknownWords: Boolean, // Prop to indicate if there are unknown words
  },
  computed: {
    resultMessage() {
      return this.hasUnknownWords
        ? "Please remove or replace unknown words with an alternative before translating."
        : "There are 12 results in English and Spanish";
    },
  },
  methods: {
    scrollToTranslateMeaningInfo() {
      const meaningSection = document.getElementById("translateMeaningInfo");
      if (meaningSection) {
        meaningSection.scrollIntoView({ behavior: "smooth" });
      }
    },
    scrollToTopAndEmitRestart() {
      window.scrollTo({ top: 0, behavior: "smooth" });
      setTimeout(() => {
        this.$emit("restart");
      }, 600);
    },
    scrollToTop() {
      // Add this missing function
      window.scrollTo({ top: 0, behavior: "smooth" });
    },
  },
};
</script>

<style scoped lang="scss">
$primary-color: #ff6666;
$button-hover-color: #9d9c9c;
$button-bg-color: #ffffff;
$font-color: #1b1b1b;
$restart-bg-color: #d0c8c8;
$restart-hover-color: #ffffff;

.nextButton {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: $button-bg-color;
  color: $font-color;
  font-size: 1.25rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease-in-out;

  i {
    margin-right: 0.5rem;
    font-size: 1.5rem;
    transition: transform 0.3s ease-in-out;
  }

  &:hover {
    background-color: $button-bg-color;
    color: $button-hover-color;

    i {
      transform: translateY(5px);
    }
  }
}

.restart-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
  background-color: $restart-bg-color;
  color: #333;
  border-radius: 15%;
  cursor: pointer;
  transition: transform 0.3s ease-in-out;

  &:hover {
    background-color: $restart-hover-color;
  }
}

.fixButton {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: $button-bg-color;
  color: $font-color;
  font-size: 1.25rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease-in-out;

  i {
    margin-right: 0.5rem;
    font-size: 1.5rem;
    transition: transform 0.3s ease-in-out;
  }

  &:hover {
    background-color: $button-bg-color;
    color: $button-hover-color;

    i {
      transform: translateY(5px);
    }
  }
}
</style>
