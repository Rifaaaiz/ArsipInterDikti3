<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Admin TU</title>
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
                <a href="/admin-tu" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <a href="/admin-tu/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>

                <!-- Profil (Aktif) -->
                <a href="/admin-tu/profil" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-1">
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

        <!-- KONTEN UTAMA PROFIL -->
        <div class="px-8 pb-10 max-w-4xl">
            <h2 class="text-3xl font-bold mb-8 text-black">Profile</h2>

            <!-- Informasi Profil -->
            <div class="space-y-6 text-base">
                <div class="flex items-center">
                    <span class="w-48 font-bold text-black">Nama :</span>
                    <span class="font-semibold text-gray-900">Nanang Sunanang</span>
                </div>
                <div class="flex items-center">
                    <span class="w-48 font-bold text-black">NIP :</span>
                    <span class="font-semibold text-gray-900">101819236705</span>
                </div>
                <div class="flex items-center">
                    <span class="w-48 font-bold text-black">Role :</span>
                    <span class="font-semibold text-gray-900">Admin TU</span>
                </div>
                <div class="flex items-center">
                    <span class="w-48 font-bold text-black">Kelompok Kerja :</span>
                    <span class="font-semibold text-gray-900">TUBMN</span>
                </div>
                <div class="flex items-center">
                    <span class="w-48 font-bold text-black">Status Akun :</span>
                    <span class="font-semibold text-gray-900 flex items-center gap-2">
                        Active <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>
                    </span>
                </div>
            </div>

        </div>
    </main>

</body>
</html>