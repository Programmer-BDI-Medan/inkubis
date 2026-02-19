<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import { ref, computed } from "vue";

// 1. DATA form Backend
const props = defineProps({
    user: Object,
    programs: Array
})

const programs = ref(props.programs);

// 3. LOGIC FILTER
const searchQuery = ref("");
const filterStatus = ref("");
const filterYear = ref("");

const filteredPrograms = computed(() => {
    return programs.value.filter((program) => {
        const matchSearch =
            program.nama_program
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) 
        const matchStatus = filterStatus.value
            ? program.stage.tahapan_inkubasi === filterStatus.value
            : true;
        const matchYear = filterYear.value
            ? new Date(program.tanggal_penyelenggaraan).getFullYear() === parseInt(filterYear.value)
            : true;
        return matchSearch && matchStatus && matchYear;
    });
});

const years = computed(() =>
    [...new Set(
        programs.value.map(p =>
            new Date(p.tanggal_penyelenggaraan).getFullYear()
        )
    )].sort((a, b) => b - a)
);

const getStatusClass = (status) => {
    if (status === "PRA-KEWIRAUSAHAAN")
        return "bg-amber-50 text-amber-700 border-amber-200";
    if (status === "PRA-INKUBASI")
        return "bg-blue-50 text-blue-700 border-blue-200";
    if (status === "MASA-INKUBASI")
        return "bg-emerald-50 text-emerald-700 border-emerald-200";
    if (status === "PASCA-INKUBASI")
        return "bg-indigo-50 text-indigo-700 border-indigo-200";
    return "bg-slate-50 text-slate-600 border-slate-200";
};
</script>

<template>
    <Head title="Dashboard Program" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-slate-50 font-sans p-6 md:p-10">
            <div class="max-w-7xl mx-auto mb-6">
                <div
                    class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-lg overflow-hidden"
                >
                    <div
                        class="px-6 py-6 md:px-8 md:py-8 flex flex-col md:flex-row items-center justify-between gap-4"
                    >
                        <div class="md:text-left">
                            <h1
                                class="text-2xl md:text-3xl font-bold text-white mb-1"
                            >
                                Hai, {{ user.name }}! 👋
                            </h1>
                            <p
                                class="text-emerald-50 text-sm font-medium opacity-90"
                            >
                                Selamat datang di dashboard monitoring program.
                            </p>
                        </div>

                        <div
                            class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-5 py-2.5 text-center min-w-[140px] flex flex-col justify-center"
                        >
                            <span
                                class="text-emerald-50 text-[10px] font-bold uppercase tracking-wider"
                                >Total Program</span
                            >
                            <span class="text-xl font-extrabold text-white">{{
                                programs.length
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="max-w-7xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden"
            >
                <div
                    class="px-6 py-5 md:px-8 md:py-6 border-b border-slate-100 bg-white"
                >
                    <div
                        class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-6"
                    >
                        <div>
                            <h3 class="font-bold text-slate-800 text-xl">
                                Daftar Program
                            </h3>
                            <p class="text-sm text-slate-500 mt-1">
                                Kelola data program inkubasi Anda di sini.
                            </p>
                        </div>

                        <div
                            class="flex flex-col sm:flex-row w-full xl:w-auto gap-3"
                        >
                            <select
                                v-model="filterYear"
                                class="w-full sm:w-32 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 hover:bg-white transition cursor-pointer"
                            >
                                <option value="">Semua Tahun</option>
                                <option v-for="y in years" :key="y" :value="y">
                                    {{ y }}
                                </option>
                            </select>

                            <select
                                v-model="filterStatus"
                                class="w-full sm:w-48 px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-600 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 hover:bg-white transition cursor-pointer"
                            >
                                <option value="">Semua Status</option>
                                <option value="PRA-KEWIRAUSAHAAN">
                                    🟢 Pra-Kewirausahaan
                                </option>
                                <option value="PRA-INKUBASI">
                                    🔵 Pra-Inkubasi
                                </option>
                                <option value="MASA-INKUBASI">🟡 Masa Inkubasi</option>
                                <option value="PASCA-INKUBASI">
                                    ⚪ Pasca Inkubasi
                                </option>
                            </select>

                            <div class="relative w-full sm:w-64">
                                <div
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                                >
                                    <svg
                                        class="h-4 w-4 text-slate-400"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50 hover:bg-white transition"
                                    placeholder="Cari program atau mitra..."
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-bold"
                            >
                                <th class="px-6 py-4 md:px-8">Nama Program</th>
                                <th class="px-6 py-4">Tahun</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 bg-white">
                            <tr
                                v-for="program in filteredPrograms"
                                :key="program.id"
                                class="group hover:bg-slate-50/80 transition-colors duration-200"
                            >
                                <td class="px-6 py-5 md:px-8 align-middle">
                                    <div class="flex flex-col">
                                        <span
                                            class="font-bold text-slate-800 text-sm md:text-base mb-1"
                                            >{{ program.nama_program }}</span
                                        >
                                    </div>
                                </td>

                                <td class="px-6 py-5 align-middle">
                                    <span
                                        class="font-mono text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200"
                                    >
                                        {{ program.tanggal_penyelenggaraan.split('-')[0] }}
                                    </span>
                                </td>

                                <td class="px-6 py-5 align-middle text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border shadow-sm"
                                        :class="getStatusClass(program.status)"
                                    >
                                        {{ program.stage.tahapan_inkubasi }}
                                    </span>
                                </td>

                                <td
                                    class="px-6 py-5 md:px-8 align-middle text-center"
                                >
                                    <Link
                                        :href="
                                            route('staff.program', {
                                                id: program.id,
                                            })
                                        "
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-white border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 rounded-xl text-xs font-bold transition-all shadow-sm active:scale-95"
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
                                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                                            />
                                        </svg>
                                        Kelola File
                                    </Link>
                                </td>
                            </tr>

                            <tr v-if="filteredPrograms.length === 0">
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div
                                        class="flex flex-col items-center justify-center"
                                    >
                                        <div
                                            class="bg-slate-50 p-4 rounded-full mb-3"
                                        >
                                            <svg
                                                class="h-8 w-8 text-slate-400"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                                />
                                            </svg>
                                        </div>
                                        <h3
                                            class="text-slate-900 font-bold text-sm"
                                        >
                                            Data tidak ditemukan
                                        </h3>
                                        <p
                                            class="text-slate-500 text-xs mt-1 mb-4"
                                        >
                                            Coba ubah kata kunci atau filter
                                            pencarian Anda.
                                        </p>
                                        <button
                                            @click="
                                                searchQuery = '';
                                                filterStatus = '';
                                                filterYear = '';
                                            "
                                            class="text-emerald-600 text-xs font-bold hover:text-emerald-700 hover:underline transition"
                                        >
                                            Reset Filter
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
