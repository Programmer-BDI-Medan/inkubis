<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};

const loginWithGoogle = () => {
    window.location.href = "/auth-google-redirect";
};
</script>

<template>
    <Head title="Register Inkubator Bisnis" />

    <div
        class="min-h-screen flex flex-col items-center justify-center p-2 bg-teal-50/50"
    >
        <div
            class="w-full max-w-4xl bg-white shadow-[0_15px_40px_rgba(0,0,0,0.08)] rounded-2xl overflow-hidden flex flex-col md:flex-row min-h-[480px]"
        >
            <div
                class="w-full md:w-1/2 p-6 sm:p-8 flex flex-col justify-center bg-white order-2 md:order-1 relative"
            >
                <div class="mb-4">
                    <h1
                        class="text-xl font-bold text-gray-800 mb-0.5 tracking-tight"
                    >
                        Buat Akun Baru
                    </h1>
                    <p
                        class="text-gray-500 text-[10px] sm:text-xs leading-relaxed"
                    >
                        Daftarkan startup atau diri Anda untuk mulai bergabung.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-2.5">
                    <div>
                        <InputLabel
                            for="name"
                            value="Nama Lengkap / Startup"
                            class="text-[9px] font-bold text-gray-600 uppercase tracking-wider"
                        />
                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-xs py-2 transition-all duration-200"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Contoh: Budi Santoso / PT Maju Jaya"
                        />
                        <InputError
                            class="mt-0.5 text-[10px]"
                            :message="form.errors.name"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="email"
                            value="Email"
                            class="text-[9px] font-bold text-gray-600 uppercase tracking-wider"
                        />
                        <TextInput
                            id="email"
                            type="email"
                            class="mt-1 block w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-xs py-2 transition-all duration-200"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="nama@email.com"
                        />
                        <InputError
                            class="mt-0.5 text-[10px]"
                            :message="form.errors.email"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="password"
                            value="Password"
                            class="text-[9px] font-bold text-gray-600 uppercase tracking-wider"
                        />
                        <TextInput
                            id="password"
                            type="password"
                            class="mt-1 block w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-xs py-2 transition-all duration-200"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                        />
                        <InputError
                            class="mt-0.5 text-[10px]"
                            :message="form.errors.password"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="password_confirmation"
                            value="Konfirmasi Password"
                            class="text-[9px] font-bold text-gray-600 uppercase tracking-wider"
                        />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="mt-1 block w-full border-gray-200 bg-gray-50 focus:bg-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-lg text-xs py-2 transition-all duration-200"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password"
                        />
                        <InputError
                            class="mt-0.5 text-[10px]"
                            :message="form.errors.password_confirmation"
                        />
                    </div>

                    <PrimaryButton
                        class="w-full justify-center py-2.5 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 rounded-lg text-xs font-bold tracking-widest uppercase transition-all shadow-md shadow-teal-200 hover:shadow-teal-300 transform hover:-translate-y-0.5 mt-2"
                        :class="{
                            'opacity-50 cursor-not-allowed': form.processing,
                        }"
                        :disabled="form.processing"
                    >
                        Daftar Sekarang
                    </PrimaryButton>
                </form>

                <div class="relative my-4">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-gray-100"></span>
                    </div>
                    <div
                        class="relative flex justify-center text-[9px] uppercase tracking-widest"
                    >
                        <span class="bg-white px-2 text-gray-400 font-bold"
                            >Atau Daftar Dengan</span
                        >
                    </div>
                </div>

                <div class="mt-2 flex justify-center">
                    <button
                        @click="loginWithGoogle"
                        type="button"
                        class="group flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-full hover:border-teal-400 hover:shadow-sm transition-all duration-300"
                    >
                        <img
                            src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg"
                            class="w-3.5 h-3.5 group-hover:scale-110 transition-transform"
                            alt="Google"
                        />
                        <span
                            class="text-xs font-bold text-gray-600 group-hover:text-teal-700"
                            >Google Account</span
                        >
                    </button>
                </div>

                <p class="mt-6 text-center text-[12px] text-gray-500">
                    Sudah punya akun?
                    <Link
                        :href="route('login')"
                        class="text-teal-600 font-bold hover:underline"
                    >
                        Masuk disini
                    </Link>
                </p>
            </div>

            <div
                class="hidden md:flex md:w-1/2 relative bg-teal-800 text-white flex-col justify-center items-center p-6 order-1 md:order-2 overflow-hidden"
            >
                <div class="absolute inset-0 z-0">
                    <img
                        src="https://images.unsplash.com/photo-1556761175-5973dc0f32e7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1632&q=80"
                        alt="Meeting Background"
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
                        class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4 shadow-xl shadow-teal-900/50 transform hover:scale-105 transition-transform duration-500 overflow-hidden p-1.5"
                    >
                        <img
                            src="/images/LOGO INBIS.png"
                            alt="Logo Inbis"
                            class="w-full h-full object-cover rounded-full"
                        />
                    </div>

                    <h2 class="text-xl font-bold mb-2">Mulai Langkahmu</h2>
                    <div class="h-1 w-10 bg-orange-400 rounded-full mb-3"></div>

                    <p
                        class="text-teal-50 text-xs leading-relaxed font-light opacity-90"
                    >
                        "Bergabunglah dengan komunitas wirausaha terbaik dan
                        kembangkan potensimu bersama BDI Medan."
                    </p>

                    <div
                        class="mt-6 w-full border-t border-teal-500/30 pt-4 flex justify-center"
                    >
                        <div class="text-center">
                            <span class="block text-lg font-bold text-white"
                                >Gratis</span
                            >
                            <span
                                class="text-[9px] text-teal-200 uppercase tracking-wider"
                                >Pendaftaran</span
                            >
                        </div>
                    </div>
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
</style>
