<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Arsip Pokja - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] flex font-sans text-gray-800">

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
                        <i id="ikon-panah" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Sub-menu (Tampil tapi tidak aktif) -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/dashboard-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/dashboard-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="/dashboard-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- Menu Aktif (Biru Solid) untuk History -->
                <a href="/dashboard-pokja/history" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-2">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen p-8">
        
        <h2 class="text-3xl font-bold mb-6 text-gray-900 mt-4">History Arsip Pokja</h2>
        
        <!-- Tabel History -->
        <div class="bg-[#f8f9fa] rounded-xl overflow-hidden shadow-sm border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Nama Arsip</th>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Kategori</th>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Tanggal Upload</th>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <!-- Baris 1 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Laporan Kelembagaan 2024</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumen Perjanjian</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">19/09/2024</td>
                        <td class="py-4 px-6">
                            <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                        </td>
                    </tr>
                    <!-- Baris 2 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumentasi Rapat</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumen Vital</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">32/10/2024</td>
                        <td class="py-4 px-6">
                            <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                        </td>
                    </tr>
                    <!-- Baris 3 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Laporan Kelembagaan 2024</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumen Perjanjian</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">19/09/2024</td>
                        <td class="py-4 px-6">
                            <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                        </td>
                    </tr>
                    <!-- Baris 4 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumentasi Rapat</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumen Vital</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">32/10/2024</td>
                        <td class="py-4 px-6">
                            <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                        </td>
                    </tr>
                    <!-- Baris 5 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Laporan Kelembagaan 2024</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumen Perjanjian</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">19/09/2024</td>
                        <td class="py-4 px-6">
                            <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                        </td>
                    </tr>
                    <!-- Baris 6 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumentasi Rapat</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumen Vital</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">32/10/2024</td>
                        <td class="py-4 px-6">
                            <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                        </td>
                    </tr>
                    <!-- Baris 7 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Laporan Kelembagaan 2024</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumen Perjanjian</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">19/09/2024</td>
                        <td class="py-4 px-6">
                            <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                        </td>
                    </tr>
                    <!-- Baris 8 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumentasi Rapat</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">Dokumen Vital</td>
                        <td class="py-4 px-6 text-sm text-gray-500 font-semibold">32/10/2024</td>
                        <td class="py-4 px-6">
                            <button class="bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination (Bisa Dipakai) -->
        <div class="flex justify-center items-center gap-4 mt-8">
            <button class="text-gray-600 hover:text-black transition">
                <i class="fa-solid fa-arrow-left"></i>
            </button>
            <div class="flex gap-2">
                <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded font-bold shadow-sm">1</button>
                <button class="w-8 h-8 flex items-center justify-center text-gray-700 font-bold hover:bg-gray-200 rounded transition">2</button>
                <button class="w-8 h-8 flex items-center justify-center text-gray-700 font-bold hover:bg-gray-200 rounded transition">3</button>
            </div>
            <button class="text-gray-600 hover:text-black transition">
                <i class="fa-solid fa-arrow-right"></i>
            </button>
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