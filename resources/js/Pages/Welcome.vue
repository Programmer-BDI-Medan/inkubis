<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

// --- LOGIKA COUNTDOWN ---
const targetDate = new Date('2026-02-10T23:59:59').getTime(); // Atur tanggal target di sini
const days = ref(0);
const hours = ref(0);
const minutes = ref(0);
const seconds = ref(0);
let timer = null;

const updateCountdown = () => {
    const now = new Date().getTime();
    const distance = targetDate - now;

    if (distance < 0) {
        clearInterval(timer);
        return;
    }

    days.value = Math.floor(distance / (1000 * 60 * 60 * 24));
    hours.value = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    minutes.value = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    seconds.value = Math.floor((distance % (1000 * 60)) / 1000);
};

onMounted(() => {
    updateCountdown();
    timer = setInterval(updateCountdown, 1000);
});

onUnmounted(() => {
    clearInterval(timer);
});
</script>

<template>
    <Head title="Pendaftaran Pelatihan" />
    
    <div class="relative min-h-screen bg-slate-50 dark:bg-zinc-950 flex flex-col items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl px-6">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 tracking-tight">
                    Pendaftaran Pelatihan <span class="text-blue-600 dark:text-blue-400">Inkubator Bisnis</span>
                </h1>
                <p class="text-lg text-slate-600 dark:text-slate-400">
                    Segera daftarkan diri Anda sebelum pendaftaran ditutup!
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 mb-12">
                <div class="flex flex-col items-center p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800">
                    <span class="text-5xl md:text-6xl font-black text-blue-600 dark:text-blue-400">{{ days }}</span>
                    <span class="text-sm uppercase tracking-widest font-bold text-slate-500 mt-2">Hari</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800">
                    <span class="text-5xl md:text-6xl font-black text-blue-600 dark:text-blue-400">{{ hours }}</span>
                    <span class="text-sm uppercase tracking-widest font-bold text-slate-500 mt-2">Jam</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800">
                    <span class="text-5xl md:text-6xl font-black text-blue-600 dark:text-blue-400">{{ minutes }}</span>
                    <span class="text-sm uppercase tracking-widest font-bold text-slate-500 mt-2">Menit</span>
                </div>
                <div class="flex flex-col items-center p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800">
                    <span class="text-5xl md:text-6xl font-black text-red-500 animate-pulse">{{ seconds }}</span>
                    <span class="text-sm uppercase tracking-widest font-bold text-slate-500 mt-2">Detik</span>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <template v-if="canLogin">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/30 text-center"
                    >
                        Masuk ke Dashboard
                    </Link>

                    <template v-else>
                        <Link
                            :href="route('register')"
                            class="w-full sm:w-auto px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all shadow-lg shadow-blue-500/30 text-center transform hover:scale-105"
                        >
                            Daftar Sekarang
                        </Link>
                        <Link
                            :href="route('login')"
                            class="w-full sm:w-auto px-10 py-4 bg-white dark:bg-zinc-800 text-slate-900 dark:text-white font-bold rounded-xl border border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-all text-center"
                        >
                            Log in
                        </Link>
                    </template>
                </template>
            </div>

            <footer class="mt-20 text-center text-slate-400 text-sm">
                <div class="flex items-center justify-center gap-4 mb-4">
                    <div class="h-px w-12 bg-slate-300 dark:bg-zinc-800"></div>
                    <span>Supported by Laravel v{{ laravelVersion }}</span>
                    <div class="h-px w-12 bg-slate-300 dark:bg-zinc-800"></div>
                </div>
            </footer>
        </div>
    </div>
</template>

<style>
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 {
    animation-delay: 2s;
}
.animation-delay-4000 {
    animation-delay: 4s;
}
</style>