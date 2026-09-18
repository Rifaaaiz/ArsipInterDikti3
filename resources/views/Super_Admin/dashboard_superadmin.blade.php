<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f6] flex font-sans text-gray-800">
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>
            <nav class="px-4 space-y-1">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <div class="pt-2">
                    <button type="button" onclick="toggleMenu('submenu-kelola')" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder-open"></i> Kelola Arsip</span>
                        <i id="ikon-kelola" class="fa-solid fa-arrow-right text-xs transition-transform duration-300"></i>
                    </button>
                    <div id="submenu-kelola" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/super-admin/penghapusan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Penghapusan Arsip</a>
                            <a href="/super-admin/laporan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Laporan Arsip</a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('superadmin.peminjaman.validasi') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>
                <a href="/super-admin/kelola" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-solid fa-users"></i> Kelola User
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-solid fa-gear"></i> Pengaturan Sistem
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
    </aside>

    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        <header class="px-8 py-6 flex items-center justify-end sticky top-0 z-10 bg-[#f4f7f6]">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">{{ $user?->name ?? $user?->username ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <div class="px-8 pb-10 max-w-7xl w-full">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <h2 class="text-3xl font-bold mb-6 text-black">Dashboard Super Admin</h2>

            <div class="grid grid-cols-1 gap-6 mb-10 md:grid-cols-4">
                <a href="/super-admin/laporan" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip Pokja</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalArsip }}</p>
                    <p class="text-xs text-gray-400 font-semibold">Dokumen</p>
                </a>
                <a href="/super-admin/peminjaman-aktif" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition">
                    <p class="text-gray-400 text-sm font-bold mb-2">Peminjaman Aktif</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalPeminjamanAktif }}</p>
                    <p class="text-xs text-gray-400 font-semibold">Peminjaman</p>
                </a>
                <a href="/super-admin/kelola" class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-500 transition">
                    <p class="text-gray-400 text-sm font-bold mb-2">User Aktif</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalUserAktif }}</p>
                    <p class="text-xs text-gray-400 font-semibold">User</p>
                </a>
                <div class="block bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Pokja</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalPokja }}</p>
                    <p class="text-xs text-gray-400 font-semibold">Kelompok</p>
                </div>
            </div>

            <h3 class="text-2xl font-bold mb-4 text-black">Arsip Terbaru Pokja</h3>
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-10">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left border-collapse">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Nama Arsip</th>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Kategori</th>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Pokja</th>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Tanggal</th>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($arsipTerbaru as $arsip)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-6 text-sm text-gray-600 font-semibold">{{ $arsip->nama_arsip }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $arsip->kategori?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $arsip->pokja?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm">
                                        @if ($arsip->file_path)
                                            <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" rel="noopener" class="rounded-md bg-blue-600 px-4 py-1.5 text-white hover:bg-blue-700">Lihat</a>
                                        @else
                                            <span class="text-xs text-gray-400">File tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-8 px-6 text-center text-sm text-gray-500">Belum ada arsip.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 text-center"><a href="/super-admin/laporan" class="text-blue-600 text-sm font-bold hover:underline">Lihat Semua</a></div>
            </div>

            <h3 class="text-2xl font-bold mb-4 text-black">Peminjaman Terbaru</h3>
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left border-collapse">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Nama Surat</th>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Pengaju</th>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Pokja</th>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Tanggal</th>
                                <th class="py-4 px-6 text-sm font-bold text-indigo-900">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($peminjamanTerbaru as $peminjaman)
                                @php
                                    $statusLabels = [
                                        'menunggu_validasi' => 'Menunggu Validasi',
                                        'aktif' => 'Aktif',
                                        'ditolak' => 'Ditolak',
                                        'selesai' => 'Selesai',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-6 text-sm text-gray-600 font-semibold">{{ $peminjaman->nama_surat }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $peminjaman->user?->name ?? $peminjaman->user?->username ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $peminjaman->user?->pokja?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $peminjaman->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600">{{ $statusLabels[$peminjaman->status] ?? ucfirst(str_replace('_', ' ', $peminjaman->status)) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="py-8 px-6 text-center text-sm text-gray-500">Belum ada peminjaman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 text-center"><a href="{{ route('superadmin.peminjaman.validasi') }}" class="text-blue-600 text-sm font-bold hover:underline">Lihat Semua</a></div>
            </div>
        </div>
    </main>

    <script>
        function toggleMenu(id) {
            const submenu = document.getElementById(id);
            const icon = document.getElementById('ikon-kelola');
            submenu.classList.toggle('max-h-40');
            submenu.classList.toggle('max-h-0');
            icon.classList.toggle('rotate-90');
        }
    </script>
</body>
</html>
