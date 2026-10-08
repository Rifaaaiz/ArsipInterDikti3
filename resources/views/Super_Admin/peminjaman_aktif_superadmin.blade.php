<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Aktif - Super Admin</title>
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
            
            <!-- Menu Sidebar -->
            <nav class="px-4 space-y-1">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>  Dashboard
                </a>
                
                <!-- Dropdown Kelola Arsip -->
                <div class="pt-1">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i> Kelola Arsip</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-1 pt-1 pb-2">
                            <a href="{{ route('superadmin.arsip.penghapusan') }}" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Pengapusan Arsip</a>
                            <a href="{{ route('superadmin.arsip.laporan') }}" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Laporan Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('superadmin.peminjaman.validasi') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check w-5"></i> Validasi Peminjaman
                </a>

                <a href="{{ route('superadmin.users') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-solid fa-users w-5"></i> Kelola User
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines w-5"></i> Panduan Penggunaan
                </a>
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-solid fa-gear w-5"></i> Pengaturan Sistem
                </a>
            </nav>
        </div>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:text-red-400 transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Header Atas -->
        <header class="px-8 py-5 flex items-center justify-end sticky top-0 z-10 bg-[#f4f7f6]">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-900">{{ auth()->user()->name ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN HALAMAN -->
        <div class="px-8 pb-10 w-full max-w-[1400px]">
            
            <!-- Judul & Tombol Back Sesuai Screenshot -->
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('superadmin.dashboard') }}" class="text-black hover:text-blue-600 text-2xl transition font-bold">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <h2 class="text-3xl font-extrabold text-black tracking-tight">Peminjaman Aktif</h2>
            </div>

            <!-- FILTER PENCARIAN (VISUAL SESUAI SCREENSHOT) -->
            <form method="GET" action="{{ route('superadmin.peminjaman.aktif') }}" class="mb-8 flex flex-col md:flex-row gap-3">
                <!-- Input Nama Surat -->
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama surat, arsip, kategori, peminjam, atau Pokja" class="w-full border border-gray-300 bg-[#f4f7f6] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:bg-white transition">
                </div>

                <!-- Select Kategori -->
                <div class="relative w-full md:w-56">
                    <select name="kategori" class="w-full border border-gray-300 bg-[#f4f7f6] rounded-md px-4 py-2.5 text-sm text-gray-400 focus:outline-none focus:border-blue-500 focus:bg-white appearance-none cursor-pointer transition">
                        <option value="">Kategori Surat</option>
                    </select>
                    <!-- Panah Kanan sesuai screenshot -->
                    <i class="fa-solid fa-arrow-right absolute right-3 top-1/2 transform -translate-y-1/2 text-black text-sm pointer-events-none"></i>
                </div>

                <!-- Input Tanggal -->
                <div class="relative w-full md:w-56">
                    <input type="text" placeholder="Tanggal Dokumen" onfocus="(this.type='date')" onblur="(this.type='text')" class="w-full border border-gray-300 bg-[#f4f7f6] rounded-md px-4 py-2.5 text-sm text-gray-400 focus:outline-none focus:border-blue-500 focus:bg-white appearance-none cursor-pointer transition">
                    <i class="fa-regular fa-calendar absolute right-3 top-1/2 transform -translate-y-1/2 text-black text-sm pointer-events-none"></i>
                </div>

                <!-- Tombol Aksi -->
                <div>
                    <button type="submit" class="bg-[#2563eb] text-white px-8 py-2.5 rounded-md text-sm font-bold hover:bg-blue-700 transition shadow-sm h-full">
                        Filter
                    </button>
                </div>
            </form>
            <p class="mb-4 text-sm text-slate-600" role="status" aria-live="polite">
                {{ $peminjamans->total() > 0 ? 'Ditemukan ' . $peminjamans->total() . ' peminjaman aktif.' : 'Tidak ditemukan peminjaman aktif yang sesuai.' }}
            </p>

            <!-- TABEL DATA -->
            <div class="bg-transparent overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Nama Arsip</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Kategori</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Tanggal Mulai</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Tanggal Selesai</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Status</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Aksi</th>
                                
                                <!-- Kolom bawaan Backend yang disembunyikan agar layout persis dengan gambar -->
                                <th class="hidden">Pengaju</th>
                                <th class="hidden">Role</th>
                                <th class="hidden">Kelompok Kerja</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150">
                            @forelse ($peminjamans as $peminjaman)
                                @php($filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path)
                                <tr class="hover:bg-white/40 transition">
                                    <td class="py-4 px-4 text-sm font-bold text-gray-600">{{ $peminjaman->nama_surat }}</td>
                                    
                                    <!-- Fallback kategori sesuai screenshot -->
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">Dokumen Peminjaman</td>
                                    
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                    
                                    <td class="py-4 px-4">
                                        @php
                                            $status = $peminjaman->display_status;
                                            $statusStyles = [
                                                'Ditolak' => ['text' => 'text-red-600', 'dot' => 'bg-red-500'],
                                                'Diterima' => ['text' => 'text-green-600', 'dot' => 'bg-green-500'],
                                                'Menunggu' => ['text' => 'text-yellow-600', 'dot' => 'bg-yellow-500'],
                                                'Aktif' => ['text' => 'text-blue-600', 'dot' => 'bg-blue-500'],
                                                'Selesai' => ['text' => 'text-gray-600', 'dot' => 'bg-gray-500'],
                                            ];
                                            $statusStyle = $statusStyles[$status] ?? ['text' => 'text-gray-600', 'dot' => 'bg-gray-500'];
                                        @endphp
                                        <span class="flex items-center gap-2 text-sm font-bold {{ $statusStyle['text'] }}">
                                            <span class="w-2.5 h-2.5 rounded-full {{ $statusStyle['dot'] }}"></span> {{ $status }}
                                        </span>
                                    </td>
                                    
                                    <td class="py-4 px-4">
                                        @if ($filePath)
                                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" rel="noopener" class="inline-block bg-[#2563eb] text-white px-5 py-1.5 rounded-md text-xs font-bold hover:bg-blue-700 transition shadow-sm">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic px-2">Tidak tersedia</span>
                                        @endif
                                    </td>

                                    <!-- Data bawaan Backend disembunyikan agar tidak bocor ke frontend -->
                                    <td class="hidden">{{ $peminjaman->user?->name ?? $peminjaman->user?->username ?? '-' }}</td>
                                    <td class="hidden">{{ $peminjaman->user?->role?->name ?? '-' }}</td>
                                    <td class="hidden">{{ $peminjaman->user?->pokja?->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-12 px-4 text-center text-sm text-gray-500 bg-white/30 rounded-lg">
                                        {{ $search !== '' ? 'Tidak ditemukan peminjaman aktif yang sesuai dengan pencarian.' : 'Belum ada peminjaman aktif.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $peminjamans->withQueryString()->links() }}
            </div>
            
        </div>
    </main>

    <!-- SCRIPT MENU SIDEBAR -->
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
