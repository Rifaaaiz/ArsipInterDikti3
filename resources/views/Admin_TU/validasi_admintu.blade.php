<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Peminjaman - Admin TU</title>
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
                <a href="{{ route('admin-tu.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-house"></i> Dashboard
                </a>
                <a href="{{ route('admin-tu.peminjaman.validasi') }}" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-1">
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
    </aside>

    <main class="flex-1 ml-64 flex flex-col min-h-screen">
        <header class="px-8 py-6 flex items-center justify-end sticky top-0 z-10 bg-[#f8f9fa]">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">{{ $user?->name ?? $user?->username ?? 'Admin TU' }}</span>
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

            <h2 class="text-3xl font-bold mb-6 text-black">Validasi Peminjaman</h2>

            <form method="GET" action="{{ route('admin-tu.peminjaman.validasi') }}" class="mb-8 grid grid-cols-1 gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm md:grid-cols-3">
                <div>
                    <label for="kategori_id" class="mb-1.5 block text-sm font-semibold text-gray-700">Kategori Surat</label>
                    <select id="kategori_id" name="kategori_id" class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ (string) $kategoriId === (string) $kategori->id ? 'selected' : '' }}>{{ $kategori->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="pokja_id" class="mb-1.5 block text-sm font-semibold text-gray-700">Kelompok Kerja</label>
                    <select id="pokja_id" name="pokja_id" class="w-full rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:outline-none">
                        <option value="">Semua Kelompok Kerja</option>
                        @foreach ($pokjas as $pokja)
                            <option value="{{ $pokja->id }}" {{ (string) $pokjaId === (string) $pokja->id ? 'selected' : '' }}>{{ $pokja->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-3">
                    <button type="submit" class="rounded-md bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Terapkan Filter</button>
                    <a href="{{ route('admin-tu.peminjaman.validasi') }}" class="rounded-md border border-gray-300 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">Reset</a>
                </div>
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[980px] text-left">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-sm font-bold text-gray-700">Nama Surat</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-700">Pengaju</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-700">Role</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-700">Pokja</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-700">Kategori</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-700">Tanggal Pengajuan</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-700">Status</th>
                                <th class="px-6 py-4 text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($pengajuan as $peminjaman)
                                @php
                                    $filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path;
                                    $roleSlug = $peminjaman->user?->role?->slug;
                                    $roleLabel = $roleSlug === 'ketua_pokja' ? 'Ketua Pokja' : ($roleSlug === 'admin_pokja' ? 'Admin Pokja' : ($roleSlug ?? '-'));
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-medium text-gray-700">{{ $peminjaman->nama_surat }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $peminjaman->user?->name ?? $peminjaman->user?->username ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $roleLabel }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $peminjaman->user?->pokja?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $peminjaman->kategori?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-gray-600">{{ $peminjaman->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-6 py-4"><span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Menunggu Validasi</span></td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-2">
                                            @if ($filePath)
                                                <a href="{{ asset('storage/' . $filePath) }}" target="_blank" rel="noopener" class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">Lihat</a>
                                            @else
                                                <span class="text-xs text-gray-400">File tidak tersedia.</span>
                                            @endif
                                            <form method="POST" action="{{ route('admin-tu.peminjaman.decision', $peminjaman) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="decision" value="reject">
                                                <button type="submit" class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-red-700">Tolak</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin-tu.peminjaman.decision', $peminjaman) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="decision" value="approve">
                                                <button type="submit" class="rounded-md bg-emerald-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-emerald-600">Terima</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-sm text-gray-500">
                                        {{ $kategoriId || $pokjaId ? 'Tidak ada peminjaman yang sesuai dengan filter.' : 'Belum ada peminjaman yang menunggu validasi.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $pengajuan->withQueryString()->links() }}</div>
        </div>
    </main>
</body>
</html>
