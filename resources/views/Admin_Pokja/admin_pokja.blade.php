<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Pokja - SIMARSIP</title>
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
                <!-- Dashboard (Active) -->
                <a href="/admin-pokja" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition">
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
                            <a href="/admin-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/admin-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <!-- Profil -->
                <a href="/admin-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- History -->
                <a href="/admin-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
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
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <span class="text-sm font-semibold text-gray-700">Dashboard Admin Pokja</span>
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-700">Admin Pokja Kelembagaan</span>
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
        </header>

        <!-- Isi Dashboard -->
        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Dashboard Admin Pokja</h2>

            <!-- 3 Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Total Arsip Pokja</p>
                    <p class="text-3xl font-bold text-gray-800">162</p>
                    <p class="text-xs text-gray-400 mt-2">Dokumen</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Peminjaman Aktif</p>
                    <p class="text-3xl font-bold text-gray-800">3</p>
                    <p class="text-xs text-gray-400 mt-2">Peminjaman</p>
                </div>
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Upload Arsip</p>
                    <p class="text-3xl font-bold text-gray-800">12</p>
                    <p class="text-xs text-gray-400 mt-2">Dokumen</p>
                </div>
            </div>

            <!-- Tombol Aksi Cepat -->
            <h3 class="text-lg font-bold mb-4">Aksi Cepat</h3>
            <div class="flex gap-4 mb-8">
                <a href="/admin-pokja/ajukan" class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm">
                    <i class="fa-solid fa-file-circle-plus"></i> Ajukan Peminjaman Arsip
                </a>
                <a href="/admin-pokja/upload" class="flex items-center gap-2 bg-transparent border-2 border-blue-600 text-blue-600 px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-50 transition shadow-sm">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload File Arsip Pokja
                </a>
            </div>

            <!-- Tabel Arsip Terbaru -->
            <h3 class="text-lg font-bold mb-4">Arsip Terbaru Pokja</h3>
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-6 text-sm font-semibold text-gray-600">Nama Arsip</th>
                            <th class="py-3 px-6 text-sm font-semibold text-gray-600">Kategori</th>
                            <th class="py-3 px-6 text-sm font-semibold text-gray-600">Tanggal Upload</th>
                            <th class="py-3 px-6 text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-6 text-sm text-gray-800">Laporan Kelembagaan 2024</td>
                            <td class="py-3 px-6 text-sm text-gray-600">Dokumen Perjanjian</td>
                            <td class="py-3 px-6 text-sm text-gray-600">19/09/2024</td>
                            <td class="py-3 px-6">
                                <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-3 px-6 text-sm text-gray-800">Dokumentasi Rapat</td>
                            <td class="py-3 px-6 text-sm text-gray-600">Dokumen Vital</td>
                            <td class="py-3 px-6 text-sm text-gray-600">32/10/2024</td>
                            <td class="py-3 px-6">
                                <button class="bg-blue-600 text-white px-3 py-1 rounded text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-200 text-center">
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
                // Buka menu
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                // Putar ikon panah ke bawah (90 derajat dari posisi kanan)
                ikonPanah.classList.add('rotate-90');
            } else {
                // Tutup menu
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                // Kembalikan ikon panah ke kanan
                ikonPanah.classList.remove('rotate-90');
            }
        }
    </script>
</body>
</html>