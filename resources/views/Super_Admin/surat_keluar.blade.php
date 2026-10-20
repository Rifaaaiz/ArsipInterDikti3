<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keluar {{ $pokjaTitle }} - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (min-width: 768px) {
            #superadmin-main { margin-left: 16rem; }
        }
    </style>
</head>
<body class="flex min-h-screen bg-[#f4f7f6] font-sans text-gray-800">
    @php
        $activePokja = $pokja;
        $activeMenu = 'surat-keluar';
    @endphp
    @include('Super_Admin.partials.sidebar_superadmin')

    <main id="superadmin-main" class="flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 bg-[#f4f7f6] px-5 py-5 sm:px-8 lg:px-10 xl:px-12">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" data-sidebar-toggle aria-label="Buka navigasi" aria-expanded="false" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm md:hidden">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
                <h1 class="truncate text-xl font-bold text-gray-900 sm:text-2xl">Surat Keluar {{ $pokjaTitle }}</h1>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <span class="text-sm font-bold text-gray-900">{{ $user?->name ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>
        <section class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8 lg:px-10 xl:px-12">
            <div class="mx-auto w-full max-w-[1600px]">
            <form method="GET" action="{{ route('superadmin.surat-keluar', $pokja->slug) }}" class="mb-6 w-full rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Nama Surat" aria-label="Nama Surat"
                        class="h-10 w-full rounded-md border border-gray-300 px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <select name="jenis_surat_id" aria-label="Jenis Surat" class="h-10 w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Jenis Surat</option>
                        @foreach ($jenisSurats as $jenis)
                            <option value="{{ $jenis->id }}" @selected(($filters['jenis_surat_id'] ?? '') == $jenis->id)>{{ $jenis->name }}</option>
                        @endforeach
                    </select>
                    <select name="klasifikasi_arsip_id" aria-label="Kode Klasifikasi" class="h-10 w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Kode Klasifikasi</option>
                        @foreach ($klasifikasis as $klasifikasi)
                            <option value="{{ $klasifikasi->id }}" @selected(($filters['klasifikasi_arsip_id'] ?? '') == $klasifikasi->id)>{{ $klasifikasi->kode }} — {{ $klasifikasi->judul }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="tanggal_dokumen" value="{{ $filters['tanggal_dokumen'] ?? '' }}" aria-label="Tanggal Dokumen"
                        class="h-10 w-full rounded-md border border-gray-300 px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <div class="flex gap-2">
                        <button type="submit" class="h-10 flex-1 rounded-md bg-blue-600 px-4 text-sm font-semibold text-white transition hover:bg-blue-700">Filter</button>
                        <a href="{{ route('superadmin.surat-keluar', $pokja->slug) }}" class="flex h-10 items-center rounded-md border border-gray-300 px-4 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Reset</a>
                    </div>
                </div>
            </form>

            <div class="mt-6 w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] border-collapse text-left">
                        <thead class="bg-gray-50">
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Nama Arsip</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Jenis Surat</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Kode Klasifikasi</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Tanggal Balasan</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Tanggal Selesai</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-indigo-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($suratKeluar as $item)
                                @php
                                    $arsip = $item->arsip;
                                    $status = 'Terkirim';
                                    $statusColor = 'text-green-700';
                                    $statusDot = 'bg-green-500';
                                    $filePath = $item->file_pengajuan ?: $arsip?->file_path;
                                    $lastReply = $item->balasans->last();
                                @endphp
                                <tr class="text-xs text-gray-600 transition hover:bg-gray-50">
                                    <td class="px-4 py-2.5 font-medium">{{ $item->nama_surat ?: $arsip?->nama_arsip ?: '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $item->kategori?->name ?? $arsip?->kategori?->name ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $item->klasifikasiArsip?->kode ?? $arsip?->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2.5">{{ $lastReply?->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2.5">{{ $item->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-4 py-2.5">
                                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap {{ $statusColor }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>{{ $status }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2.5 text-center">
                                        <a href="{{ route('superadmin.surat-masuk.peminjaman.detail', [$pokja->slug, $item]) }}" class="inline-flex rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                        @if ($filePath)
                                            <a href="{{ route('superadmin.surat-masuk.peminjaman.file', [$pokja->slug, $item]) }}" target="_blank" rel="noopener noreferrer" class="ml-1 inline-flex rounded-md border border-blue-500 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-50">File</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-5 py-12 text-center text-sm text-gray-500">Belum ada data surat keluar untuk Pokja {{ $pokjaTitle }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6">{{ $suratKeluar->links() }}</div>
            </div>
        </section>
    </main>
</body>
</html>