<style scoped>
.modal-mask {
    position: fixed;
    overflow-y:scroll;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
    transition: opacity .3s ease;
}

.modal-container {
  transition: all .3s ease;
}

/*
 * Transition "modal", auto applied to transition objects called "modal".
 */
.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  transform: scale(1.1);
}
</style>

<template>
  <transition name="modal">
    <div
      class="modal-mask flex p-1"
      @click="close"
      v-show="show"
    >
      <div
        class="modal-container container mx-auto my-auto"
        @click.stop
      >
        <slot />
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    show: Boolean,
  },

  mounted: function () {
    document.addEventListener('keydown', (e) => {
      // Close modal, if escape key is pressed
      if (this.show && e.keyCode === 27) {
        this.close();
      }
    });
  },

  methods: {

    /**
     * Close the modal.
     */
    close() {
      this.$emit('close');
    },
  },
};
</script>

