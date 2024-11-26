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
              <textarea
                class="inputBox w-full h-auto p-4 font-light text-center text-gray-400 outline-none border-none text-4xl font-light bg-transparent whitespace-pre-wrap break-words resize-none overflow-hidden"
                v-model="inputText"
                maxlength="160"
                ref="textarea"
                @input="handleInput"
                @blur="sanitizeInput"
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
  
          <!-- Special Characters Keyboard with transition classes -->
          <transition
            name="keyboard"
            mode="out-in"
          >
            <div v-if="showKeyboard" class="keyboard-container flex flex-wrap w-3/5 bg-white text-gray-400 justify-center gap-1 mt-2 p-2 rounded-xl overflow-y-auto max-h-[188px]">
              <button
                v-for="(char, index) in specialChars"
                :key="index"
                class="p-4 w-10 h-8 flex justify-center items-center text-gray-400 cursor-pointer border border-gray-300 rounded hover:bg-gray-100"
                @click="addSpecialCharacter(char)"
              >
                {{ char }}
              </button>
            </div>
          </transition>
          
          <!-- Submit Button -->
          <ButtonComponent @action="handleSubmit" :icon="'fas fa-arrow-right'" buttonText="translate" />
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
            specialChars: ["ä", "â", "á", "à", "ã", "å", "ë", "ê", "é", "è", "ï", "î", "í",
             "ì", "ö", "ô", "ó", "ò", "õ", "ü", "û", "ú", "ù", "ç", "ñ", "ø", "ß", "æ", "œ", "ÿ"],
        };
        },
        methods: {
        toggleKeyboard() {
            this.showKeyboard = !this.showKeyboard;
        },
        handleInput(event) {
            this.filterInput();
            this.autoResize(event);  
        },
        filterInput() {
            const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ']*$/;
            this.inputText = this.inputText.toLowerCase().split("").filter((char) => allowedChars.test(char)).join("");
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
        handleSubmit() {
  if (this.inputText.trim() === "") {
    alert("Please enter some text before submitting.");
  } else {
    document.body.style.overflow = 'auto';
    
    this.$emit("submit-text", this.inputText);
    const resultsSection = document.getElementById("translateResultsInfo");
    if (resultsSection) {
      resultsSection.scrollIntoView({ behavior: "smooth" });  
    }
  }
},

        autoResize(event) {
            const textarea = event.target;
            textarea.style.height = "auto"; 
            textarea.style.height = textarea.scrollHeight + "px"; 
        },
        },
        components: {
            ButtonComponent,
        },
    };
    </script>
 <style scoped>

 .v-enter-active, .v-leave-active {
   transition: opacity 0.2s ease, transform 0.2s ease;
 }
 
 .v-enter-from, .v-leave-to {
   opacity: 0;
   transform: translateY(-20px);
 }
 
 .v-leave-active {
   position: absolute; 
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
 
 .keyboard-container {
   background-color: #fff;
   transition: opacity 0.2s ease, transform 0.2s ease;
 }
 </style>
 