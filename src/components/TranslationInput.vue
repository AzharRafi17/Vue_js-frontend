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
            <!-- Editable Input -->
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
  
            <!-- Icons and Character Counter -->
            <div class="flex justify-between items-center absolute bottom-0 left-4 right-4">
              <!-- Character Counter -->
              <div class="text-sm">
                <span :class="inputText.length === 160 ? 'text-red-500' : 'text-gray-400'">
                  {{ inputText.length }}/160
                </span>
              </div>
  
              <!-- Feedback and Keyboard Buttons -->
              <div class="flex justify-center items-center">
                <button class="icon-button " @click="handleFeedback">
                  <i class="fas fa-comment-alt"></i>
                </button>
                <button class="icon-button" @click="toggleKeyboard">
                  <i class="fas fa-keyboard"></i>
                </button>
              </div>
            </div>
          </div>
  
          <!-- Special Characters Keyboard -->
          <transition name="keyboard-slide">
            <div
              v-if="showKeyboard"
              class="flex flex-wrap w-3/5 bg-white text-gray-400 justify-center gap-1 mt-2 p-2 rounded-xl overflow-y-auto max-h-[188px]"
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
          </transition>
  
          <!-- Submit Button -->
          <button
            @click="handleSubmit"
            class="submit-btn relative mt-14 bg-white text-black  transition-all duration-300 ease-in-out group shadow-lg"
          >
            <i
              class="fas fa-arrow-right absolute left-8 text-6xl transition-transform duration-500 group-hover:translate-x-3"
            ></i>
            <span
              class="translate-text opacity-0 whitespace-nowrap group-hover:opacity-100 group-hover:ml-6 transition-all duration-500 ease-in-out text-lg font-normal "
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
            textarea.style.height = "auto"; // Reset height to calculate actual scroll height
            textarea.style.height = textarea.scrollHeight + "px"; // Set height based on content
        },
        },
    };
    </script>
    
    <style scoped>
    .translationDemo {
        background: #f7f6f6;
    }
    
    .translateInputContainer {
        position: relative;
    }
    
    .textarea-wrapper {
        padding-bottom: 40px; /* Leave space for the icons and counter */
    }
    
    /* Text inside the translation input will be gray */
    .inputBox {
        padding: 10px;
        box-sizing: border-box;
        min-height: 60px;
        max-height: 200px; /* Set a max height to prevent it from growing too large */
        overflow-y: auto;
        color: #b0b2b5fb; /* Gray text for the input */
    }
    
    /* Icon button styling */
    .icon-button {
        background: none;
        cursor: pointer;
        font-size: 1rem; /* Smaller icon size */
        width: 1.5rem; /* Circle size */
        height: 2.5rem;
        /* display: flex; */
        /* align-items: center; */
        /* justify-content: center; */
    }
    
    .icon-button:hover {
        color: black;
    }
    
    /* Keyboard Slide Animation - smoother with longer duration */
    .keyboard-slide-enter-active,
    .keyboard-slide-leave-active {
        transition: transform 0.3s , opacity 0.3s;
    }
    .keyboard-slide-enter {
        transform: translateY(0px);
        opacity: 0;
    }
    .keyboard-slide-leave-to {
        transform: translateY(0px);
        opacity: 0;
    }
    
    /* Special Characters Keyboard */
    .special-character {
        color: #6b7280; /* Gray color for special characters */
    }

    .submit-btn {
    padding: 0.75rem 3rem;
    display: flex;
    align-items: center;
    border-radius: 1.075rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    justify-content: flex-start;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s;
}

/* .submit-btn:hover {
  transform: translateY(-3px);
  
} */

.arrow-icon {
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}
.submit-btn:hover .arrow-icon {
  transform: translateX(6px);
}

.translate-text {
  transition: opacity 0.5s ease, margin-left 0.6s ease;
}

.submit-btn:hover .translate-text {
  opacity: 1;
  margin-left: 5rem;
}
    </style>
    