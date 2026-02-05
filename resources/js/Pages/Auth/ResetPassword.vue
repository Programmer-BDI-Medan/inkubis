<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("password.store"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head title="Reset Password - Inkubator Bisnis" />

    <div
        class="min-h-screen flex flex-col items-center justify-center p-4 bg-teal-50/50"
    >
        <div
            class="w-full max-w-md bg-white shadow-[0_20px_50px_rgba(0,0,0,0.1)] rounded-2xl overflow-hidden relative"
        >
            <div
                class="h-1.5 w-full bg-gradient-to-r from-teal-500 via-emerald-500 to-teal-500 relative z-10"
            ></div>

            <div class="p-8 relative z-10">
                <div class="text-center mb-6">
                    <div
                        class="w-16 h-16 bg-teal-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-teal-100"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-8 h-8 text-teal-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"
                            />
                        </svg>
                    </div>

                    <h1 class="text-xl font-bold text-gray-800 tracking-tight">
                        Buat Password Baru
                    </h1>
                    <p class="text-gray-500 text-xs mt-2 leading-relaxed px-4">
                        Silakan masukkan password baru untuk akun
                        <strong>{{ email }}</strong
                        >.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel
                            for="email"
                            value="Email"
                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1"
                        />
                        <TextInput
                            id="email"
                            type="email"
                            class="block w-full border-gray-200 bg-gray-100 text-gray-500 cursor-not-allowed focus:ring-0 rounded-lg text-sm py-2.5 transition-all duration-200"
                            v-model="form.email"
                            required
                            readonly
                            autocomplete="username"
                        />
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel
                            for="password"
                            value="Password Baru"
                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1"
                        />
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-4 h-4 text-gray-400 group-focus-within:text-teal-500 transition-colors"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                                    />
                                </svg>
                            </div>
                            <TextInput
                                id="password"
                                type="password"
                                class="block w-full pl-9 border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-sm py-2.5 transition-all duration-200"
                                v-model="form.password"
                                required
                                autofocus
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                            />
                        </div>
                        <InputError
                            class="mt-1"
                            :message="form.errors.password"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="password_confirmation"
                            value="Konfirmasi Password"
                            class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1"
                        />
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-4 h-4 text-gray-400 group-focus-within:text-teal-500 transition-colors"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>
                            </div>
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="block w-full pl-9 border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-sm py-2.5 transition-all duration-200"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password baru"
                            />
                        </div>
                        <InputError
                            class="mt-1"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <PrimaryButton
                        class="w-full justify-center py-3 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 rounded-lg text-xs font-bold tracking-widest uppercase transition-all shadow-md shadow-teal-200 hover:shadow-teal-300 transform hover:-translate-y-0.5 mt-2"
                        :class="{
                            'opacity-50 cursor-not-allowed': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Simpan Password Baru
                    </PrimaryButton>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px white inset !important;
}
</style>
