<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Peminjaman - SIMARSIP</title>
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
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300 rotate-90"></i>
                    </button>
                    
                    <!-- Sub-menu Terbuka dengan "Ajukan Peminjaman" Aktif -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/dashboard-pokja/ajukan" class="block px-4 py-2 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">Ajukan Peminjaman</a>
                            <a href="/dashboard-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="/dashboard-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                <a href="/dashboard-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas dengan Tombol Back (<) di Kiri -->
        <header class="px-8 py-6 flex items-center justify-between sticky top-0 z-10 bg-[#f4f4f5]">
            <div class="flex items-center gap-4">
                    <a href="{{ route('peminjaman.ajukan') }}" class="text-black hover:text-blue-600 text-lg transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <span class="text-sm font-bold text-gray-500">Riwayat Peminjaman Terakhir - Pokja</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">admin Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA HALAMAN AJUKAN PEMINJAMAN -->
        <div class="px-8 pb-10 max-w-6xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Riwayat Peminjaman Terakhir</h2>

            <!-- Baris Filter Pencarian -->
            <div class="flex items-center gap-4 mb-6">
                <div class="flex-1">
                    <input type="text" placeholder="Nama Surat" class="w-full border border-gray-400 bg-white rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600">
                </div>
                <div class="w-64 relative">
                    <div class="w-full flex items-center justify-between border border-gray-400 bg-white rounded-md px-4 py-2.5 text-sm text-gray-500 cursor-pointer">
                        <span>Kategori Surat</span>
                        <i class="fa-solid fa-arrow-right text-black text-xs"></i>
                    </div>
                </div>
                <button class="bg-[#2563eb] text-white px-6 py-2.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition shadow-sm">
                    Filter
                </button>
            </div>

            <!-- Tabel Riwayat Peminjaman -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 mb-8">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Arsip</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Kategori</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Upload</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Status</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($riwayatPeminjaman as $peminjaman)
                            @php
                                $statusLabels = [
                                    'menunggu_validasi' => 'Menunggu Validasi',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'dikembalikan' => 'Dikembalikan',
                                    'selesai' => 'Selesai',
                                ];
                                $statusClasses = [
                                    'menunggu_validasi' => 'text-amber-600 bg-amber-50',
                                    'disetujui' => 'text-green-600 bg-green-50',
                                    'ditolak' => 'text-red-600 bg-red-50',
                                    'dikembalikan' => 'text-orange-600 bg-orange-50',
                                    'selesai' => 'text-gray-600 bg-gray-100',
                                ];
                                $status = $peminjaman->status;
                                $statusLabel = $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
                                $statusClass = $statusClasses[$status] ?? 'text-gray-600 bg-gray-100';
                                $filePath = $peminjaman->arsip?->file_path ?? $peminjaman->file_pengajuan;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-6 text-gray-600">
                                    {{ $peminjaman->arsip?->nama_arsip ?? $peminjaman->nama_surat }}
                                </td>
                                <td class="py-3.5 px-6 text-gray-500">
                                    {{ $peminjaman->kategori?->name ?? '-' }}
                                </td>
                                <td class="py-3.5 px-6 text-gray-500">
                                    {{ $peminjaman->created_at?->format('d/m/Y') ?? '-' }}
                                </td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                        <span class="w-2 h-2 rounded-full bg-current"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    @if ($filePath)
                                        <a href="{{ asset('storage/' . $filePath) }}" target="_blank" rel="noopener" class="inline-flex bg-blue-600 text-white px-5 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700 transition">
                                            Lihat PDF
                                        </a>
                                    @else
                                        <span class="text-xs text-gray-400">File tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 px-6 text-center text-sm text-gray-500">
                                    Belum ada riwayat peminjaman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Navigasi Halaman (Pagination) -->
            <div class="flex justify-center items-center gap-3">
                <button class="text-gray-400 hover:text-black transition px-2 py-1">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </button>
                <div class="flex items-center gap-1.5">
                    <button class="w-8 h-8 flex items-center justify-center bg-blue-600 text-white rounded text-sm font-bold shadow-sm">1</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-700 rounded text-sm font-bold hover:bg-gray-200 transition">2</button>
                    <button class="w-8 h-8 flex items-center justify-center text-gray-700 rounded text-sm font-bold hover:bg-gray-200 transition">3</button>
                </div>
                <button class="text-gray-700 hover:text-black transition px-2 py-1">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </button>
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
                ikonPanah.classList.add('rotate-180');
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikonPanah.classList.remove('rotate-180');
            }
        }
    </script>
</body>
</html>