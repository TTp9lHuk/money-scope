<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
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
    <Link
        :href="route('portfolios.show', portfolio.id)"
        class="group block rounded-xl border border-dark-border bg-dark-card p-5
               transition duration-200 hover:border-accent-blue/50 hover:-translate-y-0.5"
    >
        <div class="flex items-start justify-between gap-4">
            <div>
                <h3 class="text-lg font-semibold text-white transition group-hover:text-accent-blue">
                    {{ portfolio.name }}
                </h3>

                <p class="mt-1 text-sm text-slate-400">
                    Т-Инвестиции
                </p>
            </div>

            <span
                class="whitespace-nowrap rounded-full px-3 py-1 text-xs font-medium"
                :class="statusClasses[portfolio.sync_status] ?? statusClasses.idle"
            >
                {{ statusLabels[portfolio.sync_status] ?? portfolio.sync_status }}
            </span>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4">
            <div>
                <div class="text-xs text-slate-500">
                    Стоимость
                </div>

                <div class="mt-1 text-xl font-semibold text-white">
                    —
                </div>
            </div>

            <div>
                <div class="text-xs text-slate-500">
                    Доходность
                </div>

                <div class="mt-1 text-xl font-semibold text-white">
                    —
                </div>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-between border-t border-dark-border pt-4">
            <div class="text-sm text-slate-400">
                {{ portfolio.currency }}
            </div>

            <div class="flex items-center gap-2 text-sm text-slate-400 transition group-hover:text-accent-blue">
                Открыть

                <span class="transition-transform group-hover:translate-x-1">
                    →
                </span>
            </div>
        </div>
    </Link>
</template>
