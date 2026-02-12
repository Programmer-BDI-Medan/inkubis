<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed, reactive } from "vue";

// ==========================================
// 1. DATA DUMMY DOKUMEN
// ==========================================
const documents = ref([
    {
        id: 1,
        name: "SOP Inkubasi 2026.pdf",
        type: "PDF",
        ownerType: "team",
        ownerName: "Tim Inkubator Bisnis",
        date: "2026-01-10",
        url: "/files/sop.pdf",
    },
    {
        id: 2,
        name: "Laporan Keuangan Jan.xlsx",
        type: "XLSX",
        ownerType: "tenant",
        ownerName: "Kripik Masa Depan",
        date: "2026-02-01",
        url: "/files/keuangan.xlsx",
    },
    {
        id: 3,
        name: "Logo Brand Baru.png",
        type: "IMG",
        ownerType: "tenant",
        ownerName: "Batik Modern",
        date: "2026-02-05",
        url: "/files/logo.png",
    },
    {
        id: 4,
        name: "Formulir Pendaftaran.docx",
        type: "DOCX",
        ownerType: "team",
        ownerName: "Tim Inkubator Bisnis",
        date: "2026-01-15",
        url: "/files/form.docx",
    },
    {
        id: 5,
        name: "Legalitas PT.pdf",
        type: "PDF",
        ownerType: "tenant",
        ownerName: "Tech Solusi",
        date: "2026-02-08",
        url: "/files/legalitas.pdf",
    },
    {
        id: 6,
        name: "Absensi Mentor.xlsx",
        type: "XLSX",
        ownerType: "team",
        ownerName: "Tim Inkubator Bisnis",
        date: "2026-02-09",
        url: "/files/absensi.xlsx",
    },
    {
        id: 7,
        name: "Proposal Hibah.pdf",
        type: "PDF",
        ownerType: "tenant",
        ownerName: "Anyaman Rotan Jaya",
        date: "2026-01-20",
        url: "/files/proposal.pdf",
    },
]);

// ==========================================
// 2. TOAST NOTIFICATION SYSTEM (BARU)
// ==========================================
const toasts = ref([]);
let toastId = 0;

const showToast = (message, type = "success") => {
    const id = toastId++;
    const duration = 3000; // 3 detik

    // Push notifikasi baru ke array
    toasts.value.push({ id, message, type });

    // Hapus otomatis setelah durasi habis
    setTimeout(() => {
        removeToast(id);
    }, duration);
};

const removeToast = (id) => {
    toasts.value = toasts.value.filter((t) => t.id !== id);
};

// Helper untuk styling toast berdasarkan tipe
const getToastStyle = (type) => {
    if (type === "success")
        return "border-l-4 border-emerald-500 text-emerald-800 bg-emerald-50";
    if (type === "info")
        return "border-l-4 border-sky-500 text-sky-800 bg-sky-50";
    if (type === "error")
        return "border-l-4 border-rose-500 text-rose-800 bg-rose-50";
    return "bg-white border-l-4 border-gray-500";
};

const getToastIcon = (type) => {
    if (type === "success") return "✅";
    if (type === "info") return "ℹ️";
    if (type === "error") return "⚠️";
    return "📢";
};

// ==========================================
// 3. LOGIC FILTER (CORONG)
// ==========================================
const search = ref("");
const activeFilterMenu = ref(null);
const filters = reactive({ type: "", owner: "" });

const uniqueTypes = computed(() =>
    [...new Set(documents.value.map((d) => d.type))].sort(),
);
const uniqueOwners = computed(() =>
    [...new Set(documents.value.map((d) => d.ownerName))].sort(),
);

const filteredDocuments = computed(() => {
    return documents.value.filter((doc) => {
        const matchSearch = doc.name
            .toLowerCase()
            .includes(search.value.toLowerCase());
        const matchType = filters.type === "" || doc.type === filters.type;
        const matchOwner =
            filters.owner === "" || doc.ownerName === filters.owner;
        return matchSearch && matchType && matchOwner;
    });
});

const toggleFilter = (menuName) => {
    activeFilterMenu.value =
        activeFilterMenu.value === menuName ? null : menuName;
};
const setFilter = (category, value) => {
    filters[category] = value;
    activeFilterMenu.value = null;
};
const getFileIcon = (type) => {
    const map = { PDF: "📄", DOCX: "📝", XLSX: "📊", IMG: "🖼️" };
    return map[type] || "📁";
};

// ==========================================
// 4. LOGIC ACTIONS (DOWNLOAD & DELETE)
// ==========================================
const downloadFile = (doc) => {
    // 1. Munculkan Toast Info
    showToast(`Mulai mengunduh ${doc.name}...`, "info");

    // 2. Simulasi delay download selesai (misal 1.5 detik)
    setTimeout(() => {
        showToast("Download selesai!", "success");
    }, 1500);
};

const deleteDocument = (id) => {
    if (confirm("Yakin ingin menghapus dokumen ini?")) {
        documents.value = documents.value.filter((d) => d.id !== id);
        // Munculkan Toast Success
        showToast("Dokumen berhasil dihapus dari sistem.", "success");
    }
};

// ==========================================
// 5. LOGIC TAMBAH DOKUMEN (MODAL)
// ==========================================
const showModal = ref(false);
const newDocForm = reactive({
    name: "",
    ownerType: "team",
    tenantName: "",
    file: null,
});

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        newDocForm.name = file.name;
        const ext = file.name.split(".").pop().toUpperCase();
        newDocForm.fileType = ["PDF", "DOCX", "XLSX", "PNG", "JPG"].includes(
            ext,
        )
            ? ext
            : "FILE";
    }
};

const submitDocument = () => {
    if (!newDocForm.name) {
        showToast("Nama dokumen wajib diisi!", "error");
        return;
    }

    const newId = documents.value.length + 1;
    documents.value.unshift({
        id: newId,
        name: newDocForm.name,
        type: newDocForm.fileType || "PDF",
        ownerType: newDocForm.ownerType,
        ownerName:
            newDocForm.ownerType === "team"
                ? "Tim Inkubator Bisnis"
                : newDocForm.tenantName,
        date: new Date().toISOString().split("T")[0],
        url: "#",
    });

    showModal.value = false;
    newDocForm.name = "";
    newDocForm.tenantName = "";

    // Munculkan Toast Success
    showToast("Dokumen berhasil disimpan!", "success");
};
</script>

<template>
    <Head title="Manajemen Dokumen" />

    <AuthenticatedLayout>
        <div
            class="fixed top-24 right-5 z-[200] flex flex-col gap-3 pointer-events-none"
        >
            <TransitionGroup name="toast">
                <div
                    v-for="toast in toasts"
                    :key="toast.id"
                    class="pointer-events-auto flex items-center gap-3 px-4 py-3 rounded shadow-lg min-w-[300px] max-w-sm backdrop-blur-sm transition-all duration-300 transform"
                    :class="getToastStyle(toast.type)"
                >
                    <div class="text-xl">{{ getToastIcon(toast.type) }}</div>
                    <div class="flex-1">
                        <p class="text-sm font-bold">
                            {{
                                toast.type === "error"
                                    ? "Gagal"
                                    : toast.type === "info"
                                      ? "Informasi"
                                      : "Berhasil"
                            }}
                        </p>
                        <p class="text-xs opacity-90">{{ toast.message }}</p>
                    </div>
                    <button
                        @click="removeToast(toast.id)"
                        class="text-slate-400 hover:text-slate-600"
                    >
                        ✕
                    </button>
                </div>
            </TransitionGroup>
        </div>

        <div
            class="min-h-screen bg-slate-50 font-sans p-6 md:p-8"
            @click="activeFilterMenu = null"
        >
            <div
                class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-3xl p-6 md:p-8 mb-8 flex flex-col md:flex-row justify-between items-end gap-6 shadow-xl shadow-emerald-200"
            >
                <div class="w-full md:w-auto">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/30 border border-emerald-400/30 text-emerald-50 text-[10px] font-bold uppercase mb-2 backdrop-blur-sm"
                    >
                        📂 Manajemen Arsip
                    </div>
                    <h1
                        class="text-3xl md:text-4xl font-extrabold text-white tracking-tight"
                    >
                        Dokumen Inkubasi
                    </h1>
                    <p
                        class="text-emerald-100 mt-2 text-sm font-medium max-w-lg"
                    >
                        Pusat kontrol arsip Tim Inkubator dan data Tenant.
                        Kelola semua file dalam satu tampilan terintegrasi.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <div class="relative w-full md:w-64 group">
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari nama file..."
                            class="w-full rounded-xl border border-emerald-400/30 bg-emerald-700/40 text-sm pl-10 text-white placeholder-emerald-200 focus:bg-white focus:text-slate-800 focus:ring-2 focus:ring-emerald-300 focus:border-white transition-all duration-300 py-3 backdrop-blur-md shadow-inner"
                        />
                        <span
                            class="absolute left-3 top-3 text-emerald-200 group-focus-within:text-emerald-500 transition-colors"
                            >🔍</span
                        >
                    </div>
                    <button
                        @click.stop="showModal = true"
                        class="bg-white text-emerald-700 hover:bg-emerald-50 font-bold py-3 px-6 rounded-xl text-sm flex items-center justify-center gap-2 shadow-lg shadow-emerald-900/10 transition transform hover:-translate-y-0.5 active:translate-y-0"
                    >
                        <span
                            class="bg-emerald-100 text-emerald-600 rounded-full w-5 h-5 flex items-center justify-center text-xs"
                            >+</span
                        >
                        Upload
                    </button>
                </div>
            </div>

            <div
                class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-visible"
            >
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr
                            class="text-xs font-bold text-slate-400 uppercase border-b border-slate-100"
                        >
                            <th class="p-5">Nama Dokumen</th>

                            <th class="p-5 relative w-32">
                                <div
                                    class="flex items-center gap-1 cursor-pointer hover:text-emerald-600"
                                    @click.stop="toggleFilter('type')"
                                >
                                    Tipe
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        class="w-4 h-4 transition-colors"
                                        :class="
                                            filters.type
                                                ? 'text-emerald-600'
                                                : 'text-slate-300'
                                        "
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 01.628.74v2.288a2.25 2.25 0 01-.659 1.59l-4.682 4.683a2.25 2.25 0 00-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 018 18.25v-5.757a2.25 2.25 0 00-.659-1.591L2.659 6.22A2.25 2.25 0 012 4.629V2.34a.75.75 0 01.628-.74z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div
                                    v-if="activeFilterMenu === 'type'"
                                    class="absolute top-12 left-0 z-50 bg-white border border-slate-200 rounded-xl shadow-xl w-40 py-2 flex flex-col"
                                >
                                    <button
                                        @click="setFilter('type', '')"
                                        class="text-left px-4 py-2 text-sm hover:bg-slate-50"
                                        :class="
                                            filters.type === ''
                                                ? 'font-bold text-emerald-600'
                                                : 'text-slate-600'
                                        "
                                    >
                                        Semua Tipe
                                    </button>
                                    <button
                                        v-for="t in uniqueTypes"
                                        :key="t"
                                        @click="setFilter('type', t)"
                                        class="text-left px-4 py-2 text-sm hover:bg-slate-50 text-slate-600"
                                        :class="
                                            filters.type === t
                                                ? 'font-bold text-emerald-600 bg-emerald-50'
                                                : ''
                                        "
                                    >
                                        {{ t }}
                                    </button>
                                </div>
                            </th>

                            <th class="p-5 relative">
                                <div
                                    class="flex items-center gap-1 cursor-pointer hover:text-emerald-600"
                                    @click.stop="toggleFilter('owner')"
                                >
                                    Pemilik Data
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20"
                                        fill="currentColor"
                                        class="w-4 h-4 transition-colors"
                                        :class="
                                            filters.owner
                                                ? 'text-emerald-600'
                                                : 'text-slate-300'
                                        "
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M2.628 1.601C5.028 1.206 7.49 1 10 1s4.973.206 7.372.601a.75.75 0 01.628.74v2.288a2.25 2.25 0 01-.659 1.59l-4.682 4.683a2.25 2.25 0 00-.659 1.59v3.037c0 .684-.31 1.33-.844 1.757l-1.937 1.55A.75.75 0 018 18.25v-5.757a2.25 2.25 0 00-.659-1.591L2.659 6.22A2.25 2.25 0 012 4.629V2.34a.75.75 0 01.628-.74z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div
                                    v-if="activeFilterMenu === 'owner'"
                                    class="absolute top-12 left-0 z-50 bg-white border border-slate-200 rounded-xl shadow-xl w-64 py-2 flex flex-col max-h-64 overflow-y-auto"
                                >
                                    <button
                                        @click="setFilter('owner', '')"
                                        class="text-left px-4 py-2 text-sm hover:bg-slate-50 border-b border-slate-50"
                                        :class="
                                            filters.owner === ''
                                                ? 'font-bold text-emerald-600'
                                                : 'text-slate-600'
                                        "
                                    >
                                        Semua Entitas
                                    </button>
                                    <div
                                        class="px-4 py-1 text-[10px] font-bold text-slate-400 uppercase mt-1"
                                    >
                                        Internal
                                    </div>
                                    <button
                                        @click="
                                            setFilter(
                                                'owner',
                                                'Tim Inkubator Bisnis',
                                            )
                                        "
                                        class="text-left px-4 py-2 text-sm hover:bg-slate-50 text-slate-600"
                                        :class="
                                            filters.owner ===
                                            'Tim Inkubator Bisnis'
                                                ? 'font-bold text-emerald-600 bg-emerald-50'
                                                : ''
                                        "
                                    >
                                        🏢 Tim Inkubator Bisnis
                                    </button>
                                    <div
                                        class="px-4 py-1 text-[10px] font-bold text-slate-400 uppercase mt-2"
                                    >
                                        Tenants
                                    </div>
                                    <button
                                        v-for="o in uniqueOwners.filter(
                                            (n) => n !== 'Tim Inkubator Bisnis',
                                        )"
                                        :key="o"
                                        @click="setFilter('owner', o)"
                                        class="text-left px-4 py-2 text-sm hover:bg-slate-50 text-slate-600"
                                        :class="
                                            filters.owner === o
                                                ? 'font-bold text-emerald-600 bg-emerald-50'
                                                : ''
                                        "
                                    >
                                        {{ o }}
                                    </button>
                                </div>
                            </th>

                            <th class="p-5">Tanggal</th>
                            <th class="p-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr
                            v-for="doc in filteredDocuments"
                            :key="doc.id"
                            class="border-b border-slate-50 last:border-0 hover:bg-slate-50/80 transition group"
                        >
                            <td class="p-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-xl"
                                    >
                                        {{ getFileIcon(doc.type) }}
                                    </div>
                                    <div>
                                        <div
                                            class="font-bold text-slate-700 group-hover:text-emerald-600 transition"
                                        >
                                            {{ doc.name }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{
                                                (Math.random() * 5 + 1).toFixed(
                                                    1,
                                                )
                                            }}
                                            MB
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-5">
                                <span
                                    class="px-2 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200"
                                    >{{ doc.type }}</span
                                >
                            </td>
                            <td class="p-5">
                                <div
                                    v-if="doc.ownerType === 'team'"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200"
                                >
                                    🏢 Internal Tim
                                </div>
                                <div v-else class="inline-flex flex-col">
                                    <span class="text-slate-700 font-medium">{{
                                        doc.ownerName
                                    }}</span
                                    ><span class="text-[10px] text-slate-400"
                                        >Tenant</span
                                    >
                                </div>
                            </td>
                            <td class="p-5 text-slate-500 font-medium">
                                {{ doc.date }}
                            </td>
                            <td class="p-5 text-center">
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <button
                                        @click="downloadFile(doc)"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition shadow-sm border border-emerald-100"
                                        title="Download File"
                                    >
                                        📥
                                    </button>
                                    <button
                                        @click="deleteDocument(doc.id)"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 transition border border-slate-100"
                                        title="Hapus Dokumen"
                                    >
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="filteredDocuments.length === 0">
                            <td
                                colspan="5"
                                class="p-10 text-center text-slate-400"
                            >
                                <div class="text-4xl mb-2 grayscale opacity-30">
                                    🌪️
                                </div>
                                Tidak ada dokumen yang cocok dengan filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div
            v-if="showModal"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
            @click.self="showModal = false"
        >
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 animate-in fade-in zoom-in duration-200"
            >
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-800">
                        Upload Dokumen Baru
                    </h3>
                    <button
                        @click="showModal = false"
                        class="text-slate-400 hover:text-slate-600"
                    >
                        ✕
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase mb-2"
                            >Dokumen Milik Siapa?</label
                        >
                        <div class="grid grid-cols-2 gap-3">
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    v-model="newDocForm.ownerType"
                                    value="team"
                                    class="peer sr-only"
                                />
                                <div
                                    class="text-center py-2 rounded-xl border-2 border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 text-slate-500 text-sm font-bold transition"
                                >
                                    🏢 Tim Inkubator
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    v-model="newDocForm.ownerType"
                                    value="tenant"
                                    class="peer sr-only"
                                />
                                <div
                                    class="text-center py-2 rounded-xl border-2 border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 text-slate-500 text-sm font-bold transition"
                                >
                                    👤 Tenant
                                </div>
                            </label>
                        </div>
                    </div>

                    <div v-if="newDocForm.ownerType === 'tenant'">
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase mb-1"
                            >Nama Tenant</label
                        >
                        <select
                            v-model="newDocForm.tenantName"
                            class="w-full rounded-xl border-slate-200 text-sm py-2.5 focus:ring-emerald-500"
                        >
                            <option value="" disabled>Pilih Tenant...</option>
                            <option>Kripik Masa Depan</option>
                            <option>Batik Modern</option>
                            <option>Tech Solusi</option>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase mb-1"
                            >Pilih File</label
                        >
                        <input
                            type="file"
                            @change="handleFileChange"
                            class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100"
                        />
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-500 uppercase mb-1"
                            >Nama Dokumen</label
                        >
                        <input
                            v-model="newDocForm.name"
                            type="text"
                            class="w-full rounded-xl border-slate-200 text-sm py-2.5 focus:ring-emerald-500"
                            placeholder="Otomatis terisi nama file..."
                        />
                    </div>
                </div>

                <div class="mt-8 flex gap-3">
                    <button
                        @click="showModal = false"
                        class="flex-1 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50"
                    >
                        Batal
                    </button>
                    <button
                        @click="submitDocument"
                        class="flex-1 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-200"
                    >
                        Simpan Dokumen
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Animasi Toast */
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}
.toast-enter-from {
    opacity: 0;
    transform: translateX(30px);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(30px);
}
</style>
