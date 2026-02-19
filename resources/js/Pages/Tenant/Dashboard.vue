<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed, reactive } from "vue";

// ==========================================
// 1. DATA DUMMY
// ==========================================
const tenantProfile = ref({
    name: "Budi Santoso",
    businessName: "Kripik Masa Depan",
    cluster: "Makanan & Minuman",
    joinDate: "10 Januari 2026",
    status: "Inkubasi",
});

// Perhatikan: Field 'status' sudah dihapus
// Field 'source' penting untuk menentukan tombol hapus
const documents = ref([
    {
        id: 1,
        title: "Hasil Penilaian Proposal",
        phase: "Pra-Inkubasi",
        date: "10-01-2026",
        source: "Inkubator Bisnis", // Milik Admin (Gak boleh dihapus tenant)
        size: "500 KB",
        type: "pdf",
        validated: true, // Sudah divalidasi - button hapus akan disembunyikan
    },
    {
        id: 2,
        title: "Laporan Keuangan Bulan 1",
        phase: "Inkubasi",
        date: "10-02-2026",
        source: "Tenant", // Milik Tenant (Boleh dihapus jika belum valid)
        size: "800 KB",
        type: "xlsx",
        validated: false, // Belum divalidasi - button hapus ditampilkan
    },
    {
        id: 3,
        title: "Business Model Canvas",
        phase: "Pra-Inkubasi",
        date: "12-01-2026",
        source: "Tenant",
        size: "1.2 MB",
        type: "pdf",
        validated: false, // Belum divalidasi - button hapus ditampilkan
    },
    {
        id: 4,
        title: "Pitch Deck Final",
        phase: "Inkubasi",
        date: "15-02-2026",
        source: "Tenant",
        size: "2.5 MB",
        type: "pdf",
        validated: true, // Sudah divalidasi - button hapus disembunyikan
    },
]);

// ==========================================
// 2. STATE & LOGIC
// ==========================================
const activeTab = ref("Semua");
const searchQuery = ref("");
const showUploadModal = ref(false);
const isUploading = ref(false);
const showDeleteConfirm = ref(false);
const docToDelete = ref(null);
const showPreviewModal = ref(false);
const docToPreview = ref(null);
const showFilters = ref(false);
const filterType = ref("");
const filterDate = ref("");
const filterSource = ref("");
const openDropdown = ref(null); // 'type', 'date', 'source'

const tabs = [
    "Semua",
    "Pra-Inkubasi",
    "Inkubasi",
    "Pasca Inkubasi",
    "Menunggu Validasi",
];

const uploadForm = reactive({
    title: "",
    phase: "",
    file: null,
});

const toast = reactive({ show: false, message: "", type: "success" });
const showToast = (msg, type = "success") => {
    toast.message = msg;
    toast.type = type;
    toast.show = true;
    setTimeout(() => {
        toast.show = false;
    }, 3000);
};

const filteredDocuments = computed(() => {
    return documents.value.filter((doc) => {
        // Handle tab matching
        const matchTab =
            activeTab.value === "Semua"
                ? true
                : activeTab.value === "Menunggu Validasi"
                  ? doc.validated === false
                  : doc.phase === activeTab.value;

        const matchSearch = doc.title
            .toLowerCase()
            .includes(searchQuery.value.toLowerCase());

        // Jika tab "Menunggu Validasi" aktif, abaikan semua filter
        if (activeTab.value === "Menunggu Validasi") {
            return matchTab && matchSearch;
        }

        // Filter normal untuk tab lainnya
        const matchType = filterType.value
            ? doc.type === filterType.value
            : true;
        const matchDate = filterDate.value
            ? doc.date === filterDate.value
            : true;
        const matchSource = filterSource.value
            ? doc.source === filterSource.value
            : true;

        return matchTab && matchSearch && matchType && matchDate && matchSource;
    });
});

// Icon Helper
const getFileIcon = (type) => {
    if (type === "pdf" || type === "pdf") return "📄";
    if (type === "xlsx" || type === "xls") return "📊";
    if (type === "docx" || type === "doc") return "📝";
    return "📁";
};

// --- ACTIONS ---

// 1. Upload File
const handleUpload = () => {
    if (!uploadForm.title.trim()) {
        showToast("Judul dokumen tidak boleh kosong!", "error");
        return;
    }
    if (!uploadForm.phase) {
        showToast("Pilih fase program terlebih dahulu!", "error");
        return;
    }
    if (!uploadForm.file) {
        showToast("Pilih file terlebih dahulu!", "error");
        return;
    }

    // Validate file size (10MB max)
    const maxSize = 10 * 1024 * 1024;
    if (uploadForm.file.size > maxSize) {
        showToast("Ukuran file maksimal 10MB!", "error");
        return;
    }

    // Validate file type
    const allowedTypes = [
        "application/pdf",
        "application/vnd.ms-excel",
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        "application/msword",
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    ];
    if (!allowedTypes.includes(uploadForm.file.type)) {
        showToast("Hanya PDF, Excel, dan Word yang diperbolehkan!", "error");
        return;
    }

    isUploading.value = true;
    setTimeout(() => {
        const fileExt = uploadForm.file.name.split(".").pop().toLowerCase();
        documents.value.unshift({
            id: Date.now(),
            title: uploadForm.title,
            phase: uploadForm.phase,
            date: new Date().toISOString().split("T")[0],
            source: "Tenant",
            size: (uploadForm.file.size / 1024 / 1024).toFixed(2) + " MB",
            type:
                fileExt === "pdf"
                    ? "pdf"
                    : fileExt === "xlsx" || fileExt === "xls"
                      ? "xlsx"
                      : "docx",
            validated: false,
        });
        isUploading.value = false;
        showUploadModal.value = false;
        uploadForm.title = "";
        uploadForm.file = null;
        uploadForm.phase = ""; // Reset ke empty string
        showToast("File berhasil diunggah!", "success");
    }, 1500);
};

const resetUploadForm = () => {
    uploadForm.title = "";
    uploadForm.file = null;
    uploadForm.phase = "";
};

// 2. Hapus File
const deleteDocument = (id) => {
    docToDelete.value = documents.value.find((doc) => doc.id === id);
    showDeleteConfirm.value = true;
};

const confirmDelete = () => {
    documents.value = documents.value.filter(
        (doc) => doc.id !== docToDelete.value.id,
    );
    showDeleteConfirm.value = false;
    docToDelete.value = null;
    showToast("File berhasil dihapus!", "success");
};

const cancelDelete = () => {
    showDeleteConfirm.value = false;
    docToDelete.value = null;
};

// 3. Preview File
const previewDocument = (doc) => {
    docToPreview.value = doc;
    showPreviewModal.value = true;
};

// 4. Download File
const downloadDocument = (doc) => {
    showToast(`Download: ${doc.title} dimulai...`, "success");
};

const handleFileSelect = (e) => {
    uploadForm.file = e.target.files[0];
};

const resetFilters = () => {
    filterType.value = "";
    filterDate.value = "";
    filterSource.value = "";
    openDropdown.value = null;
};
</script>

<template>
    <Head title="Dashboard Tenant" />

    <AuthenticatedLayout>
        <transition name="fade">
            <div
                v-if="toast.show"
                :class="
                    toast.type === 'error'
                        ? 'border-red-500 bg-red-50 text-red-700'
                        : 'border-emerald-500 bg-emerald-50 text-emerald-700'
                "
                class="fixed top-24 right-5 z-[101] flex items-center p-4 rounded-xl shadow-2xl border-l-4 min-w-[300px]"
            >
                <div class="mr-3 font-bold text-xl">
                    {{ toast.type === "error" ? "✕" : "✓" }}
                </div>
                <div class="text-sm font-bold">{{ toast.message }}</div>
            </div>
        </transition>
        <div class="min-h-screen bg-slate-50 font-sans p-6 md:p-8">
            <div class="max-w-7xl mx-auto space-y-8">
                <div
                    class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 shadow-lg text-white relative overflow-hidden"
                >
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-3xl pointer-events-none"
                    ></div>
                    <div
                        class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6"
                    >
                        <div class="flex items-center gap-6">
                            <div
                                class="w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl font-extrabold shadow-inner border border-white/30"
                            >
                                {{ tenantProfile.name.charAt(0) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h1 class="text-3xl font-bold">
                                        {{ tenantProfile.businessName }}
                                    </h1>
                                    <span
                                        class="bg-emerald-800/50 px-3 py-1 rounded-full text-xs font-bold border border-emerald-400/30"
                                    >
                                        {{ tenantProfile.status }}
                                    </span>
                                </div>
                                <p
                                    class="text-emerald-100 text-sm font-medium mb-2"
                                >
                                    Pemilik: {{ tenantProfile.name }} •
                                    {{ tenantProfile.cluster }}
                                </p>
                                <p class="text-xs text-emerald-200 opacity-80">
                                    Bergabung sejak:
                                    {{ tenantProfile.joinDate }}
                                </p>
                            </div>
                        </div>
                        <Link
                            :href="route('tenant.profile')"
                            class="group bg-white text-emerald-700 px-6 py-3 rounded-xl font-bold text-sm shadow-md hover:bg-emerald-50 transition flex items-center gap-2"
                        >
                            <span>👤</span> My Profile
                        </Link>
                    </div>
                </div>

                <div
                    class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden min-h-[500px]"
                >
                    <div
                        class="p-6 border-b border-slate-100 flex flex-col xl:flex-row justify-between items-center gap-6 bg-slate-50/50"
                    >
                        <div class="flex items-center gap-3">
                            <div
                                class="flex p-1 bg-slate-200/60 rounded-xl overflow-x-auto max-w-full"
                            >
                                <button
                                    v-for="tab in tabs"
                                    :key="tab"
                                    @click="activeTab = tab"
                                    class="px-5 py-2 rounded-lg text-sm font-bold transition-all whitespace-nowrap"
                                    :class="
                                        activeTab === tab
                                            ? 'bg-white text-emerald-700 shadow-sm'
                                            : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'
                                    "
                                >
                                    {{ tab }}
                                </button>
                            </div>
                            <button
                                v-if="filterType || filterDate || filterSource"
                                @click="resetFilters"
                                class="text-slate-500 hover:text-slate-700 px-2 py-1 rounded-lg text-xs transition"
                                title="Reset semua filter"
                            >
                                ✕ Reset
                            </button>
                        </div>
                        <div class="flex items-center gap-3 w-full xl:w-auto">
                            <div class="relative w-full xl:w-64">
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Cari file..."
                                    class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                />
                                <span
                                    class="absolute left-3 top-3 text-slate-400 text-xs"
                                    >🔍</span
                                >
                            </div>
                            <button
                                @click="
                                    showUploadModal = true;
                                    resetUploadForm();
                                "
                                class="bg-emerald-600 text-white px-5 py-3 rounded-xl font-bold text-sm hover:bg-emerald-700 shadow-md hover:shadow-lg transition flex items-center gap-2 whitespace-nowrap active:scale-95"
                            >
                                <span>📤</span> Upload File
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto" style="overflow-y: visible">
                        <table class="w-full text-left text-sm">
                            <thead
                                class="bg-white text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100"
                            >
                                <tr>
                                    <th class="px-6 py-4">Nama Dokumen</th>
                                    <th class="px-6 py-4">Fase</th>
                                    <th class="px-6 py-4 relative">
                                        <div
                                            v-if="
                                                activeTab !==
                                                'Menunggu Validasi'
                                            "
                                            class="flex items-center gap-2 cursor-pointer hover:text-slate-600 transition"
                                            :class="
                                                filterType
                                                    ? 'text-emerald-600 font-semibold'
                                                    : ''
                                            "
                                            @click="
                                                openDropdown =
                                                    openDropdown === 'type'
                                                        ? null
                                                        : 'type'
                                            "
                                        >
                                            <span>Tipe</span>
                                            <span
                                                class="text-xs transform transition"
                                                :class="
                                                    openDropdown === 'type'
                                                        ? 'rotate-180'
                                                        : ''
                                                "
                                                >▼</span
                                            >
                                        </div>
                                        <div v-else class="text-slate-400">
                                            Tipe
                                        </div>

                                        <div
                                            v-if="
                                                openDropdown === 'type' &&
                                                activeTab !==
                                                    'Menunggu Validasi'
                                            "
                                            class="absolute top-[calc(100%+8px)] left-0 bg-white border border-slate-200 rounded-xl shadow-xl z-50 min-w-[180px] overflow-hidden"
                                        >
                                            <!-- konten dropdown tetap sama -->
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 relative">
                                        <div
                                            v-if="
                                                activeTab !==
                                                'Menunggu Validasi'
                                            "
                                            class="flex items-center gap-2 cursor-pointer hover:text-slate-600 transition"
                                            :class="
                                                filterDate
                                                    ? 'text-emerald-600 font-semibold'
                                                    : ''
                                            "
                                            @click="
                                                openDropdown =
                                                    openDropdown === 'date'
                                                        ? null
                                                        : 'date'
                                            "
                                        >
                                            <span>Tanggal</span>
                                            <span
                                                class="text-xs transform transition"
                                                :class="
                                                    openDropdown === 'date'
                                                        ? 'rotate-180'
                                                        : ''
                                                "
                                                >▼</span
                                            >
                                        </div>
                                        <div v-else class="text-slate-400">
                                            Tanggal
                                        </div>

                                        <div
                                            v-if="
                                                openDropdown === 'date' &&
                                                activeTab !==
                                                    'Menunggu Validasi'
                                            "
                                            class="absolute top-[calc(100%+8px)] left-0 bg-white border border-slate-200 rounded-xl shadow-xl z-50 min-w-[180px] max-h-[240px] overflow-y-auto"
                                        >
                                            <!-- konten dropdown tetap sama -->
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 relative">
                                        <div
                                            v-if="
                                                activeTab !==
                                                'Menunggu Validasi'
                                            "
                                            class="flex items-center gap-2 cursor-pointer hover:text-slate-600 transition"
                                            :class="
                                                filterSource
                                                    ? 'text-emerald-600 font-semibold'
                                                    : ''
                                            "
                                            @click="
                                                openDropdown =
                                                    openDropdown === 'source'
                                                        ? null
                                                        : 'source'
                                            "
                                        >
                                            <span>Pemilik</span>
                                            <span
                                                class="text-xs transform transition"
                                                :class="
                                                    openDropdown === 'source'
                                                        ? 'rotate-180'
                                                        : ''
                                                "
                                                >▼</span
                                            >
                                        </div>
                                        <div v-else class="text-slate-400">
                                            Pemilik
                                        </div>

                                        <div
                                            v-if="
                                                openDropdown === 'source' &&
                                                activeTab !==
                                                    'Menunggu Validasi'
                                            "
                                            class="absolute top-[calc(100%+8px)] left-0 bg-white border border-slate-200 rounded-xl shadow-xl z-50 min-w-[180px] overflow-hidden"
                                        >
                                            <!-- konten dropdown tetap sama -->
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <tr
                                    v-for="doc in filteredDocuments"
                                    :key="doc.id"
                                    class="hover:bg-slate-50 transition group"
                                >
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-lg border border-slate-200"
                                            >
                                                {{ getFileIcon(doc.type) }}
                                            </div>
                                            <div>
                                                <div
                                                    class="font-bold text-slate-700"
                                                >
                                                    {{ doc.title }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-1 rounded"
                                            >{{ doc.phase }}</span
                                        >
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs font-semibold text-blue-600 bg-blue-50 px-2 py-1 rounded uppercase"
                                        >
                                            {{ doc.type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="text-xs text-slate-600 font-medium"
                                        >
                                            {{ doc.date }}
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="text-xs font-semibold px-2 py-1 rounded"
                                            :class="
                                                doc.source === 'Tenant'
                                                    ? 'bg-emerald-100 text-emerald-700'
                                                    : 'bg-blue-100 text-blue-700'
                                            "
                                        >
                                            {{
                                                doc.source === "Tenant"
                                                    ? "Tenant"
                                                    : "Inkubator Bisnis"
                                            }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div
                                            class="flex items-center justify-center gap-2"
                                        >
                                            <button
                                                @click="previewDocument(doc)"
                                                title="Preview"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-blue-600 hover:bg-blue-50 border border-transparent hover:border-blue-200 transition"
                                            >
                                                👁️
                                            </button>
                                            <button
                                                @click="downloadDocument(doc)"
                                                title="Download"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-200 transition"
                                            >
                                                ⬇️
                                            </button>

                                            <button
                                                v-if="
                                                    doc.source === 'Tenant' &&
                                                    !doc.validated
                                                "
                                                @click="deleteDocument(doc.id)"
                                                title="Hapus File"
                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-red-600 hover:bg-red-50 border border-transparent hover:border-red-200 transition"
                                            >
                                                🗑️
                                            </button>
                                            <div v-else class="w-8 h-8"></div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="filteredDocuments.length === 0">
                                    <td
                                        colspan="6"
                                        class="px-6 py-12 text-center"
                                    >
                                        <div
                                            class="flex flex-col items-center justify-center text-slate-400"
                                        >
                                            <span class="text-4xl mb-2"
                                                >📂</span
                                            >
                                            <p
                                                class="text-sm font-bold text-slate-600"
                                            >
                                                Tidak ada dokumen ditemukan.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden animate-scale-in"
                >
                    <div
                        class="bg-emerald-600 p-5 flex justify-between items-center text-white"
                    >
                        <h3 class="font-bold text-lg">Upload Dokumen Baru</h3>
                        <button
                            @click="
                                showUploadModal = false;
                                resetUploadForm();
                            "
                            class="text-white/80 hover:text-white text-2xl font-bold"
                        >
                            &times;
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-1"
                                >Judul Dokumen</label
                            >
                            <input
                                type="text"
                                v-model="uploadForm.title"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 focus:bg-emerald-50/20 transition"
                                placeholder="Contoh: Laporan Keuangan Bulanan..."
                            />
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-1"
                            >
                                Untuk Fase <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="uploadForm.phase"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 focus:bg-white transition cursor-pointer"
                            >
                                <option
                                    value=""
                                    disabled
                                    class="text-slate-400"
                                >
                                    Pilih Fase Program
                                </option>
                                <option
                                    value="Pra-Inkubasi"
                                    class="text-slate-700"
                                >
                                    Pra-Inkubasi
                                </option>
                                <option value="Inkubasi" class="text-slate-700">
                                    Inkubasi
                                </option>
                                <option
                                    value="Pasca Inkubasi"
                                    class="text-slate-700"
                                >
                                    Pasca Inkubasi
                                </option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-2"
                                >File</label
                            >
                            <div
                                class="border-2 border-dashed border-emerald-300 rounded-xl p-8 text-center relative hover:bg-emerald-50 transition cursor-pointer bg-emerald-50/30"
                            >
                                <input
                                    type="file"
                                    @change="handleFileSelect"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                />
                                <div v-if="!uploadForm.file" class="py-2">
                                    <span class="text-4xl block mb-2">📁</span>
                                    <p
                                        class="text-xs font-bold text-emerald-700 mb-1"
                                    >
                                        Drag & drop atau klik untuk pilih
                                    </p>
                                    <p class="text-[10px] text-emerald-600">
                                        PDF, Excel, Word (Max 10MB)
                                    </p>
                                </div>
                                <div v-else class="py-2">
                                    <span class="text-3xl block mb-2">{{
                                        getFileIcon(
                                            uploadForm.file.name
                                                .split(".")
                                                .pop(),
                                        )
                                    }}</span>
                                    <p
                                        class="text-xs font-bold text-emerald-700 truncate mb-1"
                                    >
                                        {{ uploadForm.file.name }}
                                    </p>
                                    <p class="text-[10px] text-emerald-600">
                                        {{
                                            (
                                                uploadForm.file.size /
                                                1024 /
                                                1024
                                            ).toFixed(2)
                                        }}
                                        MB
                                    </p>
                                    <button
                                        @click.stop="uploadForm.file = null"
                                        class="mt-2 text-xs text-emerald-600 hover:text-emerald-700 font-bold hover:underline"
                                    >
                                        Ganti file
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="p-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-3"
                    >
                        <button
                            @click="
                                showUploadModal = false;
                                resetUploadForm();
                            "
                            class="px-5 py-2.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-200 transition"
                        >
                            Batal
                        </button>
                        <button
                            @click="handleUpload"
                            :disabled="isUploading"
                            class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:bg-emerald-700 flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed transition-all active:scale-95"
                        >
                            <span
                                v-if="isUploading"
                                class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"
                            ></span>
                            {{
                                isUploading ? "Sedang Upload..." : "Upload File"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Modal Konfirmasi Hapus -->
        <transition name="fade">
            <div
                v-if="showDeleteConfirm"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-sm overflow-hidden animate-scale-in"
                >
                    <div
                        class="bg-red-50 p-6 flex justify-between items-start gap-4"
                    >
                        <div>
                            <h3 class="font-bold text-lg text-red-700 mb-1">
                                Hapus File?
                            </h3>
                            <p class="text-sm text-red-600" v-if="docToDelete">
                                {{ docToDelete.title }}
                            </p>
                        </div>
                        <p class="text-2xl">🗑️</p>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-600 mb-4">
                            File yang sudah dihapus tidak dapat dipulihkan.
                            Apakah Anda yakin?
                        </p>
                    </div>
                    <div
                        class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3"
                    >
                        <button
                            @click="cancelDelete"
                            class="px-5 py-2.5 rounded-xl text-slate-700 font-bold text-sm hover:bg-slate-200 transition"
                        >
                            Batal
                        </button>
                        <button
                            @click="confirmDelete"
                            class="px-6 py-2.5 bg-red-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-red-700 transition"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Modal Preview -->
        <transition name="fade">
            <div
                v-if="showPreviewModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-scale-in"
                >
                    <div
                        class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 flex justify-between items-center text-white"
                    >
                        <div
                            v-if="docToPreview"
                            class="flex items-center gap-3"
                        >
                            <span class="text-3xl">{{
                                getFileIcon(docToPreview.type)
                            }}</span>
                            <div>
                                <h3 class="font-bold text-lg">
                                    {{ docToPreview.title }}
                                </h3>
                                <p class="text-blue-100 text-sm">
                                    {{ docToPreview.type.toUpperCase() }} •
                                    {{ docToPreview.size }}
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showPreviewModal = false"
                            class="text-2xl font-bold hover:opacity-80 transition"
                        >
                            &times;
                        </button>
                    </div>
                    <div
                        class="p-8 min-h-[450px] flex items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100"
                    >
                        <div class="w-full" v-if="docToPreview">
                            <!-- PDF Preview -->
                            <div
                                v-if="docToPreview.type === 'pdf'"
                                class="text-center"
                            >
                                <div
                                    class="bg-white rounded-lg shadow-md p-6 mb-4 max-h-[320px] overflow-y-auto"
                                >
                                    <div class="text-5xl mb-4">📄</div>
                                    <div
                                        class="bg-gradient-to-b from-gray-400 to-gray-300 rounded h-56 flex items-center justify-center text-slate-600 text-sm"
                                    >
                                        <div>
                                            <p class="font-bold mb-2">
                                                PDF Preview
                                            </p>
                                            <p class="text-xs opacity-75">
                                                {{
                                                    Math.floor(
                                                        Math.random() * 15,
                                                    ) + 5
                                                }}
                                                halaman dokumen
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- XLSX Preview -->
                            <div
                                v-else-if="docToPreview.type === 'xlsx'"
                                class="text-center"
                            >
                                <div
                                    class="bg-white rounded-lg shadow-md p-4 mb-4 max-h-[320px] overflow-y-auto"
                                >
                                    <div class="text-5xl mb-4">📊</div>
                                    <table
                                        class="w-full text-xs border-collapse"
                                    >
                                        <thead>
                                            <tr class="bg-emerald-100">
                                                <th
                                                    class="border border-slate-300 px-2 py-1.5 font-bold"
                                                >
                                                    Periode
                                                </th>
                                                <th
                                                    class="border border-slate-300 px-2 py-1.5 font-bold"
                                                >
                                                    Pemasukan
                                                </th>
                                                <th
                                                    class="border border-slate-300 px-2 py-1.5 font-bold"
                                                >
                                                    Pengeluaran
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td
                                                    class="border border-slate-300 px-2 py-1"
                                                >
                                                    Januari
                                                </td>
                                                <td
                                                    class="border border-slate-300 px-2 py-1 text-emerald-600 font-semibold"
                                                >
                                                    Rp 50.000.000
                                                </td>
                                                <td
                                                    class="border border-slate-300 px-2 py-1 text-red-600"
                                                >
                                                    Rp 30.000.000
                                                </td>
                                            </tr>
                                            <tr class="bg-slate-50">
                                                <td
                                                    class="border border-slate-300 px-2 py-1"
                                                >
                                                    Februari
                                                </td>
                                                <td
                                                    class="border border-slate-300 px-2 py-1 text-emerald-600 font-semibold"
                                                >
                                                    Rp 65.000.000
                                                </td>
                                                <td
                                                    class="border border-slate-300 px-2 py-1 text-red-600"
                                                >
                                                    Rp 40.000.000
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- DOCX Preview -->
                            <div v-else class="text-center">
                                <div
                                    class="bg-white rounded-lg shadow-md p-6 mb-4 max-h-[320px] overflow-y-auto text-left"
                                >
                                    <div class="text-5xl mb-3 text-center">
                                        📝
                                    </div>
                                    <h3
                                        class="font-bold text-slate-800 mb-3 text-center text-sm"
                                    >
                                        {{ docToPreview.title }}
                                    </h3>
                                    <div
                                        class="space-y-2 text-xs text-slate-600 leading-relaxed"
                                    >
                                        <p>
                                            Lorem ipsum dolor sit amet,
                                            consectetur adipiscing elit.
                                        </p>
                                        <p>
                                            Sed do eiusmod tempor incididunt ut
                                            labore et dolore magna aliqua ut
                                            enim ad minim veniam.
                                        </p>
                                        <p class="text-slate-500 italic">...</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Info -->
                            <div
                                class="bg-slate-100 rounded-lg p-3 text-center"
                            >
                                <p class="text-xs text-slate-700 mb-1">
                                    <span
                                        class="font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded text-[10px]"
                                        >{{ docToPreview.phase }}</span
                                    >
                                    <span class="text-slate-500 mx-2">•</span>
                                    <span
                                        class="font-semibold text-slate-700"
                                        >{{ docToPreview.date }}</span
                                    >
                                </p>
                                <p class="text-[10px] text-slate-500 mt-2">
                                    {{ docToPreview.size }} •
                                    {{ docToPreview.type.toUpperCase() }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3"
                    >
                        <button
                            @click="showPreviewModal = false"
                            class="px-5 py-2.5 rounded-xl text-slate-600 font-bold text-sm hover:bg-slate-200 transition"
                        >
                            Tutup
                        </button>
                        <button
                            @click="downloadDocument(docToPreview)"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-blue-700 flex items-center gap-2 transition"
                        >
                            <span>⬇️</span> Download
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
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
/* Allow dropdowns to overflow */
.overflow-x-auto {
    overflow-y: visible !important;
    overflow: visible auto;
}
</style>
