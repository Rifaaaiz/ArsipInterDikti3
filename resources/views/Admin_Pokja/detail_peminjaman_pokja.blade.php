@if (! request()->ajax())
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Peminjaman - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800">
@endif
    <main class="{{ request()->ajax() ? 'w-full' : 'mx-auto w-full max-w-4xl px-4 py-8 sm:px-6' }}">
        @unless (request()->ajax())
        <header class="mb-6 flex items-center justify-between gap-4">
            <a href="{{ route('pokja.peminjaman.aktif') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 hover:text-blue-800">
                <span aria-hidden="true">&larr;</span>Kembali ke Peminjaman Aktif
            </a>
            <span class="text-sm font-semibold text-slate-700">{{ $user->name }}</span>
        </header>
        @endunless

        <section class="{{ request()->ajax() ? '' : 'rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-8' }}">
            @unless (request()->ajax())
            <h1 class="sr-only">Detail Peminjaman</h1>
            @endunless
            <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-slate-500">Nama Surat</dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-900">{{ $peminjaman->nama_surat ?: $peminjaman->arsip?->nama_arsip ?: '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Jenis Surat</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $peminjaman->kategori?->name ?? $peminjaman->arsip?->kategori?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Sifat Surat</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $peminjaman->sifatSurat?->name ?? $peminjaman->arsip?->sifatSurat?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Kode Klasifikasi</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $peminjaman->klasifikasiArsip?->kode ?? $peminjaman->arsip?->klasifikasiArsip?->kode ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Tanggal Mulai</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Tanggal Selesai</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Status</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $peminjaman->display_status }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-slate-500">Pengaju</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $peminjaman->user?->name ?? '-' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-slate-500">Keterangan / Keperluan</dt>
                    <dd class="mt-1 whitespace-pre-line text-sm text-slate-800">{{ $peminjaman->keterangan ?: $peminjaman->keperluan ?: '-' }}</dd>
                </div>
            </dl>

            @if ($peminjaman->file_pengajuan)
                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($peminjaman->file_pengajuan) }}" target="_blank" rel="noopener noreferrer" class="mt-6 inline-flex rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Lihat File Pengajuan</a>
            @endif

            @if ($peminjaman->validations->isNotEmpty())
                <section class="mt-6 border-t border-slate-200 pt-5">
                    <h2 class="mb-3 text-sm font-bold text-slate-900">Informasi Validasi</h2>
                    <ul class="space-y-3">
                        @foreach ($peminjaman->validations as $validation)
                            <li class="rounded-lg bg-slate-50 px-4 py-3 text-sm">
                                <p class="font-semibold text-slate-800">
                                    {{ ucfirst(str_replace('_', ' ', $validation->status)) }}
                                    @if ($validation->validator?->name)
                                        <span class="font-normal text-slate-600">oleh {{ $validation->validator->name }}</span>
                                    @endif
                                </p>
                                <p class="mt-1 text-slate-500">{{ $validation->validated_at?->format('d/m/Y H:i') ?? '-' }}</p>
                                @if ($validation->alasan_penolakan)
                                    <p class="mt-2 whitespace-pre-line text-slate-700">{{ $validation->alasan_penolakan }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </section>
    </main>
@if (! request()->ajax())
</body>
</html>
@endif
