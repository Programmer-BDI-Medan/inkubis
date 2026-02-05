<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const loginWithGoogle = () => {
    window.location.href = "/auth-google-redirect";
};
</script>

<template>
    <Head title="Login Inkubator Bisnis" />

    <div
        class="min-h-screen flex flex-col items-center justify-center p-4 bg-teal-50/50"
    >
        <div
            class="w-full max-w-4xl bg-white shadow-[0_15px_40px_rgba(0,0,0,0.08)] rounded-2xl overflow-hidden flex flex-col md:flex-row min-h-[500px]"
        >
            <div
                class="w-full md:w-1/2 p-8 sm:p-10 flex flex-col justify-center bg-white order-2 md:order-1 relative"
            >
                <div class="mb-6">
                    <h1
                        class="text-2xl font-bold text-gray-800 mb-1 tracking-tight"
                    >
                        Selamat Datang
                    </h1>
                    <p class="text-gray-500 text-xs sm:text-sm leading-relaxed">
                        Silakan masuk untuk mengakses dashboard Inkubator
                        Bisnis.
                    </p>
                </div>

                <div
                    v-if="status"
                    class="mb-4 text-xs font-medium text-teal-600 bg-teal-50 p-2.5 rounded-lg border border-teal-100"
                >
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel
                            for="email"
                            value="Email"
                            class="text-[10px] font-bold text-gray-600 uppercase tracking-wider"
                        />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1.5 block w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-sm py-2.5 transition-all duration-200"
                            v-model="form.email"
                            required
                            autofocus
                            placeholder="nama@email.com"
                            autocomplete="username"
                        />
                        <InputError class="mt-1" :message="form.errors.email" />
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <InputLabel
                                for="password"
                                value="Password"
                                class="text-[10px] font-bold text-gray-600 uppercase tracking-wider"
                            />
                        </div>
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1.5 block w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-sm py-2.5 transition-all duration-200"
                            v-model="form.password"
                            required
                            placeholder="••••••••"
                            autocomplete="current-password"
                        />
                        <InputError
                            class="mt-1"
                            :message="form.errors.password"
                        />
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <label class="flex items-center cursor-pointer group">
                            <Checkbox
                                name="remember"
                                v-model:checked="form.remember"
                                class="w-4 h-4 text-teal-600 rounded border-gray-300 focus:ring-teal-500"
                            />
                            <span
                                class="ms-2 text-xs text-gray-500 group-hover:text-teal-600 transition-colors"
                                >Ingat Saya</span
                            >
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-xs text-teal-600 hover:text-teal-800 font-bold transition-colors"
                        >
                            Lupa Sandi?
                        </Link>
                    </div>

                    <PrimaryButton
                        class="w-full justify-center py-3 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 rounded-lg text-xs font-bold tracking-widest uppercase transition-all shadow-md shadow-teal-200 hover:shadow-teal-300 transform hover:-translate-y-0.5 mt-2"
                        :class="{
                            'opacity-50 cursor-not-allowed': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Sign In
                    </PrimaryButton>
                </form>

                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-gray-100"></span>
                    </div>
                    <div
                        class="relative flex justify-center text-[10px] uppercase tracking-widest"
                    >
                        <span class="bg-white px-2 text-gray-400 font-bold"
                            >Atau</span
                        >
                    </div>
                </div>

                <div class="flex justify-center">
                    <button
                        @click="loginWithGoogle"
                        type="button"
                        class="group flex items-center justify-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-full hover:border-teal-400 hover:shadow-sm transition-all duration-300"
                    >
                        <img
                            src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                            class="w-4 h-4 group-hover:scale-110 transition-transform"
                            alt="Google"
                        />
                        <span
                            class="text-xs font-bold text-gray-600 group-hover:text-teal-700"
                            >Google Account</span
                        >
                    </button>
                </div>

                <p class="mt-6 text-center text-xs text-gray-500">
                    Belum punya akun?
                    <Link
                        :href="route('register')"
                        class="text-teal-600 font-bold hover:underline"
                    >
                        Daftar Sekarang
                    </Link>
                </p>
            </div>

            <div
                class="hidden md:flex md:w-1/2 relative bg-teal-800 text-white flex-col justify-center items-center p-8 order-1 md:order-2 overflow-hidden"
            >
                <div class="absolute inset-0 z-0">
                    <img
                        src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1469&q=80"
                        alt="Office Background"
                        class="w-full h-full object-cover"
                    />
                </div>

                <div
                    class="absolute inset-0 bg-gradient-to-br from-teal-800/90 to-emerald-900/90 z-10 backdrop-blur-[2px]"
                ></div>

                <div
                    class="relative z-20 flex flex-col items-center text-center max-w-xs"
                >
                    <div
                        class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 shadow-xl shadow-teal-900/50 transform hover:scale-105 transition-transform duration-500"
                    >
                        <img
                            src="/images/LOGO INBIS.png"
                            alt="Logo Inbis"
                            class="w-20 h-20 object-cover rounded-full"
                        />
                    </div>

                    <h2 class="text-2xl font-bold mb-3">
                        Inkubator Bisnis BDI Medan
                    </h2>
                    <div class="h-1 w-12 bg-orange-400 rounded-full mb-4"></div>

                    <p
                        class="text-teal-50 text-sm leading-relaxed font-light opacity-90"
                    >
                        Wadah pembinaan wirausaha pemula untuk mengembangkan
                        bisnis yang tangguh dan mandiri.
                    </p>

                    <div
                        class="mt-8 w-full border-t border-teal-500/30 pt-6 flex justify-center"
                    >
                        <div class="text-center">
                            <span class="block text-xl font-bold text-white"
                                >100+</span
                            >
                            <span
                                class="text-[10px] text-teal-200 uppercase tracking-wider"
                                >Tenant</span
                            >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom Style untuk autofill chrome */
input:-webkit-autofill,
input:-webkit-autofill:hover,
input:-webkit-autofill:focus,
input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px white inset !important;
}
</style>
