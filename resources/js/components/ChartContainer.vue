<template>
<div class="col-span-1">
    <div class="h-full w-full shadow-lg rounded-lg p-4">
        <span class="text-2xl">{{title}}</span>
        <div class="mt-3">
            <slot />
        </div>
    </div>
</div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
    },
  },
};
</script>
