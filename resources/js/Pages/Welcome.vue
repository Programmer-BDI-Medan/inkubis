<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, onMounted, onUnmounted } from "vue";

const isDropdownOpen = ref(false);
const logout = () => {
    router.post(route("logout"));
};

// Refs for sections visibility (for smooth transitions)
const statsVisible = ref(false);
const companyProfileVisible = ref(false);
const programsVisible = ref(false);
const stagesVisible = ref(false);
const tenantsVisible = ref(false);
const benefitsVisible = ref(false);
const testimonialsVisible = ref(false);
const partnersVisible = ref(false);
const faqVisible = ref(false);

// Static data for statistics
const statistics = [
    { value: "150+", label: "Tenant Binaan", icon: "users" },
    { value: "85%", label: "Tingkat Keberhasilan", icon: "chart" },
    { value: "5+", label: "Tahun Pengalaman", icon: "star" },
    { value: "50+", label: "Mitra Bisnis", icon: "handshake" },
];

// Static data for programs
const programs = [
    {
        id: 1,
        nama_program: "Inkubasi Bisnis Reguler",
        deskripsi: "Program inkubasi selama 6-12 bulan dengan pendampingan intensif untuk bisnis yang sedang dalam tahap pengembangan.",
        duration: "6-12 Bulan",
        level: "Pemula - Menengah",
        features: ["Pendampingan Mentor", "Akses Permodalan", "Fasilitas Ruang Kerja"],
    },
    {
        id: 2,
        nama_program: "Pra-Inkubasi Startup",
        deskripsi: "Program intensif untuk membantu pengusaha memvalidasi ide bisnis dan membuat rencana bisnis yang matang.",
        duration: "3-6 Bulan",
        level: "Pemula",
        features: ["Validasi Ide Bisnis", "Penyusunan Business Plan", "Workshop Pelaku Usaha"],
    },
    {
        id: 3,
        nama_program: "Akselerasi Bisnis",
        program akselerasi untuk tenant yang sudah melewati fase inkubasi dan siap untuk scale-up bisnisnya.",
        duration: "3-6 Bulan",
        level: "Menengah - Lanjutan",
        features: ["Akses Investor", "Ekspansi Pasar", "Networking Nasional"],
    },
];

// Static data for stages (Tahapan Inkubasi)
const stages = [
    {
        id: 1,
        tahapan_inkubasi: "Seleksi & Onboarding",
        deskripsi:
            "Proses seleksi ketat untuk memilih calon tenant berpotensi tinggi, dilanjutkan dengan orientasi dan pengenalan program.",
    },
    {
        id: 2,
        tahapan_inkubasi: "Pra-Inkubasi",
        deskripsi:
            "Fase pengembangan konsep bisnis, validasi ide, dan penyusunan rencana bisnis yang matang.",
    },
    {
        id: 3,
        tahapan_inkubasi: "Inkubasi Inti",
        deskripsi:
            "Pendampingan intensif meliputi pengembangan produk, strategi pemasaran, dan manajemen keuangan.",
    },
    {
        id: 4,
        tahapan_inkubasi: "Akselerasi",
        deskripsi:
            "Fase pertumbuhan pesat dengan akses ke jaringan investor, partner bisnis, dan pasar yang lebih luas.",
    },
    {
        id: 5,
        tahapan_inkubasi: "Graduasi",
        deskripsi:
            "Tenant siap mandiri dengan bisnis yang berkembang dan berkelanjutan.",
    },
];

// Static data for tenants (Tenant Kami)
const tenants = [
    {
        id: 1,
        nama_tenant: "Bakso Mang Engkos",
        deskripsi:
            "Usaha bakso tradisional dengan inovasi rasa khas Sumatera Utara yang telah berkembang pesat.",
        achievement: "Omzet naik 300% dalam 1 tahun",
    },
    {
        id: 2,
        nama_tenant: "Kopi Gayo Organic",
        deskripsi:
            "Produk kopi organik dari Aceh yang mengekspor ke berbagai negara.",
        achievement: "Ekspor ke 5 negara",
    },
    {
        id: 3,
        nama_tenant: "Batik Medan Art",
        deskripsi:
            "Batik dengan motif khas Sumatera Utara yang memadukan modern dan tradisional.",
        achievement: "Pemenang Festival Batik Nasional",
    },
    {
        id: 4,
        nama_tenant: "Kue Bolu Meranti",
        deskripsi:
            "Kue tradisional khas Medan yang telah menjadi oleh-oleh wajib wisatawan.",
        achievement: "Brand lokal favorit tourist",
    },
];

// Static data for benefits (Benefit untuk Calon Tenant)
const benefits = [
    {
        icon: "education",
        title: "Pendidikan & Pelatihan",
        description:
            "Akses ke berbagai pelatihan bisnis, workshop, dan seminar yang disesuaikan dengan kebutuhan usaha Anda.",
    },
    {
        icon: "mentor",
        title: "Pendampingan Mentor",
        description:
            "Bimbingan langsung dari mentor berpengalaman dan ahli di bidangnya untuk pengembangan bisnis.",
    },
    {
        icon: "network",
        title: "Jaringan Bisnis",
        description:
            "Kesempatan memperluas jaringan dengan tenant lain, investor, dan mitra bisnis potensial.",
    },
    {
        icon: "capital",
        title: "Akses Permodalan",
        description:
            "Bantuan akses ke berbagai sumber pendanaan dan investor yang tertarik dengan bisnis Anda.",
    },
    {
        icon: "workspace",
        title: "Fasilitas Ruang Kerja",
        description:
            "Penyediaan ruang kerja, fasilitas meeting, dan infrastruktur pendukung lainnya.",
    },
    {
        icon: "market",
        title: "Akses Pasar",
        description:
            "Bantuan promosi dan akses ke berbagai event pameran untuk memperkenalkan produk Anda.",
    },
];

// Static data for testimonials
const testimonials = [
    {
        id: 1,
        nama: "Budi Santoso",
        usaha: "Bakso Mang Engkos",
        testimonial: "Inkubator Bisnis membantu saya mengubah usaha keluarga menjadi brand yang diakui. Pendampingan yang diberikan sangat komprehensif.",
        rating: 5,
    },
    {
        id: 2,
        nama: "Siti Rahayu",
        usaha: "Kopi Gayo Organic",
        testimonial: "Berkat program inkubasi, produk kopi kami berhasil ekspor ke luar negeri. Fasilitas dan jaringan yang diberikan luar biasa!",
        rating: 5,
    },
    {
        id: 3,
        nama: "Ahmad Wijaya",
        usaha: "Batik Medan Art",
        testimonial: "Mentor yang berpengalaman sangat membantu mengembangkan desain dan strategi pemasaran kami. Sangat direkomendasikan!",
        rating: 5,
    },
];

// Static data for partners
const partners = [
    { id: 1, nama: "Bank Indonesia", logo: "BI" },
    { id: 2, nama: "Kementrian UKM", logo: "KEMENKRAF" },
    { id: 3, nama: "Dinas Perdagangan", logo: "DISPERINDAG" },
    { id: 4, nama: "Universitas Negeri Medan", logo: "UNIMED" },
    { id: 5, nama: "Asosiasi Pengusaha", logo: "APINDO" },
    { id: 6, nama: "Bank BPD", logo: "BPD" },
];

// Static data for FAQ
const faqs = [
    {
        question: "Apa persyaratan untuk menjadi tenant?",
        answer: "Persyaratan utama adalah memiliki usaha yang produktif, berusia minimal 21 tahun, dan berkomitmen mengikuti program inkubasi secara penuh.Seleksi meliputi administratif, wawancara, dan presentasi konsep bisnis.",
    },
    {
        question: "Berapa lama program inkubasi berlangsung?",
        answer: "Program inkubasi reguler berlangsung selama 6-12 bulan, tergantung pada kebutuhan dan tahap развития bisnis Anda. Untuk pra-inkubasi, durasinya 3-6 bulan.",
    },
    {
        question: "Apakah ada biaya untuk mengikuti program?",
        answer: "Program inkubasi kami bersifat gratis untuk tenant yang lulus seleksi. Namun, ada biaya registrasi yang sangat terjangkau untuk menutupi kebutuhan administrasi.",
    },
    {
        question: "Apa saja fasilitas yang diberikan?",
        answer: "Tenant akan mendapatkan akses ke ruang kerja, mentoring oleh ahli, jaringan bisnis, pelatihan bisnis, akses permodalan, dan promosi di event-event kami.",
    },
    {
        question: "Bagaimana proses pendaftarannya?",
        answer: "Proses pendaftaran meliputi: 1) Isi formulir online, 2) Submit dokumen persyaratan, 3) Wawancara, 4) Presentasi konsep bisnis, 5) Pengumuman hasil seleksi.",
    },
];

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    programOpen: Object,
});

// --- LOGIKA SCROLL ---
const scrollToStats = () => {
    const element = document.getElementById("statistik");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

const scrollToProfile = () => {
    const element = document.getElementById("company-profile");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

const scrollToPrograms = () => {
    const element = document.getElementById("program-pelatihan");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

const scrollToStages = () => {
    const element = document.getElementById("tahapan-inkubasi");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

const scrollToTenants = () => {
    const element = document.getElementById("tenant-kami");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

const scrollToBenefits = () => {
    const element = document.getElementById("benefit-calon-tenant");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

const scrollToTestimonials = () => {
    const element = document.getElementById("testimoni");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

const scrollToPartners = () => {
    const element = document.getElementById("mitra");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

const scrollToFaq = () => {
    const element = document.getElementById("faq");
    if (element) element.scrollIntoView({ behavior: "smooth" });
};

// --- FAQ Toggle ---
const openFaqs = ref([]);
const toggleFaq = (id) => {
    if (openFaqs.value.includes(id)) {
        openFaqs.value = openFaqs.value.filter((i) => i !== id);
    } else {
        openFaqs.value.push(id);
    }
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
    hours.value = Math.floor(
        (distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60),
    );
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
    <Head title="Pendaftaran Pelatihan - Inkubator Bisnis" />

    <!-- Hero Section -->
    <div
        class="relative min-h-screen bg-slate-50 dark:bg-zinc-950 flex flex-col items-center justify-center overflow-hidden"
    >
        <!-- Animated Background -->
        <div class="absolute inset-0 z-0">
            <div
                class="absolute top-0 -left-4 w-72 h-72 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"
            ></div>
            <div
                class="absolute top-0 -right-4 w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"
            ></div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"
            ></div>
        </div>

        <div class="relative z-10 w-full max-w-5xl px-6 py-16">
            <!-- Hero Content -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 rounded-full mb-6">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                    <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Inkubator Bisnis Terpercaya di Sumatera Utara</span>
                </div>
                
                <h1
                    class="text-4xl md:text-6xl font-extrabold text-slate-900 dark:text-white mb-6 tracking-tight"
                >
                    Wujudkan Bisnis <br />
                    <span class="text-blue-600 dark:text-blue-400">Impian Anda</span> Bersama Kami
                </h1>
                <p
                    class="text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-3xl mx-auto mb-8"
                >
                    Bergabunglah dengan program inkubasi bisnis terbaik yang telah membantu 
                    <span class="font-semibold text-blue-600">150+ pengusaha</span> mengembangkan 
                    usaha mereka menjadi bisnis yang mandiri dan berdaya saing.
                </p>
            </div>

            <!-- Countdown Timer -->
            <div class="bg-white/80 dark:bg-zinc-900/80 backdrop-blur-md rounded-3xl p-8 shadow-2xl border border-slate-200 dark:border-zinc-800 mb-8">
                <div class="text-center mb-6">
                    <p v-if="isBuka" class="text-lg font-semibold text-slate-700 dark:text-slate-300">
                        📢 Pendaftaran Sedang Dibuka! Segera Daftar Sebelum Tutup
                    </p>
                    <p v-else class="text-lg font-semibold text-slate-700 dark:text-slate-300">
                        ⏰ Pendaftaran Dibuka Dalam:
                    </p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-900 rounded-2xl">
                        <span class="text-4xl md:text-5xl font-black text-blue-600 dark:text-blue-400">{{ days }}</span>
                        <span class="text-xs uppercase tracking-widest font-bold text-slate-500 mt-1">Hari</span>
                    </div>
                    <div class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-900 rounded-2xl">
                        <span class="text-4xl md:text-5xl font-black text-blue-600 dark:text-blue-400">{{ hours }}</span>
                        <span class="text-xs uppercase tracking-widest font-bold text-slate-500 mt-1">Jam</span>
                    </div>
                    <div class="flex flex-col items-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-900 rounded-2xl">
                        <span class="text-4xl md:text-5xl font-black text-blue-600 dark:text-blue-400">{{ minutes }}</span>
                        <span class="text-xs uppercase tracking-widest font-bold text-slate-500 mt-1">Menit</span>
                    </div>
                    <div class="flex flex-col items-center p-4 bg-gradient-to-br from-red-50 to-orange-50 dark:from-zinc-800 dark:to-zinc-900 rounded-2xl">
                        <span class="text-4xl md:text-5xl font-black text-red-500 animate-pulse">{{ seconds }}</span>
                        <span class="text-xs uppercase tracking-widest font-bold text-slate-500 mt-1">Detik</span>
                    </div>
                </div>
            </div>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <template v-if="canLogin">
                    <Link
                        :href="route('form-pendaftaran')"
                        class="w-full sm:w-auto px-10 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl btn-smooth flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        Daftar Sekarang
                    </Link>

                    <Link
                        v-if="$page.props.auth.user && $page.props.auth.user.role !== 'user'"
                        :href="route('dashboard')"
                        class="w-full sm:w-auto px-10 py-4 bg-white dark:bg-zinc-800 font-bold rounded-xl border border-slate-200 dark:border-zinc-700 hover:border-blue-400 transition-all"
                    >
                        Dashboard
                    </Link>

                    <Link
                        v-else-if="!$page.props.auth.user"
                        :href="route('login')"
                        class="w-full sm:w-auto px-10 py-4 bg-white dark:bg-zinc-800 font-bold rounded-xl border border-slate-200 dark:border-zinc-700 hover:border-blue-400 transition-all"
                    >
                        Login
                    </Link>
                </template>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <button
            @click="scrollToStats"
            class="absolute bottom-5 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-slate-400 hover:text-blue-500 transition-colors group"
        >
            <span class="text-xs uppercase tracking-widest font-bold">Lihat Profil Kami</span>
            <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"></path>
            </svg>
        </button>
    </div>

    <!-- User Dropdown (if logged in) -->
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

            <transition enter-active-class="transition ease-out duration-200" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
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

    <!-- Statistics Section -->
    <div id="statistik" class="bg-gradient-to-r from-blue-600 to-indigo-700 py-16 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div v-for="stat in statistics" :key="stat.label" class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-2xl mb-4">
                        <svg v-if="stat.icon === 'users'" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <svg v-else-if="stat.icon === 'chart'" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <svg v-else-if="stat.icon === 'star'" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <svg v-else-if="stat.icon === 'handshake'" class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="text-3xl md:text-4xl font-black text-white">{{ stat.value }}</span>
                    <span class="block text-blue-100 text-sm font-medium mt-1">{{ stat.label }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Profile Section -->
    <div id="company-profile" class="bg-white dark:bg-zinc-900 py-20 px-6 relative">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">TENTANG KAMI</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Inkubator Bisnis <span class="text-blue-600">Terbaik</span> Untuk Mengembangkan Usaha Anda
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                    Kami adalah lembaga inkubasi bisnis yang berkomitmen untuk membantu pengusaha muda 
                    mengembangkan potensi bisnis mereka menjadi usaha yang mandiri, berdaya saing, dan berkelanjutan.
                </p>
            </div>

            <!-- Vision & Mission -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-zinc-800 dark:to-zinc-900 p-8 rounded-3xl border border-blue-100 dark:border-zinc-700">
                    <div class="w-14 h-14 bg-blue-600 rounded-2xl flex items-center justify-center text-white mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Visi</h4>
                    <p class="text-slate-600 dark:text-slate-400">
                        Menjadi inkubator bisnis terkemuka yang melahirkan pengusaha sukses dan berdaya saing tinggi, 
                        berkontribusi pada pengembangan ekonomi daerah dan nasional.
                    </p>
                </div>
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-zinc-800 dark:to-zinc-900 p-8 rounded-3xl border border-purple-100 dark:border-zinc-700">
                    <div class="w-14 h-14 bg-purple-600 rounded-2xl flex items-center justify-center text-white mb-4">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Misi</h4>
                    <ul class="text-slate-600 dark:text-slate-400 space-y-2">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Memberikan pendampingan komprehensif untuk mengembangkan potensi bisnis tenant
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Menyediakan akses ke jaringan bisnis, investor, dan pasar
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Membantu transformasi bisnis dari tradisional ke modern
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Why Choose Us -->
            <div class="bg-slate-50 dark:bg-zinc-800 rounded-3xl p-8 md:p-12">
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-8 text-center">Mengapa Memilih Kami?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-2">Pendampingan Ahli</h4>
                        <p class="text-sm text-slate-500">Tim mentor berpengalaman dari berbagai bidang bisnis siap membimbing Anda</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-2">Akses Modal</h4>
                        <p class="text-sm text-slate-500">Hubungan dengan investor dan lembaga pembiayaan terpercaya</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-slate-900 dark:text-white mb-2">Jaringan Luas</h4>
                        <p class="text-sm text-slate-500">Terhubung dengan 50+ mitra bisnis dan jaringan industri</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Programs Section -->
    <div id="program-pelatihan" class="bg-slate-50 dark:bg-zinc-950 py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">PROGRAM KAMI</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Program <span class="text-blue-600">Inkubasi</span> yang Tersedia
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-3xl mx-auto">
                    Kami menyediakan berbagai program inkubasi yang disesuaikan dengan kebutuhan dan tahap развития bisnis Anda.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="program in programs" :key="program.id" class="bg-white dark:bg-zinc-900 rounded-3xl p-8 shadow-xl border border-slate-100 dark:border-zinc-800 hover:border-blue-400 transition-all hover:shadow-2xl group">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center text-white mb-6">
                        <svg v-if="program.id === 1" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <svg v-else-if="program.id === 2" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        <svg v-else class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3 group-hover:text-blue-600 transition-colors">{{ program.nama_program }}</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-sm mb-4">{{ program.deskripsi }}</p>
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-xs font-medium rounded-full">{{ program.duration }}</span>
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs font-medium rounded-full">{{ program.level }}</span>
                    </div>
                    <ul class="space-y-2">
                        <li v-for="feature in program.features" :key="feature" class="flex items-center gap-2 text-sm text-slate-500">
                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ feature }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Tahapan Inkubasi Section -->
    <div id="tahapan-inkubasi" class="bg-white dark:bg-zinc-900 py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">TAHAPAN</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Tahapan <span class="text-blue-600">Inkubasi</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Program inkubasi bisnis kami terdiri dari beberapa tahapan yang dirancang untuk mengembangkan potensi bisnis Anda secara optimal.
                </p>
            </div>

            <div class="relative">
                <!-- Timeline line -->
                <div class="absolute left-1/2 top-0 bottom-0 w-1 bg-gradient-to-b from-blue-500 via-purple-500 to-pink-500 hidden md:block transform -translate-x-1/2"></div>

                <div class="space-y-12">
                    <div v-for="(stage, index) in stages" :key="stage.id" class="relative flex items-center timeline-item" :class="index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse'">
                        <!-- Content -->
                        <div class="w-full md:w-1/2 px-6" :class="index % 2 === 0 ? 'md:text-right' : 'md:text-left'">
                            <div class="p-6 bg-slate-50 dark:bg-zinc-800 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-700">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold text-lg mb-4" :class="index % 2 === 0 ? 'md:float-right md:ml-4' : 'md:float-left md:mr-4'">
                                    {{ index + 1 }}
                                </div>
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                                    {{ stage.tahapan_inkubasi }}
                                </h3>
                                <p class="text-slate-600 dark:text-slate-400 text-sm">
                                    {{ stage.deskripsi }}
                                </p>
                            </div>
                        </div>

                        <!-- Circle on timeline -->
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-4 h-4 bg-blue-500 rounded-full border-4 border-white dark:border-zinc-900 hidden md:block z-10"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits & Tenants Section -->
    <div id="keuntungan-tenant" class="bg-slate-50 dark:bg-zinc-950 py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">KEUNTUNGAN</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Keuntungan Menjadi <span class="text-blue-600">Tenant</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Bergabung dengan program inkubasi bisnis kami memberikan banyak keuntungan untuk mengembangkan bisnis Anda menuju keberhasilan.
                </p>
            </div>

            <!-- Benefits Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
                <div v-for="benefit in benefits" :key="benefit.title" class="p-6 bg-white dark:bg-zinc-900 rounded-2xl border border-slate-100 dark:border-zinc-800 hover:border-blue-400 transition-all hover:shadow-xl group">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white mb-4">
                        <svg v-if="benefit.icon === 'education'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        <svg v-else-if="benefit.icon === 'mentor'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        <svg v-else-if="benefit.icon === 'network'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                        <svg v-else-if="benefit.icon === 'capital'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <svg v-else-if="benefit.icon === 'workspace'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                        <svg v-else-if="benefit.icon === 'market'" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 group-hover:text-blue-600 transition-colors">{{ benefit.title }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">{{ benefit.description }}</p>
                </div>
            </div>

            <!-- Tenant Examples -->
            <div class="mb-12">
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mb-8 text-center">Tenant <span class="text-blue-600">Kami</span></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="tenant in tenants" :key="tenant.id" class="p-5 bg-white dark:bg-zinc-900 rounded-xl border border-slate-200 dark:border-zinc-700 hover:border-blue-400 transition-all hover:shadow-lg group">
                        <h4 class="font-bold text-slate-900 dark:text-white mb-1 group-hover:text-blue-600 transition-colors">{{ tenant.nama_tenant }}</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-2">{{ tenant.deskripsi }}</p>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ tenant.achievement }}
                        </span>
                    </div>
                </div>
                <p class="text-center text-slate-500 dark:text-slate-400 mt-6">Dan masih banyak tenant lainnya...</p>
            </div>

            <div class="text-center">
                <Link :href="route('form-pendaftaran')" class="inline-flex items-center gap-2 px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-all hover:shadow-lg hover:shadow-blue-200">
                    <span>Mari Bergabung dengan Kami</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </Link>
            </div>
        </div>
    </div>

    <!-- Testimonials Section -->
    <div id="testimoni" class="bg-white dark:bg-zinc-900 py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">TESTIMONI</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Kisah Sukses <span class="text-blue-600">Tenant Kami</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Dengarkan pengalaman langsung dari para tenant yang telah berhasil mengembangkan bisnisnya bersama kami.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="testimonial in testimonials" :key="testimonial.id" class="bg-slate-50 dark:bg-zinc-800 rounded-3xl p-8 relative">
                    <!-- Quote Icon -->
                    <div class="absolute -top-4 left-6 w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                    </div>
                    
                    <!-- Rating -->
                    <div class="flex gap-1 mb-4 mt-4">
                        <svg v-for="i in testimonial.rating" :key="i" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    </div>
                    
                    <p class="text-slate-600 dark:text-slate-400 mb-6 italic">"{{ testimonial.testimonial }}"</p>
                    
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold">
                            {{ testimonial.nama.substring(0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white">{{ testimonial.nama }}</h4>
                            <p class="text-sm text-slate-500">{{ testimonial.usaha }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Partners Section -->
    <div id="mitra" class="bg-slate-50 dark:bg-zinc-950 py-20 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">MITRA KERJASAMA</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Mitra <span class="text-blue-600">Terpercaya</span> Kami
                </h2>
                <p class="text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Kami bekerja sama dengan berbagai institusi dan organisasi terkemuka untuk memberikan yang terbaik bagi tenant kami.
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <div v-for="partner in partners" :key="partner.id" class="bg-white dark:bg-zinc-900 rounded-2xl p-6 flex items-center justify-center border border-slate-200 dark:border-zinc-800 hover:border-blue-400 transition-all hover:shadow-lg">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-zinc-800 dark:to-zinc-700 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <span class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ partner.logo }}</span>
                        </div>
                        <span class="text-xs font-medium text-slate-500">{{ partner.nama }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div id="faq" class="bg-white dark:bg-zinc-900 py-20 px-6">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-semibold rounded-full mb-4">FAQ</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Pertanyaan <span class="text-blue-600">Yang Sering Diajukan</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-400">
                    Temukan jawaban untuk pertanyaan yang paling sering ditanyakan oleh calon tenant.
                </p>
            </div>

            <div class="space-y-4">
                <div v-for="faq in faqs" :key="faq.question" class="bg-slate-50 dark:bg-zinc-800 rounded-2xl overflow-hidden">
                    <button @click="toggleFaq(faq.question)" class="w-full px-6 py-5 flex items-center justify-between text-left">
                        <span class="font-semibold text-slate-900 dark:text-white">{{ faq.question }}</span>
                        <svg class="w-5 h-5 text-slate-500 transform transition-transform" :class="openFaqs.includes(faq.question) ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                        <div v-if="openFaqs.includes(faq.question)" class="px-6 pb-5">
                            <p class="text-slate-600 dark:text-slate-400">{{ faq.answer }}</p>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 py-20 px-6">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Siap Mengembangkan Bisnis Anda?
            </h2>
            <p class="text-blue-100 text-lg mb-8 max-w-2xl mx-auto">
                Jangan lewatkan kesempatan untuk bergabung dengan program inkubasi bisnis terbaik. 
                Daftar sekarang dan wujudkan bisnis impian Anda!
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <Link :href="route('form-pendaftaran')" class="w-full sm:w-auto px-10 py-4 bg-white text-blue-600 font-bold rounded-xl hover:bg-blue-50 transition-all hover:shadow-lg">
                    Daftar Sekarang
                </Link>
                <Link :href="scrollToFaq" class="w-full sm:w-auto px-10 py-4 bg-blue-500 text-white font-bold rounded-xl border border-blue-400 hover:bg-blue-400 transition-all">
                    Hubungi Kami
                </Link>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 px-6">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-1 md:col-span-2">
                    <h3 class="text-xl font-bold text-white mb-4">Inkubator Bisnis</h3>
                    <p class="text-sm mb-4">Membantu pengusaha muda mengembangkan bisnis mereka menjadi usaha yang mandiri dan berdaya saing.</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center hover:bg-blue-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4">Tautan</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#company-profile" class="hover:text-blue-400 transition-colors">Profil Kami</a></li>
                        <li><a href="#program-pelatihan" class="hover:text-blue-400 transition-colors">Program</a></li>
                        <li><a href="#tahapan-inkubasi" class="hover:text-blue-400 transition-colors">Tahapan</a></li>
                        <li><a href="#faq" class="hover:text-blue-400 transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Medan, Sumatera Utara
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            info@inkubatorbisnis.com
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            +62 812 3456 7890
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-sm">
                <p>&copy; {{ new Date().getFullYear() }} Inkubator Bisnis. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>

<style>
/* CSS Animations */
@keyframes blob {
    0% { transform: translate(0px, 0px) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
    100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
    animation: blob 7s infinite;
}
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-4000 { animation-delay: 4s; }

html { scroll-behavior: smooth; }

/* Card hover effects */
.tenant-card, .benefit-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}
.tenant-card:hover, .benefit-card:hover {
    transform: translateY(-5px);
}

/* Button smooth transition */
.btn-smooth {
    transition: all 0.3s ease;
}
.btn-smooth:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
}
</style>
