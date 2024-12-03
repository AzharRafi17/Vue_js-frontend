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
            <!-- Text displayed on top of the textarea -->
            <div
              class="inputBox w-full h-auto p-4 text-center text-opacity outline-none border-none text-4xl font-light bg-transparent whitespace-pre-wrap break-words resize-none overflow-hidden absolute z-10"
            >
              <span
                v-for="(word, index) in words"
                :key="index"
                :class="{'unknown-word': unknownWords.includes(word.toLowerCase())}"
                class="word-wrapper inline-block relative"
              >
                {{ word }}
                <!-- Add cross icon for unknown words -->
                <i
                  v-if="unknownWords.includes(word.toLowerCase())"
                  class="remove-word-icon fas fa-times absolute top-rightcorner cursor-pointer"
                  @click="removeWord(index)"
                ></i>
                <!-- Add a space after each word -->
                <span>&nbsp;</span>
              </span>
            </div>

            <!-- Actual hidden textarea -->
            <textarea
              class="inputBox w-full h-auto p-4 text-center outline-none border-none text-4xl font-light bg-transparent whitespace-pre-wrap break-words resize-none overflow-hidden"
              v-model="inputText"
              maxlength="160"
              ref="textarea"
              @input="handleInput"
              @blur="sanitizeInput"
              @focus="handleFocus"
              :style="{ color: 'black', caretColor: 'black' }"
            ></textarea>
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
            :class="{'max-h-0 p-0': !showKeyboard, 'max-h-[188px] p-2': showKeyboard} "
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
      inputText: "", // Main input text for translation
      showKeyboard: false, // Control for showing the special character keyboard
      specialChars: [
        "ä", "â", "á", "à", "ã", "å", "ë", "ê", "é", "è",
        "ï", "î", "í", "ì", "ö", "ô", "ó", "ò", "õ", "ü",
        "û", "ú", "ù", "ç", "ñ", "ø", "ß", "æ", "œ", "ÿ"
      ],
      unknownWords: ["asd", "suck", "tuscany"], // List of unknown words
    };
  },
  computed: {
    words() {
      return this.inputText.split(' '); // Split the input text into words
    },
  },
  methods: {
    toggleKeyboard() {
      this.showKeyboard = !this.showKeyboard;
    },
    handleInput(event) {
      const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
      this.inputText = this.inputText
        .toLowerCase()
        .split(" ")
        .filter((word) => allowedChars.test(word))
        .join(" ")
        .substring(0, 160);
      this.autoResize(event);
    },
    sanitizeInput() {
      const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
      this.inputText = this.inputText
        .toLowerCase()
        .split(" ")
        .filter((word) => allowedChars.test(word))
        .join(" ")
        .substring(0, 160);
    },
    addSpecialCharacter(char) {
      const textarea = this.$refs.textarea;
      if (!textarea) return; // Prevent null reference errors

      if (this.inputText.length >= 160) return;
      const start = textarea.selectionStart;
      const end = textarea.selectionEnd;

      this.inputText = this.inputText.substring(0, start) + char + this.inputText.substring(end);

      this.$nextTick(() => {
        if (!textarea) return; // Ensure the textarea still exists
        textarea.setSelectionRange(start + 1, start + 1);
        textarea.focus();
      });
    },
    handleSubmit() {
      if (this.inputText.trim() === "") {
        alert("Please enter some text before submitting.");
        return;
      }
      const hasUnknownWords = this.inputText
        .split(" ")
        .some((word) => this.unknownWords.includes(word.toLowerCase()));
      this.$emit("submit-text", { inputText: this.inputText, hasUnknownWords });
    },
    resetInput() {
      this.inputText = "";
    },
    autoResize(event) {
      const textarea = event.target;
      if (!textarea) return; // Ensure textarea exists before manipulating it
      textarea.style.height = "auto";
      textarea.style.height = textarea.scrollHeight + "px";
    },
    /**
     * Remove word from the input text when the cross icon is clicked.
     * This removes the word from the inputText by splitting the text into an array,
     * removing the word at the specified index, and rejoining it.
     */
    removeWord(index) {
      const words = this.words.slice(); // Create a shallow copy of the words array
      words.splice(index, 1); // Remove the word at the specified index
      this.inputText = words.join(" "); // Update the inputText with the remaining words
    },
    handleFocus() {
      const textarea = this.$refs.textarea;
      if (textarea) {
        textarea.style.color = 'transparent'; // Make text transparent but keep the caret visible
      }
    }
  },
  mounted() {
    const textarea = this.$refs.textarea;
    if (textarea) {
      textarea.style.color = 'transparent'; // Ensure text is hidden at the beginning
    }
  },
  components: {
    ButtonComponent,
  },
};
</script>

<style scoped>
.word-wrapper {
  position: relative;
  display: inline-block;
}

.unknown-word {
  color: #ff6666;
}

.remove-word-icon {
  font-size: 0.75rem;
  line-height: 0.75rem;
  position: absolute;
  /* top: 0;
  right:0.1; */
  cursor: pointer;
  color: rgb(0, 0, 0);
}

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
  padding: 2px;
  background: none;
  cursor: pointer;
  font-size: 1rem;
}
</style>
