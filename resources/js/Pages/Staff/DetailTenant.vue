<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, reactive, computed } from "vue";

// ==========================================
// 1. TERIMA DATA DARI LARAVEL (PROPS)
// ==========================================
const props = defineProps({
    tenantData: {
        type: Object,
        default: () => null,
    },
    phasesData: {
        type: Object,
        default: () => null,
    },
});

// ==========================================
// 2. DATA TENANT
// ==========================================
const tenant = ref(
    props.tenantData || {
        id: 1,
        name: "Kripik Masa Depan",
        owner: "Budi Santoso",
        cluster: "Makanan & Minuman",
        phone: "0812-3456-7890",
        address: "Jl. Gatot Subroto No. 123, Medan",
        joinDate: "10 Januari 2026",
        status: computed(() => {
            // Logic status display tetap ada (read-only based on data)
            if (phases.inkubasi.status === "Berjalan") return "Inkubasi";
            if (phases.pasca.status === "Berjalan") return "Pasca Inkubasi";
            if (phases.pasca.status === "Selesai") return "Alumni";
            return "Pra-Inkubasi";
        }),
    },
);

// ==========================================
// 3. DATA FASE
// ==========================================
const phases = reactive(
    props.phasesData || {
        pra: {
            id: "pra",
            title: "Pra-Inkubasi",
            status: "Selesai",
            progress: 100,
            description: "Tahap seleksi dan pematangan ide bisnis.",
            nextPhase: "inkubasi",
            documents: [
                {
                    id: 1,
                    title: "Proposal Bisnis",
                    type: "pdf",
                    date: "2026-01-10",
                    size: "2.5 MB",
                    uploader: "Budi S.",
                },
                {
                    id: 2,
                    title: "Hasil Penilaian Seleksi",
                    type: "xlsx",
                    date: "2026-01-12",
                    size: "500 KB",
                    tag: "Penilaian",
                    uploader: "Admin",
                },
            ],
        },
        inkubasi: {
            id: "inkubasi",
            title: "Masa Inkubasi",
            status: "Berjalan",
            progress: 45,
            description: "Pendampingan intensif dan pelatihan.",
            nextPhase: "pasca",
            documents: [
                {
                    id: 4,
                    title: "Logbook Mentoring",
                    type: "docx",
                    date: "2026-02-01",
                    size: "300 KB",
                    uploader: "Mentor",
                },
            ],
        },
        pasca: {
            id: "pasca",
            title: "Pasca Inkubasi",
            status: "Terkunci",
            progress: 0,
            description: "Monitoring perkembangan alumni tenant.",
            nextPhase: null,
            documents: [],
        },
    },
);

// ==========================================
// 4. UI STATE & LOGIC
// ==========================================
const showUploadModal = ref(false);
const showEditTenantModal = ref(false);
const showDocDetailModal = ref(false);
// const showConfirmPhaseModal dihapus

const activePhaseKey = ref(null);
const selectedDocument = ref(null);

const uploadForm = reactive({
    title: "",
    file: null,
    isUploading: false,
    progress: 0,
});

const editTenantForm = reactive({
    name: "",
    owner: "",
    cluster: "",
    phone: "",
    address: "",
});

// Helper Functions
const getStatusColor = (status) => {
    switch (status) {
        case "Selesai":
            return "bg-emerald-100 text-emerald-700 border-emerald-200";
        case "Berjalan":
            return "bg-blue-50 text-blue-600 border-blue-200 animate-pulse";
        default:
            return "bg-slate-100 text-slate-400 border-slate-200";
    }
};

const getFileIcon = (type) => {
    if (type?.includes("pdf")) return "📄";
    if (type?.includes("xls") || type?.includes("sheet")) return "📊";
    if (type?.includes("doc")) return "📝";
    return "📁";
};

// Actions (promptAdvancePhase & advancePhase dihapus)

const openUploadModal = (phaseKey) => {
    activePhaseKey.value = phaseKey;
    uploadForm.title = "";
    uploadForm.file = null;
    showUploadModal.value = true;
};

const handleFileSelect = (e) => {
    uploadForm.file = e.target.files[0];
    if (uploadForm.file && !uploadForm.title)
        uploadForm.title = uploadForm.file.name;
};

const submitUpload = () => {
    if (!uploadForm.file) return alert("Pilih file dulu!");
    uploadForm.isUploading = true;

    // Simulasi Upload
    let interval = setInterval(() => {
        uploadForm.progress += 20;
        if (uploadForm.progress >= 100) {
            clearInterval(interval);
            phases[activePhaseKey.value].documents.unshift({
                id: Date.now(),
                title: uploadForm.title,
                type: uploadForm.file.name.split(".").pop(),
                date: new Date().toISOString().slice(0, 10),
                size: "1.2 MB",
                tag: "Baru",
                uploader: "Staff",
            });

            // Auto update progress bar dikit (simulasi)
            if (
                phases[activePhaseKey.value].status === "Berjalan" &&
                phases[activePhaseKey.value].progress < 90
            ) {
                phases[activePhaseKey.value].progress += 10;
            }

            setTimeout(() => {
                showUploadModal.value = false;
                uploadForm.isUploading = false;
                uploadForm.progress = 0;
            }, 500);
        }
    }, 200);
};

const openEditTenant = () => {
    Object.assign(editTenantForm, tenant.value);
    showEditTenantModal.value = true;
};

const saveTenantProfile = () => {
    Object.assign(tenant.value, editTenantForm);
    showEditTenantModal.value = false;
};

const viewDocument = (doc) => {
    selectedDocument.value = doc;
    showDocDetailModal.value = true;
};
</script>

<template>
    <Head :title="`Detail - ${tenant.name}`" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-50 font-sans p-6 md:p-8">
            <div class="mb-8">
                <Link
                    :href="route('dashboard')"
                    class="inline-flex items-center text-slate-500 hover:text-emerald-600 font-bold text-sm mb-4 transition"
                >
                    <span class="mr-2">←</span> Kembali
                </Link>

                <div
                    class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-6"
                >
                    <div class="flex items-center gap-5">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white text-3xl font-extrabold shadow-lg"
                        >
                            {{ tenant.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h1
                                    class="text-2xl md:text-3xl font-extrabold text-slate-800"
                                >
                                    {{ tenant.name }}
                                </h1>
                                <span
                                    class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold border border-emerald-200"
                                >
                                    {{ tenant.status }}
                                </span>
                            </div>
                            <p class="text-slate-500 font-medium">
                                {{ tenant.owner }} • {{ tenant.cluster }}
                            </p>
                            <div class="flex gap-4 mt-2 text-xs text-slate-400">
                                <span class="flex items-center gap-1"
                                    >📍 {{ tenant.address }}</span
                                >
                                <span class="flex items-center gap-1"
                                    >📞 {{ tenant.phone }}</span
                                >
                            </div>
                        </div>
                    </div>

                    <button
                        @click="openEditTenant"
                        class="px-5 py-2.5 rounded-xl border border-slate-200 font-bold text-sm text-slate-600 hover:bg-slate-50 hover:border-emerald-400 hover:text-emerald-600 transition flex items-center gap-2"
                    >
                        <span>✏️</span> Edit Data
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div
                    v-for="(phase, key) in phases"
                    :key="key"
                    class="flex flex-col bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden h-full transition-all hover:shadow-md relative"
                    :class="{
                        'opacity-60 grayscale pointer-events-none':
                            phase.status === 'Terkunci',
                        'ring-2 ring-blue-400 ring-offset-2':
                            phase.status === 'Berjalan',
                    }"
                >
                    <div
                        v-if="phase.status === 'Selesai'"
                        class="absolute top-0 right-0 bg-emerald-500 text-white text-[10px] font-bold px-3 py-1 rounded-bl-xl z-10"
                    >
                        ✔ SELESAI
                    </div>

                    <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-extrabold text-lg text-slate-800">
                                {{ phase.title }}
                            </h3>
                        </div>
                        <p class="text-xs text-slate-500 mb-4 h-8 line-clamp-2">
                            {{ phase.description }}
                        </p>

                        <div
                            class="w-full h-2 bg-slate-200 rounded-full overflow-hidden"
                        >
                            <div
                                class="h-full rounded-full transition-all duration-1000"
                                :class="
                                    phase.status === 'Selesai'
                                        ? 'bg-emerald-500'
                                        : 'bg-blue-500'
                                "
                                :style="`width: ${phase.progress}%`"
                            ></div>
                        </div>
                        <div
                            class="flex justify-between mt-1 text-[10px] font-bold text-slate-400"
                        >
                            <span>Status: {{ phase.status }}</span>
                            <span>{{ phase.progress }}%</span>
                        </div>
                    </div>

                    <div class="p-4 flex-1 bg-white space-y-3 min-h-[200px]">
                        <div
                            v-if="phase.documents.length === 0"
                            class="h-full flex flex-col items-center justify-center text-slate-300 py-8"
                        >
                            <span class="text-3xl mb-2">📂</span>
                            <span class="text-xs font-bold"
                                >Belum ada berkas</span
                            >
                        </div>
                        <div
                            v-else
                            v-for="doc in phase.documents"
                            :key="doc.id"
                            class="group flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition"
                        >
                            <div
                                class="flex items-center gap-3 overflow-hidden cursor-pointer flex-1"
                                @click="viewDocument(doc)"
                            >
                                <div
                                    class="w-8 h-8 rounded bg-white border border-slate-100 flex items-center justify-center text-sm shadow-sm"
                                >
                                    {{ getFileIcon(doc.type) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <p
                                            class="text-xs font-bold text-slate-700 truncate max-w-[100px]"
                                        >
                                            {{ doc.title }}
                                        </p>
                                        <span
                                            v-if="doc.tag"
                                            class="text-[9px] bg-yellow-100 text-yellow-700 px-1.5 rounded font-bold"
                                            >{{ doc.tag }}</span
                                        >
                                    </div>
                                    <p class="text-[9px] text-slate-400">
                                        {{ doc.date }} • {{ doc.size }}
                                    </p>
                                </div>
                            </div>
                            <button
                                @click="viewDocument(doc)"
                                class="w-7 h-7 flex items-center justify-center rounded-full text-slate-400 hover:bg-emerald-100 hover:text-emerald-600 transition"
                            >
                                👁️
                            </button>
                        </div>
                    </div>

                    <div
                        class="p-4 border-t border-slate-100 bg-slate-50 space-y-2"
                        v-if="phase.status !== 'Terkunci'"
                    >
                        <button
                            @click="openUploadModal(key)"
                            class="w-full py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-bold hover:border-emerald-400 hover:text-emerald-600 bg-white transition flex items-center justify-center gap-2"
                        >
                            <span>+ Upload Berkas</span>
                        </button>
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
                    class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-scale-in"
                >
                    <div
                        class="bg-emerald-600 p-5 text-white flex justify-between items-center"
                    >
                        <div>
                            <h3 class="font-bold text-lg">Upload Berkas</h3>
                            <p class="text-xs text-emerald-100">
                                Fase: {{ phases[activePhaseKey]?.title }}
                            </p>
                        </div>
                        <button
                            @click="showUploadModal = false"
                            class="text-white/70 hover:text-white font-bold text-xl"
                        >
                            ✕
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                >Nama Dokumen</label
                            >
                            <input
                                type="text"
                                v-model="uploadForm.title"
                                class="w-full border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-emerald-500"
                            />
                        </div>
                        <div
                            class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-emerald-50 cursor-pointer relative"
                        >
                            <input
                                type="file"
                                @change="handleFileSelect"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            />
                            <div v-if="!uploadForm.file">
                                <p class="text-2xl mb-2">📎</p>
                                <p class="text-sm font-bold text-slate-600">
                                    Klik untuk upload
                                </p>
                            </div>
                            <div v-else>
                                <p class="text-2xl mb-2">✅</p>
                                <p
                                    class="text-sm font-bold text-emerald-600 truncate"
                                >
                                    {{ uploadForm.file.name }}
                                </p>
                            </div>
                        </div>
                        <div
                            v-if="uploadForm.isUploading"
                            class="w-full h-2 bg-slate-100 rounded-full overflow-hidden"
                        >
                            <div
                                class="bg-emerald-500 h-full transition-all duration-200"
                                :style="`width: ${uploadForm.progress}%`"
                            ></div>
                        </div>
                    </div>
                    <div
                        class="p-5 bg-slate-50 flex justify-end gap-2 border-t border-slate-100"
                    >
                        <button
                            @click="showUploadModal = false"
                            class="px-4 py-2 text-slate-500 font-bold text-sm"
                        >
                            Batal
                        </button>
                        <button
                            @click="submitUpload"
                            :disabled="uploadForm.isUploading"
                            class="px-6 py-2 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow"
                        >
                            {{
                                uploadForm.isUploading
                                    ? "Menyimpan..."
                                    : "Simpan"
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="fade">
            <div
                v-if="showEditTenantModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-scale-in"
                >
                    <div
                        class="bg-white p-6 border-b border-slate-100 flex justify-between items-center"
                    >
                        <h3 class="font-extrabold text-xl text-slate-800">
                            Edit Profil Tenant
                        </h3>
                        <button
                            @click="showEditTenantModal = false"
                            class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 font-bold"
                        >
                            ✕
                        </button>
                    </div>
                    <div
                        class="p-6 space-y-4 max-h-[70vh] overflow-y-auto custom-scroll"
                    >
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                >Nama Usaha</label
                            >
                            <input
                                type="text"
                                v-model="editTenantForm.name"
                                class="w-full border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-emerald-500"
                            />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                    >Pemilik</label
                                >
                                <input
                                    type="text"
                                    v-model="editTenantForm.owner"
                                    class="w-full border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-emerald-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                    >Telepon</label
                                >
                                <input
                                    type="text"
                                    v-model="editTenantForm.phone"
                                    class="w-full border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-emerald-500"
                                />
                            </div>
                        </div>
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                >Klaster</label
                            >
                            <select
                                v-model="editTenantForm.cluster"
                                class="w-full border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-emerald-500"
                            >
                                <option>Makanan & Minuman</option>
                                <option>Kriya</option>
                                <option>Fashion</option>
                                <option>Teknologi</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="text-xs font-bold text-slate-500 uppercase mb-1 block"
                                >Alamat</label
                            >
                            <textarea
                                v-model="editTenantForm.address"
                                rows="2"
                                class="w-full border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:ring-emerald-500"
                            ></textarea>
                        </div>
                    </div>
                    <div
                        class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3"
                    >
                        <button
                            @click="showEditTenantModal = false"
                            class="px-5 py-2.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-200"
                        >
                            Batal
                        </button>
                        <button
                            @click="saveTenantProfile"
                            class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-bold text-sm shadow-lg hover:bg-emerald-700"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <transition name="fade">
            <div
                v-if="showDocDetailModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-scale-in"
                >
                    <div class="p-8 text-center relative">
                        <button
                            @click="showDocDetailModal = false"
                            class="absolute top-4 right-4 w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 font-bold"
                        >
                            ✕
                        </button>
                        <div
                            class="w-20 h-20 bg-emerald-50 rounded-3xl flex items-center justify-center text-5xl mx-auto mb-4 shadow-sm text-emerald-600"
                        >
                            {{ getFileIcon(selectedDocument?.type || "") }}
                        </div>
                        <h3
                            class="text-xl font-extrabold text-slate-800 px-4 leading-tight mb-2"
                        >
                            {{ selectedDocument?.title }}
                        </h3>
                        <p class="text-sm text-slate-500 font-medium">
                            Diunggah oleh {{ selectedDocument?.uploader }}
                        </p>
                        <div class="mt-6 flex justify-center gap-4">
                            <div
                                class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-100"
                            >
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase"
                                >
                                    Ukuran
                                </p>
                                <p class="text-sm font-bold text-slate-700">
                                    {{ selectedDocument?.size }}
                                </p>
                            </div>
                            <div
                                class="px-4 py-2 bg-slate-50 rounded-xl border border-slate-100"
                            >
                                <p
                                    class="text-[10px] font-bold text-slate-400 uppercase"
                                >
                                    Tanggal
                                </p>
                                <p class="text-sm font-bold text-slate-700">
                                    {{ selectedDocument?.date }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-8 flex flex-col gap-3">
                            <button
                                class="w-full py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg hover:bg-emerald-700 transition flex items-center justify-center gap-2"
                            >
                                <span>⬇️</span> Download Dokumen
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>

<style scoped>
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
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.custom-scroll::-webkit-scrollbar {
    width: 4px;
}
.custom-scroll::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 4px;
}
</style>
