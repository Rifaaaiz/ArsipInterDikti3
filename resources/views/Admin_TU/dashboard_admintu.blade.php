<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] flex font-sans text-gray-800">

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
                <a href="/admin-tu" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <a href="/admin-tu/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>

                <a href="/admin-tu/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <a href="/admin-tu/laporan-arsip" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Laporan Arsip
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
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
        <header class="px-8 py-6 flex items-center justify-end sticky top-0 z-10 bg-[#f8f9fa]">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">admin TU</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA DASHBOARD -->
        <div class="px-8 pb-10 max-w-7xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Dashboard Admin TU</h2>

            <!-- 3 Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- Kartu 1 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Peminjaman Aktif</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">15</p>
                    <p class="text-xs text-gray-400">Peminjaman</p>
                </div>
                <!-- Kartu 2 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Menunggu Validasi</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">8</p>
                    <p class="text-xs text-gray-400">Peminjaman</p>
                </div>
                <!-- Kartu 3 -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Total Arsip</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">1909</p>
                    <p class="text-xs text-gray-400">Dokumen</p>
                </div>
            </div>

            <!-- Bagian Peminjaman Menunggu Validasi -->
            <h3 class="text-xl font-bold mb-4 text-gray-900">Peminjaman Menunggu Validasi</h3>
            
            <div class="bg-transparent overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Nama Arsip</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Kategori</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Tanggal Upload</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Peminjaman Mobil</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Dokumen Perjanjian</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">19/09/2024</td>
                            <td class="py-3 px-2">
                                <div class="flex gap-2">
                                    <button class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                                    <button class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition">Tolak</button>
                                    <button class="bg-[#10b981] text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-green-600 transition">Terima</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Peminjaman Ruangan</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Dokumen Perjanjian</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">19/09/2024</td>
                            <td class="py-3 px-2">
                                <div class="flex gap-2">
                                    <button class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                                    <button class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition">Tolak</button>
                                    <button class="bg-[#10b981] text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-green-600 transition">Terima</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Peminjaman Mobil</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Dokumen Perjanjian</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">19/09/2024</td>
                            <td class="py-3 px-2">
                                <div class="flex gap-2">
                                    <button class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                                    <button class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition">Tolak</button>
                                    <button class="bg-[#10b981] text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-green-600 transition">Terima</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-100 transition">
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Peminjaman Ruangan</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">Dokumen Perjanjian</td>
                            <td class="py-3 px-2 text-sm text-gray-500 font-semibold">19/09/2024</td>
                            <td class="py-3 px-2">
                                <div class="flex gap-2">
                                    <button class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</button>
                                    <button class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition">Tolak</button>
                                    <button class="bg-[#10b981] text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-green-600 transition">Terima</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-4">
                    <a href="#" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>