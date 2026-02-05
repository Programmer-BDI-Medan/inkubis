<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

// Data Dummy yang lebih detail
const stats = [
    {
        title: "Total Tenant",
        value: "124",
        unit: "Startups",
        icon: "M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z",
        color: "text-blue-600",
        bg: "bg-blue-50",
    },
    {
        title: "Menunggu Review",
        value: "12",
        unit: "Permohonan",
        icon: "M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z",
        color: "text-orange-600",
        bg: "bg-orange-50",
    },
    {
        title: "Tenant Aktif",
        value: "45",
        unit: "Incubated",
        icon: "M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z",
        color: "text-emerald-600",
        bg: "bg-emerald-50",
    },
    {
        title: "Total Alumni",
        value: "67",
        unit: "Graduated",
        icon: "M4.26 10.147a60.436 60.436 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.499 5.258 50.55 50.55 0 0 0-2.658.813m-15.482 0A50.55 50.55 0 0 1 12 13.489a50.55 50.55 0 0 1 10.499-3.342",
        color: "text-purple-600",
        bg: "bg-purple-50",
    },
];

const recentApplicants = [
    {
        id: 1,
        name: "Budi Santoso",
        initials: "BS",
        color: "bg-blue-100 text-blue-600",
        startup: "Kripik Masa Depan",
        category: "F&B",
        date: "04 Feb 2026",
        status: "Pending",
    },
    {
        id: 2,
        name: "Siti Aminah",
        initials: "SA",
        color: "bg-pink-100 text-pink-600",
        startup: "Tech Edukasi",
        category: "IT/App",
        date: "03 Feb 2026",
        status: "Pending",
    },
    {
        id: 3,
        name: "Joko Anwar",
        initials: "JA",
        color: "bg-amber-100 text-amber-600",
        startup: "Kopi Robot",
        category: "Teknologi",
        date: "01 Feb 2026",
        status: "Approved",
    },
    {
        id: 4,
        name: "Rina Nose",
        initials: "RN",
        color: "bg-indigo-100 text-indigo-600",
        startup: "Fashion Limbah",
        category: "Kriya",
        date: "30 Jan 2026",
        status: "Rejected",
    },
    {
        id: 5,
        name: "Ahmad Dani",
        initials: "AD",
        color: "bg-teal-100 text-teal-600",
        startup: "Pupuk Organik",
        category: "Agro",
        date: "28 Jan 2026",
        status: "Approved",
    },
];
</script>

<template>
    <Head title="Dashboard Admin" />

    <AuthenticatedLayout>
        <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto space-y-8">
            <div
                class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-teal-600 to-emerald-600 shadow-xl"
            >
                <div
                    class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-white opacity-10 blur-3xl"
                ></div>
                <div
                    class="absolute bottom-0 left-0 -ml-20 -mb-20 w-60 h-60 rounded-full bg-yellow-300 opacity-20 blur-3xl"
                ></div>

                <div
                    class="relative z-10 flex flex-col md:flex-row items-center justify-between p-8 md:p-10"
                >
                    <div class="text-white mb-6 md:mb-0">
                        <div class="flex items-center gap-3 mb-2">
                            <span
                                class="bg-white/20 backdrop-blur-md text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider border border-white/10"
                                >Dashboard Admin</span
                            >
                        </div>
                        <h1 class="text-3xl md:text-4xl font-bold mb-2">
                            Halo, Admin BDI! 👋
                        </h1>
                        <p
                            class="text-teal-100 text-sm md:text-base max-w-xl leading-relaxed"
                        >
                            Ada
                            <span class="font-bold text-white"
                                >12 permohonan tenant baru</span
                            >
                            yang menunggu tinjauan Anda hari ini. Mari bangun
                            ekosistem bisnis yang lebih baik.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <button
                            class="bg-white text-teal-700 hover:bg-teal-50 font-bold py-3 px-6 rounded-xl shadow-lg transition-all transform hover:-translate-y-1 flex items-center gap-2"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="w-5 h-5"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"
                                />
                            </svg>
                            Mulai Review
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div
                    v-for="(stat, index) in stats"
                    :key="index"
                    class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-shadow group"
                >
                    <div class="flex justify-between items-start">
                        <div>
                            <p
                                class="text-xs font-semibold text-gray-400 uppercase tracking-wider"
                            >
                                {{ stat.title }}
                            </p>
                            <h3
                                class="text-3xl font-bold text-gray-800 mt-1 group-hover:text-teal-600 transition-colors"
                            >
                                {{ stat.value }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ stat.unit }}
                            </p>
                        </div>
                        <div class="p-3 rounded-xl" :class="stat.bg">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                stroke="currentColor"
                                class="w-6 h-6"
                                :class="stat.color"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    :d="stat.icon"
                                />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
                    >
                        <div
                            class="p-6 border-b border-gray-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4"
                        >
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">
                                    Pendaftaran Terbaru
                                </h3>
                                <p class="text-sm text-gray-400">
                                    Calon tenant yang menunggu persetujuan.
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <input
                                    type="text"
                                    placeholder="Cari startup..."
                                    class="text-sm border-gray-200 rounded-lg focus:ring-teal-500 focus:border-teal-500 py-2 px-3 bg-gray-50"
                                />
                                <button
                                    class="p-2 border border-gray-200 rounded-lg hover:bg-gray-50 text-gray-600"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-5 h-5"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead
                                    class="text-xs text-gray-400 uppercase bg-gray-50/50 font-semibold"
                                >
                                    <tr>
                                        <th class="px-6 py-4">
                                            Startup / Founder
                                        </th>
                                        <th class="px-6 py-4">Kategori</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    <tr
                                        v-for="item in recentApplicants"
                                        :key="item.id"
                                        class="group hover:bg-gray-50/50 transition-colors"
                                    >
                                        <td class="px-6 py-4">
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <div
                                                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs"
                                                    :class="item.color"
                                                >
                                                    {{ item.initials }}
                                                </div>
                                                <div>
                                                    <div
                                                        class="font-bold text-gray-800 group-hover:text-teal-600 transition-colors"
                                                    >
                                                        {{ item.startup }}
                                                    </div>
                                                    <div
                                                        class="text-xs text-gray-400"
                                                    >
                                                        {{ item.name }} •
                                                        {{ item.date }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-800"
                                            >
                                                {{ item.category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border"
                                                :class="{
                                                    'bg-yellow-50 text-yellow-700 border-yellow-200':
                                                        item.status ===
                                                        'Pending',
                                                    'bg-emerald-50 text-emerald-700 border-emerald-200':
                                                        item.status ===
                                                        'Approved',
                                                    'bg-red-50 text-red-700 border-red-200':
                                                        item.status ===
                                                        'Rejected',
                                                }"
                                            >
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full"
                                                    :class="{
                                                        'bg-yellow-500':
                                                            item.status ===
                                                            'Pending',
                                                        'bg-emerald-500':
                                                            item.status ===
                                                            'Approved',
                                                        'bg-red-500':
                                                            item.status ===
                                                            'Rejected',
                                                    }"
                                                ></span>
                                                {{ item.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <button
                                                v-if="item.status === 'Pending'"
                                                class="text-teal-600 hover:text-teal-800 font-bold text-xs border border-teal-200 hover:border-teal-400 hover:bg-teal-50 px-3 py-1.5 rounded-lg transition-all"
                                            >
                                                Review
                                            </button>
                                            <button
                                                v-else
                                                class="text-gray-400 hover:text-gray-600 font-medium text-xs px-3 py-1.5"
                                            >
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            class="p-4 border-t border-gray-50 bg-gray-50/30 text-center"
                        >
                            <button
                                class="text-xs font-bold text-teal-600 hover:text-teal-800 uppercase tracking-wider"
                            >
                                Lihat Semua Data
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-2xl shadow-lg p-6 text-white relative overflow-hidden group"
                    >
                        <div
                            class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl group-hover:scale-110 transition-transform duration-500"
                        ></div>

                        <div class="relative z-10">
                            <div
                                class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center mb-4"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-6 h-6 text-white"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"
                                    />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold mb-1">
                                Upload Berkas
                            </h3>
                            <p class="text-indigo-100 text-xs mb-4">
                                Unggah dokumen penilaian dan pendampingan
                                tenant.
                            </p>
                            <button
                                class="w-full bg-white text-indigo-600 py-2.5 rounded-lg text-sm font-bold hover:bg-indigo-50 transition-colors shadow-sm"
                            >
                                Upload File
                            </button>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-gray-800">Dokumentasi</h3>
                            <span
                                class="p-2 bg-gray-50 rounded-lg text-gray-400"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 h-5"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                                    />
                                </svg>
                            </span>
                        </div>
                        <div class="space-y-3">
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-teal-50 hover:text-teal-700 transition-colors cursor-pointer group"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center text-xs font-bold"
                                    >
                                        24
                                    </div>
                                    <span class="text-sm font-medium"
                                        >Batch 2024</span
                                    >
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-4 h-4 text-gray-400 group-hover:text-teal-600"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m8.25 4.5 7.5 7.5-7.5 7.5"
                                    />
                                </svg>
                            </div>
                            <div
                                class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-teal-50 hover:text-teal-700 transition-colors cursor-pointer group"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center text-xs font-bold"
                                    >
                                        23
                                    </div>
                                    <span class="text-sm font-medium"
                                        >Batch 2023</span
                                    >
                                </div>
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-4 h-4 text-gray-400 group-hover:text-teal-600"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m8.25 4.5 7.5 7.5-7.5 7.5"
                                    />
                                </svg>
                            </div>
                        </div>
                        <button
                            class="w-full mt-4 text-xs font-bold text-gray-500 hover:text-teal-600 py-2 border border-dashed border-gray-200 rounded-lg"
                        >
                            + Folder Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
