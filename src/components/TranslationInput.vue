<template>
  <div
    class="translationDemo flex justify-center bg-gray-85 items-center w-full h-screen"
  >
    <div class="w-full h-1/4">
      <div
        class="flex flex-col justify-end items-center relative bottom-[120px]"
      >
        <!-- Main Heading -->
        <p class="text-4xl max-w-[411px] leading-[50px] text-center mb-7">
          Let's translate the world's food
        </p>
        <div
          class="translateInputContainer h-[132px] w-[45%] bg-white shadow-md rounded-lg"
        >
          <div
            class="inputBox h-[75%] w-full px-4 pt-4 text-center text-xl font-light bg-transparent outline-none resize-none border border-gray-300 overflow-hidden whitespace-pre-wrap break-words"
            contenteditable="true"
            ref="editableDiv"
            @input="handleInput"
            @keydown="preventEnterKey"
            :style="{ color: 'grey' }"
          ></div>
          <div class="h-[25%] flex justify-between items-center px-4">
            <div class="text-xs">
              <span
                :class="
                  inputText.length === 160 ? 'text-red-500' : 'text-gray-400'
                "
                ref="wordCountElement"
              >
                {{ inputText.length }}/160
              </span>
            </div>
            <div class="flex justify-center items-center space-x-2">
              <button class="icon-button" @click="handleFeedback">
                <i class="fas fa-comment-alt"></i>
              </button>
              <button class="icon-button" @click="toggleKeyboard">
                <i class="fas fa-keyboard"></i>
              </button>
            </div>
          </div>
        </div>

        <div
          class="keyboard-and-button-container flex flex-col items-center mt-2 w-[45%]"
        >
          <div
            class="keyboard-container flex flex-wrap w-full bg-white text-gray-400 justify-center gap-1 rounded-xl overflow-hidden transition-all duration-300"
            :class="{
              'max-h-0 p-0': !showKeyboard,
              'max-h-[188px] p-1': showKeyboard,
            }"
          >
            <button
              v-for="(char, index) in specialChars"
              :key="index"
              class="p-2 w-8 h-8 flex justify-center items-center text-gray-400 cursor-pointer border border-gray-300 rounded hover:bg-gray-100"
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

    <!-- Suggestion Tooltip -->
    <div
      v-if="hoveredWord && suggestions[hoveredWord.toLowerCase()]"
      class="tooltip-class absolute bg-transparent border-transparent rounded-xl p-2 flex flex-col gap-2 z-50 font-roboto-slab"
      :style="tooltipStyle"
      @mouseenter="tooltipMouseEnter"
      @mouseleave="tooltipMouseLeave"
    >
      <div
        v-for="(suggestion, index) in suggestions[hoveredWord.toLowerCase()]"
        :key="index"
        class="px-3 py-1 text-sm text-white bg-black hover:text-gray-500 rounded-2xl cursor-pointer transition-all duration-300"
        @click="replaceWord(suggestion)"
      >
        {{ suggestion }}
      </div>
    </div>
  </div>
</template>

<script>
import ButtonComponent from "./ButtonComponent.vue";
export default {
  data() {
    return {
      inputText: "",
      showKeyboard: false,
      specialChars: [
        "ä",
        "â",
        "á",
        "à",
        "ã",
        "å",
        "ë",
        "ê",
        "é",
        "è",
        "ï",
        "î",
        "í",
        "ì",
        "ö",
        "ô",
        "ó",
        "ò",
        "õ",
        "ü",
        "û",
        "ú",
        "ù",
        "ç",
        "ñ",
        "æ",
        "œ",
        "ÿ",
      ],
      unknownWords: ["asd", "suck", "tuscany"],
      suggestions: {
        suck: ["durian", "dill", "dates", "duck"],
        tuscany: ["tagliatelie", "tortellini"],
      },
      tooltipHideTimer: null,
      isHoveringTooltip: false,
      hoveredWord: null,
      tooltipStyle: {},
      maxLength: 160,
    };
  },
  methods: {
    toggleKeyboard() {
      this.showKeyboard = !this.showKeyboard;
    },

    handleInput() {
      const editableDiv = this.$refs.editableDiv;
      let rawInput = editableDiv.textContent;

      if (!rawInput.trim()) {
        this.inputText = "";
        editableDiv.textContent = "";
        this.$refs.wordCountElement.textContent = "0/160";
        return;
      }
      if (rawInput.length > this.maxLength) {
        editableDiv.textContent = rawInput.substring(0, this.maxLength);
        this.setCaretPosition(editableDiv, this.maxLength);
      }

      const { filteredText, caretPosition } = this.filterText(
        editableDiv.textContent.toLowerCase(),
        this.getCaretPosition(editableDiv)
      );

      if (filteredText.length > this.maxLength) {
        editableDiv.innerText = filteredText.substring(0, this.maxLength);
        this.setCaretPosition(editableDiv, caretPosition);
      }

      this.updateInputText();
      this.highlightUnknownWords(filteredText);
    },
    sanitizeInput() {
      const editableDiv = this.$refs.editableDiv;
      const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
      this.inputText = this.inputText
        .toLowerCase()
        .split("")
        .filter((char) => allowedChars.test(char))
        .join("")
        .substring(0, 160);
      editableDiv.innerText = this.inputText;
      this.$refs.editableDiv.innerText = this.inputText;
      this.highlightUnknownWords();
    },
    filterText(text, caretPosition) {
      const filteredText = text.replace(/[0-9]/g, ""); // Example: removing numbers
      const removedCharactersCount = text.length - filteredText.length;
      const adjustedCaretPosition = caretPosition - removedCharactersCount;

      return {
        filteredText,
        caretPosition: Math.max(0, adjustedCaretPosition),
      };
    },
    addSpecialCharacter(char) {
      const editableDiv = this.$refs.editableDiv;

      const caretPosition = this.getCaretPosition(editableDiv);

      const text = editableDiv.textContent;
      const newText =
        text.substring(0, caretPosition) + char + text.substring(caretPosition);
      editableDiv.textContent = newText;

      this.setCaretPosition(editableDiv, caretPosition + 1);
      this.updateInputText();
      this.highlightUnknownWords(newText);
    },

    highlightUnknownWords(newText) {
      const editableDiv = this.$refs.editableDiv;
      const caretPosition = this.getCaretPosition(editableDiv);
      const words = newText.match(/\S+|\s+/g) || [];
      const stack = [];

      for (let [index, word] of words.entries()) {
        let isWhiteSpace = word.trim() === "";
        word = word.replace("\n", " ");

        if (isWhiteSpace) {
          stack.push(word);
          continue;
        }

        const cleanedWord = word.trim().replace(/[\s]+/g, "");
        const unknownWord = this.unknownWords.find(
          (w) => w.toLowerCase() === cleanedWord
        );

        if (!unknownWord) {
          stack.push(word);
        } else if (cleanedWord === "asd") {
          const asdWordWrapper = `<span class="word-wrapper relative inline-block text-red-500 hover:text-black group" data-word="${cleanedWord}">${word}<span contenteditable="false" class="remove-word-icon absolute top-[10%] right-[-10px] transform translate-y-[-50%] text-xs text-black cursor-pointer hidden group-hover:block transition-opacity duration-300" data-word-index="${index}"><img src="cross.png" alt="Close Icon" width="15px" /></span></span>`;
          stack.push(asdWordWrapper);
        } else {
          const unknownWordWrapper = `<span class="word-wrapper relative inline-block text-red-500 hover:text-black group" data-word="${cleanedWord}">${word}<span contenteditable="false" class="remove-word-icon absolute top-[10%] right-[-10px] transform translate-y-[-50%] text-xs text-red-500 cursor-pointer hidden group-hover:block transition-opacity duration-300" data-word-index="${index}"><img src="cross.png" alt="Close Icon" width="15px" /></span></span>`;
          stack.push(unknownWordWrapper);
        }
      }

      editableDiv.innerHTML = stack.join("");
      this.setCaretPosition(editableDiv, caretPosition);
      const icons =
        this.$refs.editableDiv.querySelectorAll(".remove-word-icon");
      icons.forEach((icon) => {
        icon.addEventListener("click", (event) => {
          this.removeWord(event.target.closest(".word-wrapper"));
        });
      });
      const wordsToHover = editableDiv.querySelectorAll(".word-wrapper");
      wordsToHover.forEach((wordElement) => {
        wordElement.addEventListener("mouseenter", (event) => {
          const hoveredText = event.target.getAttribute("data-word");

          if (this.suggestions[hoveredText]) {
            this.hoveredWord = hoveredText;
            this.tooltipStyle = {
              top: `${event.target.getBoundingClientRect().bottom}px`,
              left: `${event.target.getBoundingClientRect().left}px`,
            };
          }
        });

        wordElement.addEventListener("mouseleave", () => {
          if (!this.isHoveringTooltip) {
            this.hoveredWord = null; // Hide tooltip when mouse leaves the word
          }
        });
      });
    },
    tooltipMouseEnter() {
      this.isHoveringTooltip = true;
    },

    tooltipMouseLeave() {
      this.isHoveringTooltip = false;
      if (!this.isHoveringTooltip) {
        this.hoveredWord = null; // Hide tooltip after mouse leaves
      }
    },
    replaceWord(suggestion) {
      if (!this.hoveredWord) return;
      const editableDiv = this.$refs.editableDiv;
      const caretPosition = this.getCaretPosition(editableDiv); // Preserve the caret position
      const regex = new RegExp(`\\b${this.hoveredWord}\\b`, "gi");
      this.inputText = this.inputText.replace(regex, () => {
        return `<span class="word-wrapper" data-word="${suggestion}">${suggestion}</span>`;
      });
      editableDiv.innerHTML = this.inputText;
      this.setCaretPosition(editableDiv, caretPosition);
      this.highlightUnknownWords(this.inputText);
      this.updateInputText();
      setTimeout(() => {
        this.hoveredWord = null;
      }, 100);
    },

    setCaretPosition(element, position) {
      const range = document.createRange();
      const selection = window.getSelection();

      let charCount = 0;
      let found = false;
      const traverseNode = (node) => {
        if (node.nodeType === Node.TEXT_NODE) {
          let nextCharCount = node.length;
          if (charCount + nextCharCount >= position) {
            range.setStart(node, position - charCount);
            found = true;
            return;
          }
          charCount += nextCharCount;
        } else {
          for (let i = 0; i < node.childNodes.length; i++) {
            traverseNode(node.childNodes[i]);
            if (found) return;
          }
        }
      };

      traverseNode(element);

      if (found) {
        range.collapse(true);
        selection.removeAllRanges();
        selection.addRange(range);
      }
    },

    getCaretPosition(element) {
      const selection = window.getSelection();
      if (selection.rangeCount === 0) return 0;
      const range = selection.getRangeAt(0);
      const preCaretRange = range.cloneRange();
      preCaretRange.selectNodeContents(element);
      preCaretRange.setEnd(range.endContainer, range.endOffset);

      return preCaretRange.toString().length;
    },

    updateInputText() {
      this.inputText = this.$refs.editableDiv.innerText;
    },

    removeWord(wordElement) {
      const wordToRemove = wordElement.getAttribute("data-word");
      if (!wordToRemove) return;

      const editableDiv = this.$refs.editableDiv;
      const caretPosition = this.getCaretPosition(editableDiv);

      this.inputText = this.inputText.replace(wordToRemove, "").trim();
      editableDiv.innerHTML = this.inputText;

      this.highlightUnknownWords(this.inputText);
      this.setCaretPosition(editableDiv, caretPosition);
      this.updateInputText();

      this.hoveredWord = null; // Clear tooltip
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
  },

  components: {
    ButtonComponent,
  },
};
</script>

<style scoped>
p {
  font-family: "Roboto Slab";
  font-weight: 300;
}

.inputBox {
  font-family: Roboto Slab;
  border: none;
  word-wrap: break-word;
  overflow-wrap: break-word;
}
.inputBox:focus-visible {
  outline: none;
}
.word-wrapper:hover {
  color: black !important;
}
.icon-button {
  padding: 4px;
  background: none;
  cursor: pointer;
  font-size: 1rem;
}
</style>
