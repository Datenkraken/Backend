<template>
    <div class="flex flex-col lg:flex-row">
        <dk-sources
            :categories="categories"
            class="w-full lg:w-1/2"
        ></dk-sources>
        <dk-categories
            class="w-full lg:w-1/2"
            :categories="categories"
            @categoriesUpdated="getCategories"
        ></dk-categories>
    </div>
</template>

<script>

export default {
    /*
     * The component's data.
     */
    data() {
        return {
            categories: [],
        };
    },

    /**
     * Prepare the component (Vue 2.x).
     */
    mounted() {
      this.getCategories();
    },

    methods: {
        /**
         * Get all categories.
         */
        getCategories() {
          axios.get('/categories/all')
            .then(response => {
                this.categories = response.data;
            });
        },
    }
}
</script>

