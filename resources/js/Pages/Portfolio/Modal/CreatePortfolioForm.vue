<script setup lang="ts">
import { computed, ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import { router } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

// Props должны быть объявлены в начале
const props = defineProps<{
    show: boolean;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const brokerAccounts = ref([]);
const isLoadingAccounts = ref(false);
const accountsError = ref('');

/*const form = () => {
    router.post('/set-portfolio', {
        name: '',
        broker_type: 'tinkoff' as 'tinkoff' | 'manual',
        broker_connection_id: '',
        currency: 'RUB',
    }, {
        preserveState: true,
        onSuccess: (page) => {

        },
        onError: (errors) => {

        },
    });
};*/

const form = useForm({
    name: '',
    broker_type: '',
    api_token: '',
    account_id: '',
    currency: 'RUB',
});

const loadBrokerAccounts = async () => {
    if (!isValidTinkoffToken.value) return;

    if (!form.api_token) {
        accountsError.value = 'Введите токен';
        return;
    }

    isLoadingAccounts.value = true;
    accountsError.value = '';

    try {
        const {data} = await axios.post('/api/p/broker-connections/fetchAccounts', {
            api_token: form.api_token,
            broker_type: form.broker_type,
        });
        console.log(data.data.accounts,'data');
        brokerAccounts.value = data.data.accounts|| [];

        if (brokerAccounts.value.length === 1) {
           console.log(brokerAccounts.value,'brokerAccounts.value');
        }

    } catch (err) {console.log(err,'err');
        accountsError.value = err.response?.data?.error || 'Ошибка загрузки';
    } finally {
        await new Promise(resolve => setTimeout(resolve, 300));
        isLoadingAccounts.value = false;
    }
};

// Computed правильно объявлен
const isValidTinkoffToken = computed(() => {
    if (!form.api_token) return false;
    // Tinkoff токен может содержать буквы, цифры, _, -, =, +, /
    const tinkoffTokenPattern = /^t\.[A-Za-z0-9_\-=+/]+$/;
    return tinkoffTokenPattern.test(form.api_token) && form.api_token.length > 10;
});

const submit = () => {
    console.log(form.data());
    form.post(route('portfolios.store'), {
        onSuccess: () => {
            form.reset();
            emit('close');
        },
        onError: (errors) => {
            console.error('Ошибка создания портфеля:', errors);
        },
    });
};
</script>

<template>
    <Modal :show="props.show">
        <div class="p-6 bg-dark-bg border border-gray-800">
            <h2 class="text-lg font-medium text-white mb-4">
                Добавление нового портфеля
            </h2>

            <form @submit.prevent="submit" class="space-y-4">
                <!-- Название -->
                <div>
                    <label class="block text-sm font-medium text-gray-400">Название</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full bg-slate-900 border-gray-700 rounded-lg text-white focus:border-accent-blue focus:ring-accent-blue"
                        placeholder="Напр: Основной Т-Банк"
                        required
                    />
                    <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">
                        {{ form.errors.name }}
                    </p>
                </div>

                <!-- Тип брокера -->
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

                <!-- API Токен (только для Tinkoff) -->
                <div v-if="form.broker_type === 'tinkoff'">
                    <label class="block text-sm font-medium text-gray-400">API Токен</label>
                    <input
                        v-model="form.api_token"
                        type="password"
                        class="mt-1 block w-full bg-slate-900 border-gray-700 rounded-lg text-white"
                        :class="{ 'border-red-500': form.api_token && !isValidTinkoffToken }"
                        placeholder="t.XXXXX..."
                    />
                    <p v-if="form.api_token && !isValidTinkoffToken" class="mt-1 text-xs text-red-500">
                        Неверный формат токена. Токен должен начинаться с "t."
                    </p>
                    <p v-else class="mt-1 text-xs text-gray-500">
                        Токен используется только для чтения данных и хранится в зашифрованном виде.
                    </p>
                    <p v-if="form.errors.api_token" class="mt-1 text-xs text-red-500">
                        {{ form.errors.api_token }}
                    </p>
                    <p class="mt-1 text-xs text-right">
                        <button
                            type="button"
                            @click="loadBrokerAccounts"
                            :disabled="!isValidTinkoffToken || brokerAccounts.length > 0"
                            class="mt-1 px-4 py-2 bg-accent-blue text-slate-900 font-bold rounded-lg hover:bg-blue-400 transition disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap"
                        >
                            {{ isLoadingAccounts ? 'Загрузка...' : 'Загрузить счета' }}
                        </button>
                    </p>

                </div>

                <!-- Выбор счета (только при валидном токене) -->
                <div v-if="brokerAccounts.length > 0 && isValidTinkoffToken">
                    <label class="block text-sm font-medium text-gray-400">Выберите счет</label>
                    <select
                        v-model="form.account_id"
                        class="mt-1 block w-full bg-slate-900 border-gray-700 rounded-lg text-white focus:border-accent-blue"
                    >
                        <option value="" disabled>Выберите счет</option>
                        <option
                            v-for="account in brokerAccounts"
                            :key="account.id"
                            :value="account.id"
                        >
                            {{ account.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.account_id" class="mt-1 text-xs text-red-500">
                        {{ form.errors.account_id }}
                    </p>
                </div>

                <!-- Кнопки -->
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        @click="emit('close')"
                        class="px-4 py-2 text-gray-400 hover:text-white transition"
                    >
                        Отмена
                    </button>
                    <button
                        v-if="form.account_id"
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
