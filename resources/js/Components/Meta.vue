<script setup>
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    title: String,
    description: String,
});

const defaultDescription = "Умный сервис аналитики для ваших инвестиций в Т-Инвестициях.";
</script>

<template>
    <Head>
        <title>{{ title ? `${title} | Money Scope` : 'Money Scope — Аналитика инвестиций' }}</title>

        <meta name="description" :content="description || defaultDescription" />

        <meta property="og:title" :content="title || 'Money Scope'" />
        <meta property="og:description" :content="description || defaultDescription" />
        <meta property="og:type" content="website" />
    </Head>
</template>
