<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Ketua Pokja</title>
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
            
            <!-- Menu Sidebar Ketua Pokja -->
            <nav class="px-4 space-y-1">
                <!-- Dashboard (Active) -->
                <a href="/ketua-pokja" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>
                
                <!-- Wrapper Menu Arsip Pokja -->
                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/ketua-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/ketua-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <!-- Validasi Upload (Khas Ketua Pokja) -->
                <a href="/ketua-pokja/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-circle-check"></i> Validasi Upload
                </a>

                <!-- Profil -->
                <a href="/ketua-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- History -->
                <a href="/ketua-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-clock"></i> History
                </a>

                <!-- Panduan Penggunaan -->
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
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
        
        <!-- Navbar Atas -->
        <header class="px-8 py-6 flex items-center justify-between sticky top-0 z-10 bg-[#f4f4f5]">
            <span class="text-sm font-semibold text-gray-500">Dashboard - Ketua Pokja</span>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">
                    {{ $user->name }}{{ $user->pokja ? ' - Ketua ' . $user->pokja->name : '' }}
                </span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA DASHBOARD KETUA POKJA -->
        <div class="px-8 pb-10 max-w-7xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Dashboard Ketua Pokja</h2>

            <!-- 3 Kartu Statistik (Termasuk Usulan Upload / Menunggu Validasi) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Kartu 1: Total Arsip Pokja -->
                    <a href="{{ url('/ketua-pokja/history') }}" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition group">
                        <p class="text-gray-500 text-sm font-semibold mb-2 group-hover:text-blue-600 transition">Total Arsip Pokja</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalArsip }}</p>
                        <p class="text-xs text-gray-400 mt-2">Dokumen</p>
                    </a>

                    <!-- Kartu 2: Peminjaman Aktif -->
                    <a href="{{ url('/ketua-pokja/peminjaman-aktif') }}" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition group">
                        <p class="text-gray-500 text-sm font-semibold mb-2 group-hover:text-blue-600 transition">Peminjaman Aktif</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $peminjamanAktif }}</p>
                        <p class="text-xs text-gray-400 mt-2">Peminjaman</p>
                    </a>

                    <!-- Kartu 3: Upload Arsip -->
                    <a href="{{ url('/ketua-pokja/upload') }}" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition group">
                        <p class="text-gray-500 text-sm font-semibold mb-2 group-hover:text-blue-600 transition">Upload Arsip</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $totalPeminjaman }}</p>
                        <p class="text-xs text-gray-400 mt-2">Dokumen</p>
                    </a>
                </div>

            <!-- Aksi Cepat -->
            <h3 class="text-lg font-bold mb-4 text-black">Aksi Cepat</h3>
            <div class="flex flex-wrap gap-4 mb-8">
                <!-- Tombol Ajukan Peminjaman Arsip (Hijau) -->
                <a href="{{ url('/ketua-pokja/ajukan') }}" class="flex items-center gap-2 bg-[#dcfce7] text-[#15803d] border border-[#bbf7d0] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#bbf7d0] transition shadow-sm text-sm">
                    <i class="fa-solid fa-file-circle-plus"></i> Ajukan Peminjaman Arsip
                </a>
                <!-- Tombol Upload File Arsip Pokja (Biru) -->
                <a href="{{ url('/ketua-pokja/upload') }}" class="flex items-center gap-2 bg-[#dbeafe] text-[#1d4ed8] border border-[#bfdbfe] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#bfdbfe] transition shadow-sm text-sm">
                    <i class="fa-solid fa-cloud-arrow-up"></i> Upload File Arsip Pokja
                </a>
                <!-- Tombol Validasi Usulan Upload (Kuning) -->
                <a href="{{ url('/ketua-pokja/validasi') }}" class="flex items-center gap-2 bg-[#fef9c3] text-[#a16207] border border-[#fde047] px-5 py-2.5 rounded-lg font-semibold hover:bg-[#fde047] transition shadow-sm text-sm">
                    <i class="fa-solid fa-circle-check"></i> Validasi Usulan Upload
                </a>
            </div>

            <!-- Arsip Terbaru Pokja -->
            <h3 class="text-lg font-bold mb-4 text-black">Arsip Terbaru Pokja</h3>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 mb-4">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Arsip</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Kategori</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Upload</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($arsipTerbaru as $arsip)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-6 text-gray-700 font-medium">{{ $arsip->nama_arsip }}</td>
                                <td class="py-3.5 px-6 text-gray-500">{{ $arsip->kategori?->name ?? '-' }}</td>
                                <td class="py-3.5 px-6 text-gray-500">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td class="py-3.5 px-6">
                                    @if ($arsip->file_path)
                                        <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" rel="noopener" class="inline-block bg-blue-600 text-white px-5 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700 transition">Lihat</a>
                                    @else
                                        <span class="text-xs text-gray-400">File tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-8 px-6 text-center text-sm text-gray-500">Belum ada arsip.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t border-gray-100 text-center">
                    <a href="{{ url('/ketua-pokja/history') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</a>
                </div>
            </div>

            <h3 class="text-lg font-bold mb-4 text-black">Peminjaman Terbaru</h3>
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 mb-4">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Surat</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Pengaju</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Status</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($peminjamanTerbaru as $peminjaman)
                            @php
                                $statusLabels = ['Menunggu' => 'Menunggu', 'Aktif' => 'Aktif', 'Ditolak' => 'Ditolak', 'Selesai' => 'Selesai'];
                                $statusClasses = ['Menunggu' => 'text-amber-700 bg-amber-100', 'Aktif' => 'text-blue-700 bg-blue-100', 'Ditolak' => 'text-red-700 bg-red-100', 'Selesai' => 'text-gray-700 bg-gray-200'];
                                $status = $peminjaman->display_status;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-3.5 px-6 text-gray-700 font-medium">{{ $peminjaman->nama_surat }}</td>
                                <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->user?->name ?? '-' }}</td>
                                <td class="py-3.5 px-6"><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$status] ?? 'text-gray-700 bg-gray-200' }}">{{ $statusLabels[$status] ?? ucfirst(str_replace('_', ' ', $status)) }}</span></td>
                                <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td class="py-3.5 px-6"><div class="flex flex-wrap gap-2">
                                    @if ($peminjaman->file_pengajuan || $peminjaman->arsip?->file_path)
                                        <a href="{{ asset('storage/' . ($peminjaman->file_pengajuan ?: $peminjaman->arsip->file_path)) }}" target="_blank" rel="noopener" class="bg-blue-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold hover:bg-blue-700">Lihat File</a>
                                    @else
                                        <span class="text-xs text-gray-400">File tidak tersedia</span>
                                    @endif
                                    <form method="POST" action="{{ route('ketua.peminjaman.decision', $peminjaman) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="approve"><button type="submit" class="bg-emerald-500 text-white px-3 py-1.5 rounded-md text-xs font-semibold hover:bg-emerald-600">Terima</button></form>
                                    <form method="POST" action="{{ route('ketua.peminjaman.decision', $peminjaman) }}">@csrf @method('PATCH')<input type="hidden" name="decision" value="reject"><button type="submit" class="bg-red-600 text-white px-3 py-1.5 rounded-md text-xs font-semibold hover:bg-red-700">Tolak</button></form>
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-8 px-6 text-center text-sm text-gray-500">Belum ada pengajuan peminjaman yang perlu divalidasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
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
