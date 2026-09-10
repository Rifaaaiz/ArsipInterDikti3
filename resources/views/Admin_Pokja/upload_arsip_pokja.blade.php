<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File Arsip - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 flex font-sans text-gray-800">

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
                <!-- Tautan ke Dashboard -->
                <a href="/dashboard-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300 rotate-90"></i>
                    </button>
                    
                    <!-- Sub-menu Terbuka -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <!-- Tautan ke Halaman Ajukan Peminjaman -->
                            <a href="/dashboard-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <!-- Tautan Aktif ke Halaman Upload File Arsip -->
                            <a href="/dashboard-pokja/upload" class="block px-4 py-2 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="/dashboard-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                <a href="/dashboard-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas -->
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <span class="text-sm font-semibold text-gray-700">Upload File Arsip Kelompok Kerja</span>
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-700">Admin Pokja Kelembagaan</span>
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
        </header>

        <!-- KONTEN UTAMA -->
        <div class="p-8">
            <h2 class="text-3xl font-bold mb-6 text-gray-900">Upload File Arsip Kelompok Kerja</h2>

            <!-- Tombol Aksi (Download Template vs Upload File Arsip) -->
            <div class="flex gap-4 mb-10">
                <button class="flex-1 flex items-center justify-center gap-2 bg-transparent border-2 border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition shadow-sm">
                    Download Template <i class="fa-solid fa-arrow-down ml-1 text-xs"></i>
                </button>
                <a href="{{ url('/dashboard-pokja/form-upload-pokja') }}" class="flex-1 flex items-center justify-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm">
                    Upload File Arsip <i class="fa-solid fa-arrow-right ml-1 text-xs"></i>
                </a>
            </div>

            <h3 class="text-xl font-bold mb-4 text-gray-900">Riwayat Upload Arsip Terakhir</h3>
            
            <!-- Tabel Riwayat Upload Arsip -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Nama Arsip</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Kategori</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Tanggal Upload</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-6 text-sm text-gray-600 font-medium">Laporan Kelembagaan 2024</td>
                            <td class="py-3 px-6 text-sm text-gray-500">Dokumen Perjanjian</td>
                            <td class="py-3 px-6 text-sm text-gray-500">19/09/2024</td>
                            <td class="py-3 px-6">
                                <button class="bg-blue-600 text-white px-5 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-6 text-sm text-gray-600 font-medium">Dokumentasi Rapat</td>
                            <td class="py-3 px-6 text-sm text-gray-500">Dokumen Vital</td>
                            <td class="py-3 px-6 text-sm text-gray-500">32/10/2024</td>
                            <td class="py-3 px-6">
                                <button class="bg-blue-600 text-white px-5 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-100 text-center">
                    <a href="/dashboard-pokja/riwayat-arsip-terakhir-pokja" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</a>
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