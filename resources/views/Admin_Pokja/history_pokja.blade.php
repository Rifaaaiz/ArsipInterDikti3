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
                    
                    <!-- Sub-menu (Tampil tapi tidak aktif) -->
                <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
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
         <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
                </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen p-8">
        
        <h2 class="text-3xl font-bold mb-6 text-gray-900 mt-4">History Surat Pokja</h2>

        <form method="GET" action="{{ route('pokja.history') }}" class="mb-8">
            <div class="w-full max-w-2xl">
                <label for="filter" class="block text-sm font-bold text-black mb-2">Kategori</label>
                <div class="relative">
                    <select id="filter" name="filter" onchange="this.form.submit()" class="w-full border border-gray-400 bg-white rounded-md px-4 py-3 pr-10 text-sm text-gray-700 focus:outline-none focus:border-blue-600 transition appearance-none cursor-pointer">
                        <option value="semua" {{ $filter === 'semua' ? 'selected' : '' }}>Semua Surat</option>
                        <option value="arsip" {{ $filter === 'arsip' ? 'selected' : '' }}>Arsip</option>
                        <option value="peminjaman" {{ $filter === 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                    </select>
                    <!-- Ikon panah custom -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-700">
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </div>
                </div>
            </div>
        </form>

        <div class="bg-[#f8f9fa] rounded-xl overflow-hidden shadow-sm border border-gray-200">
            <table class="w-full text-left border-collapse">
                <thead class="border-b border-gray-200">
                    <tr>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Nama Surat</th>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Jenis</th>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Kategori</th>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Tanggal</th>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Status</th>
                        <th class="py-4 px-6 text-base font-bold text-indigo-800">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($history as $item)
                        @php
                            $statusLabels = [
                                'menunggu_validasi' => 'Menunggu',
                                'disetujui' => 'Menunggu',
                                'ditolak' => 'Ditolak',
                                'dikembalikan' => 'Selesai',
                                'selesai' => 'Selesai',
                            ];
                            $statusClasses = [
                                'menunggu_validasi' => 'text-amber-700 bg-amber-100',
                                'disetujui' => 'text-green-700 bg-green-100',
                                'ditolak' => 'text-red-700 bg-red-100',
                                'dikembalikan' => 'text-orange-700 bg-orange-100',
                                'selesai' => 'text-gray-700 bg-gray-200',
                            ];
                            $status = $item->status ?? 'tidak_diketahui';
                            $statusLabel = $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status));
                            $statusClass = $statusClasses[$status] ?? 'text-gray-700 bg-gray-200';
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6 text-sm text-gray-500 font-semibold">{{ $item->nama }}</td>
                            <td class="py-4 px-6 text-sm text-gray-500 font-semibold">{{ ucfirst($item->jenis) }}</td>
                            <td class="py-4 px-6 text-sm text-gray-500 font-semibold">{{ $item->kategori ?? '-' }}</td>
                            <td class="py-4 px-6 text-sm text-gray-500 font-semibold">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    <span class="h-2 w-2 rounded-full bg-current"></span>{{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if ($item->file_path)
                                    <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" rel="noopener" class="inline-block bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat</a>
                                @else
                                    <span class="text-sm text-gray-400">File tidak tersedia</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 px-6 text-center text-sm text-gray-500">
                                @if ($filter === 'arsip')
                                    Belum ada surat arsip.
                                @elseif ($filter === 'peminjaman')
                                    Belum ada surat peminjaman.
                                @else
                                    Belum ada riwayat surat.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            <p class="mb-3 text-sm text-gray-500">
                Menampilkan {{ $history->firstItem() ?? 0 }}-{{ $history->lastItem() ?? 0 }} dari {{ $history->total() }} surat
            </p>
            {{ $history->withQueryString()->links() }}
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
    <x-logout-confirmation />
</body>
</html>
