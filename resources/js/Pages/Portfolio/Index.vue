<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CreatePortfolioForm from '@/Pages/Portfolio/Modal/CreatePortfolioForm.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

// Состояние для открытия/закрытия попапа
const showModal = ref(false);

const form = useForm({
    name: '',
    broker_type: 'tinkoff',
    api_token: '',
    currency: 'RUB',
});

const submit = () => {
    form.post(route('portfolios.store'), {
        onSuccess: () => {
            closeModal();
            form.reset();
        },
    });
};

const closeModal = () => {
    showModal.value = false;
};

defineProps({ portfolios: Array });
</script>

<template>
    <Head title="Мои портфели" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-white tracking-tight">Инвестиционные портфели</h2>
        </template>
        <pre style="display: none;">{{ portfolios }}</pre>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-dark-card overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <PrimaryButton
                        class="ms-4"
                        @click="showModal = true"
                    >
                        + Добавить портфель
                    </PrimaryButton>

                    <div v-if="portfolios.length === 0" class="text-gray-400 text-center py-10">
                        У вас пока нет созданных портфелей. Начните с подключения Тинькофф API.
                    </div>
                    <div v-else class="text-gray-400 text-center py-10">asdfasfdasdfas</div>
                </div>
            </div>
        </div>

        <CreatePortfolioForm
            :show="showModal"
            @close="showModal = false"
        />
    </AuthenticatedLayout>
</template>
