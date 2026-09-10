<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Aktif - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>
</head>
<body class="bg-[#f4f4f5] flex font-sans text-gray-800">

    <!-- SIDEBAR KIRI -->
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0">
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
                <!-- Dashboard (Active) -->
                <a href="/ketua-pokja" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <!-- Wrapper Menu Arsip Pokja -->
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <!-- Panah default menghadap kanan -->
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Sub-menu (Ajukan & Upload) -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <!-- TAUTAN SUDAH DITAMBAHKAN DI SINI -->
                            <a href="/ketua-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/ketua-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <!-- Validasi Upload (Khas Ketua Pokja) -->
                <a href="/ketua-pokja/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-circle-check"></i> Validasi Upload
                </a>

                <!-- Profil -->
                <a href="/ketua-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- History -->
                <a href="/ketua-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-clock"></i> History
                </a>

                <!-- Panduan Penggunaan -->
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas -->
        <header class="px-8 py-6 flex items-center justify-between sticky top-0 z-10 bg-[#f4f4f5]">
            <div class="flex items-center gap-4">
                <a href="/ketua-pokja" class="text-black hover:text-blue-600 text-lg transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">admin Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA -->
        <div class="px-8 pb-10 max-w-7xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Peminjaman Aktif</h2>

            <!-- Baris Filter Pencarian -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 items-center">
                <div>
                    <input type="text" placeholder="Nama Surat" class="w-full border border-gray-400 bg-white rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600">
                </div>
                
                <div class="relative">
                    <select class="w-full border border-gray-400 bg-white rounded-md px-4 py-2.5 text-sm text-gray-500 focus:outline-none focus:border-blue-600 cursor-pointer pr-10">
                        <option value="" disabled selected>Kategori Surat</option>
                        <option value="keterangan" class="text-gray-800">Surat Keterangan</option>
                        <option value="vital" class="text-gray-800">Surat Vital</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-black">
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </div>
                </div>

                <div class="flex gap-3 md:col-span-2">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Tanggal Dokumen" onfocus="(this.type='date')" onblur="(this.type='text')" class="w-full border border-gray-400 bg-white rounded-md px-4 py-2.5 text-sm text-gray-500 focus:outline-none focus:border-blue-600">
                    </div>
                    <button class="bg-[#2563eb] text-white px-8 py-2.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                        Filter
                    </button>
                </div>
            </div>

            <!-- Tabel Peminjaman Aktif -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 mb-8">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Arsip</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Kategori</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Mulai</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Selesai</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Status</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3.5 px-6 text-gray-600 font-medium">Peminjaman Mobil</td>
                            <td class="py-3.5 px-6 text-gray-500">Dokumen Peminjaman</td>
                            <td class="py-3.5 px-6 text-gray-500">19/09/2024</td>
                            <td class="py-3.5 px-6 text-gray-500">20/09/2024</td>
                            <td class="py-3.5 px-6">
                                <span class="inline-flex items-center gap-1.5 text-green-600 font-medium">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Diterima
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                <button class="bg-blue-600 text-white px-5 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3.5 px-6 text-gray-600 font-medium">Peminjaman Ruangan</td>
                            <td class="py-3.5 px-6 text-gray-500">Dokumen Peminjaman</td>
                            <td class="py-3.5 px-6 text-sm text-gray-500">19/09/2024</td>
                            <td class="py-3.5 px-6 text-sm text-gray-500">21/09/2024</td>
                            <td class="py-3.5 px-6">
                                <span class="inline-flex items-center gap-1.5 text-green-600 font-medium">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Diterima
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                <button class="bg-blue-600 text-white px-5 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3.5 px-6 text-gray-600 font-medium">Peminjaman Motor</td>
                            <td class="py-3.5 px-6 text-gray-500">Dokumen Peminjaman</td>
                            <td class="py-3.5 px-6 text-gray-500">19/09/2024</td>
                            <td class="py-3.5 px-6 text-gray-500">22/09/2024</td>
                            <td class="py-3.5 px-6">
                                <span class="inline-flex items-center gap-1.5 text-green-600 font-medium">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span> Diterima
                                </span>
                            </td>
                            <td class="py-3.5 px-6">
                                <button class="bg-blue-600 text-white px-5 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Navigasi Halaman (Pagination) -->
            <div class="flex justify-center items-center gap-3">
                <button class="text-gray-400 hover:text-black transition px-2 py-1">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <div class="flex items-center gap-1.5">
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded text-sm font-bold shadow-sm">1</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-700 rounded text-sm font-bold hover:bg-gray-200 transition">2</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-700 rounded text-sm font-bold hover:bg-gray-200 transition">3</button>
                </div>
                <button class="text-gray-700 hover:text-black transition px-2 py-1">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
            </div>
        </div>
    </main>

    <!-- SCRIPT ANIMASI MENU -->
    <script>
        function toggleMenuArsip() {
            const submenu = document.getElementById('submenu-arsip');
            const ikonPanah = document.getElementById('ikon-panah');
            
            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                ikonPanah.classList.add('rotate-90');
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikonPanah.classList.remove('rotate-90');
            }
        }
    </script>
</body>
</html>