<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] flex flex-col md:flex-row font-sans text-gray-800">

    <!-- SIDEBAR KIRI -->
    <aside class="w-full md:w-64 bg-[#0a1120] text-white md:min-h-screen flex flex-col md:justify-between md:fixed md:top-0 md:left-0 z-20">
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
                <!-- Dashboard (AKTIF - Biru Solid, Tanpa Hover Abu) -->
                <a href="/admin-tu" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>
                
                <!-- Menu Tidak Aktif (Punya Hover Abu-abu Gelap yang Normal) -->
                <a href="/admin-tu/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>

                <a href="/admin-tu/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <a href="/admin-tu/laporan-arsip" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Laporan Arsip
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:text-red-400 transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="w-full min-w-0 flex-1 ml-0 md:ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas -->
        <header class="px-4 sm:px-8 py-6 flex items-center justify-end sticky top-0 z-10 bg-[#f8f9fa]">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">admin TU</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA DASHBOARD -->
        <div class="w-full min-w-0 px-4 sm:px-8 pb-10">
            <h2 class="text-2xl sm:text-3xl font-bold mb-6 text-black">Dashboard Admin TU</h2>

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <!-- 3 Kartu Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <!-- Kartu 1 -->
                <a href="{{ route('admin-tu.peminjaman.aktif') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:border-blue-500 hover:shadow-md transition cursor-pointer">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Peminjaman Aktif</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ $totalPeminjamanAktif }}</p>
                    <p class="text-xs text-gray-400">Peminjaman</p>
                </a>
                <!-- Kartu 2 -->
                <a href="{{ route('admin-tu.peminjaman.validasi') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:border-blue-500 hover:shadow-md transition cursor-pointer">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Menunggu Validasi</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ $totalMenungguValidasi }}</p>
                    <p class="text-xs text-gray-400">Peminjaman</p>
                </a>
                <!-- Kartu 3 -->
                <a href="{{ route('admin-tu.laporan.arsip') }}" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:border-blue-500 hover:shadow-md transition cursor-pointer">
                    <p class="text-gray-500 text-sm font-semibold mb-2">Total Arsip</p>
                    <p class="text-4xl font-bold text-gray-900 mb-1">{{ $totalArsip }}</p>
                    <p class="text-xs text-gray-400">Dokumen</p>
                </a>
            </div>

            <h3 class="mb-4 text-2xl font-bold text-black">Peminjaman Menunggu Validasi</h3>
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-330 border-collapse text-left">
                        <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Nama Arsip</th>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Jenis Surat</th>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Sifat Surat</th>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Kelompok Kerja</th>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Kode Klasifikasi</th>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Tanggal Mulai</th>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Tanggal Selesai</th>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Status</th>
                            <th class="px-5 py-4 text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @forelse ($menungguValidasi as $peminjaman)
                            @php
                                $fileUrl = $peminjaman->file_pengajuan
                                    ? asset('storage/' . $peminjaman->file_pengajuan)
                                    : ($peminjaman->arsip?->file_path ? asset('storage/' . $peminjaman->arsip->file_path) : null);
                            @endphp
                            <tr class="hover:bg-gray-100 transition">
                                <td class="border border-gray-300 py-3 px-5 text-sm text-gray-500 font-semibold">{{ $peminjaman->arsip?->nama_arsip ?? $peminjaman->nama_surat ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-5 text-sm text-gray-500 font-semibold">{{ $peminjaman->kategori?->name ?? $peminjaman->arsip?->kategori?->name ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-5 text-sm text-gray-500 font-semibold">{{ $peminjaman->sifatSurat?->name ?? $peminjaman->arsip?->sifatSurat?->name ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-5 text-sm text-gray-500 font-semibold">{{ $peminjaman->user?->pokja?->name ?? $peminjaman->arsip?->pokja?->name ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-5 text-sm text-gray-500 font-semibold">{{ $peminjaman->klasifikasiArsip?->kode ?? $peminjaman->arsip?->klasifikasiArsip?->kode ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-5 text-sm text-gray-500 font-semibold">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-5 text-sm text-gray-500 font-semibold">{{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                <td class="border border-gray-300 py-3 px-5 text-sm text-gray-500 font-semibold">{{ $peminjaman->display_status }}</td>
                                <td class="border border-gray-300 py-3 px-2">
                                    <div class="flex flex-wrap gap-2">
                                        @if ($fileUrl)
                                            <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-blue-700 transition">Lihat File</a>
                                        @else
                                            <span class="text-xs text-gray-400">File tidak tersedia.</span>
                                        @endif
                                        <form method="POST" action="{{ route('admin-tu.peminjaman.decision', $peminjaman) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="decision" value="reject">
                                            <button type="submit" class="bg-red-600 text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-red-700 transition">Tolak</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin-tu.peminjaman.decision', $peminjaman) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="decision" value="approve">
                                            <button type="submit" class="bg-[#10b981] text-white px-4 py-1.5 rounded-md text-sm font-semibold hover:bg-green-600 transition">Terima</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada peminjaman yang menunggu validasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                <div class="mt-4 flex justify-center">
                    <div class="p-4 text-center"><a href="{{ route('admin-tu.peminjaman.validasi') }}" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua</a></div>
                </div>
            </div>

        </div>
    </main>

    <x-logout-confirmation />
</body>
</html>