<template>
    <div class="translation-container w-full max-w-3xl mx-auto p-6 h-auto text-center">
      <h1 class="text-4xl mb-6 opacity-70 transition-opacity duration-300">
        Let's translate the world's food
      </h1>
      <div class="relative w-full h-auto">
        <textarea
          v-model="inputText"
          @input="handleInput"
          maxlength="160"
          ref="textarea"
          class="custom-textarea  w-full  p-6 pr-12 rounded-lg border border-gray-300 text-lg resize-none outline-none overflow-hidden transition-opacity duration-300 focus:shadow-md"
        ></textarea>
        <div
          :class="['char-count absolute bottom-3 left-4 text-sm', inputText.length === 160 ? 'text-red-500' : 'text-gray-500']"
        >
          {{ inputText.length }} / 160
        </div>
        <button
          @click="toggleKeyboard"
          class="keyboard-icon absolute bottom-3 right-4 p-1 transition duration-200 hover:scale-125"
        >
          <span class="material-icons" style="font-size: 16px;">keyboard</span>
        </button>
      </div>
  
      <transition name="keyboard-fade">
        <div
          v-if="showKeyboard"
          class="special-characters mt-0 p-3 bg-white rounded-lg shadow-lg flex flex-wrap gap-2 w-full max-w-lg mx-auto"
        >
          <button
            v-for="(char, index) in specialChars"
            :key="index"
            @click.stop="addSpecialCharacter(char)"
            class="px-2 py-1 bg-gray-200 rounded-md hover:bg-gray-300 transition duration-200"
          >
            {{ char }}
          </button>
        </div>
      </transition>
  
      <button
        @click="handleSubmit"
        class="submit-btn relative mt-6 bg-white text-black rounded-full overflow-hidden transition-all duration-300 ease-in-out group"
      >
        <span
          class="arrow-icon material-icons absolute left-1/2 transform -translate-x-1/2 text-4xl group-hover:translate-x-6 transition-all duration-300 ease-in-out"
        >
          arrow_forward
        </span>
        <span
          class="translate-text opacity-0 whitespace-nowrap group-hover:opacity-100 group-hover:ml-10 transition-all duration-300 ease-in-out text-lg font-semibold"
        >
          translate
        </span>
      </button>
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
          'ä', 'â', 'á', 'à', 'ã', 'å', 'ë', 'ê', 'é', 'è', 'ï',
          'ö', 'ô', 'ó', 'ò', 'õ', 'ü', 'û', 'ú', 'î', 'í', 'ì',
          'ù', 'ç', 'ñ', 'ø', 'ß', 'æ', 'œ', 'ÿ'
        ],
        showError: false
      };
    },
    methods: {
      toggleKeyboard() {
        // Toggles keyboard visibility only when the icon is clicked
        this.showKeyboard = !this.showKeyboard;
      },
      hideKeyboard() {
        // Ensures keyboard stays hidden if needed (logic for blur events if necessary)
        this.showKeyboard = false;
      },
      handleInput(event) {
        this.filterInput();
        this.autoResize(event);
      },
      filterInput() {
        const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
        this.inputText = this.inputText
          .toLowerCase()
          .split('')
          .filter((char) => allowedChars.test(char))
          .join('');
      },
      addSpecialCharacter(char) {
        // Check if adding the character exceeds the maxlength
        if (this.inputText.length >= 160) return;

        const textarea = this.$refs.textarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;

        // Add the character at the cursor position
        this.inputText =
        this.inputText.substring(0, start) +
        char +
        this.inputText.substring(end);

    // Adjust cursor position and focus on the textarea
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
      }
    }
  };
  </script>