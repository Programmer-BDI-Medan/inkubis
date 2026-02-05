<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Lupa Password - Inkubator Bisnis" />

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
                <div class="text-center mb-8">
                    <div
                        class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-teal-100 border border-teal-50 p-1.5"
                    >
                        <img
                            src="/images/LOGO INBIS.png"
                            alt="Logo INBIS"
                            class="w-full h-full object-cover rounded-full"
                        />
                    </div>

                    <h1 class="text-xl font-bold text-gray-800 tracking-tight">
                        Atur Ulang Kata Sandi
                    </h1>
                    <p class="text-gray-500 text-xs mt-2 leading-relaxed px-4">
                        Masukkan email yang terdaftar di Inkubator Bisnis. Kami
                        akan mengirimkan tautan pemulihan.
                    </p>
                </div>

                <div
                    v-if="status"
                    class="mb-6 text-xs font-medium text-teal-700 bg-teal-50 p-3 rounded-lg border border-teal-200 flex items-start animate-pulse-once"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-4 h-4 mr-2 mt-0.5 flex-shrink-0"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                        />
                    </svg>
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <InputLabel
                            for="email"
                            value="Email"
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
                                        d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"
                                    />
                                </svg>
                            </div>
                            <TextInput
                                id="email"
                                type="email"
                                class="block w-full pl-9 border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-sm py-2.5 transition-all duration-200"
                                v-model="form.email"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="nama@email.com"
                            />
                        </div>
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <PrimaryButton
                        class="w-full justify-center py-3 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 rounded-lg text-xs font-bold tracking-widest uppercase transition-all shadow-md shadow-teal-200 hover:shadow-teal-300 transform hover:-translate-y-0.5"
                        :class="{
                            'opacity-50 cursor-not-allowed': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Kirim Link Reset
                    </PrimaryButton>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center text-xs font-semibold text-gray-500 hover:text-teal-600 transition-colors group"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            class="w-3 h-3 mr-1.5 transform group-hover:-translate-x-1 transition-transform"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"
                            />
                        </svg>
                        Kembali ke Halaman Login
                    </Link>
                </div>
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

@keyframes pulse-once {
    0% {
        opacity: 0.8;
        transform: scale(0.98);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}
.animate-pulse-once {
    animation: pulse-once 0.3s ease-out forwards;
}
</style>
