<script setup lang="ts">
import Modal from '@/Components/Modal.vue';
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close']);

const form = useForm({
    name: '',
    broker_type: 'tinkoff',
    api_token: '',
    currency: 'RUB',
});

const submit = () => {
    form.post(route('portfolios.store'), {
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
};

defineProps(['show']);

</script>

<template>

    <Modal :show="show">
        <div class="p-6 bg-dark-bg border border-gray-800 rounded-2xl">
            <h2 class="text-lg font-medium text-white mb-4">
                Добавление нового портфеля
            </h2>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400">Название</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full bg-slate-900 border-gray-700 rounded-lg text-white focus:border-accent-blue focus:ring-accent-blue"
                        placeholder="Напр: Основной Т-Банк"
                        required
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400">Тип брокера</label>
                    <select
                        v-model="form.broker_type"
                        class="mt-1 block w-full bg-slate-900 border-gray-700 rounded-lg text-white focus:border-accent-blue"
                    >
                        <option value="tinkoff">Т-Инвестиции (API)</option>
                        <option value="manual">Вручную</option>
                    </select>
                </div>

                <div v-if="form.broker_type === 'tinkoff'">
                    <label class="block text-sm font-medium text-gray-400">API Токен</label>
                    <input
                        v-model="form.api_token"
                        type="password"
                        class="mt-1 block w-full bg-slate-900 border-gray-700 rounded-lg text-white"
                        placeholder="t.XXXXX..."
                    />
                    <p class="mt-1 text-xs text-gray-500">Токен будет надежно зашифрован.</p>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="$emit('close')"
                        class="px-4 py-2 text-gray-400 hover:text-white transition"
                    >
                        Отмена
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-6 py-2 bg-accent-blue text-slate-900 font-bold rounded-lg hover:bg-blue-400 transition disabled:opacity-50"
                    >
                        {{ form.processing ? 'Сохранение...' : 'Создать' }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>

<style scoped>

</style>
