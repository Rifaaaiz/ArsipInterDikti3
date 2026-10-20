<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin Pokja - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 flex font-sans text-slate-800">

    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>

            <nav class="px-4 space-y-1">
                <a href="/dashboard-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold bg-blue-600 text-white rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Dashboard
                </a>

                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>

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

                <a href="/dashboard-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-clock"></i> History
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

    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        <header class="bg-white shadow-sm px-8 py-4 flex items-center justify-between sticky top-0 z-10">
            <span class="text-sm font-semibold text-gray-700">Dashboard Admin Pokja</span>
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-700">{{ Auth::user()->name ?? 'Admin Pokja' }}</span>
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">
                    <i class="fa-solid fa-user"></i>
                </div>
            </div>
        </header>

        <div class="p-8">
            <h2 class="text-2xl font-bold mb-6">Dashboard Admin Pokja</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="/dashboard-pokja/history" class="block bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition group">
                    <p class="text-slate-500 text-sm font-semibold mb-2">Total Arsip Pokja</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $totalArsip }}</p>
                    <p class="text-xs text-slate-400 mt-2">Dokumen</p>
                </a>

                <a href="{{ route('pokja.peminjaman.aktif') }}" class="block bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition group">
                    <p class="text-slate-500 text-sm font-semibold mb-2">Peminjaman Aktif</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $peminjamanAktif }}</p>
                    <p class="text-xs text-slate-400 mt-2">Peminjaman</p>
                </a>

                <a href="/dashboard-pokja/upload" class="block bg-white p-6 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition group">
                    <p class="text-slate-500 text-sm font-semibold mb-2">Upload Arsip</p>
                    <p class="text-3xl font-bold text-slate-800">{{ $uploadArsip }}</p>
                    <p class="text-xs text-slate-400 mt-2">Dokumen</p>
                </a>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold mb-4">Aksi Cepat</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('peminjaman.ajukan') }}" class="flex items-center gap-2 bg-emerald-100 text-emerald-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-emerald-200 transition shadow-sm border border-emerald-200">
                        <i class="fa-solid fa-file-circle-plus"></i> Ajukan Peminjaman Arsip
                    </a>
                    <a href="{{ route('arsip.index') }}" class="flex items-center gap-2 bg-blue-100 text-blue-700 px-5 py-2.5 rounded-xl font-semibold hover:bg-blue-200 transition shadow-sm border border-blue-200">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Upload File Arsip Pokja
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold mb-4">Arsip Terbaru Pokja</h3>
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-6 text-sm font-semibold text-slate-600">Nama Arsip</th>
                                <th class="py-3 px-6 text-sm font-semibold text-slate-600">Jenis Surat</th>
                                <th class="py-3 px-6 text-sm font-semibold text-slate-600">Sifat Surat</th>
                                <th class="py-3 px-6 text-sm font-semibold text-slate-600">Kode Klasifikasi</th>
                                <th class="py-3 px-6 text-sm font-semibold text-slate-600">Tanggal Mulai</th>
                                <th class="py-3 px-6 text-sm font-semibold text-slate-600">Tanggal Selesai</th>
                                <th class="py-3 px-6 text-sm font-semibold text-slate-600">Status</th>
                                <th class="py-3 px-6 text-sm font-semibold text-slate-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($arsipTerbaru as $arsip)
                                @php
                                    $peminjamanTerbaru = $arsip->latestPeminjaman;
                                    $statusArsip = $peminjamanTerbaru?->display_status ?? ucfirst((string) ($arsip->status ?? 'Tidak diketahui'));
                                    $statusClass = match (strtolower($statusArsip)) {
                                        'aktif' => 'bg-emerald-100 text-emerald-700',
                                        'diterima', 'disetujui' => 'bg-blue-100 text-blue-700',
                                        'selesai', 'dikembalikan' => 'bg-slate-100 text-slate-700',
                                        'ditolak', 'dihapus' => 'bg-red-100 text-red-700',
                                        default => 'bg-amber-100 text-amber-700',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="py-3 px-6 text-sm text-slate-800">{{ $arsip->nama_arsip }}</td>
                                    <td class="py-3 px-6 text-sm text-slate-600">{{ optional($arsip->kategori)->name ?? '-' }}</td>
                                    <td class="py-3 px-6 text-sm text-slate-600">{{ $arsip->sifatSurat?->name ?? '-' }}</td>
                                    <td class="py-3 px-6 text-sm text-slate-600">{{ $arsip->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="py-3 px-6 text-sm text-slate-600">{{ $peminjamanTerbaru?->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-3 px-6 text-sm text-slate-600">{{ $peminjamanTerbaru?->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-3 px-6 text-sm">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $statusArsip }}</span>
                                    </td>
                                    <td class="py-3 px-6">
                                        @php
                                            $lihatUrl = $arsip->file_path ? Storage::disk('public')->url($arsip->file_path) : '#';
                                        @endphp
                                        <a href="{{ $lihatUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center bg-blue-600 text-white px-3 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition {{ $arsip->file_path ? '' : 'pointer-events-none opacity-60' }}">Lihat</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-6 px-6 text-sm text-slate-500 text-center">
                                        Belum ada arsip yang diunggah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                    <div class="p-4 border-t border-slate-200 text-center">
                        <a href="{{ route('arsip.riwayat') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

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