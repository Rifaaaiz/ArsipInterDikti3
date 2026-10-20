<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Surat Masuk - {{ $pokja->name }}</title>
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
        $activeMenu = 'surat-masuk';
        $isPeminjaman = isset($peminjaman);
        $namaSurat = $isPeminjaman
            ? ($peminjaman->nama_surat ?: $peminjaman->arsip?->nama_arsip)
            : $arsip->nama_arsip;
        $kategoriSurat = $isPeminjaman
            ? ($peminjaman->kategori?->name ?? $peminjaman->arsip?->kategori?->name)
            : $arsip->kategori?->name;
        $sifatSurat = $isPeminjaman
            ? ($peminjaman->sifatSurat?->name ?? $peminjaman->arsip?->sifatSurat?->name)
            : $arsip->sifatSurat?->name;
        $klasifikasi = $isPeminjaman
            ? ($peminjaman->klasifikasiArsip?->kode ?? $peminjaman->arsip?->klasifikasiArsip?->kode)
            : $arsip->klasifikasiArsip?->kode;
        $filePath = $isPeminjaman
            ? ($peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path)
            : $arsip->file_path;
    @endphp
    @include('Super_Admin.partials.sidebar_superadmin')
    <main id="superadmin-main" class="flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 bg-[#f4f7f6] px-5 py-5 sm:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" data-sidebar-toggle aria-label="Buka navigasi" aria-expanded="false" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm md:hidden"><i class="fa-solid fa-bars" aria-hidden="true"></i></button>
                <h1 class="truncate text-xl font-bold text-gray-900 sm:text-2xl">Detail Surat Masuk</h1>
            </div>
            <div class="flex shrink-0 items-center gap-3"><span class="text-sm font-bold text-gray-900">{{ $user?->name ?? 'Super Admin' }}</span><i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i></div>
        </header>
        <section class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8">
            <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <h2 class="mb-6 text-lg font-bold text-gray-900">{{ $namaSurat ?: '-' }}</h2>
                <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    <div><dt class="text-sm font-medium text-gray-500">Kelompok Kerja</dt><dd class="mt-1 text-sm text-gray-800">{{ $pokja->name }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Nama Pengaju</dt><dd class="mt-1 text-sm text-gray-800">{{ $isPeminjaman ? $peminjaman->user?->name : $arsip->uploader?->name ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Nomor Dokumen</dt><dd class="mt-1 text-sm text-gray-800">{{ $isPeminjaman ? ($peminjaman->arsip?->nomor_dokumen ?? '-') : ($arsip->nomor_dokumen ?? '-') }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Jenis Surat</dt><dd class="mt-1 text-sm text-gray-800">{{ $kategoriSurat ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Sifat Surat</dt><dd class="mt-1 text-sm text-gray-800">{{ $sifatSurat ?? '-' }}</dd></div>
                    <div><dt class="text-sm font-medium text-gray-500">Kode Klasifikasi</dt><dd class="mt-1 text-sm text-gray-800">{{ $klasifikasi ?? '-' }}</dd></div>
                    @if ($isPeminjaman)
                        <div><dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Status</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->display_status }}</dd></div>
                        <div class="sm:col-span-2 xl:col-span-3"><dt class="text-sm font-medium text-gray-500">Keterangan</dt><dd class="mt-1 whitespace-pre-line text-sm text-gray-800">{{ $peminjaman->keterangan ?: $peminjaman->keperluan ?: '-' }}</dd></div>
                    @else
                        <div><dt class="text-sm font-medium text-gray-500">Tanggal Dokumen</dt><dd class="mt-1 text-sm text-gray-800">{{ $arsip->tanggal_dokumen?->format('d/m/Y') ?? '-' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Tanggal Mulai</dt><dd class="mt-1 text-sm text-gray-800">{{ $arsip->latestPeminjaman?->tanggal_mulai?->format('d/m/Y') ?? '-' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Tanggal Selesai</dt><dd class="mt-1 text-sm text-gray-800">{{ $arsip->latestPeminjaman?->tanggal_selesai?->format('d/m/Y') ?? '-' }}</dd></div>
                        <div><dt class="text-sm font-medium text-gray-500">Status</dt><dd class="mt-1 text-sm text-gray-800">{{ $arsip->latestPeminjaman?->display_status ?? ucfirst(str_replace('_', ' ', (string) $arsip->status)) }}</dd></div>
                    @endif
                </dl>
                @if ($isPeminjaman)
                    <div class="mt-7">
                        <h3 class="mb-3 text-base font-bold text-gray-900">Riwayat Balasan</h3>
                        <div class="space-y-3">
                            @forelse ($peminjaman->balasans as $balasan)
                                <article class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <div class="mb-2 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500">
                                        <span class="font-semibold text-gray-700">{{ $balasan->user?->name ?? 'Super Admin' }}</span>
                                        <time datetime="{{ $balasan->created_at?->toIso8601String() }}">{{ $balasan->created_at?->format('d/m/Y H:i') ?? '-' }}</time>
                                    </div>
                                    <p class="whitespace-pre-line text-sm text-gray-700">{{ $balasan->isi_balasan }}</p>
                                </article>
                            @empty
                                <p class="rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">Belum ada balasan untuk permohonan ini.</p>
                            @endforelse
                        </div>
                    </div>
                @endif
                <div class="mt-7 flex flex-wrap gap-3">
                    @if ($hasFile)
                        <a href="{{ $isPeminjaman ? route('superadmin.surat-masuk.peminjaman.file', [$pokja->slug, $peminjaman]) : route('superadmin.surat-masuk.file', [$pokja->slug, $arsip]) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">Lihat Lampiran</a>
                    @else
                        <span class="inline-flex items-center rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-500">Lampiran tidak tersedia</span>
                    @endif
                    <a href="{{ $isPeminjaman ? route('superadmin.surat-masuk.peminjaman.balas', [$pokja->slug, $peminjaman]) : route('superadmin.surat-masuk.balas', [$pokja->slug, $arsip]) }}" class="inline-flex items-center rounded-lg border border-blue-300 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 transition hover:bg-blue-100">Balas</a>
                    <a href="{{ route('superadmin.surat-masuk', $pokja->slug) }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Kembali</a>
                </div>
            </article>
        </section>
    </main>
</body>
</html>
