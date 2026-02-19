<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";

const props = defineProps({
    programs: Array
})

const stats = [
    {
        title: "Total program",
        value: props.programs.length.toString(),
        unit: "Program",
        icon: "M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z",
        color: "text-blue-600",
        bg: "bg-blue-50",
    },
    {
        title: "Total Tenant",
        value: props.programs.reduce((total, program) => total + (program.tenants?.length ?? 0), 0).toString(),
        unit: "UMKM",
        icon: "M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z",
        color: "text-orange-600",
        bg: "bg-orange-50",
    },
    {
        title: "Tenant Aktif",
        value: props.programs
              .filter(program => [1, 2, 4].includes(program.tahapan_inkubasi_id))
              .reduce((total, program) => total + (program.tenants?.length ?? 0), 0)
              .toString(),
        unit: "Inkubasi",
        icon: "M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z",
        color: "text-emerald-600",
        bg: "bg-emerald-50",
    },
    {
        title: "Total Alumni",
        value: props.programs
              .filter(program => [3].includes(program.tahapan_inkubasi_id))
              .reduce((total, program) => total + (program.tenants?.length ?? 0), 0)
              .toString(),
        unit: "Pasca Inkubasi",
        icon: "M4.26 10.147a60.436 60.436 0 0 0-.491 6.347A48.627 48.627 0 0 1 12 20.904a48.627 48.627 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.57 50.57 0 0 0-2.658-.813A59.905 59.905 0 0 1 12 3.493a59.902 59.902 0 0 1 10.499 5.258 50.55 50.55 0 0 0-2.658.813m-15.482 0A50.55 50.55 0 0 1 12 13.489a50.55 50.55 0 0 1 10.499-3.342",
        color: "text-purple-600",
        bg: "bg-purple-50",
    },
];

const tahapStyle = (tahap) => {
  const base = 'px-3 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider ';
  switch (tahap) {
    case 'PRA-INKUBASI': return base + 'bg-blue-50 text-blue-600 border border-blue-100';
    case 'PASCA-INKUBASI': return base + 'bg-emerald-50 text-emerald-600 border border-emerald-100';
    default: return base + 'bg-slate-50 text-slate-500 border border-slate-100';
  }
};

const tahapan = [
  { id: 4, nama: 'PRA-KEWIRAUSAHAAN' },
  { id: 1, nama: 'PRA-INKUBASI' },
  { id: 2, nama: 'INKUBASI' },
  { id: 3, nama: 'PASCA-INKUBASI' },
];

const isModalOpen = ref(false);
const openAddModal = () => {
  isModalOpen.value = true;
};

const form = useForm({
  id: null,
  nama: '',
  deskripsi: '',
  tanggal: '',
  isPraKewirausahaan: false,
  idTahapan: null,
  namaTahapan: ''
});

const isViewModalOpen = ref(false);
const openViewModal = (program) => {
  form.id = program.id;
  form.tenants = program.tenants; //isi data looping nama tenant yang ada di program
  form.nama = program.nama_program;
  form.deskripsi = program.deskripsi;
  form.tanggal = program.tanggal_penyelenggaraan?.substring(0, 10)
  form.isPraKewirausahaan = program.pra_kewirausahaan;
  form.idTahapan = program.tahapan_inkubasi_id;
  form.namaTahapan = program.stage?.tahapan_inkubasi || '';

  isViewModalOpen.value = true;
  console.log('View form data:', form);
};

const isEditModalOpen = ref(false);
const openEditModal = (program) => {
  form.id = program.id;
  form.nama = program.nama_program;
  form.deskripsi = program.deskripsi;
  form.tanggal = program.tanggal_penyelenggaraan?.substring(0, 10)
  form.isPraKewirausahaan = program.pra_kewirausahaan;
  form.idTahapan = program.tahapan_inkubasi_id;
  form.namaTahapan = program.stage?.tahapan_inkubasi || '';

  isEditModalOpen.value = true;
  console.log('Edit form data:', form);

};

const updateProgram = () => {
    // Validasi sederhana sebelum kirim
    if (!form.nama || !form.deskripsi || !form.tanggal) {
        alert('Harap isi semua field yang diperlukan.');
        return;
    }

    // Kirim data ke Laravel menggunakan PUT
    form.patch(route('admin.update-program', form.id), {
        onSuccess: () => {
            isEditModalOpen.value = false;
            form.reset();
        },
        onError: (errors) => {
            console.log('Error:', errors);
        },
        onFinish: () => {
            console.log('Edit program selesai');  
        },
    });
};

const selectedProgram = ref(null);
const isDeleteModalOpen = ref(false);
const openDeleteModal = (program) => {
    selectedProgram.value = program;
    isDeleteModalOpen.value = true;
};

const confirmDelete = () => {
    if (!selectedProgram.value) return;

    // Kirim permintaan delete ke Laravel
    form.delete(route('admin.delete-program', selectedProgram.value.id), {
        onSuccess: () => {
            isDeleteModalOpen.value = false;
            selectedProgram.value = null;
        },
        onFinish: () => {
            console.log('Hapus program selesai');  
        },
    });
};



const newProgram = useForm({
    nama: '',
    deskripsi: '',
    tanggal: '',
    isPraKewirausahaan: false
});

const addProgram = () => {
    // Validasi sederhana sebelum kirim
    if (!newProgram.nama || !newProgram.deskripsi || !newProgram.tanggal) {
        alert('Harap isi semua field yang diperlukan.');
        return;
    }

    // Kirim data ke Laravel menggunakan POST
    newProgram.post(route('admin.add-program'), {
        onSuccess: () => {
            isModalOpen.value = false;
            newProgram.reset();
        },
        onError: (errors) => {
            console.log('Error:', errors);
        },
        onFinish: () => {
            console.log('Add program selesai');  
          // Logika setelah selesai (opsional)
        },
    });
};

</script>

<template>
  <Head title="Dashboard Admin" />
  <AuthenticatedLayout>
    <div class="p-8 bg-slate-50 min-h-screen">
      <div class="max-w-7xl mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
          <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Program</h1>
            <p class="text-slate-500 text-sm">Kelola program inkubasi dan akselerasi bisnis Anda.</p>
          </div>
          <button 
            @click="openAddModal"
            class="flex items-center justify-center bg-gradient-to-r from-teal-600 to-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-emerald-100 transition-all transform active:scale-95"
          >
            <span class="text-xl mr-2 leading-none">+</span>
            Tambah Program Baru
          </button>
        </div>

        <!-- Modal Add Program -->
         <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="isModalOpen = false"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden">
                    <div class="px-6 py-4 border-b flex justify-between items-center">
                        <h2 class="text-xl font-bold text-slate-800">Tambah Program Baru</h2>
                    </div>

                    <div class="px-6 py-6 space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Program</label>
                            <input v-model="newProgram.nama" type="text" placeholder="Masukkan nama program"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                            <textarea v-model="newProgram.deskripsi" rows="3" placeholder="Jelaskan detail program..."
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Pelaksanaan</label>
                            <input v-model="newProgram.tanggal" type="date"
                                class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                        </div>

                        <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                            <input v-model="newProgram.isPraKewirausahaan" type="checkbox" id="praKewirausahaan"
                                class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                            <label for="praKewirausahaan" class="text-sm font-medium text-slate-700 cursor-pointer">
                                Apakah ada Pra-kewirausahaan?
                            </label>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 flex gap-3">
                        <button @click="isModalOpen = false" class="flex-1 py-2.5 font-bold text-slate-600 hover:text-slate-800 transition-colors">
                            Batal
                        </button>
                        <button @click="addProgram" 
                            class="flex-1 py-2.5 bg-gradient-to-br from-indigo-600 to-violet-600 text-white font-bold rounded-xl hover:opacity-90 transition-opacity shadow-lg shadow-indigo-200">
                            {{ newProgram.processing ? 'Menyimpan...' : 'Simpan Program Baru' }}
                        </button>
                    </div>
                </div>
          </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
          <div
            v-for="(stat, index) in stats"
            :key="index"
            class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all group"
          >
            <div class="flex justify-between items-start">
              <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                  {{ stat.title }}
                </p>
                <h3 class="text-3xl font-bold text-slate-800 mt-1 group-hover:text-teal-600 transition-colors">
                  {{ stat.value }}
                </h3>
                <p class="text-[11px] font-medium text-slate-400 mt-1">
                  {{ stat.unit }}
                </p>
              </div>
              <div :class="['p-3 rounded-xl transition-colors', stat.bg]">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="2"
                  stroke="currentColor"
                  :class="['w-6 h-6', stat.color]"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
          <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white">
            <h2 class="font-bold text-slate-700">Daftar Program Inkubasi</h2>
            <div class="relative">
                <input 
                    type="text" 
                    placeholder="Cari program..." 
                    class="pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 w-64 transition-all"
                />
                
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
          </div>
          
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/50 text-slate-500 text-[11px] uppercase tracking-[0.15em] font-bold">
                  <th class="px-8 py-4">Nama Program</th>
                  <th class="px-8 py-4 text-center">Total Tenant</th>
                  <th class="px-8 py-4 text-center">Tahapan</th>
                  <th class="px-8 py-4 text-center">Tahun</th>
                  <th class="px-8 py-4 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="program in programs" :key="program.id" class="hover:bg-slate-50/80 transition-colors group">
                  <td class="px-8 py-5">
                    <div class="font-bold text-slate-700 text-sm tracking-tight">{{ program.nama_program }}</div>
                  </td>
                  <td class="px-8 py-5 text-center">
                    <span class="text-sm font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">{{ program.tenants.length }}</span>
                  </td>
                  <td class="px-8 py-5 text-center">
                    <span :class="tahapStyle(program.stage?.tahapan_inkubasi)">
                      {{ program.stage?.tahapan_inkubasi }}
                    </span>
                  </td>
                  <td class="px-8 py-5 text-center">
                    <span class="text-indigo-600 text-xs font-bold tracking-wider">{{ program.tanggal_penyelenggaraan.split('-')[0] }}</span>
                  </td>

                  <!-- Action Button -->
                  <td class="px-8 py-5 text-right">

                    <!-- Button View -->
                    <button @click="openViewModal(program)" 
                            class="group p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" 
                            title="View Detail">
                      <svg xmlns="http://www.w3.org/2000/svg" 
                          class="h-5 w-5" 
                          fill="none" 
                          viewBox="0 0 24 24" 
                          stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>

                    <!-- Button Edit -->
                    <button @click="openEditModal(program)" class="p-2 text-slate-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-all">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>

                    <!-- Button Delete -->
                      <button 
                          @click="openDeleteModal(program)" 
                          class="text-red-600 font-bold hover:text-red-800 transition-colors"
                      >
                          <svg xmlns="http://www.w3.org/2000/svg" 
                              class="h-5 w-5" 
                              fill="none" 
                              viewBox="0 0 24 24" 
                              stroke="currentColor">
                              <path stroke-linecap="round" 
                                  stroke-linejoin="round" 
                                  stroke-width="2" 
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m3-3h4a1 1 0 011 1v2H9V5a1 1 0 011-1z" />
                          </svg>
                      </button>
                  </td>

                    <!-- View Modal Program -->
                     <div v-if="isViewModalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                          
                          <div @click="isViewModalOpen = false" class="fixed inset-0 bg-slate-500 bg-opacity-75 transition-opacity"></div>

                          <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                          <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            
                            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                              <h3 class="text-lg font-bold text-slate-800" id="modal-title">Detail Program</h3>
                              <button @click="isViewModalOpen = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                              </button>
                            </div>

                            <div class="px-6 py-6 space-y-4">
                              <div>
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nama Program</label>
                                <p class="text-slate-700 font-medium mt-1">{{ form.nama }}</p>
                              </div>

                              <div class="grid grid-cols-2 gap-4">
                                <div>
                                  <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Penyelenggaraan</label>
                                  <p class="text-slate-700 mt-1">{{ form.tanggal || '-' }}</p>
                                </div>
                                <div>
                                  <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tahapan</label>
                                  <div class="mt-1">
                                    <span class="px-2 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-md border border-blue-100">
                                      {{ form.namaTahapan }}
                                    </span>
                                  </div>
                                </div>
                              </div>

                              <div>
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Deskripsi</label>
                                <p class="text-slate-600 text-sm mt-1 leading-relaxed bg-slate-50 p-3 rounded-lg border border-slate-100 italic">
                                  "{{ form.deskripsi || 'Tidak ada deskripsi' }}"
                                </p>
                              </div>

                              <div>
                                <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tenants Terdaftar ({{ form.tenants?.length || 0 }})</label>
                                <div class="mt-2 flex flex-wrap gap-2">
                                  <template v-if="form.tenants && form.tenants.length > 0">
                                    <span v-for="tenant in form.tenants" :key="tenant.id" 
                                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-teal-50 text-teal-700 border border-teal-100">
                                      <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                      </svg>
                                      {{ tenant.nama_tenant }} </span>
                                  </template>
                                  <p v-else class="text-sm text-slate-400 italic">Belum ada tenant</p>
                                </div>
                              </div>

                              <div class="flex items-center space-x-2 pt-2">
                                  <div :class="form.isPraKewirausahaan ? 'bg-green-500' : 'bg-slate-300'" class="w-2 h-2 rounded-full"></div>
                                  <span class="text-sm text-slate-600">Status Pra-Kewirausahaan: <strong>{{ form.isPraKewirausahaan ? 'Ya' : 'Tidak' }}</strong></span>
                              </div>
                            </div>

                            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 text-right">
                              <button @click="isViewModalOpen = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 font-medium transition-all shadow-sm">
                                Tutup
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- Modal Edit Program -->
                      <div v-if="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                          <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="isEditModalOpen = false"></div>
                          
                          <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden">
                              <div class="px-6 py-4 border-b flex justify-between items-center">
                                  <h2 class="text-xl font-bold text-slate-800">Edit Program</h2>
                              </div>

                              <div class="px-6 py-6 space-y-4">
                                  <div>
                                      <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Program</label>
                                      <input v-model="form.nama" type="text"
                                          class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                                  </div>

                                  <div>
                                      <label class="block text-sm font-semibold text-slate-700 mb-1">Deskripsi</label>
                                      <textarea v-model="form.deskripsi" rows="3" placeholder="Jelaskan detail program..."
                                          class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all"></textarea>
                                  </div>

                                  <div>
                                      <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Pelaksanaan</label>
                                      <input v-model="form.tanggal" type="date"
                                          class="w-full px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition-all">
                                  </div>

                                  <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                                      <input v-model="form.isPraKewirausahaan" type="checkbox" id="praKewirausahaan"
                                          class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                      <label for="praKewirausahaan" class="text-sm font-medium text-slate-700 cursor-pointer">
                                          Apakah ada Pra-kewirausahaan?
                                      </label>
                                  </div>

                                  <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">TAHAP PROGRAM</label>
                                    <select v-model="form.idTahapan" class="w-full p-3 bg-slate-50 border-2 border-slate-100 rounded-xl">
                                        <option value="" disabled>-- Pilih Tahapan Program --</option>
                                        <option v-for="tahap in tahapan" :key="tahap.id" :value="tahap.id">{{ tahap.nama }}</option>
                                    </select>
                                  </div>
                              </div>

                              <div class="px-6 py-4 bg-slate-50 flex gap-3">
                                  <button @click="isEditModalOpen = false" class="flex-1 py-2.5 font-bold text-slate-600 hover:text-slate-800 transition-colors">
                                      Batal
                                  </button>
                                  <button @click="updateProgram" 
                                      class="flex-1 py-2.5 bg-gradient-to-br from-indigo-600 to-violet-600 text-white font-bold rounded-xl hover:opacity-90 transition-opacity shadow-lg shadow-indigo-200">
                                      {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                                  </button>
                              </div>
                          </div>
                      </div>

                    <!-- Delete Confirmation Modal -->
                      <div v-if="isDeleteModalOpen" 
                          class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">

                          <div class="bg-white rounded-2xl shadow-xl w-96 p-6 animate-fadeIn">
                              
                              <h2 class="text-lg font-bold text-gray-800 mb-3">
                                  Konfirmasi Hapus
                              </h2>

                              <p class="text-gray-600 mb-6">
                                  Apakah Anda yakin ingin menghapus program 
                                  <span class="font-semibold text-red-600">
                                      {{ selectedProgram?.nama_program }}
                                  </span> ?
                              </p>

                              <div class="flex justify-end gap-3">
                                  
                                  <button 
                                      @click="isDeleteModalOpen = false"
                                      class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 transition">
                                      Batal
                                  </button>

                                  <button 
                                      @click="confirmDelete"
                                      class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                                      Hapus
                                  </button>

                              </div>
                          </div>
                      </div>

                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

