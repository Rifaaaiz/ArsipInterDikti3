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
    <main class="ml-64 flex min-h-screen flex-1 flex-col">
        
        <!-- Navbar Atas -->
        <header class="sticky top-0 z-10 flex items-center justify-between bg-[#f4f4f5] px-5 py-6 sm:px-8">
            <span class="text-sm font-semibold text-gray-500">Dashboard - Ketua Pokja</span>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-900">Ketua Pokja {{ $user->pokja?->name ?? 'Kelembagaan' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA DASHBOARD KETUA POKJA -->
        <div class="w-full max-w-7xl px-5 pb-10 sm:px-8">
            <h2 class="mb-6 text-3xl font-bold text-black">Dashboard Ketua Pokja</h2>

            <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                <a href="{{ route('ketua.riwayat.arsip') }}" class="group block rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-blue-500 hover:shadow-md">
                    <p class="mb-2 text-sm font-semibold text-gray-500 transition group-hover:text-blue-600">Total Arsip Pokja</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalArsip }}</p>
                    <p class="mt-2 text-xs text-gray-400">Dokumen</p>
                </a>

                <a href="{{ route('ketua.peminjaman.aktif') }}" class="group block rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-blue-500 hover:shadow-md">
                    <p class="mb-2 text-sm font-semibold text-gray-500 transition group-hover:text-blue-600">Peminjaman Aktif</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $peminjamanAktif }}</p>
                    <p class="mt-2 text-xs text-gray-400">Peminjaman</p>
                </a>

                <a href="{{ route('ketua.peminjaman.validasi') }}" class="group block rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-blue-500 hover:shadow-md">
                    <p class="mb-2 text-sm font-semibold text-gray-500 transition group-hover:text-blue-600">Usulan Upload</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $menungguValidasi }}</p>
                    <p class="mt-2 text-xs text-gray-400">Menunggu Validasi</p>
                </a>
            </div>

            <section>
                <h3 class="mb-4 text-lg font-bold text-black">Arsip Terbaru Pokja</h3>
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[1120px] border-collapse text-left">
                            <thead class="border-b border-gray-200 bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700">Nama Arsip</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700">Jenis Surat</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700">Sifat Surat</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700">Kode Klasifikasi</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700">Tanggal Mulai</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700">Tanggal Selesai</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700">Status</th>
                                    <th class="px-6 py-4 text-sm font-bold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse ($arsipTerbaru as $arsip)
                                    @php
                                        $peminjamanTerbaru = $arsip->latestPeminjaman;
                                        $statusArsip = $peminjamanTerbaru?->display_status
                                            ?? ucfirst(str_replace('_', ' ', (string) ($arsip->status ?? 'Tidak diketahui')));
                                        $statusStyle = match (strtolower($statusArsip)) {
                                            'aktif' => ['text' => 'text-blue-600', 'dot' => 'bg-blue-500'],
                                            'diterima', 'disetujui' => ['text' => 'text-green-600', 'dot' => 'bg-green-500'],
                                            'ditolak', 'dihapus', 'nonaktif' => ['text' => 'text-red-600', 'dot' => 'bg-red-500'],
                                            'menunggu', 'menunggu validasi', 'menunggu_validasi' => ['text' => 'text-yellow-600', 'dot' => 'bg-yellow-500'],
                                            'selesai', 'dikembalikan' => ['text' => 'text-gray-600', 'dot' => 'bg-gray-500'],
                                            default => ['text' => 'text-gray-600', 'dot' => 'bg-gray-500'],
                                        };
                                    @endphp
                                    <tr class="transition hover:bg-gray-50">
                                        <td class="px-6 py-3.5 font-medium text-gray-700">{{ $arsip->nama_arsip }}</td>
                                        <td class="px-6 py-3.5 text-gray-500">{{ $arsip->kategori?->name ?? '-' }}</td>
                                        <td class="px-6 py-3.5 text-gray-500">{{ $arsip->sifatSurat?->name ?? '-' }}</td>
                                        <td class="px-6 py-3.5 text-gray-500">{{ $arsip->klasifikasiArsip?->kode ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-6 py-3.5 text-gray-500">{{ $peminjamanTerbaru?->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-6 py-3.5 text-gray-500">{{ $peminjamanTerbaru?->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                        <td class="whitespace-nowrap px-6 py-3.5">
                                            <span class="inline-flex items-center gap-1.5 font-semibold {{ $statusStyle['text'] }}">
                                                <span class="h-2 w-2 rounded-full {{ $statusStyle['dot'] }}"></span>
                                                {{ $statusArsip }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3.5">
                                            @if ($arsip->file_path)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($arsip->file_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-md bg-blue-600 px-5 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                            @else
                                                <span class="text-xs text-gray-400">File tidak tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada arsip.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="border-t border-gray-100 p-4 text-center">
                        <a href="{{ route('ketua.riwayat.arsip') }}" class="text-sm font-semibold text-blue-600 transition hover:underline">Lihat Semua</a>
                    </div>
                </div>
            </section>
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
