<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-dark-bg">
        <div class="p-6 flex items-center space-x-3">
            <ApplicationLogo class="w-10 h-10"/>
            <Link :href="route('dashboard')"
                  class="text-xl font-bold bg-gradient-to-r from-accent-blue to-accent-purple bg-clip-text text-transparent tracking-tighter">
                MONEY SCOPE
            </Link>
        </div>

        <div class="w-full bg-dark-card sm:max-w-md px-6 py-8 border-slate-700 shadow-2xl rounded-2xl p-8">
            <slot />
        </div>
    </div>
</template>
