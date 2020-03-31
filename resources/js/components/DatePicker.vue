<template>
  <div class="inline-flex items-center">
    <flat-pickr
      v-bind="$attrs"
      v-on="$listeners"
      class="flex-grow rounded py-2 px-3 appearance-none border text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
      :placeholder="$lang.get('general.datepicker-placeholder')"
      ref="datePicker"
    />
    <div class="px-1">
      <dk-button
        :title="$lang.get('general.toggle')"
        @click="toggle"
        class="text-gray-600 px-3 py-1"
        :padding="false"
        :no-color="true"
      >
        <font-awesome-icon icon="calendar"></font-awesome-icon>
      </dk-button>
      <dk-button
        :title="$lang.get('general.clear')"
        @click="clear"
        class="text-gray-600 px-3 py-1"
        :padding="false"
        :no-color="true"
      >
        <font-awesome-icon icon="times"></font-awesome-icon>
      </dk-button>
    </div>
  </div>
</template>
<script>
export default {
  methods: {
    /**
     * Toggle the datepicker.
     */
    toggle() {
      this.$refs.datePicker.fp.toggle();
    },

    /**
     * Clear the datepicker.
     */
    clear() {
      this.$refs.datePicker.fp.clear();
    },
  },
};
</script>
