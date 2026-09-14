<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola User - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] flex font-sans text-gray-800 relative">

    <!-- SIDEBAR KIRI -->
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>
            
            <!-- Menu -->
            <nav class="px-4 space-y-1">
                <a href="/super-admin" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <div class="pt-2">
                    <button onclick="toggleMenu('submenu-kelola')" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder-open"></i> Kelola Arsip</span>
                        <i id="ikon-kelola" class="fa-solid fa-arrow-right text-xs transition-transform duration-300"></i>
                    </button>
                    <!-- Sub-menu Tersembunyi (Default) -->
                    <div id="submenu-kelola" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/super-admin/penghapusan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Pengapusan Arsip</a>
                            <a href="/super-admin/laporan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Laporan Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="/super-admin/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>
                
                <!-- Kelola User (Aktif/Biru Solid) -->
                <a href="/super-admin/kelola" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition mt-1 shadow-sm">
                    <i class="fa-solid fa-users"></i> Kelola User
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-solid fa-gear"></i> Pengaturan Sistem
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
        <div class="p-6">
            <a href="#" class="flex items-center gap-3 text-red-600 font-bold hover:text-red-500 transition text-sm">
                <i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i> Logout
            </a>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas -->
        <header class="px-8 py-6 flex items-center justify-end sticky top-0 z-10 bg-[#f4f7f6]">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">Super Admin</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN KELOLA USER -->
        <div class="px-8 pb-10 max-w-7xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Kelola User</h2>

            <!-- Tombol Aksi (Hapus & Tambah) -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <button onclick="toggleDeleteMode()" id="btn-hapus-mode" class="border border-red-500 text-red-500 bg-transparent rounded-lg py-2.5 font-bold text-sm hover:bg-red-50 transition">
                    Hapus User
                </button>
                <button onclick="toggleModal('modal-tambah-user')" class="bg-blue-600 text-white rounded-lg py-2.5 font-bold text-sm hover:bg-blue-700 transition shadow-sm">
                    Tambahkan User
                </button>
            </div>

            <!-- Form Filter Pencarian -->
            <div class="flex items-center gap-3 mb-6 relative">
                <input type="text" placeholder="Nama Username" class="w-full bg-[#f4f7f6] border border-gray-400 px-4 py-2 rounded-md text-sm text-gray-700 outline-none focus:border-blue-500">
                
                <div class="relative w-full">
                    <select class="w-full bg-[#f4f7f6] border border-gray-400 px-4 py-2 rounded-md text-sm text-gray-500 outline-none focus:border-blue-500 appearance-none cursor-pointer">
                        <option value="" disabled selected>Role</option>
                        <option value="admin">Admin POKJA</option>
                        <option value="ketua">Ketua POKJA</option>
                    </select>
                    <i class="fa-solid fa-arrow-right absolute right-4 top-3 text-black text-xs pointer-events-none"></i>
                </div>

                <div class="relative w-full">
                    <select class="w-full bg-[#f4f7f6] border border-gray-400 px-4 py-2 rounded-md text-sm text-gray-500 outline-none focus:border-blue-500 appearance-none cursor-pointer">
                        <option value="" disabled selected>Kelompok Kerja</option>
                        <option value="hukum">Hukum, Kepegawaian, dan Tata Laksana</option>
                        <option value="keuangan">Keuangan</option>
                        <option value="pembelajaran">Pembelajaran dan Kemahasiswaan</option>
                    </select>
                    <i class="fa-solid fa-arrow-right absolute right-4 top-3 text-black text-xs pointer-events-none"></i>
                </div>
                
                <button class="bg-blue-600 text-white px-8 py-2 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Filter</button>
            </div>

            <!-- Tabel Data User -->
            <div class="bg-transparent overflow-hidden mt-4">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Username</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Role</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Kelompok Kerja</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Status Akun</th>
                            <!-- Header Kolom Hapus (Tersembunyi Default) -->
                            <th class="py-4 px-2 text-base font-bold text-indigo-900 text-right hidden col-hapus transition-all">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        <!-- Baris 1 -->
                        <tr class="hover:bg-gray-100 transition group">
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Nanang Sunanang</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Admin POKJA</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Hukum, Kepegawaian, dan Tata Laksana</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold flex items-center gap-2">
                                Active <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            </td>
                            <!-- Tombol Hapus (Tersembunyi Default) -->
                            <td class="py-3 px-2 text-right hidden col-hapus transition-all">
                                <button class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition shadow-sm flex items-center gap-2 ml-auto">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <!-- Baris 2 -->
                        <tr class="hover:bg-gray-100 transition group">
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Udin Surudin</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Ketua POKJA</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Hukum, Kepegawaian, dan Tata Laksana</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold flex items-center gap-2">
                                Active <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            </td>
                            <td class="py-3 px-2 text-right hidden col-hapus transition-all">
                                <button class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition shadow-sm flex items-center gap-2 ml-auto">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <!-- Baris 3 -->
                        <tr class="hover:bg-gray-100 transition group">
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Maro</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Admin POKJA</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Keuangan</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold flex items-center gap-2">
                                Active <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            </td>
                            <td class="py-3 px-2 text-right hidden col-hapus transition-all">
                                <button class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition shadow-sm flex items-center gap-2 ml-auto">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <!-- Baris 4 -->
                        <tr class="hover:bg-gray-100 transition group">
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Rhoma</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Ketua POKJA</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold">Pembelajaran dan Kemahasiswaan</td>
                            <td class="py-3 px-2 text-sm text-gray-600 font-semibold flex items-center gap-2">
                                Active <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            </td>
                            <td class="py-3 px-2 text-right hidden col-hapus transition-all">
                                <button class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition shadow-sm flex items-center gap-2 ml-auto">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination (Navigasi Halaman) -->
            <div class="flex justify-center items-center gap-4 mt-12">
                <button class="text-gray-800 hover:text-black transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <div class="flex gap-2">
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded font-bold shadow-sm">1</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-800 font-bold hover:bg-gray-200 rounded transition">2</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-800 font-bold hover:bg-gray-200 rounded transition">3</button>
                </div>
                <button class="text-gray-800 hover:text-black transition">
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            
        </div>
    </main>

    <!-- MODAL POP UP "TAMBAH USER" -->
    <!-- Default class hidden agar tersembunyi -->
    <div id="modal-tambah-user" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden backdrop-blur-sm transition-opacity">
        <!-- Card Putih Modal -->
        <div class="bg-[#f8fafc] w-full max-w-sm rounded-3xl p-8 shadow-2xl relative transform transition-transform scale-95" id="modal-content">
            <h2 class="text-3xl font-bold text-center mb-8 text-black">Tambah User</h2>
            
            <form action="#" method="POST" class="space-y-5">
                <!-- Input Username -->
                <div>
                    <input type="text" placeholder="Isi Username" class="w-full bg-transparent border border-gray-400 rounded-lg px-4 py-3.5 text-sm font-semibold text-gray-700 outline-none focus:border-blue-500">
                </div>

                <!-- Input Role -->
                <div class="relative">
                    <select class="w-full bg-transparent border border-gray-400 rounded-lg px-4 py-3.5 text-sm font-semibold text-gray-500 outline-none focus:border-blue-500 appearance-none cursor-pointer">
                        <option value="" disabled selected>Role</option>
                        <option value="admin">Admin POKJA</option>
                        <option value="ketua">Ketua POKJA</option>
                    </select>
                    <i class="fa-solid fa-arrow-right absolute right-4 top-4 text-black text-sm pointer-events-none"></i>
                </div>

                <!-- Input Kelompok Kerja -->
                <div class="relative mb-8">
                    <select class="w-full bg-transparent border border-gray-400 rounded-lg px-4 py-3.5 text-sm font-semibold text-gray-500 outline-none focus:border-blue-500 appearance-none cursor-pointer">
                        <option value="" disabled selected>Kelompok Kerja</option>
                        <option value="hukum">Hukum, Kepegawaian, dan Tata Laksana</option>
                        <option value="keuangan">Keuangan</option>
                        <option value="pembelajaran">Pembelajaran dan Kemahasiswaan</option>
                    </select>
                    <i class="fa-solid fa-arrow-right absolute right-4 top-4 text-black text-sm pointer-events-none"></i>
                </div>

                <!-- Tombol Submit -->
                <button type="submit" class="w-full bg-blue-600 text-white rounded-lg py-3.5 font-bold text-sm hover:bg-blue-700 transition shadow-md mt-4">
                    Tambahkan User
                </button>
            </form>
        </div>
    </div>

    <!-- SCRIPT INTERAKTIF -->
    <script>
        // Fungsi Dropdown Menu Kelola Arsip (Sidebar)
        function toggleMenu(id) {
            const submenu = document.getElementById(id);
            const ikon = document.getElementById('ikon-kelola');
            
            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                ikon.classList.add('rotate-90'); // Putar panah ke bawah
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikon.classList.remove('rotate-90'); // Balikkan panah ke kanan
            }
        }

        // Fungsi Memunculkan Tombol Hapus (Toggle Mode Delete)
        function toggleDeleteMode() {
            const btnHapusMode = document.getElementById('btn-hapus-mode');
            const colHapusList = document.querySelectorAll('.col-hapus');
            
            // Ubah style tombol Hapus User saat mode aktif
            btnHapusMode.classList.toggle('bg-red-50');
            
            // Tampilkan/Sembunyikan kolom aksi hapus di tabel
            colHapusList.forEach(col => {
                col.classList.toggle('hidden');
            });
        }

        // Fungsi Pop-Up Modal Tambah User
        function toggleModal(id) {
            const modal = document.getElementById(id);
            const modalContent = document.getElementById('modal-content');
            
            if (modal.classList.contains('hidden')) {
                // Tampilkan Modal
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 10);
            } else {
                // Tutup Modal
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 150); // Sesuaikan dengan durasi transisi
            }
        }

        // Tutup Modal Jika Klik Di Luar Card Putih
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('modal-tambah-user');
            const modalContent = document.getElementById('modal-content');
            
            // Cek jika area yang di-klik adalah latar hitam transparan (overlay)
            if (e.target === modal) {
                toggleModal('modal-tambah-user');
            }
        });
    </script>
</body>
</html>