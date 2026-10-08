<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin Pokja - SIMARSIP</title>
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
            
            <!-- Menu -->
            <nav class="px-4 space-y-1">
                <a href="/dashboard-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                        <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                        </svg> 
                        Dashboard
                </a>
                
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Sub-menu Tersembunyi -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/dashboard-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/dashboard-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <!-- Menu Aktif (Biru Solid) untuk Profil -->
                <a href="/dashboard-pokja/profil" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-2">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <a href="/dashboard-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-2">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-2">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout di Bawah Sidebar -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas (Hanya ada user info di kanan) -->
        <header class="px-8 py-6 flex items-center justify-end">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">admin Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN PROFIL -->
        <div class="p-8">
            <h2 class="text-4xl font-bold mb-12 text-black">Profile</h2>
            
            <!-- Daftar Detail Profil -->
            <div class="w-full max-w-4xl space-y-8">
                <!-- Baris Nama -->
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-black">Nama :</span>
                    <span class="text-lg font-bold text-black">Nanang Sunanang</span>
                </div>
                
                <!-- Baris NIP -->
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-black">NIP :</span>
                    <span class="text-lg font-bold text-black">101819236705</span>
                </div>
                
                <!-- Baris Role -->
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-black">Role :</span>
                    <span class="text-lg font-bold text-black">Admin Pokja</span>
                </div>
                
                <!-- Baris Kelompok Kerja -->
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-black">Kelompok Kerja :</span>
                    <span class="text-lg font-bold text-black">Kelembagaan</span>
                </div>
                
                <!-- Baris Status Akun -->
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold text-black">Status Akun :</span>
                    <div class="flex items-center gap-2">
                        <span class="text-lg font-bold text-black">Active</span>
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500 inline-block"></span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- SCRIPT ANIMASI MENU -->
    <<script>
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
    <x-logout-confirmation />
</body>
</html>
