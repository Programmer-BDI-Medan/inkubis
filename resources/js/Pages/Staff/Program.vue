<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, reactive, computed } from "vue";
import { usePage } from '@inertiajs/vue3'

const page = usePage()

// ==========================================
// 1. STATE & CONFIG
// ==========================================
const currentUser = page.props.auth.user.name; 
const props = defineProps({
    program: Object,
});
const activeTab = ref("stages"); // 'stages', 'tenants', 'documents'
const searchQuery = ref("");
const showFilters = ref(false);

// Filter State
const filters = reactive({
    owner: "",
    specificTenant: "",
    phase: "",
    type: "",
});

const isLoading = ref(false);
const showUploadModal = ref(false);

// TOAST SYSTEM
const toast = reactive({ show: false, message: "", type: "success" });
const showToast = (msg, type = "success") => {
    toast.message = msg;
    toast.type = type;
    toast.show = true;
    setTimeout(() => {
        toast.show = false;
    }, 3000);
};

// ==========================================
// 2. DATA DUMMY
// ==========================================

// A. DATA TAHAPAN
const stages = ref([
    { id: 1, name: "Pra-Kewirausahaan", docCount: 12, progress: 100 },
    { id: 2, name: "Pra-Inkubasi", docCount: 45, progress: 80 },
    { id: 3, name: "Inkubasi", docCount: 30, progress: 45 },
    { id: 4, name: "Pasca Inkubasi", docCount: 5, progress: 10 },
]);

// B. DATA TENANT
const tenants = ref([
    {
        id: 101,
        name: "Kripik Masa Depan",
        owner: "Budi S.",
        phase: "Inkubasi",
        progress: 75,
        docCount: 15,
    },
    {
        id: 102,
        name: "Sawit Technopreneur",
        owner: "Siti A.",
        phase: "Inkubasi",
        progress: 40,
        docCount: 8,
    },
    {
        id: 103,
        name: "Anyaman Rotan",
        owner: "Joko",
        phase: "Pra-Inkubasi",
        progress: 95,
        docCount: 20,
    },
]);

// C. DATA SEMUA DOKUMEN
const allDocuments = ref([
    {
        id: 301,
        title: "Proposal Bisnis Final",
        file: "proposal_final_v2.pdf",
        type: "pdf",
        owner: "Tenant",
        tenantName: "Kripik Masa Depan",
        phase: "Pra-Inkubasi",
        date: "2026-01-10",
        size: "2.5 MB",
    },
    {
        id: 302,
        title: "SOP Pelaksanaan Mentoring",
        file: "sop_mentoring_2026.docx",
        type: "docx",
        owner: "Inkubator Bisnis", // Internal
        tenantName: null,
        phase: "Inkubasi",
        date: "2026-01-05",
        size: "500 KB",
    },
    {
        id: 303,
        title: "Laporan Keuangan Januari",
        file: "lap_keuangan_rev.xlsx",
        type: "xlsx",
        owner: "Tenant",
        tenantName: "Anyaman Rotan",
        phase: "Inkubasi",
        date: "2026-02-01",
        size: "1.2 MB",
    },
    {
        id: 304,
        title: "Materi Workshop Digital Marketing",
        file: "materi_ws.pdf",
        type: "pdf",
        owner: "Inkubator Bisnis", // Internal
        tenantName: null,
        phase: "Pra-Inkubasi",
        date: "2026-01-20",
        size: "5.0 MB",
    },
]);

// D. SIDEBAR DATA (PESAN & LOG) - Pastikan ini ada datanya
const teamMessages = ref([
    {
        id: 1,
        from: "Pak Budi (Ketua)",
        text: "Tolong lengkapi dokumen RAB untuk fase Inkubasi segera.",
        urgent: true,
    },
    {
        id: 2,
        from: "Admin Pusat",
        text: "Format laporan bulanan diperbarui per Februari.",
        urgent: false,
    },
]);

const activityLogs = ref([
    { id: 1, user: "Rizka", action: "Upload Laporan", time: "10 menit lalu" },
    { id: 2, user: "Simon", action: "Verifikasi Tenant", time: "1 jam lalu" },
    { id: 3, user: "System", action: "Backup Data", time: "Hari ini 08:00" },
]);

// ==========================================
// 3. LOGIC & ACTIONS
// ==========================================

const tenantList = computed(() => tenants.value.map((t) => t.name));

const getFileIcon = (type) => {
    if (type === "pdf") return "📄";
    if (type === "xlsx") return "📊";
    if (type === "docx") return "📝";
    return "📁";
};

const getOwnerBadgeColor = (owner) => {
    return owner === "Tenant"
        ? "bg-blue-50 text-blue-700 border-blue-200"
        : "bg-purple-50 text-purple-700 border-purple-200";
};

const getProgressColor = (val) => {
    if (val === 100) return "bg-emerald-500";
    if (val >= 50) return "bg-blue-500";
    return "bg-orange-500";
};

const resetFilters = () => {
    searchQuery.value = "";
    filters.owner = "";
    filters.specificTenant = "";
    filters.phase = "";
    filters.type = "";
};

const filteredData = computed(() => {
    const q = searchQuery.value.toLowerCase();

    if (activeTab.value === "stages")
        return stages.value.filter((i) => i.name.toLowerCase().includes(q));
    if (activeTab.value === "tenants")
        return tenants.value.filter((i) => i.name.toLowerCase().includes(q));

    if (activeTab.value === "documents") {
        return allDocuments.value.filter((doc) => {
            const matchSearch = doc.title.toLowerCase().includes(q);
            const matchOwner = filters.owner
                ? doc.owner === filters.owner
                : true;

            let matchSpecificTenant = true;
            if (filters.owner === "Tenant" && filters.specificTenant) {
                matchSpecificTenant = doc.tenantName === filters.specificTenant;
            }

            const matchPhase = filters.phase
                ? doc.phase === filters.phase
                : true;
            const matchType = filters.type ? doc.type === filters.type : true;

            return (
                matchSearch &&
                matchOwner &&
                matchSpecificTenant &&
                matchPhase &&
                matchType
            );
        });
    }
    return [];
});

// FORM UPLOAD
const formUpload = reactive({
    target: "Inkubator Bisnis",
    tenant: "",
    phase: "",
    title: "",
    file: null,
});
const handleFile = (e) => {
    formUpload.file = e.target.files[0];
};
const submitUpload = () => {
    if (!formUpload.title || !formUpload.file) {
        showToast("Lengkapi data!", "error");
        return;
    }
    isLoading.value = true;
    setTimeout(() => {
        isLoading.value = false;
        showUploadModal.value = false;
        formUpload.title = "";
        formUpload.file = null;
        showToast("Berhasil disimpan!", "success");
    }, 1000);
};
</script>

<template>
    <Head title="Dashboard Arsip" />

    <AuthenticatedLayout>
        <transition name="slide-fade">
            <div
                v-if="toast.show"
                :class="
                    toast.type === 'error'
                        ? 'border-red-500 bg-red-50 text-red-700'
                        : 'border-emerald-500 bg-emerald-50 text-emerald-700'
                "
                class="fixed top-24 right-5 z-[100] flex items-center p-4 rounded-xl shadow-2xl border-l-4 min-w-[300px]"
            >
                <div class="mr-3 font-bold text-xl">
                    {{ toast.type === "error" ? "✕" : "✓" }}
                </div>
                <div class="text-sm font-bold">{{ toast.message }}</div>
            </div>
        </transition>

        <div class="min-h-screen bg-slate-50 font-sans p-6 md:p-8">
            <div class="max-w-7xl mx-auto mb-8">
                <div
                    class="rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 px-8 py-8 shadow-lg flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden"
                >
                    <div class="relative z-10 text-center md:text-left">
                        <h1 class="text-3xl font-bold text-white mb-1">
                            Arsip Digital {{ program.nama_program }}📂
                        </h1>
                        <p
                            class="text-emerald-50 text-sm font-medium opacity-90"
                        >
                            Halo {{ currentUser }}, kelola seluruh dokumen
                            di sini.
                        </p>
                    </div>
                    <div class="relative z-10">
                        <button
                            @click="showUploadModal = true"
                            class="group flex items-center gap-3 bg-white text-emerald-700 px-6 py-3 rounded-xl font-bold text-sm shadow-lg hover:bg-emerald-50 hover:scale-105 transition-all active:scale-95"
                        >
                            <span class="bg-emerald-100 p-1 rounded-lg"
                                >📤</span
                            >
                            Upload Dokumen Baru
                        </button>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto mb-6">
                <div class="flex gap-2 border-b border-slate-200 pb-1">
                    <button
                        @click="activeTab = 'stages'"
                        class="px-6 py-3 rounded-t-xl text-sm font-bold transition-all relative top-[1px]"
                        :class="
                            activeTab === 'stages'
                                ? 'bg-white border border-b-0 border-slate-200 text-emerald-700'
                                : 'text-slate-400 hover:text-slate-600 bg-slate-100'
                        "
                    >
                        🚀 Per Tahapan
                    </button>
                    <button
                        @click="activeTab = 'tenants'"
                        class="px-6 py-3 rounded-t-xl text-sm font-bold transition-all relative top-[1px]"
                        :class="
                            activeTab === 'tenants'
                                ? 'bg-white border border-b-0 border-slate-200 text-emerald-700'
                                : 'text-slate-400 hover:text-slate-600 bg-slate-100'
                        "
                    >
                        🏢 Per Tenant
                    </button>
                    <button
                        @click="activeTab = 'documents'"
                        class="px-6 py-3 rounded-t-xl text-sm font-bold transition-all relative top-[1px]"
                        :class="
                            activeTab === 'documents'
                                ? 'bg-white border border-b-0 border-slate-200 text-emerald-700'
                                : 'text-slate-400 hover:text-slate-600 bg-slate-100'
                        "
                    >
                        📂 Semua Dokumen
                    </button>
                </div>
            </div>

            <div
                class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-4 gap-8 items-start"
            >
                <div
                    :class="
                        activeTab === 'stages'
                            ? 'lg:col-span-3'
                            : 'lg:col-span-4'
                    "
                >
                    <div
                        class="bg-white rounded-b-2xl rounded-tr-2xl rounded-tl-none (activeTab!=='stages'?'rounded-tl-2xl':'') shadow-sm border border-slate-200 overflow-hidden min-h-[400px]"
                    >
                        <div
                            class="p-5 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50"
                        >
                            <h3 class="font-bold text-slate-700">
                                {{
                                    activeTab === "stages"
                                        ? "Fase Program"
                                        : activeTab === "tenants"
                                          ? "Daftar Tenant"
                                          : "Arsip Lengkap"
                                }}
                            </h3>

                            <div
                                class="flex items-center gap-2 w-full sm:w-auto"
                            >
                                <div class="relative w-full sm:w-64">
                                    <input
                                        type="text"
                                        v-model="searchQuery"
                                        placeholder="Cari data..."
                                        class="pl-9 pr-4 py-2 rounded-xl border border-slate-300 text-sm w-full focus:ring-emerald-500 transition"
                                    />
                                    <span
                                        class="absolute left-3 top-2.5 text-slate-400 text-sm"
                                        >🔍</span
                                    >
                                </div>

                                <button
                                    v-if="activeTab === 'documents'"
                                    @click="showFilters = !showFilters"
                                    class="p-2 rounded-xl border transition-all duration-200 flex items-center justify-center gap-2"
                                    :class="
                                        showFilters ||
                                        filters.owner ||
                                        filters.phase ||
                                        filters.type
                                            ? 'bg-emerald-100 border-emerald-300 text-emerald-700'
                                            : 'bg-white border-slate-300 text-slate-500 hover:bg-slate-50'
                                    "
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span
                                        v-if="
                                            filters.owner ||
                                            filters.phase ||
                                            filters.type
                                        "
                                        class="w-2 h-2 rounded-full bg-emerald-500"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        <div
                            v-if="activeTab === 'documents' && showFilters"
                            class="bg-slate-50/80 backdrop-blur-sm border-b border-slate-200 p-4 animate-slide-in-top"
                        >
                            <div
                                class="flex flex-col sm:flex-row flex-wrap items-center gap-3"
                            >
                                <div
                                    class="hidden md:flex items-center gap-2 text-slate-400 text-xs font-bold uppercase mr-2 border-r border-slate-300 pr-4 h-8"
                                >
                                    Filter By:
                                </div>

                                <div
                                    class="relative w-full sm:w-auto min-w-[160px]"
                                >
                                    <select
                                        v-model="filters.owner"
                                        @change="filters.specificTenant = ''"
                                        class="w-full pl-3 pr-8 py-2 text-sm rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer"
                                    >
                                        <option value="">Semua Sumber</option>
                                        <option value="Tenant">
                                            Dokumen Tenant
                                        </option>
                                        <option value="Inkubator Bisnis">
                                            Dokumen Inkubator Bisnis
                                        </option>
                                    </select>
                                </div>

                                <transition name="fade" mode="out-in">
                                    <div
                                        v-if="filters.owner === 'Tenant'"
                                        class="relative w-full sm:w-auto min-w-[200px]"
                                    >
                                        <select
                                            v-model="filters.specificTenant"
                                            class="w-full pl-3 pr-8 py-2 text-sm rounded-lg border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm bg-emerald-50/50 text-emerald-700 font-medium cursor-pointer"
                                        >
                                            <option value="">
                                                Semua Tenant
                                            </option>
                                            <option
                                                v-for="tName in tenantList"
                                                :key="tName"
                                                :value="tName"
                                            >
                                                {{ tName }}
                                            </option>
                                        </select>
                                    </div>
                                </transition>

                                <div
                                    class="relative w-full sm:w-auto min-w-[160px]"
                                >
                                    <select
                                        v-model="filters.phase"
                                        class="w-full pl-3 pr-8 py-2 text-sm rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer"
                                    >
                                        <option value="">Semua Fase</option>
                                        <option v-if="program.pra_kewirausahaan" value="Pra-Kewirausahaan">
                                            Pra-Kewirausahaan
                                        </option>
                                        <option value="Pra-Inkubasi">
                                            Pra-Inkubasi
                                        </option>
                                        <option value="Inkubasi">
                                            Inkubasi
                                        </option>
                                        <option value="Pasca Inkubasi">
                                            Pasca Inkubasi
                                        </option>
                                    </select>
                                </div>

                                <div
                                    class="relative w-full sm:w-auto min-w-[140px]"
                                >
                                    <select
                                        v-model="filters.type"
                                        class="w-full pl-9 pr-8 py-2 text-sm rounded-lg border-slate-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm cursor-pointer"
                                    >
                                        <option value="">Semua File</option>
                                        <option value="pdf">PDF</option>
                                        <option value="xlsx">Excel</option>
                                        <option value="docx">Word</option>
                                    </select>
                                    <span
                                        class="absolute left-3 top-2.5 text-slate-400 text-xs"
                                        >📄</span
                                    >
                                </div>

                                <div class="flex-grow hidden sm:block"></div>

                                <button
                                    @click="resetFilters"
                                    v-if="
                                        filters.owner ||
                                        filters.phase ||
                                        filters.type
                                    "
                                    class="group flex items-center gap-1.5 px-3 py-2 text-xs font-bold text-slate-500 hover:text-red-600 transition-colors rounded-lg hover:bg-red-50"
                                >
                                    Reset Filter
                                </button>
                            </div>
                        </div>

                        <table class="w-full text-left text-sm">
                            <thead
                                class="bg-white text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100"
                            >
                                <tr>
                                    <th class="px-6 py-4">
                                        {{
                                            activeTab === "documents"
                                                ? "Nama Dokumen"
                                                : "Nama Folder"
                                        }}
                                    </th>

                                    <th
                                        v-if="activeTab === 'stages'"
                                        class="px-6 py-4 w-1/3"
                                    >
                                        Progress Tahapan
                                    </th>

                                    <th
                                        v-if="
                                            activeTab === 'tenants' ||
                                            activeTab === 'documents'
                                        "
                                        class="px-6 py-4"
                                    >
                                        Fase
                                    </th>

                                    <th
                                        v-if="activeTab === 'tenants'"
                                        class="px-6 py-4 w-1/4"
                                    >
                                        Progress
                                    </th>

                                    <th
                                        v-if="activeTab === 'documents'"
                                        class="px-6 py-4"
                                    >
                                        Kepemilikan
                                    </th>

                                    <th
                                        v-if="activeTab === 'documents'"
                                        class="px-6 py-4 text-center"
                                    >
                                        Tipe & Size
                                    </th>

                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr
                                    v-for="item in filteredData"
                                    :key="item.id"
                                    class="hover:bg-slate-50 transition"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg border border-emerald-100"
                                            >
                                                {{
                                                    activeTab === "documents"
                                                        ? getFileIcon(item.type)
                                                        : "📁"
                                                }}
                                            </div>
                                            <div>
                                                <div
                                                    class="font-bold text-slate-800 line-clamp-1"
                                                >
                                                    {{
                                                        activeTab ===
                                                        "documents"
                                                            ? item.title
                                                            : item.name
                                                    }}
                                                </div>
                                                <div
                                                    class="text-[10px] text-slate-500 font-mono"
                                                    v-if="
                                                        activeTab ===
                                                        'documents'
                                                    "
                                                >
                                                    {{ item.file }}
                                                </div>
                                                <div
                                                    class="text-[10px] text-slate-500"
                                                    v-else
                                                >
                                                    {{
                                                        item.owner ||
                                                        item.lastUpdate
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td
                                        v-if="activeTab === 'stages'"
                                        class="px-6 py-4"
                                    >
                                        <div
                                            class="w-full bg-slate-100 rounded-full h-2 mb-1"
                                        >
                                            <div
                                                class="h-2 rounded-full"
                                                :class="
                                                    getProgressColor(
                                                        item.progress,
                                                    )
                                                "
                                                :style="`width: ${item.progress}%`"
                                            ></div>
                                        </div>
                                        <span
                                            class="text-[10px] font-bold text-slate-500"
                                            >{{ item.progress }}% Selesai</span
                                        >
                                    </td>

                                    <td
                                        v-if="
                                            activeTab === 'tenants' ||
                                            activeTab === 'documents'
                                        "
                                        class="px-6 py-4"
                                    >
                                        <span
                                            class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-[10px] font-bold border border-blue-100"
                                        >
                                            {{ item.phase }}
                                        </span>
                                    </td>

                                    <td
                                        v-if="activeTab === 'tenants'"
                                        class="px-6 py-4"
                                    >
                                        <div class="flex items-center gap-2">
                                            <div
                                                class="w-full bg-slate-100 rounded-full h-1.5"
                                            >
                                                <div
                                                    class="h-1.5 rounded-full bg-emerald-500"
                                                    :style="`width: ${item.progress}%`"
                                                ></div>
                                            </div>
                                            <span class="text-[10px] font-bold"
                                                >{{ item.progress }}%</span
                                            >
                                        </div>
                                    </td>

                                    <td
                                        v-if="activeTab === 'documents'"
                                        class="px-6 py-4"
                                    >
                                        <div class="flex flex-col items-start">
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold border uppercase mb-1 inline-block"
                                                :class="
                                                    getOwnerBadgeColor(
                                                        item.owner,
                                                    )
                                                "
                                            >
                                                {{
                                                    item.owner === "Tenant"
                                                        ? "Tenant"
                                                        : "Inkubator Bisnis"
                                                }}
                                            </span>
                                            <span
                                                v-if="item.tenantName"
                                                class="text-xs font-bold text-slate-700"
                                            >
                                                {{ item.tenantName }}
                                            </span>
                                        </div>
                                    </td>

                                    <td
                                        v-if="activeTab === 'documents'"
                                        class="px-6 py-4 text-center"
                                    >
                                        <div
                                            class="text-xs font-bold text-slate-600 uppercase"
                                        >
                                            {{ item.type }}
                                        </div>
                                        <div class="text-[10px] text-slate-400">
                                            {{ item.size }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <button
                                            v-if="activeTab === 'documents'"
                                            class="text-slate-400 hover:text-emerald-600 p-2 rounded-lg hover:bg-emerald-50 transition"
                                        >
                                            ⬇️
                                        </button>
                                        <Link
                                            v-else
                                            :href="
                                                activeTab === 'tenants'
                                                    ? route(
                                                          'staff.detail-tenant',
                                                          { id: item.id },
                                                      )
                                                    : route(
                                                          'staff.detail-phase',
                                                          { id: item.id },
                                                      )
                                            "
                                            class="text-xs font-bold text-white bg-emerald-600 px-3 py-1.5 rounded hover:bg-emerald-700 shadow-sm transition inline-block"
                                        >
                                            {{
                                                activeTab === "tenants"
                                                    ? "Detail"
                                                    : "Buka Tahap ➔"
                                            }}
                                        </Link>
                                    </td>
                                </tr>

                                <tr v-if="filteredData.length === 0">
                                    <td
                                        :colspan="
                                            activeTab === 'documents' ? 6 : 4
                                        "
                                        class="px-6 py-8 text-center text-slate-400 text-sm"
                                    >
                                        Data tidak ditemukan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div
                    v-if="activeTab === 'stages'"
                    class="lg:col-span-1 space-y-6 animate-slide-in-right"
                >
                    <div
                        class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden"
                    >
                        <div class="relative z-10">
                            <h4
                                class="font-bold text-sm mb-3 flex items-center gap-2"
                            >
                                💬 Pesan Ketua Tim
                            </h4>
                            <div class="space-y-3">
                                <div
                                    v-for="msg in teamMessages"
                                    :key="msg.id"
                                    class="bg-white/10 p-3 rounded-xl backdrop-blur-sm border border-white/10 text-xs"
                                >
                                    <div
                                        class="font-bold text-indigo-100 mb-1 flex justify-between"
                                    >
                                        {{ msg.from }}
                                        <span
                                            v-if="msg.urgent"
                                            class="bg-red-500 text-white px-1.5 rounded text-[9px]"
                                            >PENTING</span
                                        >
                                    </div>
                                    <p class="opacity-90 leading-relaxed">
                                        {{ msg.text }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm"
                    >
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-bold text-slate-800 text-sm">
                                Log Aktivitas
                            </h4>
                            <span
                                class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-1 rounded cursor-pointer hover:bg-emerald-100 transition"
                                >Lihat Semua</span
                            >
                        </div>
                        <div
                            class="relative pl-4 border-l-2 border-slate-100 space-y-6"
                        >
                            <div
                                v-for="log in activityLogs"
                                :key="log.id"
                                class="relative group"
                            >
                                <div
                                    class="absolute -left-[21px] top-0 w-3 h-3 bg-white border-2 border-slate-300 rounded-full group-hover:border-emerald-500 group-hover:bg-emerald-500 transition-colors"
                                ></div>
                                <div>
                                    <p
                                        class="text-xs font-bold text-slate-700 group-hover:text-emerald-700 transition"
                                    >
                                        {{ log.action }}
                                    </p>
                                    <p class="text-[10px] text-slate-500">
                                        Oleh
                                        <span class="font-bold">{{
                                            log.user
                                        }}</span>
                                        • {{ log.time }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <transition name="fade">
            <div
                v-if="showUploadModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-scale-in"
                >
                    <div
                        class="bg-gradient-to-r from-emerald-600 to-teal-600 p-6 flex justify-between items-center text-white"
                    >
                        <h3 class="font-bold text-lg">Upload Dokumen</h3>
                        <button
                            @click="showUploadModal = false"
                            class="text-2xl font-bold"
                        >
                            &times;
                        </button>
                    </div>
                    <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto">
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-2 block"
                                >Tujuan Arsip</label
                            >
                            <div class="grid grid-cols-2 gap-2">
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        v-model="formUpload.target"
                                        value="Inkubator Bisnis"
                                        class="peer hidden"
                                    />
                                    <div
                                        class="text-center px-2 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-emerald-600 peer-checked:text-white transition shadow-sm"
                                    >
                                        🏢 Inkubator Bisnis
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input
                                        type="radio"
                                        v-model="formUpload.target"
                                        value="Tenant"
                                        class="peer hidden"
                                    />
                                    <div
                                        class="text-center px-2 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 peer-checked:bg-emerald-600 peer-checked:text-white transition shadow-sm"
                                    >
                                        🏪 Tenant
                                    </div>
                                </label>
                            </div>
                        </div>
                        <transition name="fade" mode="out-in">
                            <div
                                v-if="formUpload.target === 'Tenant'"
                                class="relative w-full"
                            >
                                <label
                                    class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                    >Pilih Tenant</label
                                >
                                <select
                                    v-model="formUpload.tenant"
                                    class="w-full pl-3 pr-8 py-2 text-sm rounded-lg border-emerald-300 focus:ring-emerald-500 focus:border-emerald-500 shadow-sm bg-emerald-50/50 text-emerald-700 font-medium cursor-pointer"
                                >
                                    <option value="">Pilih Tenant</option>
                                    <option
                                        v-for="tName in tenantList"
                                        :key="tName"
                                        :value="tName"
                                    >
                                        {{ tName }}
                                    </option>
                                </select>
                            </div>
                        </transition>
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                >Fase Program</label
                            >
                            <select
                                v-model="formUpload.phase"
                                class="w-full border-slate-200 rounded-xl text-sm bg-slate-50"
                            >
                                <option value="" disabled>
                                    Pilih Fase Program
                                </option>
                                <option v-if="program.pra_kewirausahaan" value="Pra-Kewirausahaan">
                                    Pra-Kewirausahaan
                                </option>
                                <option value="Pra-Inkubasi">
                                    Pra-Inkubasi
                                </option>
                                <option value="Inkubasi">Inkubasi</option>
                                <option value="Pasca Inkubasi">
                                    Pasca Inkubasi
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                >Judul Dokumen</label
                            >
                            <input
                                type="text"
                                v-model="formUpload.title"
                                class="w-full border-slate-200 rounded-xl text-sm"
                                placeholder="Contoh: Laporan..."
                            />
                        </div>
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                >File</label
                            >
                            <div
                                class="border-2 border-dashed border-slate-300 rounded-xl p-4 text-center relative"
                            >
                                <input
                                    type="file"
                                    @change="(e) => handleFile(e, 'file')"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                />
                                <div v-if="!formUpload.file">
                                    <span class="text-2xl block mb-1">📄</span
                                    ><span
                                        class="text-xs font-bold text-slate-400"
                                        >Klik upload</span
                                    >
                                </div>
                                <div
                                    v-else
                                    class="text-emerald-600 text-xs font-bold"
                                >
                                    ✅ {{ formUpload.file.name }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3"
                    >
                        <button
                            @click="showUploadModal = false"
                            class="px-5 py-2.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-200"
                        >
                            Batal
                        </button>
                        <button
                            @click="submitUpload"
                            :disabled="isLoading"
                            class="px-6 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-emerald-700 flex items-center gap-2"
                        >
                            <span
                                v-if="isLoading"
                                class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"
                            ></span>
                            {{ isLoading ? "Menyimpan..." : "Simpan" }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>

<style scoped>
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}
.slide-fade-enter-from {
    transform: translateX(20px);
    opacity: 0;
}
.animate-scale-in {
    animation: scaleIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes scaleIn {
    from {
        transform: scale(0.95);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}
.animate-slide-in-right {
    animation: slideInRight 0.4s ease-out;
}
@keyframes slideInRight {
    from {
        transform: translateX(20px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
.animate-slide-in-top {
    animation: slideInTop 0.3s ease-out;
}
@keyframes slideInTop {
    from {
        transform: translateY(-10px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
