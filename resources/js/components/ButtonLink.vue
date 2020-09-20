<template>
    <a
        class="mx-1 bg-transparent font-semibold border hover:border-transparent rounded text-xl"
        :class="['text-' + color, 'border-' + color, 'hover:bg-' + hoverColor, 'hover:text-' + hoverTextColor, {'py-1 px-2': padding}]"
        :href="url"
        :target="tabbed ? '_blank' : ''"
    >
        <slot>Link</slot>
    </a>
</template>

<script>
    export default {
        name: "ButtonLink",
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
            url: {
                type: String,
                default: '/home'
            },
            tabbed: {
                type: Boolean,
                default: true,
            }
        },
    }
</script>

<style scoped>

</style>
