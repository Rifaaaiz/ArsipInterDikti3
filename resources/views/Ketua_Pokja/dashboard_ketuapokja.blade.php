<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ketua Pokja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f4f5] flex font-sans text-gray-800">

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
            
            <!-- Menu Sidebar Ketua Pokja -->
            <nav class="px-4 space-y-1">
                <!-- Dashboard (Active) -->
                <a href="/ketua-pokja" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <!-- Wrapper Menu Arsip Pokja -->
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
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
        <header class="px-8 py-6 flex items-center justify-between sticky top-0 z-10 bg-[#f4f4f5]">
            <span class="text-sm font-semibold text-gray-500">Dashboard - Ketua Pokja</span>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">Ketua Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA DASHBOARD KETUA POKJA -->
        <div class="px-8 pb-10 max-w-7xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Dashboard Admint Pokja</h2>

            <!-- 3 Kartu Statistik (Termasuk Usulan Upload / Menunggu Validasi) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Kartu 1: Total Arsip Pokja -->
                    <a href="ketua-pokja/history" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition group">
                        <p class="text-gray-500 text-sm font-semibold mb-2 group-hover:text-blue-600 transition">Total Arsip Pokja</p>
                        <p class="text-3xl font-bold text-gray-800">162</p>
                        <p class="text-xs text-gray-400 mt-2">Dokumen</p>
                    </a>

                    <!-- Kartu 2: Peminjaman Aktif -->
                    <a href="ketua-pokja/peminjaman-aktif" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition group">
                        <p class="text-gray-500 text-sm font-semibold mb-2 group-hover:text-blue-600 transition">Peminjaman Aktif</p>
                        <p class="text-3xl font-bold text-gray-800">3</p>
                        <p class="text-xs text-gray-400 mt-2">Peminjaman</p>
                    </a>

                    <!-- Kartu 3: Upload Arsip -->
                    <a href="ketua-pokja/upload" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition group">
                        <p class="text-gray-500 text-sm font-semibold mb-2 group-hover:text-blue-600 transition">Upload Arsip</p>
                        <p class="text-3xl font-bold text-gray-800">12</p>
                        <p class="text-xs text-gray-400 mt-2">Dokumen</p>
                    </a>
                </div>

            <!-- Aksi Cepat -->
            <h3 class="text-lg font-bold mb-4 text-black">Aksi Cepat</h3>
            <div class="flex flex-wrap gap-4 mb-8">
                <!-- Tombol Ajukan Peminjaman Arsip (Hijau) -->
                <button class="flex items-center gap-2 bg-[#dcfce7] text-[#15803d] border border-[#bbf7d0] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#bbf7d0] transition shadow-sm text-sm">
                    <i class="fa-solid fa-file-circle-plus"></i> Ajukan Peminjaman Arsip
                </button>
                <!-- Tombol Upload File Arsip Pokja (Biru) -->
                <button class="flex items-center gap-2 bg-[#dbeafe] text-[#1d4ed8] border border-[#bfdbfe] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#bfdbfe] transition shadow-sm text-sm">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload File Arsip Pokja
                </button>
                <!-- Tombol Validasi Usulan Upload (Kuning) -->
                <button class="flex items-center gap-2 bg-[#fef9c3] text-[#a16207] border border-[#fde047] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#fde047] transition shadow-sm text-sm">
                    <i class="fa-solid fa-circle-check"></i> Validasi Usulan Upload
                </button>
            </div>

            <!-- Arsip Terbaru Pokja -->
            <h3 class="text-lg font-bold mb-4 text-black">Arsip Terbaru Pokja</h3>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 mb-4">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Arsip</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Kategori</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Upload</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3.5 px-6 text-gray-700 font-medium">Laporan Kelembagaan 2024</td>
                            <td class="py-3.5 px-6 text-gray-500">Dokumen Perjanjian</td>
                            <td class="py-3.5 px-6 text-gray-500">19/09/2024</td>
                            <td class="py-3.5 px-6">
                                <button class="bg-blue-600 text-white px-5 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3.5 px-6 text-gray-700 font-medium">Dokumentasi Rapat</td>
                            <td class="py-3.5 px-6 text-gray-500">Dokumen Vital</td>
                            <td class="py-3.5 px-6 text-gray-500">32/10/2024</td>
                            <td class="py-3.5 px-6">
                                <button class="bg-blue-600 text-white px-5 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-100 text-center">
                    <a href="#" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</a>
                </div>
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