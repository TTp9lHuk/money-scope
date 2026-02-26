<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Meta from "@/Components/Meta.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Meta title="Money Scope — Умный учёт инвестиций"/>
    <div class="min-h-screen bg-dark-bg text-white selection:bg-accent-blue selection:text-white">
        <nav class="p-6 flex justify-between items-center max-w-7xl mx-auto">

            <div class="text-2xl font-bold bg-gradient-to-r from-accent-blue to-accent-purple bg-clip-text text-transparent flex">
                <ApplicationLogo
                    class="text-2xl font-bold bg-gradient-to-r from-accent-blue to-accent-purple bg-clip-text text-transparent"
                />
                MONEY SCOPE
            </div>
            <div v-if="canLogin" class="space-x-4">
                <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-gray-400 hover:text-white transition">Личный кабинет</Link>
                <template v-else>
                    <Link :href="route('login')" class="text-gray-400 hover:text-white transition">Войти</Link>
                    <Link :href="route('register')" class="bg-accent-blue px-5 py-2 rounded-lg font-medium hover:bg-blue-600 transition shadow-lg shadow-blue-500/20">Начать бесплатно</Link>
                </template>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto px-6 pt-20 pb-32 text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
                Простой и удобный <br>
                <span class="text-accent-blue">учёт инвестиций</span>
            </h1>
            <p class="text-gray-400 text-xl max-w-2xl mx-auto mb-10">
                Все ваши активы в одном месте. Календарь дивидендов, аналитика портфеля и автоматическая синхронизация с Тинькофф API.
            </p>

            <div class="flex flex-col md:flex-row justify-center gap-4 mb-16">
                <Link :href="route('register')" class="text-lg bg-accent-blue px-10 py-4 rounded-xl font-bold hover:scale-105 transition shadow-xl shadow-blue-500/20">Присоединяйтесь, это бесплатно</Link>
                <button class="text-lg border border-gray-700 hover:bg-gray-800 px-10 py-4 rounded-xl font-bold transition">Посмотреть демо</button>
            </div>

            <div class="relative max-w-5xl mx-auto">
                <div class="absolute -inset-1 bg-gradient-to-r from-accent-blue to-accent-purple rounded-2xl blur opacity-20"></div>
                <div class="relative bg-gray-900 border border-gray-800 rounded-2xl aspect-video flex items-center justify-center shadow-2xl overflow-hidden">
                    <div class="text-gray-600 italic">Скриншот интерфейса (будет здесь после разработки Dashboard)</div>
                </div>
            </div>
        </main>
    </div>
</template>
