<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

const isDropdownOpen = ref(false);
const logout = () => {
    router.post(route("logout"));
};

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    programOpen: Object,
});

// --- DATA STATIS ---
const stages = ref([
    {
        id: 1,
        title: "Pra Inkubasi",
        description:
            "Tahap sosialisasi, seleksi proposal bisnis, serta kunjungan verifikasi kelayakan calon tenant hingga penandatanganan kontrak.",
        icon: "M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2",
    },
    {
        id: 2,
        title: "Inkubasi",
        description:
            "Tahap pengembangan intensif (maksimal 1 tahun) yang berfokus pada mentoring bisnis, layanan konseling, dan evaluasi usaha secara berkala.",
        icon: "M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z",
    },
    {
        id: 3,
        title: "Pasca Inkubasi",
        description:
            "Tahap pemantauan selama 2 tahun yang mencakup fasilitasi komunitas (networking), akses ke sumber pembiayaan (investor), dan konsultasi legal.",
        icon: "M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z",
    },
]);

const benefits = ref([
    {
        title: "Mentoring Bisnis",
        description:
            "Dapatkan pendampingan dari para ahli industri dan praktisi bisnis berpengalaman.",
        icon: "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z",
    },
    {
        title: "Fasilitas Produksi",
        description:
            "Akses ke fasilitas produksi, laboratorium, dan peralatan modern untuk pengembangan produk.",
        icon: "M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z",
    },
    {
        title: "Pelatihan Intensif",
        description:
            "Program pelatihan komprehensif mencakup manajemen bisnis, pemasaran, dan teknis produksi.",
        icon: "M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253",
    },
    {
        title: "Akses Permodalan",
        description:
            "Bantuan akses ke investor, crowdfunding, dan program pembiayaan untuk pengembangan usaha.",
        icon: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    },
    {
        title: "Networking Industri",
        description:
            "Koneksi dengan pelaku industri, supplier, distributor, dan calon mitra bisnis potensial.",
        icon: "M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9",
    },
    {
        title: "Sertifikasi & Legalitas",
        description:
            "Bantuan proses sertifikasi produk, izin edar, dan legalitas usaha.",
        icon: "M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z",
    },
]);

const tenants = ref([
    {
        id: 1,
        name: "Rempahara / Bakulan Mbak Ning",
        kategori: "Minuman",
        product: "Jahe Kunyit Asam Sirih",
        owner: "Ningrum Utami Pangat",
        address:
            "Jalan Purnawirawan, Desa Medan Estate, Kec. Percut Sei Tuan, Kab. Deli Serdang",
        phone: "0852-6147-0705",
        image: "/images/tenant/logo-rempahara.png", // Sesuaikan path jika ada logo
        produkImage: "/images/tenant/produk-rempahara.jpg", // Menggunakan 1 gambar utama saja
    },
    {
        id: 2,
        name: "AL - KUBRA",
        kategori: "Minuman",
        product: "Sirup Kasturi Citrusia",
        owner: "Cut Vera Julina",
        address:
            "Jl. Beringin 6 No.25 A, Kel. Helvetia, Kec. Medan Helvetia, Kota Medan",
        phone: "0813-6002-3803",
        image: "/images/tenant/logo-alkubra.png",
        produkImage: "/images/tenant/produk-alkubra.jpg",
    },
    {
        id: 3,
        name: "Hawais Cake",
        kategori: "Makanan",
        product: "Bolu Nenas",
        owner: "Zulaiha Rangkuti",
        address: "Gg. Al-Banjari, Amplas, Kec. Medan Amplas, Kota Medan",
        phone: "0853-5898-5155",
        image: "/images/tenant/logo-hawais.png",
        produkImage: "/images/tenant/produk-hawais.jpg",
    },
    {
        id: 4,
        name: "Cosmic Foodies",
        kategori: "Makanan",
        product: "Choux Au Craquellin",
        owner: "Cut Raqiqha Az Zahra",
        address:
            "Jln. Suka Sabar No.13B STM, Kel. Suka Maju, Kec. Medan Johor, Kota Medan",
        phone: "0853-5898-5155",
        image: "/images/tenant/logo-cosmic.png",
        produkImage: "/images/tenant/produk-cosmic.jpg",
    },
]);
const selectedTenant = ref(null);
const showTenantModal = ref(false);

const openTenantDetail = (tenant) => {
    selectedTenant.value = tenant;
    showTenantModal.value = true;
};

const closeTenantModal = () => {
    showTenantModal.value = false;
    selectedTenant.value = null;
};

// --- LOGIKA SCROLL ---
const scrollToProfile = () => {
    const element = document.getElementById("company-profile");
    element.scrollIntoView({ behavior: "smooth" });
};

// --- BACK TO TOP ---
const showBackToTop = ref(false);

const handleScroll = () => {
    showBackToTop.value = window.scrollY > 500;
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
};

// --- DARK MODE TOGGLE ---
const isDarkMode = ref(false);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    const html = document.querySelector("html");
    if (isDarkMode.value) {
        html.classList.add("dark");
        localStorage.setItem("theme", "dark");
    } else {
        html.classList.remove("dark");
        localStorage.setItem("theme", "light");
    }
};

const initTheme = () => {
    const savedTheme = localStorage.getItem("theme");
    const html = document.querySelector("html");

    // Default ke light mode
    if (savedTheme === "dark") {
        isDarkMode.value = true;
        html.classList.add("dark");
    } else {
        isDarkMode.value = false;
        html.classList.remove("dark");
        localStorage.setItem("theme", "light");
    }
};

onMounted(() => {
    updateCountdown();
    timer = setInterval(updateCountdown, 1000);
    window.addEventListener("scroll", handleScroll);
    initTheme();
});

onUnmounted(() => {
    clearInterval(timer);
    window.removeEventListener("scroll", handleScroll);
});

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
    hours.value = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
    );
    minutes.value = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    seconds.value = Math.floor((distance % (1000 * 60)) / 1000);
};
</script>

<template>
    <Head title="Pendaftaran Pelatihan" />

    <div
        class="relative min-h-screen bg-emerald-50 dark:bg-zinc-950 flex flex-col items-center justify-center overflow-hidden transition-colors duration-300"
    >
        <div class="absolute inset-0 z-0">
            <div
                class="absolute top-0 -left-4 w-72 h-72 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"
            ></div>
            <div
                class="absolute top-0 -right-4 w-72 h-72 bg-teal-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"
            ></div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-green-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"
            ></div>
        </div>

        <div class="relative z-10 w-full max-w-4xl px-6 mb-12">
            <div class="text-center mb-12">
                <h1
                    class="text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 tracking-tight"
                >
                    Pendaftaran Pelatihan
                    <span class="text-emerald-600 dark:text-emerald-400"
                        >Inkubator Bisnis</span
                    >
                </h1>
                <p
                    v-if="isBuka"
                    class="text-lg text-slate-600 dark:text-slate-400"
                >
                    Segera daftarkan diri Anda sebelum pendaftaran ditutup!
                </p>
                <p v-else class="text-lg text-slate-600 dark:text-slate-400">
                    Akan segera dibuka dalam waktu:
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 mb-12">
                <div
                    class="flex flex-col items-center p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800"
                >
                    <span
                        class="text-5xl md:text-6xl font-black text-emerald-600 dark:text-emerald-400"
                        >{{ days }}</span
                    >
                    <span
                        class="text-sm uppercase tracking-widest font-bold text-slate-500 mt-2"
                        >Hari</span
                    >
                </div>
                <div
                    class="flex flex-col items-center p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800"
                >
                    <span
                        class="text-5xl md:text-6xl font-black text-emerald-600 dark:text-emerald-400"
                        >{{ hours }}</span
                    >
                    <span
                        class="text-sm uppercase tracking-widest font-bold text-slate-500 mt-2"
                        >Jam</span
                    >
                </div>
                <div
                    class="flex flex-col items-center p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800"
                >
                    <span
                        class="text-5xl md:text-6xl font-black text-emerald-600 dark:text-emerald-400"
                        >{{ minutes }}</span
                    >
                    <span
                        class="text-sm uppercase tracking-widest font-bold text-slate-500 mt-2"
                        >Menit</span
                    >
                </div>
                <div
                    class="flex flex-col items-center p-6 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800"
                >
                    <span
                        class="text-5xl md:text-6xl font-black text-red-500 animate-pulse"
                        >{{ seconds }}</span
                    >
                    <span
                        class="text-sm uppercase tracking-widest font-bold text-slate-500 mt-2"
                        >Detik</span
                    >
                </div>
            </div>

            <div
                class="flex flex-col sm:flex-row items-center justify-center gap-4"
            >
                <template v-if="canLogin">
                    <!-- tombol daftar -->
                    <Link 
                        :href="route('form-pendaftaran', programOpen.id)" 
                        class="w-full sm:w-auto px-10 py-4 bg-blue-600 hover:bg-emerald-700 text-white font-bold rounded-xl"
                    >
                        Daftar Sekarang
                    </Link>

                    <!-- kalau login & bukan user -->
                    <Link
                        v-if="
                            $page.props.auth.user &&
                            $page.props.auth.user.role !== 'user'
                        "
                        :href="route('dashboard')"
                        class="w-full sm:w-auto px-10 py-4 bg-white text-slate-800 font-bold rounded-xl border hover:bg-slate-200 hover:border-slate-300 transition-all duration-300"
                    >
                        Masuk ke Dashboard
                    </Link>

                    <!-- kalau belum login -->
                    <Link
                        v-else-if="!$page.props.auth.user"
                        :href="route('login')"
                        class="w-full sm:w-auto px-10 py-4 bg-white text-slate-800 font-bold rounded-xl border hover:bg-slate-200 hover:border-slate-300 transition-all duration-300"
                    >
                        Log in
                    </Link>
                </template>
            </div>
        </div>

        <button
            @click="scrollToProfile"
            class="absolute bottom-5 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-slate-400 hover:text-emerald-500 transition-colors group"
        >
            <span class="text-xs uppercase tracking-widest font-bold"
                >Profil BDI Medan</span
            >
            <svg
                class="w-6 h-6 animate-bounce"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 13l-7 7-7-7m14-8l-7 7-7-7"
                ></path>
            </svg>
        </button>
    </div>

    <!-- Dark Mode Toggle -->
    <button
        @click="toggleDarkMode"
        class="fixed top-6 right-6 z-50 w-12 h-12 bg-white dark:bg-zinc-800 rounded-full shadow-lg border border-slate-200 dark:border-zinc-700 flex items-center justify-center hover:scale-110 transition-all duration-300"
        aria-label="Toggle dark mode"
    >
        <svg
            v-if="!isDarkMode"
            class="w-6 h-6 text-amber-500"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
            />
        </svg>
        <svg
            v-else
            class="w-6 h-6 text-slate-300"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
            />
        </svg>
    </button>

    <nav v-if="$page.props.auth.user" class="absolute top-0 right-0 p-6 z-40">
        <div class="relative">
            <button
                @click="isDropdownOpen = !isDropdownOpen"
                class="flex items-center gap-3 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md px-4 py-2 rounded-2xl shadow-sm border border-slate-200 dark:border-zinc-800 hover:border-emerald-400 transition-all group"
            >
                <div class="flex flex-col text-right hidden sm:block">
                    <span
                        class="text-xs font-bold text-slate-500 uppercase tracking-widest"
                        >Halo,</span
                    >
                    <span
                        class="text-sm font-bold text-slate-900 dark:text-white"
                        >{{ $page.props.auth.user.name }}</span
                    >
                </div>
                <div
                    class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold shadow-lg shadow-emerald-200 dark:shadow-none uppercase"
                >
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
                <div
                    v-if="isDropdownOpen"
                    class="absolute right-0 mt-3 w-48 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800 py-2 z-50"
                >
                    <Link
                        :href="route('profile.edit')"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-zinc-800 transition-colors"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                            />
                        </svg>
                        Edit Profil
                    </Link>

                    <div
                        class="h-px bg-slate-100 dark:bg-zinc-800 my-1 mx-2"
                    ></div>

                    <button
                        @click="logout"
                        class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>
                        Keluar
                    </button>
                </div>
            </transition>
        </div>
    </nav>

    <!-- Inkubator Bisnis Section -->
    <div
        id="company-profile"
        class="bg-white dark:bg-zinc-900 py-16 px-6 relative"
    >
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-16">
                <span
                    class="text-emerald-600 font-semibold tracking-wider uppercase text-sm"
                    >Program Unggulan</span
                >
                <h2
                    class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mt-2 mb-4"
                >
                    Inkubator
                    <span class="text-emerald-600">Bisnis BDI Medan</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Program pengembangan wirausaha yang komprehensif untuk
                    membantu calon entrepreneur mengubah ide menjadi bisnis yang
                    sustainable dan profitable
                </p>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col lg:flex-row items-center gap-8">
                <div class="w-full lg:w-1/2">
                    <div class="relative">
                        <div
                            class="absolute -inset-4 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-3xl opacity-20 blur-lg"
                        ></div>
                        <div
                            class="relative h-[25rem] bg-slate-200 dark:bg-zinc-800 rounded-3xl overflow-hidden shadow-2xl"
                        >
                            <div
                                class="flex items-center justify-center h-full text-slate-400 flex-col gap-4"
                            >
                                <svg
                                    class="w-20 h-20 text-slate-300"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                    />
                                </svg>
                                <span class="text-lg font-medium"
                                    >Inkubator Bisnis BDI Medan</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 space-y-6">
                    <p
                        class="text-slate-600 dark:text-slate-400 leading-relaxed text-lg text-justify"
                    >
                        <strong class="text-slate-900 dark:text-white"
                            >Inkubator Bisnis BDI Medan</strong
                        >
                        adalah program unggulan yang dirancang khusus untuk
                        mengakselerasi pertumbuhan startup dan UMKM di sektor
                        pangan, kreatif, dan teknologi. Dengan kurikulum
                        berbasis experiential learning dan pendampingan intensif
                        selama 6-12 bulan.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div
                            class="p-4 bg-emerald-50 dark:bg-zinc-800 rounded-2xl border border-emerald-100 dark:border-zinc-700"
                        >
                            <div
                                class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center mb-3"
                            >
                                <svg
                                    class="w-6 h-6 text-teal-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"
                                    />
                                </svg>
                            </div>
                            <h4
                                class="font-bold text-slate-900 dark:text-white mb-2"
                            >
                                Target Program
                            </h4>
                            <p class="text-sm text-slate-500 text-justify">
                                Memberikan pendampingan intensif dan eksklusif
                                selama 1 tahun untuk tenant terpilih, mulai dari
                                standarisasi produk, legalitas, hingga uji
                                pemasaran.
                            </p>
                        </div>
                        <div
                            class="p-4 bg-emerald-50 dark:bg-zinc-800 rounded-2xl border border-emerald-100 dark:border-zinc-700"
                        >
                            <div
                                class="w-10 h-10 bg-teal-100 dark:bg-teal-900/30 rounded-xl flex items-center justify-center mb-3"
                            >
                                <svg
                                    class="w-6 h-6 text-teal-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                            <h4
                                class="font-bold text-slate-900 dark:text-white mb-2"
                            >
                                Keberlanjutan Alumni
                            </h4>
                            <p class="text-sm text-slate-500 text-justify">
                                Lulusan inkubator terus didukung pasca-program
                                melalui monitoring berkala dan fasilitasi
                                pameran untuk memastikan bisnis tetap tumbuh.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <div
                            class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400"
                        >
                            <svg
                                class="w-5 h-5 text-emerald-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span>Mentoring Bisnis 1-on-1</span>
                        </div>
                        <div
                            class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400"
                        >
                            <svg
                                class="w-5 h-5 text-emerald-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span>Akses Pasar & Pendanaan</span>
                        </div>
                        <div
                            class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400"
                        >
                            <svg
                                class="w-5 h-5 text-emerald-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>
                            <span>Legalitas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tahapan Inkubasi Section -->
    <div class="bg-emerald-50 dark:bg-zinc-950 py-16 px-6 relative">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span
                    class="text-emerald-600 font-semibold tracking-wider uppercase text-sm"
                    >Proses</span
                >
                <h2
                    class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mt-2 mb-4"
                >
                    Tahapan
                    <span class="text-emerald-600">Inkubator Bisnis</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Program inkubasi bisnis kami terdiri dari 3 tahapan utama
                    untuk memastikan kesuksesan tenant
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    v-for="(stage, index) in stages"
                    :key="stage.id"
                    class="relative group"
                >
                    <!-- Connector Line -->
                    <div
                        v-if="index < stages.length - 1"
                        class="hidden md:block absolute top-10 left-full w-full h-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 z-0"
                    ></div>

                    <div
                        class="relative z-10 bg-white dark:bg-zinc-900 rounded-2xl p-6 shadow-lg border border-emerald-100 dark:border-zinc-800 hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                    >
                        <div
                            class="absolute -top-5 left-6 w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg"
                        >
                            {{ index + 1 }}
                        </div>
                        <div class="mt-4">
                            <div
                                class="w-12 h-12 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl flex items-center justify-center mb-4"
                            >
                                <svg
                                    class="w-6 h-6 text-emerald-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        :d="stage.icon"
                                    />
                                </svg>
                            </div>
                            <h3
                                class="text-lg font-bold text-slate-900 dark:text-white mb-2"
                            >
                                {{ stage.title }}
                            </h3>
                            <p
                                class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed line-clamp-3"
                            >
                                {{ stage.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="bg-white dark:bg-zinc-900 py-16 px-6 relative">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span
                    class="text-emerald-600 font-semibold tracking-wider uppercase text-sm"
                    >Keuntungan</span
                >
                <h2
                    class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mt-2 mb-4"
                >
                    Benefit <span class="text-emerald-600">Calon Tenant</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Bergabung dengan program inkubasi BDI Medan dan dapatkan
                    berbagai manfaat eksklusif
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div
                    v-for="benefit in benefits"
                    :key="benefit.title"
                    class="group p-5 bg-emerald-50 dark:bg-zinc-800 rounded-2xl border border-emerald-100 dark:border-zinc-700 hover:border-emerald-300 dark:hover:border-emerald-700 transition-all duration-300 hover:shadow-lg"
                >
                    <div
                        class="w-11 h-11 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform"
                    >
                        <svg
                            class="w-5 h-5 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                :d="benefit.icon"
                            />
                        </svg>
                    </div>
                    <h3
                        class="text-base font-bold text-slate-900 dark:text-white mb-2"
                    >
                        {{ benefit.title }}
                    </h3>
                    <p
                        class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed line-clamp-2"
                    >
                        {{ benefit.description }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tenant Section -->
    <div class="bg-emerald-50 dark:bg-zinc-950 py-16 px-6 relative">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-12">
                <span
                    class="text-emerald-600 font-semibold tracking-wider uppercase text-sm"
                    >Tenant Kami</span
                >
                <h2
                    class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mt-2 mb-4"
                >
                    Inkubator <span class="text-emerald-600">Bisnis</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Berbagai tenant yang telah bergabung dan berkembang bersama
                    BDI Medan
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div
                    v-for="tenant in tenants"
                    :key="tenant.id"
                    class="group bg-white dark:bg-zinc-900 rounded-2xl overflow-hidden shadow-md border border-emerald-100 dark:border-zinc-800 hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                >
                    <div
                        class="h-36 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 flex items-center justify-center"
                    >
                        <div
                            class="w-16 h-16 bg-white dark:bg-zinc-800 rounded-xl shadow-md flex items-center justify-center"
                        >
                            <span class="text-2xl font-bold text-emerald-600">{{
                                tenant.name.charAt(0)
                            }}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <span
                            class="text-xs font-semibold text-emerald-600 uppercase tracking-wider"
                            >{{ tenant.product }}</span
                        >
                        <h3
                            class="text-base font-bold text-slate-900 dark:text-white mt-1 mb-2"
                        >
                            {{ tenant.name }}
                        </h3>
                        <p
                            class="text-slate-600 dark:text-slate-400 text-sm mb-3 line-clamp-2"
                        >
                            {{ tenant.description }}
                        </p>
                        <button
                            @click="openTenantDetail(tenant)"
                            class="w-full py-2.5 px-3 bg-emerald-100 dark:bg-zinc-800 hover:bg-emerald-200 dark:hover:bg-emerald-900/20 text-emerald-700 dark:text-slate-300 hover:text-emerald-800 dark:hover:text-emerald-400 font-semibold rounded-lg transition-colors flex items-center justify-center gap-2 text-sm"
                        >
                            <span>Lihat Detail</span>
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 5l7 7-7 7"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-12 text-center">
                <p class="text-slate-600 dark:text-slate-400 mb-6">
                    Tertarik untuk bergabung dengan program inkubasi kami?
                </p>
                <Link
                    v-if="canLogin"
                    :href="route('form-pendaftaran')"
                    class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all"
                >
                    <span>Daftar Sekarang</span>
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 8l4 4m0 0l-4 4m4-4H3"
                        />
                    </svg>
                </Link>
            </div>
        </div>
    </div>

    <!-- Tenant Detail Modal -->
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="showTenantModal"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
            @click="closeTenantModal"
        >
            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 scale-100"
                leave-to-class="opacity-0 scale-95"
            >
                <div
                    v-if="selectedTenant"
                    class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
                    @click.stop
                >
                    <!-- Header -->
                    <div
                        class="relative h-32 bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center"
                    >
                        <div
                            class="relative w-20 h-20 bg-white rounded-xl shadow-lg flex items-center justify-center overflow-hidden"
                        >
                            <img
                                v-if="selectedTenant.image"
                                :src="selectedTenant.image"
                                class="w-full h-full object-cover"
                                alt="Logo"
                            />
                            <span
                                v-else
                                class="text-3xl font-bold text-emerald-600"
                                >{{ selectedTenant.name.charAt(0) }}</span
                            >
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Kategori Badge -->
                        <span
                            class="inline-block px-3 py-1 text-xs font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-100 dark:bg-emerald-900/30 rounded-full mb-3"
                            >{{ selectedTenant.kategori }}</span
                        >

                        <!-- Nama Tenant -->
                        <h3
                            class="text-xl font-bold text-slate-900 dark:text-white mb-1"
                        >
                            {{ selectedTenant.name }}
                        </h3>

                        <!-- Produk -->
                        <p
                            class="text-emerald-600 dark:text-emerald-400 font-medium mb-4"
                        >
                            {{ selectedTenant.product }}
                        </p>

                        <!-- Info Grid -->
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="w-8 h-8 bg-emerald-50 dark:bg-zinc-800 rounded-lg flex items-center justify-center flex-shrink-0"
                                >
                                    <svg
                                        class="w-4 h-4 text-emerald-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                </div>
                                <span
                                    class="text-slate-600 dark:text-slate-400"
                                    >{{ selectedTenant.owner }}</span
                                >
                            </div>
                            <div class="flex items-start gap-3 text-sm">
                                <div
                                    class="w-8 h-8 bg-emerald-50 dark:bg-zinc-800 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5"
                                >
                                    <svg
                                        class="w-4 h-4 text-emerald-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                    </svg>
                                </div>
                                <span
                                    class="text-slate-600 dark:text-slate-400 leading-relaxed"
                                    >{{ selectedTenant.address }}</span
                                >
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div
                                    class="w-8 h-8 bg-emerald-50 dark:bg-zinc-800 rounded-lg flex items-center justify-center flex-shrink-0"
                                >
                                    <svg
                                        class="w-4 h-4 text-emerald-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                        />
                                    </svg>
                                </div>
                                <span
                                    class="text-slate-600 dark:text-slate-400"
                                    >{{ selectedTenant.phone }}</span
                                >
                            </div>
                        </div>

                        <!-- Gambar Produk -->
                        <div
                            v-if="selectedTenant.produkImage"
                            class="aspect-video bg-slate-100 dark:bg-zinc-800 rounded-xl overflow-hidden mb-4"
                        >
                            <img
                                :src="selectedTenant.produkImage"
                                class="w-full h-full object-cover"
                                alt="Produk"
                            />
                        </div>

                        <!-- Tombol -->
                        <div class="flex gap-3">
                            <button
                                @click="closeTenantModal"
                                class="flex-1 py-2.5 px-4 bg-slate-100 dark:bg-zinc-800 hover:bg-slate-200 dark:hover:bg-zinc-700 text-slate-700 dark:text-slate-300 font-semibold rounded-lg transition-colors text-sm"
                            >
                                Tutup
                            </button>
                            <Link
                                v-if="canLogin"
                                :href="route('form-pendaftaran')"
                                class="flex-1 py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors text-center text-sm"
                            >
                                Daftar Juga
                            </Link>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </transition>

    <!-- Back to Top Button -->
    <transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="opacity-0 translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-4"
    >
        <button
            class="fixed bottom-8 right-8 z-50 w-12 h-12 bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white rounded-full shadow-lg hover:shadow-xl transition-all flex items-center justify-center group"
            aria-label="Back to top"
        >
            <svg
                class="w-6 h-6 group-hover:-translate-y-0.5 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5 10l7-7m0 0l7 7m-7-7v18"
                />
            </svg>
        </button>
    </transition>

    <!-- Footer -->
    <footer
        class="bg-white dark:bg-zinc-900 py-8 px-6 border-t border-emerald-100 dark:border-zinc-800"
    >
        <div class="max-w-6xl mx-auto">
            <div
                class="flex flex-col md:flex-row items-center justify-between gap-6"
            >
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center text-white font-bold text-xl"
                    >
                        BDI
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white">
                            BDI Medan
                        </h4>
                        <p class="text-sm text-slate-500">
                            Balai Diklat Industri Medan
                        </p>
                    </div>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-slate-400 text-xs mt-1">
                        © 2026 Kementerian Perindustrian Republik Indonesia
                    </p>
                </div>
            </div>
        </div>
    </footer>
</template>

<style>
/* CSS Kamu yang lama tetap dipertahankan */
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }
    33% {
        transform: translate(30px, -50px) scale(1.1);
    }
    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }
    100% {
        transform: translate(0px, 0px) scale(1);
    }
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
