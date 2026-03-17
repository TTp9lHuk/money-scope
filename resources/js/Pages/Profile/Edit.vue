<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Meta from "@/Components/Meta.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
   <Meta title="Профайл"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-white tracking-tight">Настройки профиля</h2>
        </template>

        <div class="py-12 space-y-8 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-dark-card border-slate-700 shadow-2xl rounded-2xl p-8">
                <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
            </div>

            <div class="bg-dark-card border-slate-700 shadow-2xl rounded-2xl p-8">
                <UpdatePasswordForm />
            </div>

        </div>
    </AuthenticatedLayout>
</template>
