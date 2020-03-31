<template>
    <dk-modal :show="show" @close="close()">
        <div class="bg-white rounded text-black text-center p-5">
            <p class="block p-3 text-xl">
                <slot />
            </p>
            <dk-button
                @click="close()"
                class="py-2 px-4 mx-1"
                hover-color="red-500"
                :padding="false"
            >
                {{ $lang.get('general.abort') }}
            </dk-button>
            <dk-button
                @click="confirm()"
                class="py-2 px-4 mx-1"
                :padding="false"
            >
                {{ $lang.get('general.confirm') }}
            </dk-button>
        </div>
    </dk-modal>
</template>

<script lang="js">
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

        /**
         * Send confirm event.
         */
        confirm() {
            this.$emit('confirm');
        },
    },
}
</script>
