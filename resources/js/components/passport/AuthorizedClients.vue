<template>
    <div>
        <div class="p-2">
            <!-- No Tokens Notice -->
            <p
                class="mb-0 block text-white-700 text-sm font-bold"
                v-if="tokens.length === 0"
            >
                {{ $lang.get('oauth.no-autorized-clients') }}
            </p>

            <!-- Authorized Clients -->
            <table
                class="mb-0 w-full"
                v-if="tokens.length > 0"
            >
                <thead class="border-b">
                    <tr class="text-xl">
                        <th>{{ $lang.get('oauth.autorized-client-name') }}</th>
                        <th>{{ $lang.get('oauth.autorized-client-revoke') }}</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        class="my-1"
                        v-for="token in tokens"
                        :key="token.id"
                    >
                        <!-- Client Name -->
                        <td style="px-2 py-3">
                            {{ token.autorized-client.name }}
                        </td>

                        <!-- Scopes -->
                        <td style="px-2 py-3">
                            <span v-if="token.scopes.length > 0">
                                {{ token.scopes.join(', ') }}
                            </span>
                        </td>

                        <!-- Revoke Button -->
                        <td class="text-right p-1 py-3">
                            <dk-button
                                class="mx-1"
                                hover-color="red-600"
                                @click="revoke(token)"
                            >
                                {{ $lang.get('oauth.autorized-client-revoke') }}
                            </dk-button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                tokens: []
            };
        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {
                this.getTokens();
            },

            /**
             * Get all of the authorized tokens for the user.
             */
            getTokens() {
                axios.get('/oauth/tokens')
                        .then(response => {
                            this.tokens = response.data;
                        });
            },

            /**
             * Revoke the given token.
             */
            revoke(token) {
                axios.delete('/oauth/tokens/' + token.id)
                        .then(response => {
                            this.getTokens();
                        });
            }
        }
    }
</script>
