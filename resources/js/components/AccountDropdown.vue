<template>
    <div class="relative">
        <button @click="isOpen = !isOpen" :class="['block text-white', isOpen ? 'text-gray-500' : '']">
            <font-awesome-icon icon="cogs" size="lg"></font-awesome-icon>
        </button>
        <button v-if="isOpen" @click="isOpen = false" tabindex="-1" class="fixed w-full h-full inset-0 cursor-default"></button>
        <div v-if="isOpen" class="absolute z-50 right-0 bg-white mt-2 p-2 rounded-md shadow-lg">
            <a
                href="/logout"
                class="block flex items-center px-2 py-3 whitespace-no-wrap text-red-700"
                @click.prevent="$refs.logoutForm.submit()"
            >
                <font-awesome-icon class="mr-2" icon="sign-out-alt"></font-awesome-icon>
				{{ $lang.get('account.logout') }}
            </a>
            <a href="/account/password" class="block flex items-center px-2 py-3 whitespace-no-wrap text-gray-700">
                <font-awesome-icon class="mr-2" icon="key"></font-awesome-icon>
				{{ $lang.get('account.change-password') }}
            </a>
        </div>
        <form ref="logoutForm" action="/logout" method="POST" class="hidden">
            <input type="hidden" name="_token" :value="csrfToken">
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            isOpen: false,
            csrfToken: null
        }
    },
    mounted() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').content
    },
}
</script>
