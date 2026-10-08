<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Peminjaman - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] flex font-sans text-gray-800">
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
                <!-- Dashboard -->
                <a href="/ketua-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>
                
                <!-- Arsip Pokja (Dropdown) -->
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Sub-menu Tersembunyi (Default) -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="{{ url('/ketua-pokja/ajukan') }}" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="{{ url('/ketua-pokja/upload') }}" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <!-- Validasi Peminjaman (Menu Aktif - Biru) -->
                <a href="{{ url('/ketua-pokja/validasi') }}" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>

                <!-- Profil -->
                <a href="{{ url('/ketua-pokja/profil') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- History -->
                <a href="{{ url('/ketua-pokja/history') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                
                <!-- Panduan Penggunaan -->
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </form>
    </aside>

    <main class="flex-1 ml-64 min-h-screen p-8">
        <header class="flex justify-end mb-8"><span class="text-base font-bold text-gray-900">{{ auth()->user()->name }}</span><i class="fa-solid fa-circle-user text-2xl text-black ml-3"></i></header>
        <h2 class="text-3xl font-bold text-black mb-5">Validasi Peminjaman Ketua Pokja</h2>
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif
        <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200"><tr>
                        <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Surat</th>
                        <th class="py-4 px-6 text-sm font-bold text-gray-700">Pengaju</th>
                        <th class="py-4 px-6 text-sm font-bold text-gray-700">Kategori</th>
                        <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal</th>
                        <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pengajuan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6 text-sm font-semibold text-gray-700">{{ $item->nama_surat }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $item->user?->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $item->kategori?->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td class="py-4 px-6"><div class="flex flex-wrap gap-2">
                                    @if ($item->file_pengajuan || $item->arsip?->file_path)
                                        <a href="{{ asset('storage/' . ($item->file_pengajuan ?: $item->arsip->file_path)) }}" target="_blank" rel="noopener" class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700">Lihat File</a>
                                    @else
                                        <span class="text-xs text-gray-400">File tidak tersedia.</span>
                                    @endif
                                    <form method="POST" action="{{ route('ketua.peminjaman.decision', $item) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="approve"><button type="submit" class="bg-emerald-500 text-white px-4 py-1.5 rounded-md text-xs font-semibold hover:bg-emerald-600">Terima</button></form>
                                    <form method="POST" action="{{ route('ketua.peminjaman.decision', $item) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="reject"><button type="submit" class="bg-red-600 text-white px-4 py-1.5 rounded-md text-xs font-semibold hover:bg-red-700">Tolak</button></form>
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-10 px-6 text-center text-sm text-gray-500">Belum ada pengajuan peminjaman yang perlu divalidasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-6"><p class="mb-3 text-sm text-gray-500">Menampilkan {{ $pengajuan->firstItem() ?? 0 }}-{{ $pengajuan->lastItem() ?? 0 }} dari {{ $pengajuan->total() }} pengajuan</p>{{ $pengajuan->withQueryString()->links() }}</div>
    </main>
    <x-logout-confirmation />
</body>

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
</html>
