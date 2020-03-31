<template>
    <div>
        <div class="p-2">
            <!-- Current Clients -->
            <p
                class="mb-0 block text-white-700 text-sm font-bold"
                v-if="clients.length === 0"
            >
                {{ $lang.get('oauth.no-clients') }}
            </p>

            <table
                class="mb-0 w-full"
                v-if="clients.length > 0"
            >
                <thead class="border-b">
                    <tr class="text-xl">
                        <th>{{ $lang.get('oauth.client-id') }}</th>
                        <th>{{ $lang.get('oauth.client-name') }}</th>
                        <th>{{ $lang.get('oauth.client-secret') }}</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        class="my-1"
                        v-for="client in clients"
                        :key="client._id"
                    >
                        <!-- ID -->
                        <td class="text-center">
                            {{ client._id }}
                        </td>

                        <!-- Name -->
                        <td class="text-center">
                            {{ client.name }}
                        </td>

                        <!-- Secret -->
                        <td class="text-center">
                            <code>{{ client.secret }}</code>
                        </td>

                        <!-- Edit Button -->
                        <td class="text-right p-1 py-3">
                            <dk-button @click="edit(client)">
                                {{ $lang.get('general.edit') }}
                            </dk-button>
                            <dk-button
                                @click="destroy(client)"
                                color="red-600"
                                hover-color="red-600"
                            >
                                {{ $lang.get('general.delete') }}
                            </dk-button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex flex-col mt-6">
                <dk-button
                    class="py-2 px-4 mx-1 self-end"
                    :padding="false"
                    @click.stop="dialogCreate = true;"
                >
                    {{ $lang.get('oauth.client-new') }}
                </dk-button>
            </div>
        </div>

        <!-- Create Client Modal -->
        <dk-modal
            :show="dialogCreate"
            @close="dialogCreate = false"
            ref="dialogCreate"
        >
            <div class="bg-white rounded text-black p-5">
                <div class="mb-3">
                    <span class="text-xl">
                        {{ $lang.get('oauth.client-create') }}
                    </span>
                </div>

                <div class="text-gray-900">
                    <div>
                        <!-- Form Errors -->
                        <div
                            class="w-full"
                            v-if="createform.errors.length > 0"
                        >
                            <p class="mb-0">
                                <strong>Whoops!</strong>{{ $lang.get('oauth.something-wrong') }}
                            </p>
                            <ul
                                class="text-red-700"
                            >
                                <li
                                    v-for="error in createform.errors"
                                    :key="error"
                                >
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- create client form -->
                        <form
                            role="form"
                            class="w-full"
                            @submit.prevent="store"
                        >
                            <!-- name -->
                            <label class="block mb-2">
                                {{ $lang.get('oauth.client-name') }}
                                <ul
                                    v-if="createform.errors['name'] !== undefined"
                                    class="text-red-700 mb-2"
                                >
                                    <li
                                    v-for="error in createform.errors['name']"
                                    :key="error"
                                    >
                                        {{ error }}
                                    </li>
                                </ul>
                                <input
                                    id="create-client-name"
                                    type="text"
                                    class="form-input w-full block"
                                    v-model="createform.name"
                                >
                                <span class="text-gray-700">
                                    {{ $lang.get('oauth.client-name-desc') }}
                                </span>
                            </label>

                            <!-- redirect url -->
                            <label class="block mb-2">
                                {{ $lang.get('oauth.client-url') }}
                                <ul
                                    v-if="createform.errors['redirect'] !== undefined"
                                    class="text-red-700 mb-2"
                                >
                                    <li
                                    v-for="error in createform.errors['redirect']"
                                    :key="error"
                                    >
                                        {{ error }}
                                    </li>
                                </ul>
                                <input
                                    type="text"
                                    class="form-input w-full block"
                                    name="redirect"
                                    v-model="createform.redirect"
                                >
                                <span class="text-gray-700">
                                    {{ $lang.get('oauth.client-url-desc') }}
                                </span>
                            </label>

                            <!-- confidential -->
                            <div class="block mb-2">
                                <label>
                                    <input type="checkbox" class="form-checkbox" v-model="createform.confidential">

                                    {{ $lang.get('oauth.client-confidential') }}
                                </label>
                                <br>
                                <span class="text-gray-700">
                                    {{ $lang.get('oauth.client-confidential-desc') }}
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- modal actions -->
                <div class="mt-2 flex justify-end">
                    <dk-button
                        class="py-2 px-4 mx-1"
                        hover-color="red-500"
                        :padding="false"
                        @click="dialogCreate = false"
                    >
                        {{ $lang.get('general.close') }}
                    </dk-button>
                    <dk-button
                        class="py-2 px-4 mx-1"
                        @click="store"
                        :padding="false"
                    >
                        {{ $lang.get('general.save') }}
                    </dk-button>
                </div>
            </div>
        </dk-modal>

        <!-- edit client modal -->
        <dk-modal
            :show="dialogedit"
            @close="dialogedit = false"
            ref="dialogedit"
        >
            <div class="bg-white rounded text-black p-5">
                <div class="mb-3">
                    <span class="text-xl">{{ $lang.get('oauth.client-edit') }}</span>
                </div>

                <div class="text-gray-900">
                    <div>
                        <!-- form errors -->
                        <div
                            class="w-full"
                            v-if="editform.errors.length > 0"
                        >
                            <p class="mb-0">
                                <strong>whoops!</strong>{{ $lang.get('oauth.something-wrong') }}
                            </p>
                            <ul
                                class="text-red-700"
                            >
                                <li
                                    v-for="error in editform.errors"
                                    :key="error"
                                >
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- edit client form -->
                        <form
                            role="form"
                            class="w-full"
                            @submit.prevent="update"
                        >
                            <!-- name -->
                            <label
                                class="block mb-2"
                            >
                                {{ $lang.get('oauth.client-name') }}
                                <ul
                                    v-if="editform.errors['name'] !== undefined"
                                    class="text-red-700 mb-2"
                                >
                                    <li
                                    v-for="error in editform.errors['name']"
                                    :key="error"
                                    >
                                        {{ error }}
                                    </li>
                                </ul>
                                <input
                                    id="edit-client-name"
                                    type="text"
                                    class="form-input w-full block"
                                    v-model="editform.name"
                                >

                                <span class="text-gray-700">
                                    {{ $lang.get('oauth.client-name-desc') }}
                                </span>
                            </label>

                            <!-- redirect url -->
                            <label
                                class="block mb-2"
                            >
                                {{ $lang.get('oauth.client-url') }}
                                <ul
                                    v-if="editform.errors['redirect'] !== undefined"
                                    class="text-red-700 mb-2"
                                >
                                    <li
                                    v-for="error in editform.errors['redirect']"
                                    :key="error"
                                    >
                                        {{ error }}
                                    </li>
                                </ul>
                                <input
                                    type="text"
                                    class="form-input w-full block"
                                    name="redirect"
                                    v-model="editform.redirect"
                                >

                                <span class="text-gray-700">
                                    {{ $lang.get('oauth.client-url-desc') }}
                                </span>
                            </label>
                        </form>
                    </div>
                </div>

                <!-- modal actions -->
                <div class="mt-2 flex justify-end">
                    <dk-button
                        class="py-2 px-4 mx-1"
                        hover-color="red-500"
                        :padding="false"
                        @click="dialogedit = false"
                    >
                        {{ $lang.get('general.abort') }}
                    </dk-button>
                    <dk-button
                        class="py-2 px-4 mx-1"
                        :padding="false"
                        @click="update"
                    >
                        {{ $lang.get('general.save-changes') }}
                    </dk-button>
                </div>
            </div>
        </dk-modal>
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                dialogCreate: false,
                dialogedit: false,
                clients: [],

                createform: {
                    errors: [],
                    name: '',
                    redirect: '',
                    confidential: true
                },

                editform: {
                    errors: [],
                    name: '',
                    redirect: ''
                }
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
             * Prepare the component.
             */
            prepareComponent() {
                this.getClients();
            },

            /**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data;
                        });
            },

            /**
             * Create a new OAuth client for the user.
             */
            store() {
                this.persistClient(
                    'post', '/oauth/clients',
                    this.createform, this.$refs.dialogCreate
                );
            },

            /**
             * Edit the given client.
             */
            edit(client) {
                this.editform.id = client._id;
                this.editform.name = client.name;
                this.editform.redirect = client.redirect;
                this.editform.errors = [];

                // Show edit modal
                this.dialogedit = true;
            },

            /**
             * Update the client being edited.
             */
            update() {
                this.persistClient(
                    'put', '/oauth/clients/' + this.editform.id,
                    this.editform, this.$refs.dialogedit
                );
            },

            /**
             * Persist the client to storage using the given form.
             */
            persistClient(method, uri, form, modal) {
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
                        this.getClients();

                        form.name = '';
                        form.redirect = '';
                        form.errors = [];

                        // close the modal that send the request
                        modal.close();
                    })
                    .catch(error => {
                        if (error.response !== undefined && typeof error.response.data === 'object') {
                            if (error.response.data.errors !== undefined) {
                                form.errors = error.response.data.errors;
                            } else if (error.response.data.message !== undefined) {
                                form.errors = [error.response.data.message];
                            } else {
                                form.errors = [error.response.status + ' ' + error.response.statusText];
                            }
                        } else {
                            console.log(error)
                            form.errors = ['Something went wrong. Please try again!'];
                        }
                    });
            },

            /**
             * Destroy the given client.
             */
            destroy(client) {
                axios.delete('/oauth/clients/' + client._id)
                        .then(response => {
                            this.getClients();
                        });
            }
        }
    }
</script>
