<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, reactive } from "vue";

// ==========================================
// 1. DATA DUMMY
// ==========================================
const tenantProfile = ref({
    name: "Budi Santoso",
    phone: "081234567890",
    address: "Jl. Merdeka No. 123, Medan, Sumatera Utara",
    socialMedia: [
        { type: "instagram", value: "@kripikmasadepan" },
        { type: "facebook", value: "Kripik Masa Depan" },
    ],
    businessName: "Kripik Masa Depan",
    productType: "Makanan & Minuman",
    description:
        "Kripik Masa Depan adalah usaha yang bergerak di bidang produksi kripik lokal dengan inovasi rasa modern. Kami menyediakan berbagai varian kripik dari bahan-bahan lokal berkualitas tinggi dengan harga terjangkau.",
    logo: null,
    joinDate: "10 Januari 2026",
    phase: "Inkubasi",
    // Data dummy foto produk awal dengan deskripsi
    productPhotos: [
        {
            url: "https://via.placeholder.com/400x400?text=Kripik+Pedas",
            description:
                "Kripik Pedas Manis - Varian terlaris kami dengan rasa pedas yang pas",
        },
        {
            url: "https://via.placeholder.com/400x400?text=Kripik+Original",
            description: "Kripik Original - Rasa klasik yang gurih dan renyah",
        },
    ],
});

// ==========================================
// 2. STATE & LOGIC
// ==========================================
const showEditIdentityModal = ref(false);
const showAddProductModal = ref(false);
const showProductDetailModal = ref(false);
const selectedProduct = ref(null);
const isSaving = ref(false);

const editIdentityForm = reactive({
    phone: "",
    address: "",
    socialMedia: [],
    description: "",
    logo: null,
    logoPreview: null,
});

const addProductForm = reactive({
    newPhotos: [], // Temporary array untuk foto baru yang akan ditambahkan
});

const productDetailForm = reactive({
    index: null,
    url: "",
    description: "",
});

// ==========================================
// MODAL HANDLERS
// ==========================================

// Open Edit Identity Modal
const openEditIdentityModal = () => {
    editIdentityForm.phone = tenantProfile.value.phone;
    editIdentityForm.address = tenantProfile.value.address;
    // Deep copy to avoid mutating original state directly
    editIdentityForm.socialMedia = JSON.parse(
        JSON.stringify(tenantProfile.value.socialMedia || []),
    );
    editIdentityForm.description = tenantProfile.value.description;
    editIdentityForm.logo = null;
    editIdentityForm.logoPreview = tenantProfile.value.logo;
    showEditIdentityModal.value = true;
};

// Handle Logo Change
const handleLogoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        editIdentityForm.logo = file;
        editIdentityForm.logoPreview = URL.createObjectURL(file);
    }
};

// Open Add Product Modal
const openAddProductModal = () => {
    addProductForm.newPhotos = [];
    showAddProductModal.value = true;
};

// Open Product Detail Modal (View/Edit single product)
const openProductDetailModal = (product, index) => {
    selectedProduct.value = product;
    productDetailForm.index = index;
    productDetailForm.url = product.url;
    productDetailForm.description = product.description;
    showProductDetailModal.value = true;
};

// Handle Product Photos Change (Multiple Files)
const handleProductPhotosChange = (e) => {
    const files = Array.from(e.target.files);
    files.forEach((file) => {
        addProductForm.newPhotos.push({
            url: URL.createObjectURL(file),
            description: "",
        });
    });
};

// Remove photo in add product modal
const removeNewPhoto = (index) => {
    addProductForm.newPhotos.splice(index, 1);
};

// Save Identity Changes
const saveIdentity = () => {
    isSaving.value = true;
    setTimeout(() => {
        tenantProfile.value.phone = editIdentityForm.phone;
        tenantProfile.value.address = editIdentityForm.address;
        tenantProfile.value.socialMedia = editIdentityForm.socialMedia;
        tenantProfile.value.description = editIdentityForm.description;

        if (editIdentityForm.logo) {
            tenantProfile.value.logo = editIdentityForm.logoPreview;
        }
        isSaving.value = false;
        showEditIdentityModal.value = false;
        showToast("Identitas berhasil diperbarui!");
    }, 1000);
};

// Save New Products
const saveNewProducts = () => {
    if (addProductForm.newPhotos.length === 0) {
        showToast("Pilih minimal 1 foto produk!", "error");
        return;
    }

    isSaving.value = true;
    setTimeout(() => {
        // Tambahkan foto baru ke array existing
        tenantProfile.value.productPhotos.push(
            ...addProductForm.newPhotos.map((p) => ({ ...p })),
        );

        isSaving.value = false;
        showAddProductModal.value = false;
        showToast(
            `${addProductForm.newPhotos.length} produk berhasil ditambahkan!`,
        );
    }, 1000);
};

// Save Product Detail (Edit single product)
const saveProductDetail = () => {
    if (productDetailForm.index !== null) {
        tenantProfile.value.productPhotos[productDetailForm.index].description =
            productDetailForm.description;
        showProductDetailModal.value = false;
        showToast("Deskripsi produk berhasil diperbarui!");
    }
};

// Delete Product from Detail Modal
const deleteProductFromDetail = () => {
    if (productDetailForm.index !== null) {
        tenantProfile.value.productPhotos.splice(productDetailForm.index, 1);
        showProductDetailModal.value = false;
        showToast("Produk berhasil dihapus!");
    }
};

const getInitial = (name) => {
    return name ? name.charAt(0).toUpperCase() : "?";
};

// Toast notification logic
const toast = reactive({ show: false, message: "", type: "success" });
const showToast = (msg, type = "success") => {
    toast.message = msg;
    toast.type = type;
    toast.show = true;
    setTimeout(() => {
        toast.show = false;
    }, 3000);
};

const socialMediaOptions = [
    { value: "instagram", text: "Instagram", icon: "📷" },
    { value: "facebook", text: "Facebook", icon: "👥" },
    { value: "tiktok", text: "TikTok", icon: "🎵" },
    { value: "website", text: "Website", icon: "🌐" },
    { value: "x", text: "X (Twitter)", icon: "🐦" },
    { value: "linkedin", text: "LinkedIn", icon: "💼" },
];

const getSocialIcon = (type) => {
    const option = socialMediaOptions.find((opt) => opt.value === type);
    return option ? option.icon : "🔗";
};

const addSocialMedia = () => {
    editIdentityForm.socialMedia.push({
        type: "instagram",
        value: "",
    });
};

const removeSocialMedia = (index) => {
    editIdentityForm.socialMedia.splice(index, 1);
};
</script>

<template>
    <Head title="Profil Tenant" />

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
            <div class="max-w-6xl mx-auto space-y-6">
                <div
                    class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 shadow-lg text-white relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-4"
                >
                    <div
                        class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-10 -mt-10 blur-3xl pointer-events-none"
                    ></div>
                    <div class="relative z-10">
                        <h1 class="text-3xl font-bold">Profil Tenant</h1>
                        <p class="text-emerald-100 opacity-90">
                            Kelola informasi bisnis dan identitas Anda.
                        </p>
                    </div>
                    <Link
                        :href="route('tenant.dashboard')"
                        class="relative z-10 bg-white/20 hover:bg-white/30 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition backdrop-blur-md border border-white/20"
                    >
                        ← Kembali ke Dashboard
                    </Link>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- SIDEBAR: Identitas Tenant -->
                    <div class="lg:col-span-1 space-y-6">
                        <div
                            class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden sticky top-6"
                        >
                            <div
                                class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center"
                            >
                                <h2 class="font-bold text-slate-700 text-lg">
                                    Identitas Tenant
                                </h2>
                                <button
                                    @click="openEditIdentityModal"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg font-bold text-xs shadow-md hover:shadow-lg transition flex items-center gap-1"
                                >
                                    <span>✏️</span> Edit
                                </button>
                            </div>

                            <div
                                class="p-6 flex flex-col items-center border-b border-slate-100"
                            >
                                <div
                                    class="w-24 h-24 bg-slate-50 rounded-2xl border-2 border-dashed border-emerald-200 flex items-center justify-center overflow-hidden mb-3 relative group"
                                >
                                    <img
                                        v-if="tenantProfile.logo"
                                        :src="tenantProfile.logo"
                                        class="w-full h-full object-contain p-2"
                                    />
                                    <span v-else class="text-5xl">🏢</span>
                                </div>
                                <div class="text-center">
                                    <h3
                                        class="text-lg font-bold text-slate-800 mb-1"
                                    >
                                        {{ tenantProfile.businessName }}
                                    </h3>
                                    <span
                                        class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-200"
                                    >
                                        {{ tenantProfile.phase }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-5 space-y-3 text-sm">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-sm shrink-0"
                                    >
                                        {{ getInitial(tenantProfile.name) }}
                                    </div>
                                    <div class="overflow-hidden min-w-0">
                                        <p
                                            class="font-bold text-slate-800 text-xs truncate"
                                        >
                                            {{ tenantProfile.name }}
                                        </p>
                                        <p class="text-[10px] text-slate-500">
                                            Pemilik Usaha
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-2 text-slate-700"
                                >
                                    <span class="text-base">📱</span>
                                    <span class="text-xs">{{
                                        tenantProfile.phone || "-"
                                    }}</span>
                                </div>

                                <div
                                    class="flex items-start gap-2 text-slate-700"
                                >
                                    <span class="text-base mt-0.5">📍</span>
                                    <span class="text-xs leading-relaxed">{{
                                        tenantProfile.address || "-"
                                    }}</span>
                                </div>

                                <div class="pt-2 border-t border-slate-100">
                                    <div
                                        class="flex items-center justify-between mb-2"
                                    >
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase"
                                            >Sosial Media</span
                                        >
                                    </div>
                                    <div
                                        v-if="
                                            tenantProfile.socialMedia &&
                                            tenantProfile.socialMedia.length > 0
                                        "
                                        class="space-y-1.5"
                                    >
                                        <div
                                            v-for="(
                                                social, index
                                            ) in tenantProfile.socialMedia"
                                            :key="index"
                                            class="flex items-center gap-2 text-xs"
                                        >
                                            <span>{{
                                                getSocialIcon(social.type)
                                            }}</span>
                                            <span class="text-slate-700">{{
                                                social.value
                                            }}</span>
                                        </div>
                                    </div>
                                    <div
                                        v-else
                                        class="text-[10px] text-slate-400 italic"
                                    >
                                        Belum ada sosial media
                                    </div>
                                </div>

                                <div class="pt-2 border-t border-slate-100">
                                    <div
                                        class="flex items-center gap-2 text-slate-600"
                                    >
                                        <span class="text-base">🗓️</span>
                                        <span class="text-xs">{{
                                            tenantProfile.joinDate
                                        }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- MAIN CONTENT: Informasi & Produk (Digabung) -->
                    <div class="lg:col-span-2">
                        <div
                            class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden"
                        >
                            <div
                                class="p-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center"
                            >
                                <div>
                                    <h2
                                        class="font-bold text-slate-700 text-lg"
                                    >
                                        Produk & Bisnis
                                    </h2>
                                    <p class="text-xs text-slate-400 mt-1">
                                        {{ tenantProfile.productPhotos.length }}
                                        Foto Produk
                                    </p>
                                </div>
                                <button
                                    @click="openAddProductModal"
                                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition flex items-center gap-2"
                                >
                                    <span>➕</span> Tambah Produk
                                </button>
                            </div>

                            <!-- Ganti bagian "Info Singkat" di dalam card "Produk & Bisnis" -->

                            <div class="p-6 space-y-6">
                                <!-- Info Singkat -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 pb-4 border-b border-slate-100"
                                >
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-400 uppercase mb-2"
                                            >Kategori Produk</label
                                        >
                                        <span
                                            class="inline-block px-4 py-2.5 bg-slate-100 text-slate-700 rounded-lg text-sm font-semibold border border-slate-200"
                                        >
                                            🏷️ {{ tenantProfile.productType }}
                                        </span>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-xs font-bold text-slate-400 uppercase mb-2"
                                            >Deskripsi Bisnis</label
                                        >
                                        <p
                                            class="text-slate-600 text-justify text-sm leading-relaxed"
                                        >
                                            {{
                                                tenantProfile.description ||
                                                "Belum ada deskripsi."
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Galeri Produk -->
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-400 uppercase mb-3"
                                        >Galeri Produk</label
                                    >
                                    <div
                                        v-if="
                                            tenantProfile.productPhotos.length >
                                            0
                                        "
                                        class="grid grid-cols-2 md:grid-cols-4 gap-3"
                                    >
                                        <div
                                            v-for="(
                                                photo, index
                                            ) in tenantProfile.productPhotos"
                                            :key="index"
                                            @click="
                                                openProductDetailModal(
                                                    photo,
                                                    index,
                                                )
                                            "
                                            class="group relative aspect-square bg-slate-100 rounded-xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-lg transition cursor-pointer"
                                        >
                                            <img
                                                :src="photo.url"
                                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                                            />
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition flex items-end p-2"
                                            >
                                                <p
                                                    class="text-white text-[10px] font-medium line-clamp-2"
                                                >
                                                    {{
                                                        photo.description ||
                                                        "Klik untuk detail"
                                                    }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        v-else
                                        class="text-center py-10 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200"
                                    >
                                        <span
                                            class="text-4xl block mb-2 opacity-50"
                                            >📷</span
                                        >
                                        <p class="text-sm text-slate-500 mb-2">
                                            Belum ada foto produk.
                                        </p>
                                        <button
                                            @click="openAddProductModal"
                                            class="text-emerald-600 hover:text-emerald-700 font-bold text-xs hover:underline"
                                        >
                                            Tambahkan foto pertama →
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL: Edit Identitas -->
        <transition name="fade">
            <div
                v-if="showEditIdentityModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-scale-in max-h-[90vh] flex flex-col"
                >
                    <div
                        class="bg-emerald-600 p-5 flex justify-between items-center text-white shrink-0"
                    >
                        <h3 class="font-bold text-lg">Edit Identitas</h3>
                        <button
                            @click="showEditIdentityModal = false"
                            class="text-white/80 hover:text-white text-2xl font-bold"
                        >
                            &times;
                        </button>
                    </div>

                    <div class="p-6 space-y-5 overflow-y-auto custom-scrollbar">
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-2"
                                >Logo Bisnis</label
                            >
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-24 h-24 bg-slate-100 rounded-2xl border-2 border-dashed border-emerald-200 flex items-center justify-center overflow-hidden relative group"
                                >
                                    <img
                                        v-if="editIdentityForm.logoPreview"
                                        :src="editIdentityForm.logoPreview"
                                        class="w-full h-full object-contain p-2"
                                    />
                                    <span v-else class="text-5xl">🏢</span>
                                </div>
                                <div class="relative">
                                    <input
                                        type="file"
                                        @change="handleLogoChange"
                                        accept="image/*"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                    />
                                    <button
                                        type="button"
                                        class="bg-white border border-slate-300 hover:bg-slate-100 text-slate-700 px-4 py-2 rounded-lg font-bold text-xs shadow-sm transition"
                                    >
                                        Ganti Logo
                                    </button>
                                    <p class="text-[10px] text-slate-400 mt-1">
                                        PNG atau JPG (Max 2MB)
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-2"
                                >No. Telepon</label
                            >
                            <input
                                type="text"
                                v-model="editIdentityForm.phone"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition"
                                placeholder="Contoh: 081234567890"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-2"
                                >Alamat</label
                            >
                            <textarea
                                v-model="editIdentityForm.address"
                                rows="3"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition resize-none"
                                placeholder="Alamat lengkap bisnis Anda..."
                            ></textarea>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-3"
                                >Sosial Media</label
                            >
                            <div class="space-y-2">
                                <div
                                    v-for="(
                                        social, index
                                    ) in editIdentityForm.socialMedia"
                                    :key="index"
                                    class="flex items-center gap-2 p-2 bg-slate-50 rounded-lg"
                                >
                                    <select
                                        v-model="social.type"
                                        class="pl-4 pr-10 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white"
                                    >
                                        <option
                                            v-for="option in socialMediaOptions"
                                            :key="option.value"
                                            :value="option.value"
                                        >
                                            {{ option.text }}
                                        </option>
                                    </select>
                                    <input
                                        type="text"
                                        v-model="social.value"
                                        class="flex-1 px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition bg-white"
                                        placeholder="Username atau URL"
                                    />
                                    <button
                                        @click="removeSocialMedia(index)"
                                        type="button"
                                        class="w-9 h-9 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition font-bold text-lg"
                                        title="Hapus"
                                    >
                                        &times;
                                    </button>
                                </div>
                                <button
                                    @click="addSocialMedia"
                                    type="button"
                                    class="w-full text-center py-2.5 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-lg hover:bg-emerald-100 transition border-2 border-dashed border-emerald-200"
                                >
                                    + Tambah Sosial Media
                                </button>
                            </div>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-2"
                                >Deskripsi Bisnis</label
                            >
                            <textarea
                                v-model="editIdentityForm.description"
                                rows="5"
                                maxlength="500"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition resize-none"
                                placeholder="Ceritakan tentang bisnis dan keunggulan produk Anda..."
                            ></textarea>
                            <p
                                class="text-[10px] text-slate-400 mt-1 text-right"
                            >
                                {{ editIdentityForm.description.length }}/500
                            </p>
                        </div>

                        <div
                            class="bg-blue-50 p-3 rounded-lg flex items-start gap-3 border border-blue-100"
                        >
                            <span class="text-xl">ℹ️</span>
                            <p class="text-xs text-blue-700 leading-relaxed">
                                <strong>Catatan:</strong> Data seperti Nama
                                Tenant, Kategori Produk, dan Fase Inkubasi
                                dikelola oleh admin. Hubungi admin jika terdapat
                                kesalahan data tersebut.
                            </p>
                        </div>
                    </div>

                    <div
                        class="p-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 shrink-0"
                    >
                        <button
                            @click="showEditIdentityModal = false"
                            class="px-5 py-2.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-200 transition"
                        >
                            Batal
                        </button>
                        <button
                            @click="saveIdentity"
                            :disabled="isSaving"
                            class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:bg-emerald-700 flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed transition-all active:scale-95"
                        >
                            <span
                                v-if="isSaving"
                                class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"
                            ></span>
                            {{ isSaving ? "Menyimpan..." : "Simpan Perubahan" }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- MODAL: Tambah Produk (Simplified - hanya upload) -->
        <transition name="fade">
            <div
                v-if="showAddProductModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden animate-scale-in"
                >
                    <div
                        class="bg-emerald-600 p-5 flex justify-between items-center text-white shrink-0"
                    >
                        <h3 class="font-bold text-lg">Tambah Produk</h3>
                        <button
                            @click="showAddProductModal = false"
                            class="text-white/80 hover:text-white text-2xl font-bold"
                        >
                            &times;
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div
                            class="border-2 border-dashed border-emerald-300 rounded-xl p-8 text-center relative hover:bg-emerald-50 transition cursor-pointer bg-emerald-50/30"
                        >
                            <input
                                type="file"
                                multiple
                                @change="handleProductPhotosChange"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            />
                            <span class="text-5xl block mb-3">📸</span>
                            <p class="text-sm font-bold text-emerald-700 mb-1">
                                Klik atau drag & drop untuk memilih foto
                            </p>
                            <p class="text-xs text-emerald-600">
                                PNG, JPG, atau JPEG (Max 5MB per file)
                            </p>
                        </div>

                        <div
                            v-if="addProductForm.newPhotos.length > 0"
                            class="space-y-3"
                        >
                            <p
                                class="text-xs font-bold text-slate-500 uppercase"
                            >
                                {{ addProductForm.newPhotos.length }} Foto
                                Terpilih
                            </p>
                            <div
                                class="space-y-2 max-h-[300px] overflow-y-auto custom-scrollbar"
                            >
                                <div
                                    v-for="(
                                        photo, index
                                    ) in addProductForm.newPhotos"
                                    :key="index"
                                    class="flex items-start gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200"
                                >
                                    <img
                                        :src="photo.url"
                                        class="w-16 h-16 object-cover rounded-lg shrink-0"
                                    />
                                    <div class="flex-1 min-w-0">
                                        <input
                                            type="text"
                                            v-model="photo.description"
                                            class="w-full text-xs border border-slate-200 rounded-lg p-2 mb-1 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                            placeholder="Deskripsi produk"
                                        />
                                    </div>
                                    <button
                                        @click="removeNewPhoto(index)"
                                        class="text-red-500 hover:text-red-700 font-bold text-lg shrink-0"
                                        title="Hapus"
                                    >
                                        ✕
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="p-5 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 shrink-0"
                    >
                        <button
                            @click="showAddProductModal = false"
                            class="px-5 py-2.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-200 transition"
                        >
                            Batal
                        </button>
                        <button
                            @click="saveNewProducts"
                            :disabled="isSaving"
                            class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:bg-emerald-700 flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed transition-all active:scale-95"
                        >
                            <span
                                v-if="isSaving"
                                class="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full"
                            ></span>
                            {{ isSaving ? "Menyimpan..." : "Simpan Produk" }}
                        </button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- MODAL: Detail Produk (View/Edit Single Product) -->
        <transition name="fade">
            <div
                v-if="showProductDetailModal"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
            >
                <div
                    class="bg-white rounded-3xl shadow-2xl w-full max-w-2xl overflow-hidden animate-scale-in"
                >
                    <div
                        class="bg-gradient-to-r from-emerald-600 to-teal-600 p-5 flex justify-between items-center text-white shrink-0"
                    >
                        <h3 class="font-bold text-lg">Detail Produk</h3>
                        <button
                            @click="showProductDetailModal = false"
                            class="text-white/80 hover:text-white text-2xl font-bold"
                        >
                            &times;
                        </button>
                    </div>

                    <div class="p-6">
                        <div
                            class="aspect-video bg-slate-100 rounded-2xl overflow-hidden mb-4 border border-slate-200"
                        >
                            <img
                                :src="productDetailForm.url"
                                class="w-full h-full object-contain"
                            />
                        </div>

                        <div>
                            <label
                                class="block text-xs font-bold text-slate-500 uppercase mb-2"
                                >Deskripsi Produk</label
                            >
                            <textarea
                                v-model="productDetailForm.description"
                                rows="4"
                                class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition resize-none"
                                placeholder="Tambahkan deskripsi produk..."
                            ></textarea>
                        </div>
                    </div>

                    <div
                        class="p-5 bg-slate-50 border-t border-slate-100 flex justify-between gap-3 shrink-0"
                    >
                        <button
                            @click="deleteProductFromDetail"
                            class="px-5 py-2.5 rounded-xl text-red-600 font-bold text-sm hover:bg-red-50 transition border border-red-200"
                        >
                            🗑️ Hapus
                        </button>
                        <div class="flex gap-3">
                            <button
                                @click="showProductDetailModal = false"
                                class="px-5 py-2.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-200 transition"
                            >
                                Batal
                            </button>
                            <button
                                @click="saveProductDetail"
                                class="px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-lg hover:shadow-xl hover:bg-emerald-700 transition active:scale-95"
                            >
                                Simpan
                            </button>
                        </div>
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
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #f1f5f9;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: #94a3b8;
}
</style>
