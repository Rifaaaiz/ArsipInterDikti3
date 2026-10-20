ta<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balas Surat Peminjaman - Super Admin</title>
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
        $klasifikasi = $isPeminjaman
            ? ($peminjaman->klasifikasiArsip?->kode ?? $peminjaman->arsip?->klasifikasiArsip?->kode)
            : $arsip->klasifikasiArsip?->kode;
        $sifatSurat = $isPeminjaman
            ? ($peminjaman->sifatSurat?->name ?? $peminjaman->arsip?->sifatSurat?->name)
            : $arsip->sifatSurat?->name;
        $keterangan = $isPeminjaman
            ? ($peminjaman->keterangan ?: $peminjaman->keperluan)
            : null;
        $filePath = $isPeminjaman
            ? ($peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path)
            : $arsip->file_path;
    @endphp
    @include('Super_Admin.partials.sidebar_superadmin')
    <main id="superadmin-main" class="flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 bg-[#f4f7f6] px-5 py-5 sm:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" data-sidebar-toggle aria-label="Buka navigasi" aria-expanded="false" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm md:hidden">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
                <h1 class="truncate text-xl font-bold text-gray-900 sm:text-2xl">Balas Surat Peminjaman</h1>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <span class="text-sm font-bold text-gray-900">{{ $user?->name ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>
        <section class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8">
            @if (session('success'))
                <div role="status" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif
            <div class="mt-5 rounded-xl border border-gray-200 bg-white p-5 shadow-sm sm:p-7">
                <h2 class="mb-5 text-2xl font-bold text-gray-900">Balas Surat Peminjaman</h2>
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Kelompok Kerja</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $pokja->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Nama Arsip</dt>
                        <dd class="mt-1 text-sm font-semibold text-gray-900">{{ $namaSurat ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Jenis Surat</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $kategoriSurat ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Kode Klasifikasi</dt>
                        <dd class="mt-1 text-sm text-gray-700">{{ $klasifikasi ?? '-' }}</dd>
                    </div>
                    @if ($isPeminjaman)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pengaju</dt>
                            <dd class="mt-1 text-sm text-gray-700">{{ $peminjaman->user?->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Periode Peminjaman</dt>
                            <dd class="mt-1 text-sm text-gray-700">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }} – {{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-6 border-t border-gray-200 pt-5">
                    <div class="mb-4">
                        <h3 class="text-base font-bold text-gray-900">Informasi Tambahan Permohonan</h3>
                        <p class="mt-1 text-xs text-gray-500">Informasi ini berasal dari pengajuan asli dan tidak dapat diubah saat membalas.</p>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="detail_sifat_surat" class="mb-1.5 block text-sm font-semibold text-gray-700">Sifat Surat</label>
                            <input id="detail_sifat_surat" type="text" value="{{ $sifatSurat ?? '-' }}" readonly class="h-11 w-full rounded-md border border-gray-300 bg-gray-50 px-3 text-sm text-gray-700">
                        </div>
                        <div>
                            <label for="detail_keterangan" class="mb-1.5 block text-sm font-semibold text-gray-700">Keterangan / Keperluan</label>
                            <textarea id="detail_keterangan" rows="2" readonly class="w-full rounded-md border border-gray-300 bg-gray-50 px-3 py-2.5 text-sm text-gray-700">{{ $keterangan ?: '-' }}</textarea>
                        </div>
                    </div>
                    @if ($filePath)
                        <div class="mt-4 rounded-lg border border-dashed border-blue-300 bg-blue-50 px-4 py-3">
                            <p class="text-sm font-semibold text-gray-800">File Pengajuan</p>
                            <p class="mt-1 text-xs text-gray-600">Lampiran dari permohonan ini tersedia untuk ditinjau melalui tombol Lihat Arsip.</p>
                        </div>
                    @endif
                </div>

                <form method="POST" action="{{ $isPeminjaman ? route('superadmin.surat-masuk.peminjaman.balas.store', [$pokja->slug, $peminjaman]) : route('superadmin.surat-masuk.balas.store', [$pokja->slug, $arsip]) }}" enctype="multipart/form-data" class="mt-6">
                    @csrf
                    <label for="isi_balasan" class="mb-2 block text-sm font-semibold text-gray-700">Isi Balasan</label>
                    <textarea id="isi_balasan" name="isi_balasan" rows="6" maxlength="10000" required class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('isi_balasan') }}</textarea>
                    @error('isi_balasan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-4">
                        <label for="lampiran_balasan" class="mb-2 block text-sm font-semibold text-gray-700">Lampiran Balasan <span class="font-normal text-gray-500">(opsional)</span></label>
                        <label for="lampiran_balasan" class="flex min-h-44 cursor-pointer flex-col items-center justify-center rounded-lg border border-dashed border-blue-400 bg-[#f8f9fa] px-5 py-6 text-center transition hover:bg-blue-50">
                            <input id="lampiran_balasan" name="lampiran_balasan" type="file" accept=".pdf,.doc,.docx,.zip,.rar" class="sr-only">
                            <i class="fa-solid fa-cloud-arrow-up mb-3 text-3xl text-blue-600" aria-hidden="true"></i>
                            <span id="file-name" class="font-bold text-blue-700">Select File</span>
                            <span class="mt-2 text-sm text-gray-600">Upload File Kearsipan Disini (PDF, DOC, DOCX, ZIP, RAR - Maks 5 Mb)</span>
                            <span id="file-error" class="mt-1 text-xs text-red-600" aria-live="polite"></span>
                        </label>
                        <p class="mt-2 text-xs text-gray-500">Maximal upload file 5 MB. (Jika Lebih Bisa Di Kompres Filenya Terlebih Dahulu)</p>
                        @error('lampiran_balasan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="mt-4 flex h-12 w-full items-center justify-center gap-2 rounded-md bg-blue-600 px-5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span>Kirim Balasan</span><i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>

                <div class="mt-8">
                    <h3 class="mb-3 text-base font-bold text-gray-900">Riwayat Balasan</h3>
                    <div class="space-y-3">
                        @forelse ($balasans as $balasan)
                            <article class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <div class="mb-2 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500">
                                    <span class="font-semibold text-gray-700">{{ $balasan->user?->name ?? 'Pengguna' }}</span>
                                    <time datetime="{{ $balasan->created_at->toIso8601String() }}">{{ $balasan->created_at->format('d/m/Y H:i') }}</time>
                                </div>
                                <p class="whitespace-pre-line text-sm text-gray-700">{{ $balasan->isi_balasan }}</p>
                                @if ($balasan->attachment_path)
                                    <a href="{{ asset('storage/' . $balasan->attachment_path) }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-800">
                                        <i class="fa-solid fa-paperclip" aria-hidden="true"></i>Lihat / unduh lampiran
                                    </a>
                                @endif
                            </article>
                        @empty
                            <p class="rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">Belum ada balasan untuk {{ $isPeminjaman ? 'permohonan' : 'arsip' }} ini.</p>
                        @endforelse
                    </div>
                    <div class="mt-4">{{ $balasans->links() }}</div>
                </div>
                <div class="mt-5 flex flex-wrap gap-3">
                    @if ($hasFile)
                        <a href="{{ $isPeminjaman ? route('superadmin.surat-masuk.peminjaman.file', [$pokja->slug, $peminjaman]) : route('superadmin.surat-masuk.file', [$pokja->slug, $arsip]) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                            <i class="fa-regular fa-eye" aria-hidden="true"></i>Lihat Arsip
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-500">Lampiran tidak tersedia</span>
                    @endif
                    <a href="{{ route('superadmin.surat-masuk', $pokja->slug) }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>Kembali ke Surat Masuk
                    </a>
                </div>
            </div>
        </section>
    </main>
    <script>
        (() => {
            const input = document.getElementById('lampiran_balasan');
            const fileName = document.getElementById('file-name');
            const error = document.getElementById('file-error');
            const form = input?.closest('form');
            const maxSize = 5 * 1024 * 1024;
            const validate = () => {
                const file = input?.files?.[0];
                if (!file || file.size <= maxSize) { error.textContent = ''; if (file) fileName.textContent = file.name; return true; }
                input.value = ''; fileName.textContent = 'Select File'; error.textContent = 'Ukuran file maksimal 5 MB.'; return false;
            };
            input?.addEventListener('change', validate);
            form?.addEventListener('submit', (event) => { if (!validate()) event.preventDefault(); });
        })();
    </script>
</body>
</html>
