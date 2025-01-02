<template>
  <button
    @click="triggerAction"
    class="submit-btn relative mt-14 bg-white text-black transition-all duration-300 ease-in-out group shadow-lg"
  >
    <i
      :class="icon"
      class="absolute left-8 text-6xl transition-transform duration-500 group-hover:translate-x-3"
    ></i>
    <span
      class="translate-text opacity-0 whitespace-nowrap group-hover:opacity-100 group-hover:ml-6 transition-all duration-500 ease-in-out text-lg font-normal"
    >
      {{ buttonText }}
    </span>
  </button>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      default: "fas fa-arrow-right",
    },
    buttonText: {
      type: String,
      default: "Translate",
    },
  },
  methods: {
    triggerAction() {
      this.$emit("action");
    },
  },
};
</script>

<style scoped>
.submit-btn {
  padding: 0.75rem 3rem;
  display: flex;
  align-items: center;
  border-radius: 1.075rem;
  padding-left: 1.5rem;
  padding-right: 1.5rem;
  justify-content: flex-start;
  transition:
    transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
    box-shadow 0.4s;
}

.arrow-icon {
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.submit-btn:hover .arrow-icon {
  transform: translateX(6px);
}

.translate-text {
  transition:
    opacity 0.5s ease,
    margin-left 0.6s ease;
}

.submit-btn:hover .translate-text {
  opacity: 1;
  margin-left: 5rem;
}
</style>
