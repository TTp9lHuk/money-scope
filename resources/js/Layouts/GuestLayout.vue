<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-dark-bg">
        <div>
            <Link href="/" class="text-3xl font-bold bg-gradient-to-r from-accent-blue to-accent-purple bg-clip-text text-transparent">
                MONEY SCOPE
            </Link>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-gray-900/50 border border-gray-800 shadow-2xl overflow-hidden sm:rounded-2xl backdrop-blur-xl">
            <slot />
        </div>
    </div>
</template>
