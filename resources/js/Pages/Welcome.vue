<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const isDropdownOpen = ref(false);
const logout = () => {
    router.post(route('logout'));
};

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    programOpen: Object,
});

// --- LOGIKA SCROLL ---
const scrollToProfile = () => {
    const element = document.getElementById('company-profile');
    element.scrollIntoView({ behavior: 'smooth' });
};


// --- LOGIKA COUNTDOWN ---
const buka = props.programOpen?.tanggal_buka
    ? new Date(props.programOpen.tanggal_buka).getTime()
    : null;

const tutup = props.programOpen?.tanggal_tutup
    ? new Date(props.programOpen.tanggal_tutup).getTime()
    : null;

const now = new Date().getTime();

const isBuka = buka && now < buka ? false : true;

const targetDate = buka && now < buka ? buka : tutup;

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

        <div class="relative z-10 w-full max-w-4xl px-6 mb-12">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 tracking-tight">
                    Pendaftaran Pelatihan <span class="text-blue-600 dark:text-blue-400">Inkubator Bisnis</span>
                </h1>
                <p v-if="isBuka" class="text-lg text-slate-600 dark:text-slate-400">
                    Segera daftarkan diri Anda sebelum pendaftaran ditutup!
                </p>
                <p v-else class="text-lg text-slate-600 dark:text-slate-400">
                    Akan segera dibuka dalam waktu:
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
        
                    <!-- tombol daftar -->
                    <Link 
                        :href="route('form-pendaftaran', programOpen.id)" 
                        class="w-full sm:w-auto px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl"
                    >
                        Daftar Sekarang
                    </Link>

                    <!-- kalau login & bukan user -->
                    <Link 
                        v-if="$page.props.auth.user && $page.props.auth.user.role !== 'user'" 
                        :href="route('dashboard')" 
                        class="w-full sm:w-auto px-10 py-4 bg-white font-bold rounded-xl border"
                    >
                        Masuk ke Dashboard
                    </Link>

                    <!-- kalau belum login -->
                    <Link 
                        v-else-if="!$page.props.auth.user"
                        :href="route('login')" 
                        class="w-full sm:w-auto px-10 py-4 bg-white font-bold rounded-xl border"
                    >
                        Log in
                    </Link>

                </template>
            </div>
        </div>

        <button @click="scrollToProfile" class="absolute bottom-5 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-slate-400 hover:text-blue-500 transition-colors group">
            <span class="text-xs uppercase tracking-widest font-bold">Profil BDI Medan</span>
            <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path>
            </svg>
        </button>

        
        
    </div>
    
    <nav v-if="$page.props.auth.user" class="absolute top-0 right-0 p-6 z-50">
        <div class="relative">
            <button 
                @click="isDropdownOpen = !isDropdownOpen"
                class="flex items-center gap-3 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md px-4 py-2 rounded-2xl shadow-sm border border-slate-200 dark:border-zinc-800 hover:border-blue-400 transition-all group"
            >
                <div class="flex flex-col text-right hidden sm:block">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Halo,</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $page.props.auth.user.name }}</span>
                </div>
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow-lg shadow-blue-200 dark:shadow-none uppercase">
                    {{ $page.props.auth.user.name.substring(0, 1) }}
                </div>
            </button>

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95"
            >
                <div v-if="isDropdownOpen" class="absolute right-0 mt-3 w-48 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800 py-2 z-50">
                    <Link :href="route('profile.edit')" class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Edit Profil
                    </Link>
                    
                    <div class="h-px bg-slate-100 dark:bg-zinc-800 my-1 mx-2"></div>
                    
                    <button @click="logout" class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </div>
            </transition>
        </div>
    </nav>

    <div id="company-profile" class="min-h-screen bg-white dark:bg-zinc-900 py-24 px-6 relative">
        <div class="max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div class="w-full md:w-1/2 h-80 bg-slate-200 dark:bg-zinc-800 rounded-3xl overflow-hidden shadow-2xl">
                    <div class="flex items-center justify-center h-full text-slate-400">
                        [Image: Balai Diklat Industri Medan]
                    </div>
                </div>
                
                <div class="w-full md:w-1/2">
                    <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-6">
                        Tentang <span class="text-blue-600">BDI Medan</span>
                    </h2>
                    <p class="text-slate-600 dark:text-slate-400 mb-6 leading-relaxed">
                        Balai Diklat Industri (BDI) Medan merupakan unit pelaksana teknis di bawah Kementerian Perindustrian yang berfokus pada pengembangan SDM industri, khususnya di sektor pengolahan pangan dan industri kreatif.
                    </p>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">Visi</h4>
                            <p class="text-sm text-slate-500">Menjadi pusat unggulan pendidikan dan pelatihan industri yang kompeten.</p>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">Misi</h4>
                            <p class="text-sm text-slate-500">Menciptakan tenaga kerja terampil dan wirausaha baru yang mandiri.</p>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="mt-24 text-center text-slate-400 text-sm">
                <div class="flex items-center justify-center gap-4">
                    <div class="h-px w-12 bg-slate-300 dark:bg-zinc-800"></div>
                    <span>Supported by Laravel v{{ laravelVersion }}</span>
                    <div class="h-px w-12 bg-slate-300 dark:bg-zinc-800"></div>
                </div>
            </footer>
        </div>
    </div>
</template>

<style>
/* CSS Kamu yang lama tetap dipertahankan */
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

/* Tambahan kelancaran scroll jika browser tidak mendukung smooth behavior lewat JS */
html {
    scroll-behavior: smooth;
}
</style>