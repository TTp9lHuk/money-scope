<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({ portfolios: Array });
</script>

<template>
    <Head title="Мои портфели" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Инвестиционные портфели</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <button class="bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded mb-4">
                        + Добавить портфель
                    </button>

                    <div v-if="portfolios.length === 0" class="text-gray-500 text-center py-10">
                        У вас пока нет созданных портфелей. Начните с подключения Тинькофф API.
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
