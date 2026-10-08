<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Aktif - Admin TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] flex font-sans text-gray-800">
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>

            <nav class="px-4 space-y-1">
                <a href="/admin-tu" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>  Dashboard
                </a>
                <a href="{{ route('admin-tu.peminjaman.validasi') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>
                <a href="/admin-tu/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                <a href="{{ route('admin-tu.laporan.arsip') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Laporan Arsip
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 text-red-600 font-bold hover:text-red-500 transition text-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-flip-horizontal"></i> Logout
                </button>
            </form>
        </div>
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:text-red-400 transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
    </aside>

    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        <header class="px-8 py-6 flex items-center justify-end sticky top-0 z-10 bg-[#f8f9fa]">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">{{ $user?->name ?? $user?->username ?? 'Admin TU' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <div class="px-8 pb-10 max-w-7xl w-full">
            
            <!-- BAGIAN YANG DIUBAH: Mengganti justify-between menjadi gap-4 dan items-center -->
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('admin-tu.dashboard') }}" class="text-black hover:text-blue-600 text-xl transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <h2 class="text-3xl font-bold text-black">Peminjaman Aktif</h2>
            </div>

            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[760px]">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Surat</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Pengaju</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Pokja</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Mulai</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Selesai</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Status</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($peminjamans as $peminjaman)
                                @php($filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3.5 px-6 text-gray-600 font-medium">{{ $peminjaman->nama_surat }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->user?->name ?? $peminjaman->user?->username ?? '-' }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->user?->pokja?->name ?? '-' }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-6">
                                        @php
                                            $status = $peminjaman->display_status;
                                            $statusStyles = [
                                                'Ditolak' => ['badge' => 'bg-red-100 text-red-700', 'dot' => 'bg-red-500'],
                                                'Diterima' => ['badge' => 'bg-green-100 text-green-700', 'dot' => 'bg-green-500'],
                                                'Menunggu' => ['badge' => 'bg-yellow-100 text-yellow-700', 'dot' => 'bg-yellow-500'],
                                                'Aktif' => ['badge' => 'bg-blue-100 text-blue-700', 'dot' => 'bg-blue-500'],
                                                'Selesai' => ['badge' => 'bg-gray-200 text-gray-700', 'dot' => 'bg-gray-500'],
                                            ];
                                            $statusStyle = $statusStyles[$status] ?? ['badge' => 'bg-gray-200 text-gray-700', 'dot' => 'bg-gray-500'];
                                        @endphp
                                        <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyle['badge'] }}">
                                            <span class="h-2 w-2 rounded-full {{ $statusStyle['dot'] }}"></span> {{ $status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        @if ($filePath)
                                            <a href="{{ asset('storage/' . $filePath) }}" target="_blank" rel="noopener" class="text-blue-600 font-semibold hover:underline">Lihat File</a>
                                        @else
                                            <span class="text-xs text-gray-400">File tidak tersedia.</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="py-10 px-6 text-center text-sm text-gray-500">Belum ada peminjaman aktif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6">{{ $peminjamans->links() }}</div>
        </div>
    </main>
    <x-logout-confirmation />
</body>
</html>
