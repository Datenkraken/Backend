<template>
    <div class="relative mx-2">
        <button @click="isOpen = !isOpen" :class="['block text-white', isOpen ? 'text-gray-500' : '']">
            <gb-flag :code="curLocaleIcon"></gb-flag>
        </button>
        <button v-if="isOpen" @click="isOpen = false" tabindex="-1" class="fixed w-full h-full inset-0 cursor-default"></button>
        <div v-if="isOpen" class="absolute z-50 right-0 bg-white mt-2 p-2 rounded-md shadow-lg">
            <a v-for="locale in unactiveLocales" :href="'/lang/' + locale.language" class="block w-8">
                <gb-flag :code="locale.icon"></gb-flag>
            </a>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            isOpen: false,
            csrfToken: null,
            locales: [
                {
                    language: 'en',
                    icon: 'gb'
                },
                {
                    language: 'de',
                    icon: 'de'
                },
            ],
        }
    },
    mounted() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').content
    },
    computed: {
        unactiveLocales() {
            return this.locales.filter(item => item.language != this.$lang.getLocale());
        },
        curLocaleIcon() {
            return this.locales.find( item => item.language == this.$lang.getLocale()).icon;
        }
    },
	methods: {
		/**
		 * return the url to change the locale
		 */
		getLocaleUrl() {

			var newLocale = this.$lang.getLocale() == 'en' ? 'de' : 'en';

			return '/lang/' + newLocale + '/'
		},
	},
}
</script>
