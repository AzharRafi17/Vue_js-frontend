<template>
  <div class="translationDemo flex justify-center bg-color gray items-center w-full h-screen">
   <div class="w-full h-1/4">
     <div class="flex flex-col justify-end items-center relative bottom-[120px]">
      <!-- Main Heading -->
       <p class="text-[44px] max-w-[411px] leading-[50px] text-center mb-[30px] font-light">
         Let's translate the world's food</p>
        <div class="translateInputContainer h-[132px] w-[45%] bg-white ">
         <div
            class="inputBox h-[75%] w-full px-4 pt-4 text-center text-[22px] font-light bg-transparent outline-none resize-none border border-gray-300 overflow-hidden whitespace-pre-wrap break-words"
            contenteditable="true"
            ref="editableDiv"
            @input="handleInput"
            @blur="sanitizeInput"
            @keydown="preventEnterKey"
            @click="handleClick"
            :style="{ color: 'grey' }"
         ></div>
            <div class="h-[25%] flex justify-between items-center px-4">
              <div class="text-sm text-[11px]">
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

       <div class="keyboard-and-button-container flex flex-col items-center mt-2 w-[45%]">
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
     inputText: "", // Main input text for translation
     showKeyboard: false, // Control for showing the special character keyboard
     specialChars: [
       "ä","â", "á", "à", "ã", "å", "ë", "ê", "é", "è",
       "ï", "î", "í", "ì", "ö", "ô", "ó", "ò", "õ", "ü",
       "û", "ú", "ù", "ç", "ñ","æ", "œ", "ÿ"
     ],
     unknownWords: ["asd", "suck", "tuscany"], // List of unknown words
   };
 },
 methods: {
   toggleKeyboard() {
     this.showKeyboard = !this.showKeyboard;
   },
   
   // Handle valid input and maintain caret position
   handleInput() {
     const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
     const editableDiv = this.$refs.editableDiv;
     const selection = window.getSelection();
     const range = selection.getRangeAt(0);

     let caretOffset = 0;
     let node = range.startContainer;

     // Calculate caret position
     if (node.nodeType === Node.TEXT_NODE) {
       caretOffset = range.startOffset;
     } else if (node.nodeType === Node.ELEMENT_NODE) {
       caretOffset = Array.from(node.childNodes).slice(0, range.startOffset).reduce((sum, child) => {
         return sum + (child.textContent || "").length;
       }, 0);
     }

     // Get the raw input text and filter only valid characters
     let rawInput = editableDiv.innerText;
     let sanitizedInput = rawInput
       .toLowerCase()
       .split("")
       .filter((char) => allowedChars.test(char))
       .join("");

     // If the input has changed, update the content and caret
     if (sanitizedInput !== this.inputText) {
       this.inputText = sanitizedInput.substring(0, 160); // Limit to 160 chars
       editableDiv.innerText = this.inputText; // Update the editableDiv with valid input

       // Restore caret position after sanitizing
       this.setCaretPosition(editableDiv, caretOffset);
     }

     // Highlight unknown words
     this.highlightUnknownWords();
   },

     // Prevent enter key from creating new lines
     preventEnterKey(event) {
       if (event.key === "Enter") {
       event.preventDefault();
     }
   },

   // Set caret position in the editable div
   setCaretPosition(node, position) {
     const range = document.createRange();
     const selection = window.getSelection();

     let charCount = 0;
     const traverseNode = (node) => {
       if (node.nodeType === Node.TEXT_NODE) {
         let nextCharCount = node.length;
         if (charCount + nextCharCount >= position) {
           range.setStart(node, position - charCount);
           range.setEnd(node, position - charCount);
           selection.removeAllRanges();
           selection.addRange(range);
           return true;
         }
         charCount += nextCharCount;
       } else {
         for (let child of node.childNodes) {
           if (traverseNode(child)) return true;
         }
       }
       return false;
     };

     traverseNode(node);
   },

   // Sanitize input by removing invalid characters
   sanitizeInput() {
     const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
     this.inputText = this.inputText
       .toLowerCase()
       .split("")
       .filter((char) => allowedChars.test(char))
       .join("")
       .substring(0, 160);

     this.$refs.editableDiv.innerText = this.inputText;
     this.highlightUnknownWords();
   },

   addSpecialCharacter(char) {
     const editableDiv = this.$refs.editableDiv;

     if (!editableDiv || this.inputText.length >= 160) return;

    
     const selection = window.getSelection();
     const range = selection.getRangeAt(0);

     range.deleteContents();
     const textNode = document.createTextNode(char);
     range.insertNode(textNode);

     range.setStartAfter(textNode);
     range.setEndAfter(textNode);
     selection.removeAllRanges();
     selection.addRange(range);

   
     this.inputText = editableDiv.innerText.substring(0, 160);
     this.highlightUnknownWords();
   },

  highlightUnknownWords() {
  const words = this.inputText.split(" ");
  const highlightedText = words
    .map((word, index) => {
      if (this.unknownWords.includes(word)) {
        return `<span class="word-wrapper relative inline-block mr-1 text-red-500 hover:text-black group">${word}<span class="remove-word-icon absolute top-[-6px] right-[-6px] text-xs text-red-500 cursor-pointer opacity-0 group-hover:opacity-100 group-hover:text-black transition-all duration-200" data-word-index="${index}">&#10005;</span></span>`;
      }
      return word;
    })
    .join(" ");

  this.$refs.editableDiv.innerHTML = highlightedText;

  // Add event listeners for the cross icons
  const icons = this.$refs.editableDiv.querySelectorAll(".remove-word-icon");
  icons.forEach((icon) => {
    icon.addEventListener("click", (event) => {
      const wordIndex = event.target.getAttribute("data-word-index");
      this.removeWord(wordIndex);
    });
  });

  // Restore caret position
  const selection = window.getSelection();
  const range = document.createRange();
  range.selectNodeContents(this.$refs.editableDiv);
  range.collapse(false);
  selection.removeAllRanges();
  selection.addRange(range);
},
   
   removeWord(index) {
      const words = this.inputText.split(' ');
      words.splice(index, 1);
      this.inputText = words.join(' ');
      this.highlightUnknownWords(); 
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
   font-family: 'Roboto Slab';
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
