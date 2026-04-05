<script setup>
defineProps({
    class: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <img
        src="/images/logo.png"
        alt="Logo"
        :class="['w-auto object-contain', $props.class]"
    />
</template>
