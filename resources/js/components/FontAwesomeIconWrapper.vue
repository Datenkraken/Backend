<template>
<font-awesome-icon :icon="[prefix, icon]"></font-awesome-icon>
</template>

<script>
    export default {
        name: "FontAwesomeIconWrapper",
        props: {
            prefix: {
                type: String,
                required: true,
            },
            icon: {
                type: String,
                required: true,
            }
        }
    }
</script>

<style scoped>

</style>
