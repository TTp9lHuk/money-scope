<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    portfolio: {
        type: Object,
        required: true,
    },
});

const statusLabels = {
    idle: 'Не синхронизирован',
    syncing: 'Синхронизация',
    success: 'Синхронизирован',
    error: 'Ошибка синхронизации',
};

const statusClasses = {
    idle: 'bg-slate-700/50 text-slate-300',
    syncing: 'bg-sky-500/10 text-sky-400',
    success: 'bg-emerald-500/10 text-emerald-400',
    error: 'bg-red-500/10 text-red-400',
};
</script>

<template>
    <Head :title="portfolio.name" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl">
            <!-- Назад -->
            <Link
                :href="route('portfolios.index')"
                class="mb-6 inline-flex items-center gap-2 text-sm text-slate-400 transition hover:text-white"
            >
                <span>←</span>
                Все портфели
            </Link>

            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="text-2xl font-semibold text-white">
                            {{ portfolio.name }}
                        </h1>

                        <span
                            class="rounded-full px-3 py-1 text-xs font-medium"
                            :class="statusClasses[portfolio.sync_status] ?? statusClasses.idle"
                        >
                            {{ statusLabels[portfolio.sync_status] ?? portfolio.sync_status }}
                        </span>
                    </div>

                    <p class="mt-2 text-sm text-slate-400">
                        Т-Инвестиции · {{ portfolio.currency }}
                    </p>
                </div>

                <button
                    type="button"
                    class="rounded-lg bg-accent-blue px-4 py-2 text-sm font-medium text-slate-950
                           transition hover:opacity-90"
                >
                    Обновить данные
                </button>
            </div>

            <!-- Summary -->
            <div class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="rounded-xl border border-dark-border bg-dark-card p-5">
                    <div class="text-sm text-slate-400">
                        Стоимость портфеля
                    </div>

                    <div class="mt-2 text-2xl font-semibold text-white">
                        —
                    </div>
                </div>

                <div class="rounded-xl border border-dark-border bg-dark-card p-5">
                    <div class="text-sm text-slate-400">
                        Доходность
                    </div>

                    <div class="mt-2 text-2xl font-semibold text-white">
                        —
                    </div>
                </div>

                <div class="rounded-xl border border-dark-border bg-dark-card p-5">
                    <div class="text-sm text-slate-400">
                        Последнее обновление
                    </div>

                    <div class="mt-2 text-base font-medium text-white">
                        {{ portfolio.last_synced_at ?? 'Еще не синхронизирован' }}
                    </div>
                </div>
            </div>

            <!-- Positions -->
            <div class="mt-8 overflow-hidden rounded-xl border border-dark-border bg-dark-card">
                <div class="border-b border-dark-border px-5 py-4">
                    <h2 class="font-semibold text-white">
                        Позиции
                    </h2>
                </div>

                <div class="px-5 py-16 text-center">
                    <div class="text-base font-medium text-slate-300">
                        Позиции пока отсутствуют
                    </div>

                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        После первой синхронизации здесь появятся активы,
                        количество, текущая стоимость и доходность.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
