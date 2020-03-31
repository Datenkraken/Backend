<template>
  <button
    :type="$attrs.type !== undefined ? $attrs.type : 'button'"
    class="mx-1 bg-transparent font-semibold border hover:border-transparent rounded"
    v-bind="$attrs"
    v-on="$listeners"
    :class="['text-' + color, 'border-' + color, 'hover:bg-' + hoverColor, 'hover:text-' + hoverTextColor, {'py-1 px-2': padding}]"
  >
    <slot>Button</slot>
  </button>
</template>
<script>
export default {
  props: {
    padding: {
      type: Boolean,
      default: true,
    },
    color: {
      type: String,
      default: 'bgprimary',
    },
    hoverColor: {
      type: String,
      default: 'bgprimary',
    },
    hoverTextColor: {
      type: String,
      default: 'white',
    },
  },
};
</script>

