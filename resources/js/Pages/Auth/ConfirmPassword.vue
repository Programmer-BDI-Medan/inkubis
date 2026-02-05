<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";

const form = useForm({
    password: "",
});

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Konfirmasi Password" />

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
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                            />
                        </svg>
                    </div>

                    <h1 class="text-xl font-bold text-gray-800 tracking-tight">
                        Konfirmasi Akses
                    </h1>
                    <p class="text-gray-500 text-xs mt-2 leading-relaxed px-2">
                        Ini adalah area aman aplikasi. Mohon konfirmasi password
                        Anda sebelum melanjutkan.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <InputLabel
                            for="password"
                            value="Password"
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
                                        d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"
                                    />
                                </svg>
                            </div>

                            <TextInput
                                id="password"
                                type="password"
                                class="block w-full pl-9 border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-sm py-2.5 transition-all duration-200"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                autofocus
                                placeholder="Masukkan password Anda"
                            />
                        </div>
                        <InputError
                            class="mt-1"
                            :message="form.errors.password"
                        />
                    </div>

                    <PrimaryButton
                        class="w-full justify-center py-3 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 rounded-lg text-xs font-bold tracking-widest uppercase transition-all shadow-md shadow-teal-200 hover:shadow-teal-300 transform hover:-translate-y-0.5"
                        :class="{
                            'opacity-50 cursor-not-allowed': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Konfirmasi Password
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
