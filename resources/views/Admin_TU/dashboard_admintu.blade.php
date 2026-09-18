<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] flex font-sans text-gray-800">

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
                <!-- Dashboard (Aktif) -->
                <a href="/admin-tu" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                
                <a href="/admin-tu/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>

                <a href="/admin-tu/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <a href="/admin-tu/laporan-arsip" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Laporan Arsip
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
        <div class="p-6">
            <a href="#" class="flex items-center gap-3 text-red-600 font-bold hover:text-red-500 transition text-sm">
                <i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i> Logout
            </a>
        </div>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        
        <!-- Navbar Atas -->
        <header class="px-8 py-6 flex items-center justify-end sticky top-0 z-10 bg-[#f8f9fa]">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">admin TU</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN UTAMA DASHBOARD -->
        <div class="px-8 pb-10 max-w-7xl">
            <h2 class="text-3xl font-bold mb-6 text-black">Dashboard Admin TU</h2>

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

            <h3 class="text-xl font-bold mb-4 text-gray-900">Peminjaman Menunggu Validasi</h3>
            <div class="bg-transparent overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Nama Surat</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Pengaju</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Kategori</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Tanggal Pengajuan</th>
                            <th class="py-4 px-2 text-base font-bold text-indigo-900">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-transparent">
                        @forelse ($menungguValidasi as $peminjaman)
                            @php
                                $fileUrl = $peminjaman->file_pengajuan
                                    ? asset('storage/' . $peminjaman->file_pengajuan)
                                    : ($peminjaman->arsip?->file_path ? asset('storage/' . $peminjaman->arsip->file_path) : null);
                                $roleLabel = $peminjaman->user?->role?->slug === 'ketua_pokja' ? 'Ketua Pokja' : 'Admin Pokja';
                            @endphp
                            <tr class="hover:bg-gray-100 transition">
                                <td class="py-3 px-2 text-sm text-gray-500 font-semibold">{{ $peminjaman->nama_surat }}</td>
                                <td class="py-3 px-2 text-sm text-gray-500 font-semibold">{{ $peminjaman->user?->name ?? $peminjaman->user?->username ?? '-' }} ({{ $roleLabel }})</td>
                                <td class="py-3 px-2 text-sm text-gray-500 font-semibold">{{ $peminjaman->kategori?->name ?? '-' }}</td>
                                <td class="py-3 px-2 text-sm text-gray-500 font-semibold">{{ $peminjaman->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td class="py-3 px-2">
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
                            <tr><td colspan="5" class="py-8 px-2 text-center text-sm text-gray-500">Belum ada peminjaman yang menunggu validasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    <a href="{{ route('admin-tu.peminjaman.validasi') }}" class="text-blue-600 text-sm font-semibold hover:underline">Lihat Semua</a>
                </div>
            </div>

        </div>
    </main>

</body>
</html>