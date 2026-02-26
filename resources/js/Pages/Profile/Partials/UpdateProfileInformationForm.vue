<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-white">Информация профиля</h2>
            <p class="mt-1 text-sm text-gray-400">
                Обновите данные вашего аккаунта и адрес электронной почты.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Имя" class="text-white-300" />
                <TextInput id="name" type="text" class="mt-1 block w-full bg-gray-900/50 border-dark-border text-white focus:border-accent-blue" v-model="form.name" required autofocus />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" class="bg-accent-blue hover:bg-blue-500 text-dark-bg font-bold">
                    Сохранить изменения
                </PrimaryButton>
            </div>
        </form>
    </section>
</template>
