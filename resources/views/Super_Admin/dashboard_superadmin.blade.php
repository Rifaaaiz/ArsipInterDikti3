<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] flex font-sans text-gray-800">

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
                <!-- Dashboard (Aktif) -->
                <a href="/super-admin" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <!-- Wrapper Menu Kelola Arsip -->
                <div class="pt-2">
                    <button onclick="toggleMenu('submenu-kelola')" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder-open"></i> Kelola Arsip</span>
                        <i id="ikon-kelola" class="fa-solid fa-arrow-down text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Sub-menu Terbuka -->
                    <div id="submenu-kelola" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/super-admin/penghapusan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Pengapusan Arsip</a>
                            <a href="/super-admin/laporan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Laporan Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="/super-admin/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>
                
                <a href="/super-admin/kelola" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
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

        <!-- KONTEN DASHBOARD SUPER ADMIN -->
        <div class="px-8 pb-10 max-w-7xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Dashboard Super Admin</h2>

            <!-- 4 Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <!-- Kartu 1 -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip Pokja</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">1909</p>
                    <p class="text-xs text-gray-400 font-semibold">Dokumen</p>
                </div>
                <!-- Kartu 2 -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Peminjaman Aktif</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">32</p>
                    <p class="text-xs text-gray-400 font-semibold">Peminjaman</p>
                </div>
                <!-- Kartu 3 -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">User Aktif</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">38</p>
                    <p class="text-xs text-gray-400 font-semibold">User</p>
                </div>
                <!-- Kartu 4 -->
                <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Pokja</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">11</p>
                    <p class="text-xs text-gray-400 font-semibold">Kelompok</p>
                </div>
            </div>

            <!-- Tabel Arsip Terbaru Pokja -->
            <h3 class="text-2xl font-bold mb-4 text-black">Arsip Terbaru Pokja</h3>
            <div class="bg-transparent border border-gray-300 rounded-xl overflow-hidden mb-10">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-gray-300">
                        <tr>
                            <th class="py-4 px-6 text-base font-bold text-indigo-900">Nama Arsip</th>
                            <th class="py-4 px-6 text-base font-bold text-indigo-900">Kategori</th>
                            <th class="py-4 px-6 text-base font-bold text-indigo-900">Tanggal Upload</th>
                            <th class="py-4 px-6 text-base font-bold text-indigo-900 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-4 px-6 text-sm text-gray-600 font-semibold">Laporan Kelembagaan 2024</td>
                            <td class="py-4 px-6 text-sm text-gray-600 font-semibold">Dokumen Perjanjian</td>
                            <td class="py-4 px-6 text-sm text-gray-600 font-semibold">19/09/2024</td>
                            <td class="py-4 px-6 text-center">
                                <button class="bg-blue-500 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-600 transition">Lihat</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-4 px-6 text-sm text-gray-600 font-semibold">Dokumentasi Rapat</td>
                            <td class="py-4 px-6 text-sm text-gray-600 font-semibold">Dokument Vital</td>
                            <td class="py-4 px-6 text-sm text-gray-600 font-semibold">32/10/2024</td>
                            <td class="py-4 px-6 text-center">
                                <button class="bg-blue-500 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-600 transition">Lihat</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-3 text-center">
                    <a href="#" class="text-blue-600 text-sm font-bold hover:underline">Lihat Semua</a>
                </div>
            </div>

            <!-- Tabel Peminjaman Menunggu Validasi -->
            <h3 class="text-2xl font-bold mb-4 text-black">Peminjaman Menunggu Validasi</h3>
            <div class="bg-transparent overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-4 px-6 text-base font-bold text-indigo-900">Nama Arsip</th>
                            <th class="py-4 px-6 text-base font-bold text-indigo-900">Kategori</th>
                            <th class="py-4 px-6 text-base font-bold text-indigo-900">Tanggal Upload</th>
                            <th class="py-4 px-6 text-base font-bold text-indigo-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-4 px-6 text-sm text-gray-500 font-bold">Peminjaman Mobil</td>
                            <td class="py-4 px-6 text-sm text-gray-500 font-bold">Dokumen Perjanjian</td>
                            <td class="py-4 px-6 text-sm text-gray-500 font-bold">19/09/2024</td>
                            <td class="py-4 px-6">
                                <div class="flex gap-2">
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Lihat</button>
                                    <button class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-red-700 transition shadow-sm">Tolak</button>
                                    <button class="bg-[#10b981] text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-green-600 transition shadow-sm">Terima</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-4 px-6 text-sm text-gray-500 font-bold">Peminjaman Ruangan</td>
                            <td class="py-4 px-6 text-sm text-gray-500 font-bold">Dokumen Perjanjian</td>
                            <td class="py-4 px-6 text-sm text-gray-500 font-bold">19/09/2024</td>
                            <td class="py-4 px-6">
                                <div class="flex gap-2">
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">Lihat</button>
                                    <button class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-red-700 transition shadow-sm">Tolak</button>
                                    <button class="bg-[#10b981] text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-green-600 transition shadow-sm">Terima</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-4 text-center">
                    <a href="#" class="text-blue-600 text-sm font-bold hover:underline">Lihat Semua</a>
                </div>
            </div>

        </div>
    </main>

    <!-- SCRIPT INTERAKTIF DROPDOWN MENU -->
    <script>
        function toggleMenu(id) {
            const submenu = document.getElementById(id);
            const ikon = document.getElementById('ikon-kelola');
            
            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                ikon.classList.add('rotate-180');
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikon.classList.remove('rotate-180');
            }
        }
    </script>
</body>
</html>