<template>
  <div id="top" class="translationDemoflex flex-col bg-color gray items-center w-full h-screen">
    <TranslateComponent 
      ref="inputRef" 
      :initialText="inputText" 
      @submit-text="handleTranslationSubmit" 
    />
    <ResultAndMeaningComponent
      v-if="showResults"
      :hasUnknownWords="hasUnknownWords"
      @view-meaning="handleViewMeaning"
      @restart="handleRestart"
      @fix="handleFix"
    />
  </div>
</template>

<script>
import TranslateComponent from './components/TranslationInput.vue';
import ResultAndMeaningComponent from './components/ResultAndMeaningComponent.vue';

export default {
  data() {
    return {
      showResults: false,
      hasUnknownWords: false,
      inputText: "", // Store the input text here
    };
  },
  methods: {
    handleTranslationSubmit({ inputText, hasUnknownWords }) {
      this.inputText = inputText; // Preserve the input text
      this.showResults = true;
      this.hasUnknownWords = hasUnknownWords;

      document.body.style.overflow = 'auto';

      this.$nextTick(() => {
        this.scrollToSection('translateResultsInfo');
      });
    },
    handleViewMeaning() {
      this.$nextTick(() => {
        this.scrollToSection('translateMeaningInfo');
      });
    },
    handleRestart() {
      // Clear the input text and reset the state
      this.inputText = ""; // Clear input text
      this.showResults = false;
      this.hasUnknownWords = false;

      document.body.style.overflow = 'hidden'; // Disable scrolling

      this.scrollToSection('top');
    },
    handleFix() {
      // Retain the input text and reset the state
      this.showResults = false;

      document.body.style.overflow = 'hidden'; // Disable scrolling

      this.scrollToSection('top');
    },
    scrollToSection(id) {
      const targetSection = document.getElementById(id);
      if (targetSection) {
        targetSection.scrollIntoView({ behavior: 'smooth' });
      }
    },
  },
  components: {
    TranslateComponent,
    ResultAndMeaningComponent,
  },
};
</script>

<style>
.translationDemo {
  background-color: #f7f6f6;
}
</style>  