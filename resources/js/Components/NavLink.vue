<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() =>
    props.active
        ? 'flex items-center px-4 py-3 bg-accent-blue/15 text-accent-blue rounded-xl font-bold transition shadow-sm'
        : 'flex items-center px-4 py-3 text-gray-300 hover:bg-gray-800/80 hover:text-white rounded-xl transition text-sm'
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
