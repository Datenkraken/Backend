<template>
    <div class="flex-grow h-full bg-gray-100">
        <table class="sm:w-10/12 m-3">
            <thead>

            <tr>
                <th class="py-4 px-6 bg-gray-100 font-bold uppercase text-sm
                text-gray-900 border-b-2 border-gray-300">
                    {{ $lang.get('categories.name') }}
                </th>
                <th class="py-4 px-6 bg-grey-lightest font-bold uppercase text-sm
                text-gray-900 border-b-2 border-grey-300 text-right">
                    <dk-button
                        @click="dialogCreate = 'add'"
                    >
                        {{ $lang.get('categories.add') }}
                    </dk-button>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr
                v-if="categories.length == 0">
                <td>{{ $lang.get('categories.no-category') }}</td>
                <td></td>
            </tr>
            <tr
                v-else
                v-for="category in categories"
                :key="category._id"
                class="hover:bg-gray-200 border-b border-gray-300"
            >
                <td class="py-4 px-6">
                    {{ category.name }}
                </td>
                <td class="py-4 px-6 text-right">
                    <dk-button
                        class="p-1"
                        @click="editCategory(category)"
                    >
                        <font-awesome-icon icon="edit"></font-awesome-icon>
                    </dk-button>
                    <dk-button
                        @click="deleteCategory(category._id)"
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
                    <span class="text-xl">{{ $lang.get('categories.add') }}</span>
                </div>

                <div class="text-gray-900">
                    <div>
                        <!-- Form Errors -->
                        <div
                            class="w-full"
                            v-if="categoryForm.errors.length > 0"
                        >
                            <p class="mb-0">
                                <strong>Whoops!</strong> {{
                                $lang.get('categories.something-wrong') }}
                            </p>
                            <ul
                                class="text-red-700"
                            >
                                <li
                                    v-for="error in categoryForm.errors"
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
                                {{ $lang.get('categories.category-name') }}
                                <ul
                                    v-if="categoryForm.errors['name'] !== undefined"
                                    class="text-red-700 mb-2"
                                >
                                    <li
                                    v-for="error in categoryForm.errors['name']"
                                    :key="error"
                                    >
                                        {{ error }}
                                    </li>
                                </ul>
                                <input
                                    type="text"
                                    class="form-input w-full block"
                                    v-model="categoryForm.name"
                                >
                                <span class="text-gray-700">
                                    {{ $lang.get('categories.category-name-desc') }}
                                </span>
                            </label>
                        </form>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="mt-2 flex justify-end">
                    <dk-button
                        class="py-2 px-4 mx-1"
                        hoverColor="red-500"
                        @click="closeCategoryModal"
                    >
                        {{ $lang.get('general.abort') }}
                    </dk-button>
                    <dk-button
                        class="py-2 px-4 mx-1"
                        @click="confirmCategoryModal"
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
      category: null,
      dialogCreate: '',

      categoryForm: {
          id: '',
          name: '',
          url: '',
          errors: [],
      },
    };
  },

  methods: {
    /**
     * Close and reset Modal
     */
    closeCategoryModal() {
        this.categoryForm.id = '';
        this.categoryForm.name = '';
        this.categoryForm.errors = [];

        this.dialogCreate = '';
    },

    /**
     * Tries to send the request for adding or updating the category,
     * depending on the type of modal.
     */
    confirmCategoryModal() {
        if (this.dialogCreate === 'add') {
            // send add request
            this.persistCategory(
                'post', '/categories/new',
                this.$refs.dialogCreate
            );
        } else if (this.dialogCreate === 'edit') {
            // send update request
            this.persistCategory(
                'put', '/categories/update/',
                this.$refs.dialogCreate
            );
        }
    },

    /**
     * Display the modal for editing the given category.
     */
    editCategory(category) {
        this.categoryForm.id = category._id;
        this.categoryForm.name = category.name;

        this.dialogCreate = 'edit';
    },

    /**
     * Send the request to the given endpoint with the given data
     */
    persistCategory(method, uri, modal) {
        this.categoryForm.errors = [];

        axios[method](uri, this.categoryForm)
            .then(response => {
                this.$emit('categoriesUpdated');

                this.closeCategoryModal();
            })
            .catch(error => {
                if (error.response !== undefined && typeof error.response.data === 'object') {
                    if (error.response.data.errors !== undefined) {
                        this.categoryForm.errors = error.response.data.errors;
                    } else if (error.response.data.message !== undefined) {
                        this.categoryForm.errors = [error.response.data.message];
                    } else {
                        this.categoryForm.errors = [error.response.status + ' ' + error.response.statusText];
                    }
                } else {
                    console.log(error)
                    this.categoryForm.errors = ['Something went wrong. Please try again!'];
                }
            }
        );
    },

    /**
     * Sends the request to delete the category with the given id.
     */
    deleteCategory(id) {
        axios.delete('/categories/delete/' + id)
        .then(response => {
          this.$emit('categoriesUpdated');
        });
    },
  },
};
</script>
