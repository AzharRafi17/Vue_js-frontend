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
                    class="inputBox w-full h-auto p-4 font-light text-center outline-none border-none text-4xl font-light bg-transparent whitespace-pre-wrap break-words resize-none overflow-hidden"
                    v-model="inputText"
                    maxlength="160"
                    ref="textarea"
                    @input="handleInput"
                    @blur="sanitizeInput"
                ></textarea>
                <div
                    class="absolute inset-0 p-4 font-light text-center text-4xl bg-transparent pointer-events-none whitespace-pre-wrap break-words"
                    style="color: inherit;"
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
            inputText: "",
            showKeyboard: false,
            specialChars: ["ä", "â", "á", "à", "ã", "å", "ë", "ê", "é", "è", "ï", "î", "í", "ì", "ö", "ô", "ó", "ò", "õ", "ü", "û", "ú", "ù", "ç", "ñ", "ø", "ß", "æ", "œ", "ÿ"],
            unknownWords: ["asd", "suck", "tuscany"], // List of unknown words
        };
        },
        computed: {
        highlightedText() {
            // Highlight unknown words in red
            return this.inputText
            .split(" ")
            .map((word) => {
                if (this.unknownWords.includes(word.toLowerCase())) {
                return `<span style="color: #ff6666;">${word}</span>`;
                

                }
                return word;
            })
            .join(" ");
        },
        },
        methods: {
        toggleKeyboard() {
            this.showKeyboard = !this.showKeyboard;
        },
        handleInput(event) {
            const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
            this.inputText = this.inputText
            .toLowerCase() // Convert to lowercase
            .split("")
            .filter((char) => allowedChars.test(char)) // Allow only specific characters
            .join("")
            .substring(0, 160); // Enforce length limit
            this.autoResize(event);
        },
        sanitizeInput() {
            // Additional cleanup for allowed characters
            const allowedChars = /^[a-z0-9 ,.:\-äâáàãåëêéèïîíìöôóòõüûúùçñøßæœÿ]*$/;
            this.inputText = this.inputText
            .toLowerCase()
            .split("")
            .filter((char) => allowedChars.test(char))
            .join("")
            .substring(0, 160);
        },
        addSpecialCharacter(char) {
            if (this.inputText.length >= 160) return; // Enforce length limit
            const textarea = this.$refs.textarea;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            // Insert special character at caret or selection
            this.inputText = this.inputText.substring(0, start) + char + this.inputText.substring(end);
            this.$nextTick(() => {
            textarea.setSelectionRange(start + 1, start + 1); // Move caret position
            textarea.focus();
            });
        },
        handleSubmit() {
      if (this.inputText.trim() === "") {
        alert("Please enter some text before submitting.");
        return;
      }

      // Check if there are unknown words in the input
      const hasUnknownWords = this.inputText
        .split(" ")
        .some((word) => this.unknownWords.includes(word.toLowerCase()));

      // Emit the input text and unknown words status to the parent
      this.$emit("submit-text", { inputText: this.inputText, hasUnknownWords });
    },
        resetInput() {
            this.inputText = "";
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
    