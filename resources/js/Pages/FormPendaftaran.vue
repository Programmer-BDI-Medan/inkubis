<script setup>
import { ref} from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'

const props = defineProps({
      program: Object
})

const page = usePage()

const systemData = {
  programName: props.program?.inkubis.nama_program || '',
  userName: page.props.auth.user.name
}

const formData = useForm({
  namaTenant: '',
  jenisProduk: '',
  deskripsi: '',
  email: '',
  noHp: '',
  dokumen: null
})

const fileName = ref('')

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    formData.dokumen = file
    fileName.value = file.name
  }
}

const goBack = () => {
  // Gunakan router jika menggunakan vue-router
  // router.back() atau router.push('/dashboard')
  console.log('Navigasi kembali ke Dashboard')
  window.history.back()
}

const handleSubmit = () => {
  console.log('Data Pendaftaran:', formData)
  formData.post(route('form-pendaftaran.submit', props.program.id),{
    forceFormData: true,
  })
  alert('Berhasil! Data Anda sedang diproses oleh Tim Inkubator BDI Medan.')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
      
      <button @click="goBack" class="group flex items-center text-gray-600 hover:text-[#0b796a] mb-6 transition-colors duration-200">
        <div class="bg-white p-2 rounded-full shadow-sm group-hover:shadow-md mr-3 border border-gray-100">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
        </div>
        <span class="font-medium text-sm uppercase tracking-wider">Kembali ke Dashboard</span>
      </button>

      <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-[#0b796a] p-6 text-white">
          <h2 class="text-2xl font-bold">Form Pendaftaran Calon Tenant</h2>
          <p class="mt-2 text-teal-100 opacity-90">Silakan lengkapi data di bawah ini</p>
        </div>

        <form @submit.prevent="handleSubmit" class="p-8 space-y-6">
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-100 p-3 rounded-lg border border-gray-200">
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Program Inkubator</label>
              <p class="text-sm font-semibold text-gray-700 leading-tight">{{ systemData.programName }}</p>
            </div>
            <div class="bg-gray-100 p-3 rounded-lg border border-gray-200">
              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Login Sebagai</label>
              <p class="text-sm font-semibold text-gray-700">{{ systemData.userName }}</p>
            </div>
          </div>

          <hr class="border-gray-100" />

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1 italic">Nama Tenant / Usaha</label>
              <input v-model="formData.namaTenant" type="text" required 
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#14a38f] focus:border-transparent outline-none transition"
                placeholder="Contoh: CV. Berdikari Digital">
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1 italic">Jenis Produk</label>
              <select v-model="formData.jenisProduk" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#14a38f] outline-none transition">
                <option value="" disabled>Pilih Kategori Produk</option>
                <option value="Makanan & Minuman">Makanan & Minuman</option>
                <option value="Teknologi/IT">Teknologi/IT</option>
                <option value="Kriya/Fashion">Kriya/Fashion</option>
                <option value="Jasa Kreatif">Jasa Kreatif</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1 italic">Deskripsi Singkat Produk</label>
              <textarea v-model="formData.deskripsi" rows="3" required
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#14a38f] outline-none transition"
                placeholder="Jelaskan inovasi atau manfaat produk Anda..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 italic">Email Bisnis</label>
                <input v-model="formData.email" type="email" required
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#14a38f] outline-none transition"
                  placeholder="kontak@bisnis.com">
              </div>
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1 italic">No. WhatsApp</label>
                <input v-model="formData.noHp" type="tel" required
                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#14a38f] outline-none transition"
                  placeholder="08xxxxxxxxxx">
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-700 mb-1 italic">Upload Dokumen Pendukung</label>
              <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-teal-500 transition cursor-pointer relative bg-gray-50/50">
                <div class="space-y-1 text-center">
                  <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <p class="text-xs text-gray-500"><span class="text-teal-600 font-bold uppercase tracking-widest">Pilih File</span> atau drag-and-drop</p>
                </div>
                <input type="file" @change="handleFileUpload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
              </div>
              <p v-if="fileName" class="mt-2 text-xs text-teal-700 font-bold bg-teal-50 p-2 rounded inline-block">✓ {{ fileName }}</p>
            </div>
          </div>

          <div class="pt-4">
            <button type="submit" 
              class="w-full bg-[#14a38f] hover:bg-[#0b796a] text-white font-black py-4 px-4 rounded-xl shadow-lg transform transition active:scale-95 uppercase tracking-widest">
              Daftar Sekarang
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>

