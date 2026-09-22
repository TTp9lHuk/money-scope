<script setup>
import { computed, ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    portfolio: {
        type: Object,
        required: true,
    },
})

const syncing = ref(false)

/**
 * Статусы синхронизации
 */
const statusLabels = {
    idle: 'Не синхронизирован',
    syncing: 'Синхронизация...',
    success: 'Синхронизирован',
    error: 'Ошибка синхронизации',
}

const statusClasses = {
    idle: 'bg-slate-700 text-slate-300',
    syncing: 'bg-sky-500/10 text-sky-400',
    success: 'bg-emerald-500/10 text-emerald-400',
    error: 'bg-red-500/10 text-red-400',
}

/**
 * Синхронизация портфеля
 */
const syncPortfolio = () => {
    router.post(
        route('portfolios.sync', props.portfolio.id),
        {},
        {
            preserveScroll: true,

            onStart: () => {
                syncing.value = true
            },

            onFinish: () => {
                syncing.value = false
            },
        },
    )
}

/**
 * Общая текущая стоимость позиций.
 *
 * Пока считаем на frontend.
 * Позже эти агрегаты лучше будет отдавать с backend.
 */
const portfolioValue = computed(() => {
    return (props.portfolio.positions ?? []).reduce(
        (total, position) => {
            return total + Number(position.current_value ?? 0)
        },
        0,
    )
})

/**
 * Общий ожидаемый доход.
 */
const portfolioYield = computed(() => {
    return (props.portfolio.positions ?? []).reduce(
        (total, position) => {
            return total + Number(position.expected_yield ?? 0)
        },
        0,
    )
})

/**
 * Форматирование количества.
 */
const formatNumber = (value) => {
    if (value === null || value === undefined) {
        return '—'
    }

    return new Intl.NumberFormat('ru-RU', {
        maximumFractionDigits: 8,
    }).format(Number(value))
}

/**
 * Форматирование денежных значений.
 */
const formatMoney = (value, currency = 'RUB') => {
    if (value === null || value === undefined) {
        return '—'
    }

    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: currency?.toUpperCase() || 'RUB',
        maximumFractionDigits: 2,
    }).format(Number(value))
}

/**
 * Денежное значение со знаком + для прибыли.
 */
const formatSignedMoney = (value, currency = 'RUB') => {
    if (value === null || value === undefined) {
        return '—'
    }

    const number = Number(value)

    return `${number > 0 ? '+' : ''}${formatMoney(number, currency)}`
}

/**
 * Цвет прибыли/убытка.
 */
const yieldClass = (value) => {
    const number = Number(value)

    if (number > 0) {
        return 'text-emerald-400'
    }

    if (number < 0) {
        return 'text-red-400'
    }

    return 'text-slate-300'
}

/**
 * Последняя синхронизация.
 */
const formatDateTime = (value) => {
    if (!value) {
        return 'Еще не синхронизирован'
    }

    return new Intl.DateTimeFormat('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(new Date(value))
}
</script>

<template>
    <Head :title="portfolio.name" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl">

            <!-- Back -->
            <Link
                :href="route('portfolios.index')"
                class="mb-6 inline-flex items-center gap-2 text-sm text-slate-400 transition hover:text-white"
            >
                <span>←</span>
                Все портфели
            </Link>

            <!-- Header -->
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl font-semibold text-white">
                            {{ portfolio.name }}
                        </h1>

                        <span
                            class="rounded-full px-3 py-1 text-xs font-medium"
                            :class="
                                statusClasses[portfolio.sync_status]
                                ?? statusClasses.idle
                            "
                        >
                            {{
                                statusLabels[portfolio.sync_status]
                                ?? portfolio.sync_status
                            }}
                        </span>
                    </div>

                    <p class="mt-2 text-sm text-slate-400">
                        Т-Инвестиции · {{ portfolio.currency }}
                    </p>
                </div>

                <button
                    type="button"
                    :disabled="syncing"
                    @click="syncPortfolio"
                    class="rounded-lg bg-accent-blue px-4 py-2
                           text-sm font-medium text-slate-950
                           transition hover:opacity-90
                           disabled:cursor-not-allowed
                           disabled:opacity-50"
                >
                    {{ syncing ? 'Обновляем...' : 'Обновить данные' }}
                </button>
            </div>

            <!-- Sync error -->
            <div
                v-if="portfolio.sync_status === 'error' && portfolio.sync_error_message"
                class="mt-6 rounded-xl border border-red-500/20
                       bg-red-500/10 px-5 py-4"
            >
                <div class="text-sm font-medium text-red-400">
                    Не удалось обновить портфель
                </div>

                <div class="mt-1 text-sm text-red-300/80">
                    {{ portfolio.sync_error_message }}
                </div>
            </div>

            <!-- Summary -->
            <div class="mt-8 grid grid-cols-1 gap-4 md:grid-cols-3">

                <!-- Portfolio value -->
                <div
                    class="rounded-xl border border-dark-border
                           bg-dark-card p-5"
                >
                    <div class="text-sm text-slate-400">
                        Стоимость портфеля
                    </div>

                    <div class="mt-2 text-2xl font-semibold text-white">
                        {{
                            portfolio.positions?.length
                                ? formatMoney(
                                    portfolioValue,
                                    portfolio.currency,
                                )
                                : '—'
                        }}
                    </div>
                </div>

                <!-- Yield -->
                <div
                    class="rounded-xl border border-dark-border
                           bg-dark-card p-5"
                >
                    <div class="text-sm text-slate-400">
                        Доход
                    </div>

                    <div
                        class="mt-2 text-2xl font-semibold"
                        :class="yieldClass(portfolioYield)"
                    >
                        {{
                            portfolio.positions?.length
                                ? formatSignedMoney(
                                    portfolioYield,
                                    portfolio.currency,
                                )
                                : '—'
                        }}
                    </div>
                </div>

                <!-- Last sync -->
                <div
                    class="rounded-xl border border-dark-border
                           bg-dark-card p-5"
                >
                    <div class="text-sm text-slate-400">
                        Последнее обновление
                    </div>

                    <div class="mt-2 text-base font-medium text-white">
                        {{ formatDateTime(portfolio.last_synced_at) }}
                    </div>
                </div>
            </div>

            <!-- Positions -->
            <div
                class="mt-8 overflow-hidden rounded-xl
                       border border-dark-border bg-dark-card"
            >
                <!-- Positions header -->
                <div
                    class="flex items-center justify-between
                           border-b border-dark-border px-5 py-4"
                >
                    <h2 class="font-semibold text-white">
                        Позиции
                    </h2>

                    <span
                        v-if="portfolio.positions?.length"
                        class="text-sm text-slate-400"
                    >
                        {{ portfolio.positions.length }}
                    </span>
                </div>

                <!-- Positions table -->
                <div
                    v-if="portfolio.positions?.length"
                    class="overflow-x-auto"
                >
                    <table class="w-full min-w-[900px]">

                        <thead>
                        <tr
                            class="border-b border-dark-border
                                       text-left text-xs text-slate-400"
                        >
                            <th class="px-5 py-3 font-medium">
                                Инструмент
                            </th>

                            <th class="px-5 py-3 text-right font-medium">
                                Количество
                            </th>

                            <th class="px-5 py-3 text-right font-medium">
                                Средняя цена
                            </th>

                            <th class="px-5 py-3 text-right font-medium">
                                Текущая цена
                            </th>

                            <th class="px-5 py-3 text-right font-medium">
                                Стоимость
                            </th>

                            <th class="px-5 py-3 text-right font-medium">
                                Доход
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr
                            v-for="position in portfolio.positions"
                            :key="position.id"
                            class="border-b border-dark-border/70
                                       transition
                                       last:border-b-0
                                       hover:bg-white/[0.025]"
                        >
                            <!-- Asset -->
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <!-- Placeholder icon -->
                                    <div
                                        class="flex h-10 w-10 shrink-0
                                                   items-center justify-center
                                                   rounded-full bg-slate-700
                                                   text-xs font-semibold
                                                   text-slate-300"
                                    >
                                        {{
                                            position.asset?.ticker
                                                ?.substring(0, 2)
                                                ?.toUpperCase()
                                            ?? '?'
                                        }}
                                    </div>

                                    <div class="min-w-0">
                                        <div
                                            class="font-medium text-white"
                                        >
                                            {{
                                                position.asset?.ticker
                                                ?? '—'
                                            }}
                                        </div>

                                        <div
                                            class="mt-0.5 max-w-[240px]
                                                       truncate text-xs
                                                       text-slate-400"
                                        >
                                            {{
                                                position.asset?.name
                                                ?? 'Неизвестный инструмент'
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Quantity -->
                            <td
                                class="whitespace-nowrap px-5 py-4
                                           text-right text-sm text-slate-200"
                            >
                                {{ formatNumber(position.quantity) }}
                            </td>

                            <!-- Average price -->
                            <td
                                class="whitespace-nowrap px-5 py-4
                                           text-right text-sm text-slate-200"
                            >
                                {{
                                    formatMoney(
                                        position.average_position_price,
                                        position.currency,
                                    )
                                }}
                            </td>

                            <!-- Current price -->
                            <td
                                class="whitespace-nowrap px-5 py-4
                                           text-right text-sm text-slate-200"
                            >
                                {{
                                    formatMoney(
                                        position.current_price,
                                        position.currency,
                                    )
                                }}
                            </td>

                            <!-- Current value -->
                            <td
                                class="whitespace-nowrap px-5 py-4
                                           text-right text-sm
                                           font-medium text-white"
                            >
                                {{
                                    formatMoney(
                                        position.current_value,
                                        position.currency,
                                    )
                                }}
                            </td>

                            <!-- Expected yield -->
                            <td
                                class="whitespace-nowrap px-5 py-4
                                           text-right text-sm font-medium"
                                :class="
                                        yieldClass(
                                            position.expected_yield,
                                        )
                                    "
                            >
                                {{
                                    formatSignedMoney(
                                        position.expected_yield,
                                        position.currency,
                                    )
                                }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty state -->
                <div
                    v-else
                    class="flex min-h-52 flex-col items-center
                           justify-center px-6 text-center"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-full bg-slate-700/70 text-xl
                               text-slate-400"
                    >
                        ₽
                    </div>

                    <div class="mt-4 font-medium text-slate-200">
                        Позиции пока отсутствуют
                    </div>

                    <div
                        class="mt-2 max-w-md text-sm
                               leading-6 text-slate-400"
                    >
                        После первой синхронизации здесь появятся
                        активы, количество, текущая стоимость
                        и доходность.
                    </div>

                    <button
                        type="button"
                        :disabled="syncing"
                        @click="syncPortfolio"
                        class="mt-5 rounded-lg bg-accent-blue
                               px-4 py-2 text-sm font-medium
                               text-slate-950 transition
                               hover:opacity-90
                               disabled:cursor-not-allowed
                               disabled:opacity-50"
                    >
                        {{
                            syncing
                                ? 'Обновляем...'
                                : 'Синхронизировать портфель'
                        }}
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
