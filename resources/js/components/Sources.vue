<template>
    <div class="flex-grow h-full bg-gray-100">
        <table class="sm:w-10/12 m-3">
            <thead>

            <tr>
                <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm
                text-gray-900 border-b-2 border-gray-300">
                    {{ $lang.get('sources.name') }}
                </th>
                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm
                text-gray-900 border-b-2 border-grey-300">
                    Url
                </th>
                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm
                text-gray-900 border-b-2 border-grey-300 text-right">
                    <dk-button
                        @click="dialogCreate = 'add'"
                    >
                        {{ $lang.get('sources.add-source') }}
                    </dk-button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="sources.length == 0">
                <td></td>
                <td>{{ $lang.get('sources.no-source') }}</td>
                <td></td>
            </tr>
            <tr
                v-else
                v-for="source in sources"
                :key="source._id"
                class="hover:bg-gray-200 border-b border-gray-300"
            >
                <td class="py-4 px-6">
                    {{ source.name }}
                </td>
                <td class="py-4 px-6">
                    {{ source.url }}
                </td>
                <td class="py-4 px-6 text-right">
                    <dk-button
                        class="p-1"
                        @click="editSource(source)"
                    >
                        <font-awesome-icon icon="edit"></font-awesome-icon>
                    </dk-button>
                    <dk-button
                        @click="deleteSource(source._id)"
                        class="p-1"
                        color="red-600"
                        hoverColor="red-600"
                    >
                        <font-awesome-icon icon="trash"></font-awesome-icon>
                    </dk-button>
                </td>
            </tr>
            </tbody>
        </table>
        <dk-modal
            :show="dialogCreate != ''"
            @close="dialogCreate = ''"
            ref="dialogCreate"
        >
            <div class="bg-white rounded text-black p-5">
                <div class="mb-3">
                    <span class="text-xl">{{ dialogCreate === 'add' ?
                        $lang.get('sources.add-source') :
                        $lang.get('sources.edit-source') }}</span>
                </div>

                <div class="text-gray-900">
                    <div>
                        <!-- Form Errors -->
                        <div
                            class="w-full"
                            v-if="sourceForm.errors.length > 0"
                        >
                            <p class="mb-0">
                                <strong>Whoops!</strong> {{
                                $lang.get('sources.something-wrong') }}
                            </p>
                            <ul
                                class="text-red-700"
                            >
                                <li
                                    v-for="error in sourceForm.errors"
                                    :key="error"
                                >
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <form
                            role="form"
                            class="w-full"
                            @submit.prevent="store"
                        >
                            <!-- Name -->
                            <label class="block mb-2">
                                {{ $lang.get('sources.source-name') }}
                                <ul
                                    v-if="sourceForm.errors['name'] !== undefined"
                                    class="text-red-700 mb-2"
                                >
                                    <li
                                        v-for="error in sourceForm.errors['name']"
                                        :key="error"
                                    >
                                        {{ error }}
                                    </li>
                                </ul>
                                <input
                                    type="text"
                                    class="form-input w-full block"
                                    v-model="sourceForm.name"
                                >
                                <span class="text-gray-700">
                                    {{ $lang.get('sources.source-name-desc') }}
                                </span>
                            </label>

                            <!-- Redirect URL -->
                            <label class="block mb-2">
                                    {{ $lang.get('sources.source-url') }}
                                <ul
                                    v-if="sourceForm.errors['url'] !== undefined"
                                    class="text-red-700 mb-2"
                                >
                                    <li
                                    v-for="error in sourceForm.errors['url']"
                                    :key="error"
                                    >
                                        {{ error }}
                                    </li>
                                </ul>
                                <input
                                    type="text"
                                    class="form-input w-full block"
                                    name="url"
                                    v-model="sourceForm.url"
                                >
                                <span class="text-gray-700">
                                    {{ $lang.get('sources.source-url-desc') }}
                                </span>
                            </label>
                            <div class="mb-2">
                                {{ $lang.get('sources.selected-categories') }}
                                <ul
                                    v-if="sourceForm.errors['categories'] !== undefined"
                                    class="text-red-700 mb-2"
                                >
                                    <li
                                        v-for="error in sourceForm.errors['categories']"
                                        :key="error"
                                    >
                                        {{ error }}
                                    </li>
                                </ul>
                                <div class="overflow-y-auto flex flex-wrap
                                    flex-row h-1/12">
                                    <label
                                        v-for="category in categories"
                                        :key="category._id"
                                        class="p-2 block"
                                    >
                                        <input
                                            name="category-box"
                                            type="checkbox"
                                            class="form-checkbox"
                                            :modelid="category._id"
                                        >
                                            {{ category.name }}
                                        </input>
                                    </label>
                                </div>
                                <span class="text-gray-700">
                                    {{ $lang.get('sources.source-categories-desc') }}
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="mt-2 flex justify-end">
                    <dk-button
                        class="py-2 px-4 mx-1"
                        hoverColor="red-500"
                        @click="closeSourceModal"
                    >
                        {{ $lang.get('general.close') }}
                    </dk-button>
                    <dk-button
                        class="py-2 px-4 mx-1"
                        @click="confirmSourceModal"
                    >
                        {{ $lang.get('general.save') }}
                    </dk-button>
                </div>
            </div>
        </dk-modal>
    </div>
</template>

<script>
import moment from 'moment';

export default {
  props: ['categories'],
  /*
   * The component's data.
   */
  data() {
    return {
      sources: [],
      source: null,
      dialogCreate: '',

      category: null,

      sourceForm: {
          id: '',
          name: '',
          url: '',
          categories: [],
          errors: [],
      },
    };
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
      this.getSources();
    },

    /**
     * Close and reset Modal
     */
    closeSourceModal() {
        this.sourceForm.id = '';
        this.sourceForm.url = '';
        this.sourceForm.name = '';
        this.sourceForm.categories = [];
        this.sourceForm.errors = [];

        var nodes = document.querySelectorAll('input[name=category-box]');
        for (var i = 0; i < nodes.length; i++) {
            nodes[i].checked = false;
        }

        this.dialogCreate = '';
    },

    /**
     * Tries to send the request for adding or updating the source,
     * depending on the type of modal.
     */
    confirmSourceModal() {
        var checkedBoxes = document.querySelectorAll('input[name=category-box]:checked');
        this.sourceForm.categories = Array.from(checkedBoxes).map(a =>
            a.getAttribute('modelid'));

        if (this.dialogCreate === 'add') {
            // send add request
            this.persistSource(
                'post', '/sources/new',
                this.$refs.dialogCreate
            );
        } else if (this.dialogCreate === 'edit') {
            // send update request
            this.persistSource(
                'put', '/sources/update/',
                this.$refs.dialogCreate
            );
        }
    },

    /**
     * Get all sources.
     */
    getSources() {
      axios.get('/sources/all')
        .then(response => {
            this.sources = response.data;
        });
    },

    /**
     * Display the modal for editing the given source
     */
    editSource(source) {
        this.sourceForm.id = source._id;
        this.sourceForm.name = source.name;
        this.sourceForm.url = source.url;

        var categories = source.categories.map(s => s._id);
        this.sourceForm.categories = categories;
        var nodes = document.querySelectorAll('input[name=category-box]');
        for (var i = 0; i < nodes.length; i++) {
            if (categories.includes(nodes[i].getAttribute('modelid'))) {
                nodes[i].checked = true;
            }
        }

        this.dialogCreate = 'edit';
    },

    /**
     * Send the request to the given endpoint with the given data
     */
    persistSource(method, uri, modal) {
        this.sourceForm.errors = [];

        axios[method](uri, this.sourceForm)
            .then(response => {
                this.getSources();

                this.closeSourceModal();
            })
            .catch(error => {
                if (error.response !== undefined && typeof error.response.data === 'object') {
                    if (error.response.data.errors !== undefined) {
                        this.sourceForm.errors = error.response.data.errors;
                    } else if (error.response.data.message !== undefined) {
                        this.sourceForm.errors = [error.response.data.message];
                    } else {
                        this.sourceForm.errors = [error.response.status + ' ' + error.response.statusText];
                    }
                } else {
                    console.log(error)
                    this.sourceForm.errors = ['Something went wrong. Please try again!'];
                }
            }
        );
    },

    /**
     * Sends the request to delete the category with the given id.
     */
    deleteSource(id) {
        axios.delete('/sources/delete/' + id)
        .then(response => {
          this.getSources();
        });
    },
  },
};
</script>
