<template>
  <Head title="Manajemen User" />
  <AuthenticatedLayout>
    <div class="p-8 bg-slate-50 min-h-screen">
      <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
          <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Pengguna</h1>
          <p class="text-slate-500 text-sm">Kelola hak akses dan peran pengguna sistem di sini.</p>
        </div>
        <div class="relative">
          <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </span>
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Cari nama atau email..." 
            class="pl-10 pr-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none w-full md:w-64 bg-white transition shadow-sm" 
          />
        </div>
      </div>

      <div class="flex p-1 space-x-1 bg-slate-200/50 rounded-xl mb-6 w-fit">
        <button 
          v-for="role in roles" 
          :key="role"
          @click="activeTab = role"
          :class="[
            'px-6 py-2 text-sm font-semibold rounded-lg capitalize transition-all duration-200',
            activeTab === role ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/50'
          ]"
        >
          {{ role }}
        </button>
      </div>

      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden text-sm sm:text-base">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-slate-50 border-b border-slate-100">
                <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500">Profil</th>
                <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 px-6">Email</th>
                <th v-if="activeTab === 'tenant'" class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500">Program Inkubator</th>
                <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500">Status Role</th>
                <th class="p-4 text-xs font-bold uppercase tracking-wider text-slate-500 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium">
              <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-blue-50/30 transition-colors group">
                <td class="p-4">
                  <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                      {{ user.name.charAt(0) }}
                    </div>
                    <span class="text-slate-700">{{ user.name }}</span>
                  </div>
                </td>
                <td class="p-4 px-6 text-slate-600">{{ user.email }}</td>
                <td v-if="activeTab === 'tenant'" class="p-4">
                    <span class="text-slate-700 italic">
                        {{ getProgramName(user.inkubator_id) }}
                    </span>
                </td>
                <td class="p-4">
                  <span :class="roleBadgeStyle(user.role)">
                    <span class="h-1.5 w-1.5 rounded-full mr-2" :class="roleDotStyle(user.role)"></span>
                    {{ user.role }}
                  </span>
                </td>
                <td class="p-4 text-right">
                  <button 
                    @click="openEditModal(user)"
                    class="inline-flex items-center px-3 py-1.5 border border-slate-200 text-sm font-semibold rounded-lg text-slate-700 bg-white hover:bg-slate-50 hover:border-blue-300 hover:text-blue-600 transition-all shadow-sm"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Ubah Role
                  </button>
                </td>
              </tr>
              <tr v-if="filteredUsers.length === 0">
                <td colspan="5" class="p-12 text-center text-slate-500">
                  <div class="flex flex-col items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-200 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Data tidak ditemukan.
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="isModalOpen = false"></div>
        
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all overflow-hidden z-10">
          <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-xl font-bold text-slate-800">Ubah Peran Pengguna</h2>
            <button @click="isModalOpen = false" class="text-slate-400 hover:text-slate-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div class="p-6">
            <div class="flex items-center gap-4 mb-6 p-3 bg-blue-50 rounded-xl">
              <div class="h-12 w-12 rounded-full bg-blue-600 flex items-center justify-center text-white font-bold text-lg">
                {{ selectedUser.name.charAt(0) }}
              </div>
              <div>
                <p class="font-bold text-slate-900 leading-tight">{{ selectedUser.name }}</p>
                <p class="text-sm text-blue-600">{{ selectedUser.email }}</p>
              </div>
            </div>

            <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Pilih Role Baru</label>
            <div class="grid grid-cols-2 gap-3">
              <button 
                v-for="role in roles" 
                :key="role"
                @click="newRole = role"
                :class="[
                  'relative px-4 py-3 rounded-xl border-2 transition-all duration-200 text-sm capitalize flex items-center justify-center gap-2 group',
                  newRole === role 
                    ? 'border-blue-500 ring-2 ring-blue-100 bg-blue-50 text-blue-700 font-bold' 
                    : 'border-slate-100 bg-slate-50 text-slate-500 hover:border-slate-400 font-semibold'
                ]"
              >
                <svg v-if="newRole === role" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                {{ role }}
              </button>
            </div>

            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
            >
                <div v-if="newRole === 'tenant'" class="mt-6 pt-6 border-t border-slate-100">
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Program Inkubator Bisnis</label>
                    <div class="relative">
                        <select 
                            v-model="selectedProgram"
                            class="w-full pl-4 pr-10 py-3 bg-slate-50 border-2 border-slate-100 rounded-xl focus:border-blue-400 focus:ring-2 focus:ring-blue-100 outline-none appearance-none transition-all font-semibold text-slate-700"
                        >
                            <option value="" disabled>-- Pilih Program --</option>
                            <option v-for="program in inkubatorPrograms" :key="program.id" :value="program.id">
                                {{ program.name }}
                            </option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </transition>
          </div>

          <div class="px-6 py-4 bg-slate-50 flex flex-col sm:flex-row gap-3 text-sm">
            <button @click="isModalOpen = false" class="flex-1 px-4 py-2.5 text-slate-600 font-bold hover:bg-slate-200 rounded-xl transition">Batal</button>
            <button @click="updateUserRole" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition active:scale-95">
              Simpan Perubahan
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, computed } from 'vue';

const roles = ['admin', 'staff', 'tenant', 'user'];
const activeTab = ref('admin');
const isModalOpen = ref(false);
const selectedUser = ref(null);
const newRole = ref('');
const searchQuery = ref('');

// Data Master Program Inkubator (Nanti ambil dari DB via Props/API)
const inkubatorPrograms = ref([
  { id: 1, name: 'Startup Digital Batch 1' },
  { id: 2, name: 'Inkubasi Bisnis UMKM' },
  { id: 3, name: 'Creative Techno Hub 2024' },
]);
const selectedProgram = ref('');

// Mock Data Users
const users = ref([
  { id: 1, name: 'Budi Admin', email: 'budi@admin.com', role: 'admin' },
  { id: 2, name: 'Siti Staff', email: 'siti@kantor.com', role: 'staff' },
  { id: 3, name: 'Toko Berkah', email: 'berkah@tenant.com', role: 'tenant', inkubator_id: 1 },
  { id: 4, name: 'Andi Biasa', email: 'andi@gmail.com', role: 'user' },
  { id: 5, name: 'Ani Staff', email: 'ani@kantor.com', role: 'staff' },
]);

// Helper untuk menampilkan nama program di tabel
const getProgramName = (id) => {
    const program = inkubatorPrograms.value.find(p => p.id === id);
    return program ? program.name : 'Belum memilih program';
};

const filteredUsers = computed(() => {
  let data = users.value.filter(u => u.role === activeTab.value);
  if (searchQuery.value) {
    data = data.filter(u => 
      u.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
      u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }
  return data;
});

const openEditModal = (user) => {
  selectedUser.value = { ...user };
  newRole.value = user.role;
  selectedProgram.value = user.inkubator_id || '';
  isModalOpen.value = true;
};

const updateUserRole = () => {
  if (newRole.value === 'tenant' && !selectedProgram.value) {
      alert('Tolong pilih Program Inkubator untuk user tenant.');
      return;
  }

  const index = users.value.findIndex(u => u.id === selectedUser.value.id);
  if (index !== -1) {
    users.value[index].role = newRole.value;
    
    // Update data program jika role nya tenant
    if (newRole.value === 'tenant') {
        users.value[index].inkubator_id = selectedProgram.value;
    } else {
        delete users.value[index].inkubator_id;
    }

    activeTab.value = newRole.value;
  }
  isModalOpen.value = false;
};

const roleBadgeStyle = (role) => {
  const base = "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold capitalize ";
  const colors = {
    admin: 'bg-rose-100 text-rose-700',
    staff: 'bg-indigo-100 text-indigo-700',
    tenant: 'bg-emerald-100 text-emerald-700',
    user: 'bg-slate-100 text-slate-700'
  };
  return base + colors[role];
};

const roleDotStyle = (role) => {
  const colors = {
    admin: 'bg-rose-500',
    staff: 'bg-indigo-500',
    tenant: 'bg-emerald-500',
    user: 'bg-slate-500'
  };
  return colors[role];
};
</script>