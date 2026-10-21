<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File Kearsipan - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [data-dropdown-menu] {
            opacity: 0;
            transform: translateY(-0.5rem);
            visibility: hidden;
            pointer-events: none;
            transition: opacity 200ms ease-out, transform 200ms ease-out, visibility 200ms ease-out;
        }

        [data-dropdown-menu][data-open="true"] {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
            pointer-events: auto;
        }

        [data-dropdown-trigger][aria-expanded="true"] [data-dropdown-arrow] {
            transform: rotate(90deg);
        }

        [data-dropdown-option][aria-selected="true"] {
            color: #1d4ed8;
            background: #eff6ff;
        }

        [data-klasifikasi-modal] {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity 200ms ease-out, visibility 200ms ease-out;
        }

        [data-klasifikasi-modal][data-open="true"] {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        [data-klasifikasi-panel] {
            opacity: 0;
            transform: translateY(0.5rem);
            transition: opacity 200ms ease-out, transform 200ms ease-out;
        }

        [data-klasifikasi-modal][data-open="true"] [data-klasifikasi-panel] {
            opacity: 1;
            transform: translateY(0);
        }

        [data-klasifikasi-trigger][aria-expanded="true"] [data-klasifikasi-arrow] {
            transform: rotate(180deg);
        }

        .klasifikasi-option[aria-checked="true"] {
            border-color: #93c5fd;
            background: #eff6ff;
        }

        @media (prefers-reduced-motion: reduce) {
            [data-dropdown-menu],
            [data-klasifikasi-modal],
            [data-klasifikasi-panel] {
                transition: none;
            }
        }
    </style>
</head>
<body class="bg-[#f4f4f5] flex font-sans text-gray-800">
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>

            <nav class="px-4 space-y-1">
                <a href="/dashboard-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Dashboard
                </a>

                <div class="pt-2">
                    <button type="button" onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/dashboard-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/dashboard-pokja/upload" class="block px-4 py-2 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="/dashboard-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                <a href="/dashboard-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                <a href="{{ route('arsip.seluruh') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 transition hover:text-white">
                    <i class="fa-solid fa-box-archive"></i> Seluruh Arsip
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </button>
        </form>
    </aside>

    <main class="ml-64 flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="px-8 py-6 flex items-center justify-between sticky top-0 z-10 bg-[#f4f4f5]">
            <div class="flex items-center gap-4">
                <a href="/dashboard-pokja/upload" class="text-black hover:text-blue-600 text-lg transition" aria-label="Kembali">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <span class="text-sm font-bold text-gray-500">Upload File Arsip - admin Pokja</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">{{ auth()->user()?->name ?? auth()->user()?->username }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>
        
        <div class="box-border w-full min-w-0 max-w-none p-5 sm:p-8">
            <h2 class="text-4xl font-bold mb-6 text-black">Upload File Kearsipan</h2>

                @if ($errors->has('pokja_id'))
                    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">
                        {{ $errors->first('pokja_id') }}
                    </div>
                @endif

                <form id="arsip-upload-form" action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="mb-1">
                        <label for="nama_arsip" class="block text-sm font-bold text-black mb-1">Nama Surat</label>
                        <input type="text" id="nama_arsip" name="nama_arsip" value="{{ old('nama_arsip') }}" placeholder="Nama Surat" required
                            class="w-full h-10 border border-gray-400 bg-white rounded-md px-3 text-sm text-gray-700 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                        @error('nama_arsip')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-1 gap-2 md:grid-cols-4 mt-1 mb-2">
                        <div class="min-w-0">
                            <label for="nomor_dokumen" class="block text-sm font-bold text-black mb-1">Nomor Dokumen</label>
                            <input type="text" id="nomor_dokumen" name="nomor_dokumen" value="{{ old('nomor_dokumen') }}" placeholder="Nomor Dokumen (opsional)" maxlength="255"
                                class="h-10 w-full rounded-md border border-gray-400 bg-white px-3 text-sm text-gray-700 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                            @error('nomor_dokumen')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="min-w-0">
                            <label id="jenis-surat-label" class="block text-sm font-bold text-black mb-1">Jenis Surat</label>
                            @php
                                $jenisValue = (string) old('jenis_surat_id', old('kategori_arsip_id', ''));
                                $selectedJenis = $jenisSurats->first(fn ($item) => (string) $item->id === $jenisValue);
                            @endphp
                            <div class="relative" data-custom-dropdown data-required="true">
                                <input type="hidden" name="jenis_surat_id" value="{{ $jenisValue }}" data-dropdown-value>
                                <button type="button" id="jenis-surat-trigger" data-dropdown-trigger aria-haspopup="listbox" aria-expanded="false" aria-labelledby="jenis-surat-label"
                                    class="flex h-10 w-full items-center justify-between gap-2 rounded-md border border-gray-400 bg-white px-3 text-left text-sm text-gray-700 shadow-sm transition hover:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <span data-dropdown-label class="truncate">{{ $selectedJenis?->name ?? 'Pilih Jenis Surat' }}</span>
                                    <i class="fa-solid fa-arrow-right text-sm transition-transform duration-200" data-dropdown-arrow></i>
                                </button>
                                <div class="absolute left-0 right-0 top-full z-30 mt-2 max-h-64 overflow-y-auto rounded-md border border-gray-200 bg-white p-1 shadow-lg" data-dropdown-menu data-open="false" role="listbox" aria-labelledby="jenis-surat-label">
                                    @foreach ($jenisSurats as $jenisSurat)
                                        <button type="button" role="option" data-dropdown-option data-value="{{ $jenisSurat->id }}" aria-selected="{{ $jenisValue === (string) $jenisSurat->id ? 'true' : 'false' }}"
                                            class="flex w-full items-center justify-between rounded px-3 py-2 text-left text-sm text-gray-700 transition hover:bg-blue-50 hover:text-blue-700">
                                            <span>{{ $jenisSurat->name }}</span><i class="fa-solid fa-check text-blue-600 {{ $jenisValue === (string) $jenisSurat->id ? '' : 'invisible' }}" data-dropdown-check></i>
                                        </button>
                                    @endforeach
                                </div>
                                <p class="mt-1 hidden text-xs text-red-600" data-dropdown-error></p>
                                @error('jenis_surat_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="min-w-0">
                            <label id="sifat-surat-label" class="block text-sm font-bold text-black mb-1">Sifat Surat</label>
                            @php
                                $sifatValue = (string) old('sifat_surat_id', '');
                                $selectedSifat = $sifatSurats->first(fn ($item) => (string) $item->id === $sifatValue);
                            @endphp
                            <div class="relative" data-custom-dropdown data-required="true">
                                <input type="hidden" name="sifat_surat_id" value="{{ $sifatValue }}" data-dropdown-value>
                                <button type="button" id="sifat-surat-trigger" data-dropdown-trigger aria-haspopup="listbox" aria-expanded="false" aria-labelledby="sifat-surat-label"
                                    class="flex h-10 w-full items-center justify-between gap-2 rounded-md border border-gray-400 bg-white px-3 text-left text-sm text-gray-700 shadow-sm transition hover:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    <span data-dropdown-label class="truncate">{{ $selectedSifat?->name ?? 'Pilih Sifat Surat' }}</span>
                                    <i class="fa-solid fa-arrow-right text-sm transition-transform duration-200" data-dropdown-arrow></i>
                                </button>
                                <div class="absolute left-0 right-0 top-full z-30 mt-2 max-h-64 overflow-y-auto rounded-md border border-gray-200 bg-white p-1 shadow-lg" data-dropdown-menu data-open="false" role="listbox" aria-labelledby="sifat-surat-label">
                                    @foreach ($sifatSurats as $sifatSurat)
                                        <button type="button" role="option" data-dropdown-option data-value="{{ $sifatSurat->id }}" aria-selected="{{ $sifatValue === (string) $sifatSurat->id ? 'true' : 'false' }}"
                                            class="flex w-full items-center justify-between rounded px-3 py-2 text-left text-sm text-gray-700 transition hover:bg-blue-50 hover:text-blue-700">
                                            <span>{{ $sifatSurat->name }}</span><i class="fa-solid fa-check text-blue-600 {{ $sifatValue === (string) $sifatSurat->id ? '' : 'invisible' }}" data-dropdown-check></i>
                                        </button>
                                    @endforeach
                                </div>
                                <p class="mt-1 hidden text-xs text-red-600" data-dropdown-error></p>
                                @error('sifat_surat_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div class="min-w-0">
                            <label for="tanggal_dokumen" class="block text-sm font-bold text-black mb-1">Tanggal Upload</label>
                            <div class="relative">
                                <input type="date" id="tanggal_dokumen" name="tanggal_dokumen" value="{{ old('tanggal_dokumen') }}" required
                                    class="h-10 w-full rounded-md border border-gray-400 bg-white px-3 pr-10 text-sm text-gray-700 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                <button type="button" class="absolute inset-y-0 right-0 px-3 text-gray-700" aria-label="Pilih tanggal" data-date-picker>
                                    <i class="fa-regular fa-calendar"></i>
                                </button>
                            </div>
                            @error('tanggal_dokumen')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-6">
                    <label class="block text-sm font-bold text-black mb-2">Kode Klasifikasi</label>
                    @php
                        $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) old('klasifikasi_arsip_id', ''));
                        $formatMasaAktif = static function ($klasifikasi) {
                            if (! $klasifikasi) {
                                return '';
                            }

                            $tahun = $klasifikasi->masa_aktif_tahun;
                            $catatan = trim((string) $klasifikasi->masa_aktif_catatan);

                            if ($tahun !== null) {
                                return $tahun . ' tahun' . ($catatan !== '' ? ' (' . $catatan . ')' : '');
                            }

                            return $catatan !== '' ? $catatan : 'Tidak tersedia';
                        };
                        $formatMasaInaktif = static fn ($klasifikasi) => $klasifikasi
                            ? ($klasifikasi->masa_inaktif_tahun !== null
                                ? $klasifikasi->masa_inaktif_tahun . ' tahun'
                                : 'Tidak tersedia')
                            : '';
                    @endphp
                    <div class="relative w-full" data-klasifikasi-root="pilih-klasifikasi_arsip_id">
                        <input type="hidden" name="klasifikasi_arsip_id" value="{{ old('klasifikasi_arsip_id') }}" data-klasifikasi-value>
                        <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-400 bg-white px-4 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none">
                            <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi?->kode ?? 'Pilih Kode Klasifikasi' }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-500 transition-transform duration-200" data-klasifikasi-arrow></i>
                        </button>

                        <div class="fixed inset-0 z-[100] p-4 sm:p-6" data-klasifikasi-modal data-open="false" aria-hidden="true">
                            <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                            <section data-klasifikasi-panel class="relative mx-auto flex max-h-[88vh] w-full max-w-[1180px] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)]" role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                                <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <input type="search" data-klasifikasi-search placeholder="Cari Kode Klasifikasi" class="h-[42px] w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <button type="button" data-klasifikasi-search-button class="h-[42px] rounded-md bg-[#1d4ed8] px-5 text-sm font-semibold text-white hover:bg-[#1e40af] sm:w-[110px]">Cari</button>
                                        <button type="button" data-klasifikasi-reset class="h-[42px] rounded-md border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50 sm:w-[110px]">Reset</button>
                                    </div>
                                </div>
                                <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-6 pt-4 sm:px-6" data-klasifikasi-content>
                                    <div class="grid gap-3 md:grid-cols-2" id="upload-klasifikasi-list">
                                        @foreach ($klasifikasis as $klasifikasi)
                                            <button type="button" role="radio" aria-checked="{{ (string) $klasifikasi->id === (string) old('klasifikasi_arsip_id', '') ? 'true' : 'false' }}" class="klasifikasi-option flex cursor-pointer gap-3 rounded-lg border border-slate-200 p-3 text-left text-sm transition hover:border-blue-300 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-300" data-search="{{ strtolower($klasifikasi->kode . ' ' . $klasifikasi->judul . ' ' . $klasifikasi->uraian) }}" data-value="{{ $klasifikasi->id }}" data-code="{{ $klasifikasi->kode }}" data-masa-aktif="{{ $formatMasaAktif($klasifikasi) }}" data-masa-inaktif="{{ $formatMasaInaktif($klasifikasi) }}">
                                                <span class="flex-1">
                                                    <b class="text-slate-900">{{ $klasifikasi->kode }}</b><br>
                                                    <span class="text-slate-600">{{ $klasifikasi->judul }}</span>
                                                    @if ($klasifikasi->uraian)
                                                        <br><small class="text-slate-500">{{ $klasifikasi->uraian }}</small>
                                                    @endif
                                                </span>
                                                <i class="fa-solid fa-check mt-1 text-blue-600 {{ (string) $klasifikasi->id === (string) old('klasifikasi_arsip_id', '') ? '' : 'invisible' }}" data-klasifikasi-check></i>
                                            </button>
                                        @endforeach
                                    </div>
                                    <p id="upload-klasifikasi-empty" class="hidden py-8 text-center text-sm text-slate-500">Kode klasifikasi tidak ditemukan.</p>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-2">
                        <div class="min-w-0">
                            <label for="masa-aktif-klasifikasi" class="mb-1 block text-sm font-bold text-black">Masa Aktif</label>
                            <input id="masa-aktif-klasifikasi" type="text" value="{{ $formatMasaAktif($selectedKlasifikasi) }}" placeholder="Pilih Kode Klasifikasi" readonly aria-live="polite"
                                class="h-10 w-full rounded-md border border-gray-300 bg-gray-100 px-3 text-sm text-gray-600 shadow-sm focus:outline-none">
                        </div>
                        <div class="min-w-0">
                            <label for="masa-inaktif-klasifikasi" class="mb-1 block text-sm font-bold text-black">Masa Inaktif</label>
                            <input id="masa-inaktif-klasifikasi" type="text" value="{{ $formatMasaInaktif($selectedKlasifikasi) }}" placeholder="Pilih Kode Klasifikasi" readonly aria-live="polite"
                                class="h-10 w-full rounded-md border border-gray-300 bg-gray-100 px-3 text-sm text-gray-600 shadow-sm focus:outline-none">
                        </div>
                    </div>
                    @error('klasifikasi_arsip_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                    <fieldset class="mb-4">
                        <legend class="mb-2 block text-sm font-bold text-black">Tanda Tangan</legend>
                        <div class="flex flex-wrap gap-5">
                            <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                                <input type="radio" name="tanda_tangan" value="digital" required @checked(old('tanda_tangan') === 'digital') class="h-4 w-4 accent-blue-600">
                                <span>Digital</span>
                            </label>
                            <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-700">
                                <input type="radio" name="tanda_tangan" value="konvensional" required @checked(old('tanda_tangan') === 'konvensional') class="h-4 w-4 accent-blue-600">
                                <span>Konvensional</span>
                            </label>
                        </div>
                        @error('tanda_tangan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </fieldset>

                    <div class="mb-4">
                        <label for="keterangan" class="mb-1 block text-sm font-bold text-black">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="4" placeholder="Keterangan"
                            class="w-full resize-y rounded-md border border-gray-400 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('keterangan') }}</textarea>
                        @error('keterangan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-2">
                        <label for="file_arsip" class="sr-only">File arsip</label>
                        <div id="file-drop-box" class="relative flex min-h-[124px] w-full cursor-pointer flex-col items-center justify-center overflow-hidden rounded-lg border border-dashed border-blue-500 bg-transparent px-4 py-5 text-center transition hover:bg-blue-50">
                            <input type="file" id="file_arsip" name="file_arsip" required accept=".pdf,.doc,.docx,.zip,.rar"
                                class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" aria-describedby="file-drop-subtext">
                            <span id="file-drop-text" class="pointer-events-none z-0 mb-1 text-sm font-bold text-blue-600">Select File</span>
                            <span id="file-drop-subtext" class="pointer-events-none z-0 text-xs text-gray-400">Upload File Kearsipan Disini (PDF, Doc, DOCX, ZIP, RAR - Maks 5 Mb)</span>
                        </div>
                        <p id="file-client-error" class="mt-1 hidden text-xs text-red-600" role="alert"></p>
                        <p class="mt-1 text-xs text-gray-400">Maximal upload file 5 MB. (Jika Lebih Bisa Di Kompress Filenya Terlebih Dahulu)</p>
                        @error('file_arsip')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="mt-1 flex h-10 w-full items-center justify-center gap-2 rounded-md bg-[#1d4ed8] px-4 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        Submit Peminjaman <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>
            </section>
        </div>
    </main>

    <script>
        function toggleMenuArsip() {
            const submenu = document.getElementById('submenu-arsip');
            const arrow = document.getElementById('ikon-panah');

            submenu.classList.toggle('max-h-0');
            submenu.classList.toggle('max-h-40');
            arrow.classList.toggle('rotate-180');
        }

        document.querySelectorAll('[data-custom-dropdown]').forEach((dropdown) => {
            const trigger = dropdown.querySelector('[data-dropdown-trigger]');
            const menu = dropdown.querySelector('[data-dropdown-menu]');
            const valueInput = dropdown.querySelector('[data-dropdown-value]');
            const label = dropdown.querySelector('[data-dropdown-label]');
            const options = Array.from(dropdown.querySelectorAll('[data-dropdown-option]'));
            const searchInput = dropdown.querySelector('[data-dropdown-search]');
            const emptyMessage = dropdown.querySelector('[data-dropdown-empty]');
            const errorMessage = dropdown.querySelector('[data-dropdown-error]');

            const visibleOptions = () => options.filter((option) => !option.hidden);
            const closeMenu = (restoreFocus = false) => {
                menu.dataset.open = 'false';
                trigger.setAttribute('aria-expanded', 'false');
                if (restoreFocus) trigger.focus();
            };
            const openMenu = () => {
                document.querySelectorAll('[data-custom-dropdown]').forEach((other) => {
                    if (other !== dropdown) {
                        const otherMenu = other.querySelector('[data-dropdown-menu]');
                        otherMenu.dataset.open = 'false';
                        other.querySelector('[data-dropdown-trigger]').setAttribute('aria-expanded', 'false');
                    }
                });
                menu.dataset.open = 'true';
                trigger.setAttribute('aria-expanded', 'true');
                if (searchInput) {
                    searchInput.focus();
                } else {
                    const selected = visibleOptions().find((option) => option.dataset.value === valueInput.value);
                    (selected || visibleOptions()[0])?.focus();
                }
            };
            const selectOption = (option) => {
                valueInput.value = option.dataset.value;
                label.textContent = option.querySelector('span').textContent.trim();
                options.forEach((item) => {
                    const selected = item === option;
                    item.setAttribute('aria-selected', selected ? 'true' : 'false');
                    item.querySelector('[data-dropdown-check]')?.classList.toggle('invisible', !selected);
                });
                trigger.classList.remove('border-red-500');
                errorMessage?.classList.add('hidden');
                closeMenu();
                trigger.focus();
            };

            trigger.addEventListener('click', () => {
                if (menu.dataset.open === 'true') closeMenu();
                else openMenu();
            });

            options.forEach((option) => option.addEventListener('click', () => selectOption(option)));

            trigger.addEventListener('keydown', (event) => {
                if (event.key === 'ArrowDown' || event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    if (menu.dataset.open !== 'true') openMenu();
                    else visibleOptions()[0]?.focus();
                }
            });

            menu.addEventListener('keydown', (event) => {
                const currentOptions = visibleOptions();
                const currentIndex = currentOptions.indexOf(document.activeElement);
                if (event.key === 'Escape') {
                    event.preventDefault();
                    closeMenu(true);
                } else if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
                    event.preventDefault();
                    if (currentOptions.length) {
                        const nextIndex = currentIndex < 0
                            ? (event.key === 'ArrowDown' ? 0 : currentOptions.length - 1)
                            : (currentIndex + (event.key === 'ArrowDown' ? 1 : -1) + currentOptions.length) % currentOptions.length;
                        currentOptions[nextIndex]?.focus();
                    }
                } else if (event.key === 'Home') {
                    event.preventDefault();
                    currentOptions[0]?.focus();
                } else if (event.key === 'End') {
                    event.preventDefault();
                    currentOptions[currentOptions.length - 1]?.focus();
                } else if (event.key === 'Enter' && document.activeElement === searchInput && currentOptions.length) {
                    event.preventDefault();
                    selectOption(currentOptions[0]);
                }
            });

            searchInput?.addEventListener('input', () => {
                const query = searchInput.value.trim().toLocaleLowerCase('id');
                let matches = 0;
                options.forEach((option) => {
                    const visible = (option.dataset.search || '').toLocaleLowerCase('id').includes(query);
                    option.hidden = !visible;
                    if (visible) matches++;
                });
                emptyMessage?.classList.toggle('hidden', matches > 0);
            });

            document.addEventListener('click', (event) => {
                if (!dropdown.contains(event.target)) closeMenu();
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                document.querySelectorAll('[data-custom-dropdown]').forEach((dropdown) => {
                    const menu = dropdown.querySelector('[data-dropdown-menu]');
                    menu.dataset.open = 'false';
                    dropdown.querySelector('[data-dropdown-trigger]').setAttribute('aria-expanded', 'false');
                });
            }
        });

        const klasifikasiRoot = document.querySelector('[data-klasifikasi-root="pilih-klasifikasi_arsip_id"]');
        const klasifikasiTrigger = klasifikasiRoot.querySelector('[data-klasifikasi-trigger]');
        const klasifikasiModal = klasifikasiRoot.querySelector('[data-klasifikasi-modal]');
        const klasifikasiSearch = klasifikasiRoot.querySelector('[data-klasifikasi-search]');
        const klasifikasiValue = klasifikasiRoot.querySelector('[data-klasifikasi-value]');
        const klasifikasiLabel = klasifikasiRoot.querySelector('[data-klasifikasi-label]');
        const klasifikasiOptions = Array.from(klasifikasiRoot.querySelectorAll('.klasifikasi-option'));
        const klasifikasiEmpty = klasifikasiRoot.querySelector('#upload-klasifikasi-empty');
        const masaAktifInput = document.getElementById('masa-aktif-klasifikasi');
        const masaInaktifInput = document.getElementById('masa-inaktif-klasifikasi');

        const closeKlasifikasiModal = (restoreFocus = false) => {
            klasifikasiModal.dataset.open = 'false';
            klasifikasiModal.setAttribute('aria-hidden', 'true');
            klasifikasiTrigger.setAttribute('aria-expanded', 'false');
            document.body.classList.remove('overflow-hidden');
            if (restoreFocus) klasifikasiTrigger.focus();
        };

        const openKlasifikasiModal = () => {
            klasifikasiModal.dataset.open = 'true';
            klasifikasiModal.setAttribute('aria-hidden', 'false');
            klasifikasiTrigger.setAttribute('aria-expanded', 'true');
            document.body.classList.add('overflow-hidden');
            klasifikasiSearch.focus();
        };

        const visibleKlasifikasiOptions = () => klasifikasiOptions.filter((option) => !option.hidden);
        const selectKlasifikasi = (option) => {
            klasifikasiValue.value = option.dataset.value;
            klasifikasiLabel.textContent = option.dataset.code;
            masaAktifInput.value = option.dataset.masaAktif || 'Tidak tersedia';
            masaInaktifInput.value = option.dataset.masaInaktif || 'Tidak tersedia';
            klasifikasiOptions.forEach((item) => {
                const selected = item === option;
                item.setAttribute('aria-checked', selected ? 'true' : 'false');
                item.querySelector('[data-klasifikasi-check]').classList.toggle('invisible', !selected);
            });
            closeKlasifikasiModal(true);
        };

        klasifikasiTrigger.addEventListener('click', () => {
            if (klasifikasiModal.dataset.open === 'true') closeKlasifikasiModal();
            else openKlasifikasiModal();
        });
        klasifikasiRoot.querySelector('[data-klasifikasi-close]').addEventListener('click', () => closeKlasifikasiModal(true));

        const applyKlasifikasiFilter = () => {
            const query = klasifikasiSearch.value.trim().toLocaleLowerCase('id');
            let visibleCount = 0;
            klasifikasiOptions.forEach((option) => {
                const matches = (option.dataset.search || '').toLocaleLowerCase('id').includes(query);
                option.hidden = !matches;
                if (matches) visibleCount++;
            });
            klasifikasiEmpty.classList.toggle('hidden', visibleCount > 0);
        };

        klasifikasiSearch.addEventListener('input', applyKlasifikasiFilter);
        klasifikasiRoot.querySelector('[data-klasifikasi-search-button]').addEventListener('click', applyKlasifikasiFilter);
        klasifikasiRoot.querySelector('[data-klasifikasi-reset]').addEventListener('click', () => {
            klasifikasiSearch.value = '';
            klasifikasiValue.value = '';
            klasifikasiLabel.textContent = 'Pilih Kode Klasifikasi';
            masaAktifInput.value = '';
            masaInaktifInput.value = '';
            klasifikasiOptions.forEach((option) => {
                option.hidden = false;
                option.setAttribute('aria-checked', 'false');
                option.querySelector('[data-klasifikasi-check]').classList.add('invisible');
            });
            klasifikasiEmpty.classList.add('hidden');
            klasifikasiSearch.focus();
        });
        klasifikasiOptions.forEach((option) => {
            option.addEventListener('click', () => selectKlasifikasi(option));
            option.addEventListener('keydown', (event) => {
                if (event.key !== 'ArrowDown' && event.key !== 'ArrowUp') return;
                event.preventDefault();
                const visible = visibleKlasifikasiOptions();
                const index = visible.indexOf(option);
                const step = event.key === 'ArrowDown' ? 1 : -1;
                visible[(index + step + visible.length) % visible.length]?.focus();
            });
        });
        klasifikasiSearch.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                visibleKlasifikasiOptions()[0]?.focus();
            } else if (event.key === 'ArrowDown') {
                event.preventDefault();
                visibleKlasifikasiOptions()[0]?.focus();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && klasifikasiModal.dataset.open === 'true') {
                closeKlasifikasiModal(true);
            }
        });

        document.querySelectorAll('[data-date-picker]').forEach((button) => {
            button.addEventListener('click', () => {
                const dateInput = button.parentElement.querySelector('input[type="date"]');
                if (typeof dateInput.showPicker === 'function') dateInput.showPicker();
                else dateInput.focus();
            });
        });

        const uploadForm = document.getElementById('arsip-upload-form');
        uploadForm.addEventListener('submit', (event) => {
            let firstInvalidDropdown = null;
            document.querySelectorAll('[data-custom-dropdown][data-required="true"]').forEach((dropdown) => {
                const input = dropdown.querySelector('[data-dropdown-value]');
                const error = dropdown.querySelector('[data-dropdown-error]');
                const invalid = !input.value;
                error.textContent = invalid ? 'Pilihan ini wajib diisi.' : '';
                error.classList.toggle('hidden', !invalid);
                dropdown.querySelector('[data-dropdown-trigger]').classList.toggle('border-red-500', invalid);
                if (invalid && !firstInvalidDropdown) firstInvalidDropdown = dropdown;
            });

            if (!uploadForm.reportValidity() || firstInvalidDropdown) {
                event.preventDefault();
                firstInvalidDropdown?.querySelector('[data-dropdown-trigger]').focus();
            }
        });

        const fileInput = document.getElementById('file_arsip');
        const dropBox = document.getElementById('file-drop-box');
        const fileName = document.getElementById('file-drop-text');
        const fileHint = document.getElementById('file-drop-subtext');
        const fileClientError = document.getElementById('file-client-error');
        const defaultFileHint = 'Upload File Kearsipan Disini (PDF, Doc, DOCX, ZIP, RAR - Maks 5 Mb)';
        const maxFileSize = 5 * 1024 * 1024;
        const allowedExtensions = ['pdf', 'doc', 'docx', 'zip', 'rar'];

        const showFile = (file) => {
            fileInput.setCustomValidity('');
            fileClientError.textContent = '';
            fileClientError.classList.add('hidden');
            fileName.textContent = file ? 'File Terpilih' : 'Select File';
            fileHint.textContent = file ? file.name : defaultFileHint;
        };

        const rejectInvalidFile = (file) => {
            if (!file) return false;
            const extension = file.name.split('.').pop().toLowerCase();
            let message = '';
            if (!allowedExtensions.includes(extension)) {
                message = 'Format file harus PDF, DOC, DOCX, ZIP, atau RAR.';
            } else if (file.size > maxFileSize) {
                message = 'Ukuran file maksimal 5 MB.';
            }
            if (!message) return false;
            fileInput.value = '';
            fileInput.setCustomValidity(message);
            fileClientError.textContent = message;
            fileClientError.classList.remove('hidden');
            fileName.textContent = 'File tidak valid';
            fileHint.textContent = 'Pilih file dengan format dan ukuran yang diizinkan.';
            return true;
        };

        fileInput.addEventListener('change', () => {
            if (!rejectInvalidFile(fileInput.files[0])) showFile(fileInput.files[0]);
        });
        ['dragenter', 'dragover'].forEach((eventName) => {
            dropBox.addEventListener(eventName, (event) => {
                event.preventDefault();
                dropBox.classList.add('bg-blue-50', 'ring-2', 'ring-blue-200');
            });
        });
        ['dragleave', 'drop'].forEach((eventName) => {
            dropBox.addEventListener(eventName, (event) => {
                event.preventDefault();
                dropBox.classList.remove('bg-blue-50', 'ring-2', 'ring-blue-200');
            });
        });
        dropBox.addEventListener('drop', (event) => {
            if (!event.dataTransfer.files.length) return;
            if (rejectInvalidFile(event.dataTransfer.files[0])) return;
            const transfer = new DataTransfer();
            Array.from(event.dataTransfer.files).forEach((file) => transfer.items.add(file));
            fileInput.files = transfer.files;
            showFile(fileInput.files[0]);
        });

    </script>
    <x-logout-confirmation />
</body>
</html>
