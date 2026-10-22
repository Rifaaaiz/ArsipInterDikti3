<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Arsip Vital - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-width: 256px; }
        #superadmin-sidebar { width: var(--sidebar-width); transition: width 200ms ease-out, transform 200ms ease-out; }
        #superadmin-main { margin-left: var(--sidebar-width); transition: margin-left 200ms ease-out; }
        .sidebar-collapsed { --sidebar-width: 76px; }
        .sidebar-collapsed [data-sidebar-label],
        .sidebar-collapsed [data-sidebar-heading] { display: none; }
        .sidebar-collapsed #sidebar-logo { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-collapsed #superadmin-sidebar nav > a,
        .sidebar-collapsed #superadmin-sidebar nav > div > button { justify-content: center; padding-left: 0.5rem; padding-right: 0.5rem; }
        .sidebar-collapsed #superadmin-sidebar nav > div > button > span { justify-content: center; }
        .sidebar-collapsed #superadmin-sidebar nav > div > button > i { display: none; }
        .sidebar-collapsed #superadmin-sidebar nav > div > div { display: none; }
        .sidebar-collapsed #superadmin-sidebar form button { justify-content: center; }
        @media (max-width: 767px) {
            #superadmin-sidebar { width: 256px; transform: translateX(-100%); }
            #superadmin-main { margin-left: 0; }
            .sidebar-mobile-open #superadmin-sidebar { transform: translateX(0); }
            .sidebar-collapsed { --sidebar-width: 0px; }
            #sidebar-resize-handle { display: none; }
        }
    </style>
</head>
<body class="flex min-h-screen bg-[#f4f7f9] font-sans text-gray-800">
    @include('Super_Admin.partials.sidebar_superadmin')
    <main id="superadmin-main" class="flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 bg-[#f4f7f9] px-5 py-5 sm:px-8">
            <button id="sidebar-toggle" data-sidebar-toggle type="button" aria-label="Ciutkan atau buka sidebar" aria-expanded="true" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-900">{{ $user?->name ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <section class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8">
            <div class="mb-6 flex items-center gap-3">
                <a href="{{ route('superadmin.arsip-vital.aktif') }}" aria-label="Kembali ke Surat Vital Aktif" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 transition hover:border-blue-500 hover:text-blue-600">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Tambah Arsip Vital</h1>
            </div>

            <div class="w-full rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('superadmin.arsip-vital.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="submission_token" value="{{ $submissionToken }}">

                    <div class="mb-5">
                        <label for="nama_arsip" class="mb-1.5 block text-sm font-bold text-slate-800">Nama Surat <span class="text-red-600">*</span></label>
                        <input type="text" id="nama_arsip" name="nama_arsip" value="{{ old('nama_arsip') }}" placeholder="Nama Surat" required class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        @error('nama_arsip')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label for="nomor_dokumen" class="mb-1.5 block text-sm font-bold text-slate-800">Nomor Dokumen</label>
                            <input type="text" id="nomor_dokumen" name="nomor_dokumen" value="{{ old('nomor_dokumen') }}" placeholder="Nomor Dokumen" class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            @error('nomor_dokumen')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="kategori_arsip_id" class="mb-1.5 block text-sm font-bold text-slate-800">Jenis Surat <span class="text-red-600">*</span></label>
                            <select id="kategori_arsip_id" name="kategori_arsip_id" required class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                                <option value="">Pilih Jenis Surat</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" @selected((string) old('kategori_arsip_id') === (string) $kategori->id)>{{ $kategori->name }}</option>
                                @endforeach
                            </select>
                            @error('kategori_arsip_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="sifat_surat_id" class="mb-1.5 block text-sm font-bold text-slate-800">Sifat Surat <span class="text-red-600">*</span></label>
                            <select id="sifat_surat_id" name="sifat_surat_id" required class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                                <option value="">Pilih Sifat Surat</option>
                                @foreach ($sifatSurats as $sifatSurat)
                                    <option value="{{ $sifatSurat->id }}" @selected((string) old('sifat_surat_id') === (string) $sifatSurat->id)>{{ $sifatSurat->name }}</option>
                                @endforeach
                            </select>
                            @error('sifat_surat_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="tanggal_dokumen" class="mb-1.5 block text-sm font-bold text-slate-800">Tanggal Upload <span class="text-red-600">*</span></label>
                            <input type="date" id="tanggal_dokumen" name="tanggal_dokumen" value="{{ old('tanggal_dokumen', now()->format('Y-m-d')) }}" required class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            @error('tanggal_dokumen')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <div>
                            <label for="klasifikasi_arsip_id" class="mb-1.5 block text-sm font-bold text-slate-800">Kode Klasifikasi</label>
                            <select id="klasifikasi_arsip_id" name="klasifikasi_arsip_id" class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                                <option value="">Pilih Kode Klasifikasi</option>
                                @foreach ($klasifikasis as $klasifikasi)
                                    <option value="{{ $klasifikasi->id }}" @selected((string) old('klasifikasi_arsip_id') === (string) $klasifikasi->id)>{{ $klasifikasi->kode }} - {{ $klasifikasi->judul ?? $klasifikasi->uraian }}</option>
                                @endforeach
                            </select>
                            @error('klasifikasi_arsip_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <fieldset class="mb-5">
                        <legend class="mb-2 block text-sm font-bold text-slate-800">Tanda Tangan <span class="text-red-600">*</span></legend>
                        <div class="flex flex-wrap gap-5">
                            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-700">
                                <input type="radio" name="tanda_tangan" value="digital" required @checked(old('tanda_tangan') === 'digital') class="h-4 w-4 accent-blue-600">
                                <span>Digital</span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-700">
                                <input type="radio" name="tanda_tangan" value="konvensional" required @checked(old('tanda_tangan') === 'konvensional') class="h-4 w-4 accent-blue-600">
                                <span>Konvensional</span>
                            </label>
                        </div>
                        @error('tanda_tangan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </fieldset>

                    <div class="mb-5">
                        <label for="keterangan" class="mb-1.5 block text-sm font-bold text-slate-800">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="4" placeholder="Keterangan" class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5">
                        <label for="file_arsip" class="mb-1.5 block text-sm font-bold text-slate-800">Upload File <span class="text-red-600">*</span></label>
                        <label class="relative flex min-h-44 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center transition hover:border-blue-500 hover:bg-blue-50/40">
                            <input type="file" id="file_arsip" name="file_arsip" accept=".pdf,.doc,.docx,.zip,.rar,.jpg,.jpeg,.png,.gif,.bmp,.webp" required class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                            <span class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-600"><i class="fa-solid fa-cloud-arrow-up text-xl" aria-hidden="true"></i></span>
                            <span class="text-base font-semibold text-slate-800">Select File</span>
                            <span id="selected-file-name" class="mt-1 text-sm text-slate-500">Upload File Kearsipan Disini (PDF, Doc, DOCX, ZIP, RAR - Maks 5 Mb)</span>
                        </label>
                        <p class="mt-2 text-xs text-slate-500">Maximal upload file 5 MB. (Jika Lebih Bisa Di Kompress Filenya Terlebih Dahulu)</p>
                        @error('file_arsip')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-lg bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                        <span>Submit Arsip Vital</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </section>
    </main>
    <script>
        const fileInput = document.getElementById('file_arsip');
        const fileNameLabel = document.getElementById('selected-file-name');
        fileInput?.addEventListener('change', () => {
            fileNameLabel.textContent = fileInput.files?.[0]?.name || 'Upload File Kearsipan Disini (PDF, Doc, DOCX, ZIP, RAR - Maks 5 Mb)';
        });
    </script>
</body>
</html>