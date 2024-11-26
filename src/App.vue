<template>
  <div class="translationDemoflex flex-col   bg-color gray items-center w-full h-screen">
    <TranslateComponent @submit-text="handleTranslationSubmit" />
    <ResultAndMeaningComponent
      v-if="showResults"
      @view-meaning="handleViewMeaning"
      @restart="handleRestart"
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
      showMeaning: false,
    };
  },
 methods: {
  handleTranslationSubmit() {
    this.showResults = true;
    this.showMeaning = false;

    
    document.body.style.overflow = 'auto';

   
    this.$nextTick(() => {
      this.scrollToSection('translateResultsInfo');
    });
  },
  handleViewMeaning() {
    this.showMeaning = true;

   
    this.$nextTick(() => {
      this.scrollToSection('translateMeaningInfo');
    });
  },
  handleRestart() {
    this.showResults = false;
    this.showMeaning = false;

  
    document.body.style.overflow = 'hidden';

    
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