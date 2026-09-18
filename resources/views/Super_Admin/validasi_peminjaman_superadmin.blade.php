<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Peminjaman - Super Admin</title>
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
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-house w-5"></i> Dashboard
                </a>
                
                <!-- Dropdown Kelola Arsip -->
                <div class="pt-1">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i> Kelola Arsip</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-1 pt-1 pb-2">
                            <a href="#" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Pengapusan Arsip</a>
                            <a href="#" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Laporan Arsip</a>
                        </div>
                    </div>
                </div>

                <!-- Menu Validasi (Aktif / Biru Solid) -->
                <a href="{{ route('superadmin.peminjaman.validasi') }}" class="flex items-center gap-3 px-4 py-3 bg-blue-600 text-white rounded-lg text-sm font-semibold transition shadow-sm mt-1">
                    <i class="fa-regular fa-circle-check w-5"></i> Validasi Peminjaman
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
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
        <div class="p-6">
            <form method="POST" action="#">
                <!-- Dummy logout form as placeholder for UI -->
                @csrf
                <button type="button" class="flex items-center gap-3 text-red-500 font-bold hover:text-red-400 transition text-sm w-full">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Header Atas -->
        <header class="px-8 py-5 flex items-center justify-end sticky top-0 z-10 bg-[#f4f7f6]">
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-900">{{ $user?->name ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN HALAMAN -->
        <div class="px-8 pb-10 w-full max-w-[1400px]">
            
            @if (session('success'))
                <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700 shadow-sm">
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
            @endif

            <h2 class="text-3xl font-extrabold mb-6 text-black tracking-tight">Validasi Peminjaman</h2>

            <!-- FILTER PENCARIAN -->
            <form method="GET" action="{{ route('superadmin.peminjaman.validasi') }}" class="mb-8 flex flex-col md:flex-row gap-3">
                
                <!-- Search Text (Visual Match) -->
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" placeholder="Nama Surat" class="w-full border border-gray-300 bg-[#f4f7f6] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:bg-white transition">
                </div>

                <!-- Select Kategori -->
                <div class="relative w-full md:w-56">
                    <select name="kategori_id" class="w-full border border-gray-300 bg-[#f4f7f6] rounded-md px-4 py-2.5 text-sm text-gray-500 focus:outline-none focus:border-blue-500 focus:bg-white appearance-none cursor-pointer transition">
                        <option value="">Kategori Surat</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ (string) $kategoriId === (string) $kategori->id ? 'selected' : '' }}>{{ $kategori->name }}</option>
                        @endforeach
                    </select>
                    <!-- Panah Kanan sesuai screenshot -->
                    <i class="fa-solid fa-arrow-right absolute right-3 top-1/2 transform -translate-y-1/2 text-black text-sm pointer-events-none"></i>
                </div>

                <!-- Select Pokja -->
                <div class="relative w-full md:w-56">
                    <select name="pokja_id" class="w-full border border-gray-300 bg-[#f4f7f6] rounded-md px-4 py-2.5 text-sm text-gray-500 focus:outline-none focus:border-blue-500 focus:bg-white appearance-none cursor-pointer transition">
                        <option value="">Kelompok Kerja</option>
                        @foreach ($pokjas as $pokja)
                            <option value="{{ $pokja->id }}" {{ (string) $pokjaId === (string) $pokja->id ? 'selected' : '' }}>{{ $pokja->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-arrow-right absolute right-3 top-1/2 transform -translate-y-1/2 text-black text-sm pointer-events-none"></i>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex gap-2">
                    <button type="submit" class="bg-[#2563eb] text-white px-8 py-2.5 rounded-md text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                        Filter
                    </button>
                    <a href="{{ route('superadmin.peminjaman.validasi') }}" class="bg-gray-200 text-gray-600 px-4 py-2.5 rounded-md text-sm font-bold hover:bg-gray-300 transition flex items-center justify-center">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                </div>
            </form>

            <!-- TABEL DATA -->
            <div class="bg-transparent overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Nama Arsip</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Kategori</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Kelompok Kerja</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Tanggal Upload</th>
                                <th class="py-3 px-4 text-sm font-bold text-[#445585]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-150">
                            @forelse ($pengajuan as $item)
                                @php($filePath = $item->file_pengajuan ?: $item->arsip?->file_path)
                                <tr class="hover:bg-white/40 transition">
                                    <td class="py-4 px-4 text-sm font-bold text-gray-600">{{ $item->nama_surat }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $item->kategori?->name ?? '-' }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $item->user?->pokja?->name ?? '-' }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-4 px-4">
                                        
                                        <!-- Container Aksi (Sejajar sesuai screenshot) -->
                                        <div class="flex items-center gap-2">
                                            
                                            <!-- Tombol Lihat -->
                                            @if ($filePath)
                                                <a target="_blank" rel="noopener" href="{{ asset('storage/' . $filePath) }}" class="bg-[#2563eb] text-white px-5 py-1.5 rounded text-xs font-bold hover:bg-blue-700 transition shadow-sm">
                                                    Lihat
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400 italic px-2">Tidak tersedia</span>
                                            @endif

                                            <!-- Tombol Tolak -->
                                            <form method="POST" action="{{ route('superadmin.peminjaman.decision', $item) }}" class="inline-block m-0">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="decision" value="reject">
                                                <button type="submit" class="bg-[#ef4444] text-white px-5 py-1.5 rounded text-xs font-bold hover:bg-red-600 transition shadow-sm">
                                                    Tolak
                                                </button>
                                            </form>

                                            <!-- Tombol Terima -->
                                            <form method="POST" action="{{ route('superadmin.peminjaman.decision', $item) }}" class="inline-block m-0">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="decision" value="approve">
                                                <button type="submit" class="bg-[#10b981] text-white px-5 py-1.5 rounded text-xs font-bold hover:bg-emerald-600 transition shadow-sm">
                                                    Terima
                                                </button>
                                            </form>
                                        </div>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 px-4 text-center text-sm text-gray-500 bg-white/30 rounded-lg">
                                        {{ $kategoriId || $pokjaId ? 'Tidak ada peminjaman yang sesuai dengan filter.' : 'Belum ada peminjaman yang perlu divalidasi.' }}
                                    </td>
                                </tr>
                            @endforelse
                            
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $pengajuan->withQueryString()->links() }}
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