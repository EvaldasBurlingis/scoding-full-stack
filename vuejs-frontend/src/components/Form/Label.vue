<template>
    <label :for="id"
        class="text-sm font-semibold text-gray-900 mb-1 block"
    >{{ title }}</label>
</template>

<script>
export default {
    name: 'Label',
    props: {
        id: String,
        title: String
    }
}
</script>