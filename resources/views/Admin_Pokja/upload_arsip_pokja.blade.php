<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File Arsip - SIMARSIP</title>
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
                    
                    <!-- Sub-menu (Tampil tapi tidak aktif) -->
                <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/dashboard-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/dashboard-pokja/upload" class="block pl-11 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                 <a href="/dashboard-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                <a href="/dashboard-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                <a href="{{ route('arsip.seluruh') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 transition hover:text-white">
                    <i class="fa-solid fa-box-archive"></i> Seluruh Arsip
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
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
    <main class="flex-1 ml-64 flex flex-col min-h-screen">

        <!-- Navbar Atas -->
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-gray-600">Upload File Arsip Kelompok Kerja</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-800">{{ auth()->user()?->name ?? auth()->user()?->username ?? 'admin Pokja Kelembagaan' }}</span>
                <div class="w-8 h-8 rounded-full bg-[#e8efff] flex items-center justify-center text-blue-700">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
            </div>
        </header>

        <!-- KONTEN UTAMA -->
        <div class="p-8 w-full">
            <h2 class="text-2xl font-bold mb-6 text-gray-900">Upload Arsip</h2>

            {{-- PESAN SUKSES --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tombol Aksi -->
            <div class="flex gap-4 mb-10 w-full">
                <button class="flex-1 flex items-center justify-center gap-2 bg-transparent border border-blue-600 text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition shadow-sm">
                    Download Template <i class="fa-solid fa-arrow-down ml-1 text-sm"></i>
                </button>
                <a href="{{ route('arsip.form') }}" class="flex-1 flex items-center justify-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm">
                    Upload Arsip <i class="fa-solid fa-arrow-right ml-1 text-sm"></i>
                </a>a
            </div>

            <h3 class="text-xl font-bold mb-4 text-gray-900">Riwayat Upload Arsip Terakhir</h3>

            <!-- Tabel Riwayat Upload Arsip -->
            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 mb-6">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Nama Arsip</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Nomor Dokumen</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Jenis Surat</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Sifat Surat</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Kode Klasifikasi</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Tanggal Upload</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Status</th>
                            <th class="py-4 px-6 text-sm font-bold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($arsips as $arsip)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-6 text-sm text-gray-600 font-medium">{{ $arsip->nama_arsip }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $arsip->nomor_dokumen ?: '-' }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ optional($arsip->kategori)->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $arsip->sifatSurat?->name ?? '-' }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $arsip->klasifikasiArsip?->kode ?? 'DT.04.01' }}</td>
                                <td class="py-4 px-6 text-sm text-gray-500">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td class="py-4 px-6 text-sm">
                                    <span class="inline-flex items-center rounded-full bg-[#eafff5] px-2.5 py-1 text-xs font-semibold text-[#1d8f5a]">
                                        • Aktif
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <a href="{{ Storage::url($arsip->file_path) }}" target="_blank"
                                        class="inline-block bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-blue-700 transition">
                                        Lihat
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-gray-500">
                                    Belum ada arsip yang diupload.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                
                <!-- Link Lihat Semua -->
                <div class="p-4 border-t border-gray-100 text-center">
                    <a href="{{ route('arsip.riwayat') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</a>
                </div>
            </div>

            <!-- Pagination aman di dalam tag main, didorong ke kanan -->
            @if ($arsips instanceof \Illuminate\Contracts\Pagination\Paginator)
                <div class="pb-8 flex justify-end">
                    @if ($arsips->hasPages())<nav class="flex justify-center gap-2" aria-label="Pagination">@foreach ($arsips->getUrlRange(max(1, $arsips->currentPage() - 2), min($arsips->lastPage(), $arsips->currentPage() + 2)) as $page => $url)<a href="{{ $url }}" @class(['rounded px-3 py-2 text-sm font-semibold', 'bg-blue-600 text-white' => $page === $arsips->currentPage(), 'border border-gray-300 text-gray-700' => $page !== $arsips->currentPage()])>{{ $page }}</a>@endforeach</nav>@endif
                </div>
            @endif

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
