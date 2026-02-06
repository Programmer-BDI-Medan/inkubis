<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3"; // Tambahkan useForm
import { ref, computed } from 'vue';

const props = defineProps({
    users: Array
})

// --- STATE UI ---
const roles = ['admin', 'staff', 'tenant', 'user'];
const activeTab = ref('user');
const isModalOpen = ref(false);
const searchQuery = ref('');
const inkubatorPrograms = ref([
    { id: 1, name: 'Startup Digital Batch 1' },
    { id: 2, name: 'Inkubasi Bisnis UMKM' },
    { id: 3, name: 'Creative Techno Hub 2024' },
]);

// --- STATE FORM (Inertia useForm) ---
const form = useForm({
    id: null,
    name: '', 
    email: '', 
    role: '',
    inkubator_id: null,
});

// --- LOGIC ---

const filteredUsers = computed(() => {
    // filter langsung dari props.users
    let data = props.users.filter(u => u.role === activeTab.value);
    if (searchQuery.value) {
        data = data.filter(u => 
            u.name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
            u.email.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    return data;
});

const openEditModal = (user) => {
    // Isi data form dengan user yang dipilih
    form.id = user.id;
    form.name = user.name;
    form.email = user.email;
    form.role = user.role;
    form.inkubator_id = user.inkubator_id || '';
    
    isModalOpen.value = true;
};

const updateUserRole = () => {
    // Validasi sederhana sebelum kirim
    if (form.role === 'tenant' && !form.inkubator_id) {
        alert('Tolong pilih Program Inkubator untuk user tenant.');
        return;
    }

    // Kirim data ke Laravel menggunakan PATCH
    // Ganti 'users.updateRole' dengan nama route di web.php kamu
    form.patch(route('admin.updateRole', form.id), {
        onSuccess: () => {
            isModalOpen.value = false;
            activeTab.value = form.role; // Pindah tab ke role baru setelah berhasil
            form.reset();
        },
        onFinish: () => {
            console.log('Update role selesai');  
          // Logika setelah selesai (opsional)
        },
    });
};

// Helper tampilan (Badge & Program Name)
const getProgramName = (id) => {
    const program = inkubatorPrograms.value.find(p => p.id === id);
    return program ? program.name : 'Belum memilih program';
};

const roleBadgeStyle = (role) => {
    const base = "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold capitalize ";
    const colors = {
        admin: 'bg-rose-100 text-rose-700',
        staff: 'bg-indigo-100 text-indigo-700',
        tenant: 'bg-violet-100 text-violet-700', // Diubah ke Violet
        user: 'bg-slate-100 text-slate-700'
    };
    return colors[role] ? base + colors[role] : base + colors.user;
};

const roleDotStyle = (role) => {
    const colors = {
        admin: 'bg-rose-500',
        staff: 'bg-indigo-500',
        tenant: 'bg-violet-500', // Diubah ke Violet
        user: 'bg-slate-500'
    };
    return colors[role] || 'bg-slate-500';
};
</script>

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
                    <input v-model="searchQuery" type="text" placeholder="Cari nama atau email..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-xl w-full md:w-64" />
                </div>
            </div>

            <div class="flex p-1 space-x-1 bg-slate-200/50 rounded-xl mb-6 w-fit">
                <button v-for="role in roles" :key="role" @click="activeTab = role"
                    :class="[ 'px-6 py-2 text-sm font-semibold rounded-lg capitalize transition-all', 
                    activeTab === role ? 'bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-200' ]">
                    {{ role }}
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden text-sm sm:text-base">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 border-b">
                            <th class="p-4 text-xs font-bold uppercase text-slate-500">Profil</th>
                            <th class="p-4 text-xs font-bold uppercase text-slate-500 px-6">Email</th>
                            <th v-if="activeTab === 'tenant'" class="p-4 text-xs font-bold uppercase text-slate-500">Program</th>
                            <th class="p-4 text-xs font-bold uppercase text-slate-500">Status</th>
                            <th class="p-4 text-xs font-bold uppercase text-slate-500 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-blue-50/30">
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center text-white font-bold">{{ user.name.charAt(0) }}</div>
                                    <span class="text-slate-700">{{ user.name }}</span>
                                </div>
                            </td>
                            <td class="p-4 px-6 text-slate-600">{{ user.email }}</td>
                            <td v-if="activeTab === 'tenant'" class="p-4 italic">{{ getProgramName(user.inkubator_id) }}</td>
                            <td class="p-4">
                                <span :class="roleBadgeStyle(user.role)">
                                    <span class="h-1.5 w-1.5 rounded-full mr-2" :class="roleDotStyle(user.role)"></span>
                                    {{ user.role }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button @click="openEditModal(user)" class="text-indigo-600 font-bold hover:text-violet-700 transition-colors">Ubah Role</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="isModalOpen = false"></div>
                
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md z-10 overflow-hidden">
                    <div class="px-6 py-4 border-b flex justify-between items-center">
                        <h2 class="text-xl font-bold">Ubah Role Pengguna</h2>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-6 p-3 bg-indigo-50 rounded-xl flex items-center gap-4">
                            <div class="h-12 w-12 rounded-full bg-gradient-to-br from-indigo-600 to-violet-600 flex items-center justify-center text-white font-bold">{{ form.name.charAt(0) }}</div>
                            <div>
                                <p class="font-bold text-slate-900">{{ form.name }}</p>
                                <p class="text-sm text-indigo-600">{{ form.email }}</p>
                            </div>
                        </div>

                        <label class="block text-sm font-bold text-slate-700 mb-2">PILIH ROLE BARU</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button v-for="role in roles" :key="role" 
                                @click="form.role = role"
                                :class="[ 'px-4 py-3 rounded-xl border-2 transition-all text-sm capitalize', form.role === role ? 'border-blue-500 bg-blue-50 text-blue-700 font-bold' : 'border-slate-100 text-slate-500' ]">
                                {{ role }}
                            </button>
                        </div>

                        <div v-if="form.role === 'tenant'" class="mt-6 pt-6 border-t">
                            <label class="block text-sm font-bold text-slate-700 mb-2">PROGRAM INKUBATOR</label>
                            <select v-model="form.inkubator_id" class="w-full p-3 bg-slate-50 border-2 border-slate-100 rounded-xl">
                                <option value="" disabled>-- Pilih Program --</option>
                                <option v-for="program in inkubatorPrograms" :key="program.id" :value="program.id">{{ program.name }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 flex gap-3">
                        <button @click="isModalOpen = false" class="flex-1 py-2.5 font-bold text-slate-600">Batal</button>
                        <button @click="updateUserRole" 
                            class="flex-1 py-2.5 bg-gradient-to-br from-indigo-600 to-violet-600 text-white font-bold rounded-xl hover:opacity-90 transition-opacity">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>