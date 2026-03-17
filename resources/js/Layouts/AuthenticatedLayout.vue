<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-dark-bg flex">
        <aside class="w-64 bg-gray-900 border-r border-gray-800 flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 flex items-center space-x-3">
                <ApplicationLogo class="w-10 h-10"/>
                <Link :href="route('dashboard')"
                      class="text-xl font-bold bg-gradient-to-r from-accent-blue to-accent-purple bg-clip-text text-transparent tracking-tighter">
                    MONEY SCOPE
                </Link>
            </div>

            <nav class="flex-1 px-4 space-y-1">
                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    Сводка
                </NavLink>

                <NavLink :href="route('portfolios.index')" :active="route().current('portfolios.*')">
                    <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Портфели
                </NavLink>
            </nav>

            <div class="p-4 border-t border-gray-800">
                <div class="text-xs text-gray-500 uppercase font-bold mb-2 px-4">Настройки</div>
                <NavLink :href="route('profile.edit')" :active="route().current('profile.edit')">
                    ⚙️ Профиль
                </NavLink>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="bg-gray-900/50 backdrop-blur-md border-b border-gray-800">
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                    <div class="font-semibold text-xl text-white tracking-tight">
                        <slot name="header" />
                    </div>

                    <div class="flex items-center">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center text-gray-400 hover:text-white transition">
                                    {{ $page.props.auth.user.name }}
                                    <svg class="ml-2 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Профиль</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Выйти</DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 text-gray-300">
                <slot />
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Кастомные стили для NavLink если нужно,
   но лучше править сам компонент NavLink.vue */
</style>
