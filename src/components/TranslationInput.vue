<template>
  <div class="translation-container w-full max-w-7xl mx-auto p-8 h-auto text-center">
    <!-- Main Title -->
    <h1 class="text-6xl text-center mb-12 transition-opacity font-light duration-300">
      Let's translate the world's food
    </h1>

    <!-- Input Area -->
    <div class="relative w-full max-w-6xl mx-auto">
      <!-- Textarea for input -->
      <textarea
        v-model="inputText"
        @input="handleInput"
        maxlength="160"
        ref="textarea"
        class="custom-textarea w-full h-40 opacity-55 p-6 pr-12 rounded-lg border border-gray-300 text-lg resize-none outline-none transition-opacity duration-300 focus:shadow-md"
      ></textarea>

      <!-- Character counter -->
      <div
        :class="['char-count absolute bottom-3 left-4 text-sm', inputText.length === 160 ? 'text-red-500' : 'text-gray-500 opacity-55']"
      >
        {{ inputText.length }} / 160
      </div>

      <!-- Icons for Feedback and Keyboard -->
      <div class="absolute bottom-0 right-3 flex items-center space-x-3">
        <!-- Feedback Icon -->
        <button
          @click="handleFeedback"
          class="feedback-icon opacity-55 p-1 transition duration-200 hover:scale-125"
        >
          <span class="material-icons" style="font-size: 16px;">feedback</span>
        </button>

        <!-- Keyboard Icon -->
        <button
          @click="toggleKeyboard"
          class="keyboard-icon opacity-55 p-1 transition duration-200 hover:scale-125"
        >
          <span class="material-icons" style="font-size: 16px;">keyboard</span>
        </button>
      </div>
    </div>

    <!-- Special characters keyboard (with transitions) -->
    <transition
      name="keyboard-slide"
      @before-enter="beforeEnter"
      @enter="enter"
      @leave="leave"
    >
      <div
        v-if="showKeyboard"
        class="keyboard-container flex flex-wrap justify-center gap-1 mt-4 p-2 rounded-xl overflow-y-auto sm:overflow-hidden bg-white w-full max-w-3xl mx-auto"
      >
        <div
          v-for="(char, index) in specialChars"
          :key="index"
          @click="addSpecialCharacter(char)"
          class="keyboard-key opacity-55 p-1 w-6 h-6 lg:w-8 lg:h-8 xl:w-10 xl:h-10 flex justify-center items-center text-secondary-content cursor-pointer border border-gray-300 rounded hover:bg-gray-100 hover:shadow-thin-n-strict"
        >
          {{ char }}
        </div>
      </div>
    </transition>

    <!-- Submit button (with bounce transition) -->
    <transition name="bounce">
      <button
        @click="handleSubmit"
        class="submit-btn relative mt-8 bg-white text-black rounded-full transition-all duration-300 ease-in-out group"
      >
        <strong
          class="arrow-icon material-icons absolute left-1/2 transform -translate-x-1/2 text-5xl font-bold group-hover:translate-x-6 transition-transform duration-300"
        >
          arrow_forward
        </strong>
        <span
          class="translate-text opacity-0 whitespace-nowrap group-hover:opacity-100 group-hover:ml-10 transition-all duration-300 text-lg font-semibold"
        >
          translate
        </span>
      </button>
    </transition>

    <!-- Error message if input is empty -->
    <p v-if="showError" class="error-message mt-4 text-red-500 font-semibold">
      Please enter some text before submitting.
    </p>
  </div>
</template>

  
  <script>
export default {
  data() {
    return {
      inputText: '',
      showKeyboard: false,
      specialChars: [
        'ä', 'â', 'á', 'à', 'ã', 'å', 'ë', 'ê', 'é', 'è', 'ï', 'î', 'í', 'ì',
        'ö', 'ô', 'ó', 'ò', 'õ', 'ü', 'û', 'ú', 'ù', 'ç', 'ñ', 'ø', 'ß', 'æ', 'œ', 'ÿ',
      ],
      allowedCharsPattern: /^[a-z0-9\s,.:_-]*$/, // Regular expression for allowed characters
      showError: false,
    };
  },
  methods: {
    toggleKeyboard() {
      this.showKeyboard = !this.showKeyboard;
    },
    handleFeedback() {
      alert('Feedback button clicked!');
    },
    handleInput(event) {
      this.autoResize(event);
      this.inputText = this.inputText.toLowerCase();
      if (!this.allowedCharsPattern.test(this.inputText)) {
        this.inputText = this.inputText.replace(/[^a-z0-9\s,.:_-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]/g, '');
      }
    },
    addSpecialCharacter(char) {
      if (this.inputText.length >= 160) return;
      const textarea = this.$refs.textarea;
      const start = textarea.selectionStart;
      const end = textarea.selectionEnd;
      this.inputText =
        this.inputText.substring(0, start) +
        char +
        this.inputText.substring(end);
      this.$nextTick(() => {
        textarea.setSelectionRange(start + 1, start + 1);
        textarea.focus();
      });
    },
    handleSubmit() {
      if (this.inputText.trim() === '') {
        this.showError = true;
        setTimeout(() => (this.showError = false), 2000);
      } else {
        this.showError = false;
        alert('Translation submitted!');
      }
    },
    autoResize(event) {
      const textarea = event.target;
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    },
  },
};
</script>
