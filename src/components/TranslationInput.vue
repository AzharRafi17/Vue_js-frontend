<template>
  <div
      class="translationDemo flex justify-center bg-color gray items-center w-full h-screen"
  >
      <div class="w-full h-1/4">
          <div
              class="flex flex-col justify-end items-center relative bottom-[120px]"
          >
              <!-- Main Heading -->
              <p
              class="text-[44px] max-w-[411px] leading-[50px] text-center mb-[30px]"
              >
                  Let's translate the world's food
              </p>
              <div class="translateInputContainer h-[132px] w-[45%] bg-white">
                  <div
                      class="inputBox h-[75%] w-full px-4 pt-4 text-center text-[22px] font-light bg-transparent outline-none resize-none border border-gray-300 overflow-hidden whitespace-pre-wrap break-words"
                      contenteditable="true"
                      ref="editableDiv"
                      @input="handleInput"
                      @keydown="preventEnterKey"
                      :style="{ color: 'grey' }"
                  ></div>
                  <div class="h-[25%] flex justify-between items-center px-4">
                      <div class="text-sm text-[11px]">
                          <span
                              :class="inputText.length === 160 ? 'text-red-500' : 'text-black-400'"  ref="wordCountElement"
                          >
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

              <div
                  class="keyboard-and-button-container flex flex-col items-center mt-2 w-[45%]"
              >
                  <div
                      class="keyboard-container flex flex-wrap w-full bg-white text-gray-400 justify-center gap-1 rounded-xl overflow-hidden transition-all duration-300"
                      :class="{ 'max-h-0 p-0': !showKeyboard, 'max-h-[188px] p-1': showKeyboard }"
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
       inputText: "",
       showKeyboard: false,
       specialChars: [
         "ä","â", "á", "à", "ã", "å", "ë", "ê", "é", "è",
         "ï", "î", "í", "ì", "ö", "ô", "ó", "ò", "õ", "ü",
         "û", "ú", "ù", "ç", "ñ","ø","ß","æ", "œ", "ÿ"
       ],
       unknownWords: ["asd", "suck", "tuscany"],
         maxLength: 160
     };
   },
    methods: {
      toggleKeyboard() {
       this.showKeyboard = !this.showKeyboard;
      },

      handleInput() { 
        const editableDiv = this.$refs.editableDiv;
        let rawInput = editableDiv.textContent;

        if (rawInput.length > this.maxLength) {
          editableDiv.textContent = rawInput.substring(0, this.maxLength);  
          this.setCaretPosition(editableDiv, this.maxLength);  
        }

        let {
          filteredText,
          caretPosition
        } = this.filterText(editableDiv.textContent.toLowerCase(), this.getCaretPosition(editableDiv));

        
        if (filteredText.length > this.maxLength) {
          editableDiv.innerText = filteredText.substring(0, this.maxLength);
          this.setCaretPosition(editableDiv, caretPosition);
        }
       

        this.updateInputText();  
        this.highlightUnknownWords(filteredText);
      },

      filterText(input, caretPosition) {
        const allowedCharacters = /[a-z0-9 ,.,]/g;
        let filteredText = '';
        for (let i = 0; i < input.length; i++) {
          const char = input[i];
          if (char.match(allowedCharacters) || this.specialChars.includes(char)) {
            filteredText += char.toLowerCase();
          } else {
            if (i < caretPosition) {
              caretPosition -= 1;
            }
          }
        }
        return {filteredText, caretPosition};
      },

       preventEnterKey(event) {
         if (event.key === "Enter") {
         event.preventDefault();
       }
     },

     setCaretPosition(element, position) {
       const range = document.createRange();
       const selection = window.getSelection();

       let charCount = 0;
       let found = false
       const traverseNode = (node) => {
         if (node.nodeType === Node.TEXT_NODE) {
           let nextCharCount = node.length;
           if (charCount + nextCharCount >= position) {
             range.setStart(node, position - charCount);
             found = true
             return;
           }
           charCount += nextCharCount;
         } else {
           for (let i = 0; i < node.childNodes.length; i++) {
             traverseNode(node.childNodes[i]);
           }
         }
       };

       traverseNode(element);

       if (found) {
         range.collapse(true);
         selection.removeAllRanges();
         selection.addRange(range)
       }
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
          word = word.replace('\n', ' ');

          isWhiteSpace = !word.trim();

          if (isWhiteSpace) {
            stack.push(word);
            continue;
          }

          const cleanedWord = word.trim().replace(/[\s]+/g, "");
         
          const unknownWord = this.unknownWords.find((w) => w.toLowerCase() === cleanedWord);
               if (!unknownWord) {
            stack.push(word);
          } else {
            const unknownWordWrapper = `<span class="word-wrapper relative inline-block text-red-500 hover:text-black group">${word}<span contenteditable="false" class="remove-word-icon absolute top-[10%] right-[-10px] transform translate-y-[-50%] text-xs text-red-500 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity duration-300" data-word-index="${index}"><img src="cross.png" alt="Close Icon" width="15px" /></span></span>`;
            stack.push(unknownWordWrapper);
          }
        }

        editableDiv.innerHTML = stack.join("");
        this.setCaretPosition(editableDiv, caretPosition);

        const icons = this.$refs.editableDiv.querySelectorAll(".remove-word-icon");
        icons.forEach((icon) => {
          icon.addEventListener("click", (event) => {
            this.removeWord(event.target);
          });
        });
      },

  moveCaretToEnd(element) {
    const range = document.createRange();
    const selection = window.getSelection();
    range.selectNodeContents(element);
    range.collapse(false);
    selection.removeAllRanges();
    selection.addRange(range);

  },
  getCaretPosition(element) {
    const selection = window.getSelection();
    const range = selection.getRangeAt(0)
    const clonedRange = range.cloneRange()
    clonedRange.selectNodeContents(element)
    clonedRange.setEnd(range.endContainer, range.endOffset)

    return clonedRange.toString().length
  },
  updateInputText() {
    this.inputText = this.$refs.editableDiv.innerText;
    
  },
     removeWord(icon) {
        icon.parentNode.parentNode.remove()
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

  .icon-button {
      padding: 4px;
      background: none;
      cursor: pointer;
      font-size: 1rem;
  }
</style>
