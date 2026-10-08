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
                <a href="/ketua-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>
                
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300 rotate-90"></i>
                    </button>
                    
                    <!-- Sub-menu Terbuka dengan "Ajukan Peminjaman" Aktif -->
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/ketua-pokja/ajukan" class="block px-4 py-2 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">Ajukan Peminjaman</a>
                            <a href="/ketua-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="/ketua-pokja/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-circle-check"></i> Validasi Upload
                </a>
                <a href="/ketua-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                <a href="/ketua-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
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
                <a href="/ketua-pokja/ajukan" class="text-black hover:text-blue-600 text-lg transition">
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

            <form method="GET" class="mb-6 flex flex-col gap-3 md:flex-row">
                <input type="text" name="search" value="{{ $search }}" placeholder="Nama surat, arsip, atau kategori" class="flex-1 rounded-md border border-gray-400 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-blue-600 focus:outline-none">
                <select name="kategori_id" class="w-full rounded-md border border-gray-400 bg-white px-4 py-2.5 text-sm text-gray-700 focus:border-blue-600 focus:outline-none md:w-64">
                    <option value="">Semua kategori</option>
                    @foreach ($kategoris as $kategori)<option value="{{ $kategori->id }}" @selected($kategoriId === $kategori->id)>{{ $kategori->name }}</option>@endforeach
                </select>
                <div class="flex gap-2"><button class="rounded-md bg-[#2563eb] px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Filter</button><a href="{{ route('ketua.riwayat.peminjaman') }}" class="rounded-md border border-gray-300 px-4 py-2.5 text-sm text-gray-600 transition hover:bg-gray-100">Reset</a></div>
            </form>

            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 mb-8">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Surat</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Kategori</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Pengajuan</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Status</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($peminjamans as $peminjaman)
                            @php
                                $statusLabel = [
                                    'menunggu_validasi' => 'Menunggu Validasi',
                                    'disetujui' => 'Disetujui',
                                    'ditolak' => 'Ditolak',
                                    'aktif' => 'Aktif',
                                    'selesai' => 'Selesai',
                                ];
                                $statusColor = ['Menunggu' => 'text-amber-600 bg-amber-100', 'Aktif' => 'text-blue-600 bg-blue-100', 'Ditolak' => 'text-red-600 bg-red-100', 'Selesai' => 'text-gray-600 bg-gray-200'];
                                $fileUrl = $peminjaman->file_pengajuan ? asset('storage/' . $peminjaman->file_pengajuan) : ($peminjaman->arsip?->file_path ? asset('storage/' . $peminjaman->arsip->file_path) : null);
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-6 text-gray-600">{{ $peminjaman->nama_surat }}</td>
                                <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->kategori?->name ?? '-' }}</td>
                                <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td class="py-3.5 px-6">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2 py-1 font-medium {{ $statusColor[$peminjaman->display_status] ?? 'text-gray-600 bg-gray-200' }}">
                                        {{ $peminjaman->display_status }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-6">
                                    @if ($fileUrl)
                                        <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="bg-blue-600 text-white px-5 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700 transition">Lihat</a>
                                    @else
                                        <span class="text-xs text-gray-400">File tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-sm text-gray-500">{{ $search !== '' || $kategoriId ? 'Tidak ada data yang sesuai dengan filter.' : 'Belum ada riwayat peminjaman.' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-center items-center gap-3">
                {{ $peminjamans->withQueryString()->links() }}
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
    <x-logout-confirmation />
</body>
</html>
