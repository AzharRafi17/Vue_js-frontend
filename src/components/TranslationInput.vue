<template>
    <div class="translationDemo flex justify-center bg-color gray items-center w-full h-screen">
      <!-- Main Translation Container -->
      <div class="w-full h-1/4">
        <!-- Title -->
        <div class="flex flex-col justify-end items-center relative bottom-[120px]">
          <p class="text-6xl w-3/5 text-center mb-12 font-light">
            Let's translate the world's food
          </p>
  
          <!-- Translate Input Container -->
          <div class="translateInputContainer relative h-40 w-3/5 bg-white rounded-xl">
            <!-- Editable Input -->
            <textarea
              class="inputBox w-full h-full p-4 text-center text-secondary-content outline-none border-none text-4xl font-light bg-transparent whitespace-pre-wrap break-words resize-none overflow-hidden"
              v-model="inputText"
              maxlength="160"
              ref="textarea"
              @input="handleInput"
              @blur="sanitizeInput"
              placeholder="Start typing here..."
            ></textarea>
  
            <!-- Icons and Character Counter -->
            <div class="flex justify-between items-center absolute bottom-2 left-4 right-4">
              <!-- Character Counter -->
              <div class="text-sm">
                <span :class="inputText.length === 160 ? 'text-red-500' : 'text-gray-500'">
                  {{ inputText.length }}
                </span>
                /160
              </div>
  
              <!-- Feedback and Keyboard Buttons -->
              <div class="flex justify-center items-center gap-3">
                <button class="icon-button" @click="handleFeedback">
                  <span class="material-icons">feedback</span>
                </button>
                <button class="icon-button" @click="toggleKeyboard">
                  <span class="material-icons">keyboard</span>
                </button>
              </div>
            </div>
          </div>
  
          <!-- Special Characters Keyboard -->
          <transition name="keyboard-slide">
            <div
              v-if="showKeyboard"
              class="flex flex-wrap w-3/5 bg-white justify-center gap-1 mt-2 p-2 rounded-xl overflow-y-auto transition-all duration-300 max-h-[188px] opacity-100 pointer-events-auto"
            >
              <button
                v-for="(char, index) in specialChars"
                :key="index"
                class="p-2 w-8 h-8 flex justify-center items-center text-secondary-content cursor-pointer border border-gray-300 rounded hover:bg-gray-100 hover:shadow-thin-n-strict"
                @click="addSpecialCharacter(char)"
              >
                {{ char }}
              </button>
            </div>
          </transition>
  
          <!-- Submit button -->
          <button
            @click="handleSubmit"
            class="submit-btn relative mt-8 bg-white text-black rounded-full transition-all duration-300 ease-in-out group"
          >
            <span
              class="arrow-icon material-icons absolute left-8 text-4xl transition-transform duration-300 group-hover:-translate-x-6"
            >
              arrow_forward
            </span>
            <span
              class="translate-text opacity-0 whitespace-nowrap group-hover:opacity-100 group-hover:ml-10 transition-all duration-300 text-lg font-semibold"
            >
              translate
            </span>
          </button>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        inputText: "",
        showKeyboard: false,
        specialChars: [
          "ä", "â", "á", "à", "ã", "å", "ë", "ê", "é", "è", "ï", "î", "í", "ì",
          "ö", "ô", "ó", "ò", "õ", "ü", "û", "ú", "ù", "ç", "ñ", "ø", "ß", "æ",
          "œ", "ÿ",
        ],
      };
    },
    methods: {
      toggleKeyboard() {
        this.showKeyboard = !this.showKeyboard;
      },
      handleFeedback() {
        alert("Feedback button clicked!");
      },
      handleInput(event) {
        this.filterInput();
        this.autoResize(event);
      },
      filterInput() {
        const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ']*$/;
        this.inputText = this.inputText
          .toLowerCase()
          .split("")
          .filter((char) => allowedChars.test(char))
          .join("");
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
        if (this.inputText.trim() === "") {
          alert("Please enter some text before submitting.");
        } else {
          alert("Translation submitted!");
          this.inputText = "";
        }
      },
      autoResize(event) {
        const textarea = event.target;
        textarea.style.height = "auto";
        textarea.style.height = textarea.scrollHeight + "px";
      },
    },
  };
  </script>
  
  <style scoped>
  .translationDemo {
    background: #f9f9f9;
  }
  
  .translateInputContainer {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    position: relative;
  }
  
  .inputBox {
    padding: 10px;
    box-sizing: border-box;
    height: calc(100% - 30px); /* Leaves space for icons */
    overflow-y: hidden;
  }
  
  .flex {
    display: flex;
  }
  
  .icon-button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.5rem;
    color: gray;
  }
  
  .icon-button:hover {
    color: black;
  }
  
  /* Keyboard Slide Animation */
  .keyboard-slide-enter-active,
  .keyboard-slide-leave-active {
    transition: transform 0.3s ease, opacity 0.3s ease;
  }
  .keyboard-slide-enter {
    transform: translateY(20px);
    opacity: 0;
  }
  .keyboard-slide-leave-to {
    transform: translateY(20px);
    opacity: 0;
  }
  
  /* Submit Button */
  .submit-btn {
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }
  
  .submit-btn:hover {
    width: 13rem;
  }
  
  .arrow-icon {
    transition: transform 0.4s ease;
  }
  
  .submit-btn:hover .arrow-icon {
    transform: translateX(-8px);
  }
  
  .translate-text {
    opacity: 0;
    transition: opacity 0.3s ease, margin-left 0.3s ease;
  }
  
  .submit-btn:hover .translate-text {
    opacity: 1;
    margin-left: 1rem;
  }
  </style>
  