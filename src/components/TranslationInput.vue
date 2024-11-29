<template>
  <div class="translationDemo flex justify-center bg-color gray items-center w-full h-screen">
    <!-- Main Translation Container -->
    <div class="w-full h-1/4">
      <!-- Title -->
      <div class="flex flex-col justify-end items-center relative bottom-[120px]">
        <p class="text-6xl w-3/5 text-center mb-12 font-thin">
          Let's translate the world's food
        </p>

        <!-- Translate Input Container -->
        <div class="translateInputContainer relative h-auto w-3/5 bg-white rounded-xl">
          <div class="textarea-wrapper relative">
            <!-- Textarea for input -->
            <div
            contenteditable="true"
              class="inputBox w-full h-32 p-4 font-light text-center outline-none border-none text-4xl font-light bg-transparent whitespace-pre-wrap break-words resize-none overflow-y-auto"
              :value="inputText"
              maxlength="160"
              ref="textarea"
              @input="handleInput"
              @blur="sanitizeInput"
            ></div>

            <!-- Highlighted text with hoverable unknown words -->
            <div
              class="absolute inset-0 p-4 font-light text-center text-4xl bg-transparent pointer-events-none whitespace-pre-wrap break-words overflow-y-hidden"
              style="color: inherit; max-height: 8rem;"
              v-html="highlightedText"
            ></div>
          </div>

          <div class="flex justify-between items-center absolute bottom-0 left-4 right-4">
            <div class="text-sm">
              <span :class="inputText.length === 160 ? 'text-red-500' : 'text-gray-400'">
                {{ inputText.length }}/160
              </span>
            </div>

            <div class="flex justify-center items-center">
              <button class="icon-button" @click="handleFeedback">
                <i class="fas fa-comment-alt"></i>
              </button>
              <button class="icon-button" @click="toggleKeyboard">
                <i class="fas fa-keyboard"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="keyboard-and-button-container flex flex-col items-center mt-2 w-3/5">
          <div
            class="keyboard-container flex flex-wrap w-full bg-white text-gray-400 justify-center gap-1 rounded-xl overflow-hidden transition-all duration-300"
            :class="{'max-h-0 p-0': !showKeyboard, 'max-h-[188px] p-2': showKeyboard}"
          >
            <button
              v-for="(char, index) in specialChars"
              :key="index"
              class="p-4 w-10 h-8 flex justify-center items-center text-gray-400 cursor-pointer border border-gray-300 rounded hover:bg-gray-100"
              @click="addSpecialCharacter(char)"
            >
              {{ char }}
            </button>
          </div>

          <ButtonComponent
            @action="handleSubmit"
            :icon="'fas fa-arrow-right'"
            buttonText="translate"
            class="mt-12"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ButtonComponent from './ButtonComponent.vue';

export default {
  data() {
    return {
      inputText: '',
      showKeyboard: false,
      specialChars: ['ä', 'â', 'á', 'à', 'ã', 'å', 'ë', 'ê', 'é', 'è', 'ï', 'î', 'í', 'ì', 'ö', 'ô', 'ó', 'ò', 'õ', 'ü', 'û', 'ú', 'ù', 'ç', 'ñ', 'ø', 'ß', 'æ', 'œ', 'ÿ'],
      unknownWords: ['asd', 'suck', 'tuscany'],
    };
  },
  computed: {
    highlightedText() {
      return this.inputText
        .split(' ')
        .map((word, index) => {
          if (this.unknownWords.includes(word.toLowerCase())) {
            return `<span class="unknownWordSpan" style="color: #ff6666; position: relative;">
                      ${word}
                      <span class="removeIcon" contenteditable="false" onclick="window._removeWordFromTranslatorInput(${index})" style="cursor:pointer; font-size: 0.6em; position: absolute; top: -10px; right: -10px; color: black;">
                        <i class="fas fa-times-circle"></i>
                      </span>
                    </span>`;
          }
          return word;
        })
        .join(' ');
    },
  },
  methods: {
    toggleKeyboard() {
      this.showKeyboard = !this.showKeyboard;
    },
    handleInput() {
      const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
      this.inputText = this.inputText
        .toLowerCase()
        .split('')
        .filter((char) => allowedChars.test(char))
        .join('')
        .substring(0, 160);
    },
    sanitizeInput() {
      const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
      this.inputText = this.inputText
        .toLowerCase()
        .split('')
        .filter((char) => allowedChars.test(char))
        .join('')
        .substring(0, 160);
    },
    addSpecialCharacter(char) {
      if (this.inputText.length >= 160) return;
      const textarea = this.$refs.textarea;
      const start = textarea.selectionStart;
      const end = textarea.selectionEnd;
      this.inputText = this.inputText.substring(0, start) + char + this.inputText.substring(end);
      this.$nextTick(() => {
        textarea.setSelectionRange(start + 1, start + 1);
        textarea.focus();
      });
    },
    removeWord(index) {
      const words = this.inputText.split(' ');
      words.splice(index, 1);
      this.inputText = words.join(' ');
    },
    handleSubmit() {
      if (this.inputText.trim() === '') {
        alert('Please enter some text before submitting.');
        return;
      }
      const hasUnknownWords = this.inputText
        .split(' ')
        .some((word) => this.unknownWords.includes(word.toLowerCase()));
      this.$emit('submit-text', { inputText: this.inputText, hasUnknownWords });
    },
    resetInput() {
      this.inputText = '';
    },
  },
  components: {
    ButtonComponent,
  },
};

// Expose a global function to remove words from the input (since we are using v-html for dynamic content)
window._removeWordFromTranslatorInput = function (index) {
  const app = document.querySelector('#app').__vue__;
  app.removeWord(index);
};
</script>
    <style scoped>
        .keyboard-slide-enter-active,
        .keyboard-slide-leave-active {
        transition: all 0.3s ease;
        }
    
        .keyboard-slide-enter-from {
        opacity: 0;
        transform: translateY(-20px);
        }
    
        .keyboard-slide-enter-to {
        opacity: 1;
        transform: translateY(0);
        }
    
        .keyboard-slide-leave-from {
        opacity: 1;
        transform: translateY(0);
        }
    
        .keyboard-slide-leave-to {
        opacity: 0;
        transform: translateY(-20px);
        }
    
        .icon-button {
        background: none;
        cursor: pointer;
        font-size: 1rem;
        width: 1.5rem;
        height: 2.5rem;
        transition: color 0.2s ease;
        }
    
        .icon-button:hover {
        color: #333;
        }
    </style>
    