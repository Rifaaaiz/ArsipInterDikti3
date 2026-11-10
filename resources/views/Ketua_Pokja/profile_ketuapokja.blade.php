<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Ketua Pokja - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f4f5] flex flex-col md:flex-row font-sans text-gray-800">

    <!-- SIDEBAR KIRI -->
    <aside class="w-full md:w-64 bg-[#0a1120] text-white md:min-h-screen flex flex-col md:justify-between md:fixed md:top-0 md:left-0 z-20">
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
                <a href="/ketua-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>

                <a href="/ketua-pokja/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-circle-check"></i> Validasi Upload
                </a>

                <!-- Menu Aktif (Biru Solid) untuk Profil -->
                <a href="/ketua-pokja/profil" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-2">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <a href="/ketua-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-2">
                    <i class="fa-regular fa-clock"></i> History
                </a>

                <a href="{{ route('ketua.arsip.seluruh') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-solid fa-box-archive"></i> Seluruh Arsip
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
    <main class="w-full min-w-0 flex-1 ml-0 md:ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas (Hanya ada user info di kanan) -->
        <header class="sticky top-0 z-10 flex items-center justify-between bg-[#f4f4f5] px-5 py-6 sm:px-8">
            <a href="{{ route('ketua.dashboard') }}" aria-label="Kembali ke Dashboard Ketua Pokja" class="text-lg text-black transition hover:text-blue-600">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
            <div class="flex items-center gap-3">
                <span class="text-right text-sm font-bold text-gray-900 sm:text-base">Ketua Pokja {{ auth()->user()->pokja?->name ?? '-' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <!-- KONTEN PROFIL -->
        <div class="w-full min-w-0 px-4 pb-10 sm:px-8">
            @php($profileUser = auth()->user())
            <h2 class="mb-8 text-2xl font-bold text-black sm:text-3xl">Profile</h2>

            <div class="w-full divide-y divide-gray-200 rounded-xl border border-gray-200 bg-white px-4 shadow-sm sm:px-6">
                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:items-center">
                    <span class="w-full font-bold text-gray-700 sm:w-56">Nama</span>
                    <span class="min-w-0 break-words font-semibold text-gray-900">{{ $profileUser?->username ?? $profileUser?->name ?? '-' }}</span>
                </div>
                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:items-center">
                    <span class="w-full font-bold text-gray-700 sm:w-56">Role</span>
                    <span class="min-w-0 break-words font-semibold text-gray-900">{{ $profileUser?->role?->name ?? '-' }}</span>
                </div>
                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:items-center">
                    <span class="w-full font-bold text-gray-700 sm:w-56">Kelompok Kerja</span>
                    <span class="min-w-0 break-words font-semibold text-gray-900">{{ $profileUser?->pokja?->name ?? 'Tidak terkait Pokja' }}</span>
                </div>
                <div class="flex flex-col gap-1 py-4 sm:flex-row sm:items-center">
                    <span class="w-full font-bold text-gray-700 sm:w-56">Status Akun</span>
                    <span class="inline-flex w-fit items-center gap-2 rounded-full px-3 py-1 text-sm font-semibold {{ $profileUser?->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                        {{ $profileUser?->status === 'active' ? 'Active' : 'Inactive' }}
                        <span class="h-2 w-2 rounded-full {{ $profileUser?->status === 'active' ? 'bg-emerald-500' : 'bg-slate-500' }}"></span>
                    </span>
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
    <x-logout-confirmation />
</body>
</html>
