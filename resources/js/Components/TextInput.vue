<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="bg-gray-900 border-gray-800 text-gray-100 focus:border-accent-blue focus:ring-accent-blue rounded-xl shadow-sm w-full"
        v-model="model"
        ref="input"
    />
</template>
