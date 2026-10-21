<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Peminjaman - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dropdown-item label, select.custom-dropdown option {
            font-size: 15px !important;
            line-height: 1.5;
        }
    </style>
</head>
<body class="bg-[#f4f4f5] flex font-sans text-gray-800">
    @php
        $isKetuaPokja = auth()->user()?->role?->slug === 'ketua_pokja';
    @endphp

    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>

            <nav class="px-4 space-y-1">
                <a href="{{ $isKetuaPokja ? route('ketua.dashboard') : url('/dashboard-pokja') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Dashboard
                </a>

                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>

                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-40">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="{{ route($isKetuaPokja ? 'ketua.ajukan' : 'peminjaman.ajukan') }}" class="block px-4 py-2 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-medium transition shadow-sm">Ajukan Peminjaman</a>
                            <a href="{{ route($isKetuaPokja ? 'ketua.upload' : 'arsip.index') }}" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
                        </div>
                    </div>
                </div>

                 <a href="/dashboard-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                <a href="/dashboard-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                <a href="{{ route('arsip.seluruh') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 transition hover:text-white">
                    <i class="fa-solid fa-box-archive"></i> Seluruh Arsip
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
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
                <a href="{{ route($isKetuaPokja ? 'ketua.ajukan' : 'peminjaman.ajukan') }}" class="text-black hover:text-blue-600 text-lg transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <span class="text-sm font-bold text-gray-500">Input Peminjaman - admin Pokja</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">{{ auth()->user()?->name ?? auth()->user()?->username }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <div class="box-border w-full min-w-0 max-w-none p-5 sm:p-8">
            <h2 class="text-4xl font-bold mb-6 text-black">Ajukan Peminjaman</h2>

            <form id="peminjaman-form" action="{{ route(auth()->user()?->role?->slug === 'ketua_pokja' ? 'ketua.peminjaman.store' : 'peminjaman.store') }}" method="POST" enctype="multipart/form-data" class="w-full">
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6">
                    <label for="nama_surat" class="block text-sm font-bold text-black mb-2">Nama Surat</label>
                    <input
                        type="text"
                        id="nama_surat"
                        name="nama_surat"
                        value="{{ old('nama_surat') }}"
                        required
                        placeholder="Masukkan nama surat"
                        class="w-full h-12 border border-gray-300 bg-white rounded-md px-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100"
                    >
                    @error('nama_surat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 grid grid-cols-1 gap-6 md:grid-cols-4">
                    <div>
                        <label for="kategori_id" class="block text-sm font-bold text-black mb-2">Jenis Surat</label>
                        <select id="kategori_id" name="kategori_id" required class="w-full h-12 border border-gray-300 bg-white rounded-md px-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                            <option value="">Pilih Kategori Surat</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="sifat_surat_id" class="block text-sm font-bold text-black mb-2">Sifat Surat</label>
                        <select id="sifat_surat_id" name="sifat_surat_id" class="w-full h-12 border border-gray-300 bg-white rounded-md px-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                            <option value="">Pilih Sifat Surat</option>
                            @foreach ($sifatSurats as $sifatSurat)
                                <option value="{{ $sifatSurat->id }}" @selected(old('sifat_surat_id') == $sifatSurat->id)>{{ $sifatSurat->name }}</option>
                            @endforeach
                        </select>
                        @error('sifat_surat_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-bold text-black mb-2">Tanggal Mulai</label>
                        <input id="tanggal_mulai" type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required class="w-full h-12 border border-gray-300 bg-white rounded-md px-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-bold text-black mb-2">Tanggal Selesai</label>
                        <input id="tanggal_selesai" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required class="w-full h-12 border border-gray-300 bg-white rounded-md px-4 text-sm text-gray-700 shadow-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-100">
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-black mb-2">Kode Klasifikasi</label>
                    @php
                        $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) old('klasifikasi_arsip_id', ''));
                    @endphp
                    <div class="relative w-full" data-klasifikasi-root="pilih-klasifikasi_arsip_id">
                        <input type="hidden" name="klasifikasi_arsip_id" value="{{ old('klasifikasi_arsip_id') }}" data-klasifikasi-value>
                        <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-400 bg-white px-4 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none">
                            <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi?->kode ?? 'Pilih Kode Klasifikasi' }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-500"></i>
                        </button>

                        <div class="fixed inset-0 z-[100] hidden p-4 sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                            <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                            <section class="relative mx-auto flex max-h-[88vh] w-full max-w-[1180px] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)]">
                                <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <input type="search" data-klasifikasi-search placeholder="Cari Kode Klasifikasi" class="h-[42px] w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <button type="button" data-klasifikasi-search-button class="h-[42px] rounded-md bg-[#1d4ed8] px-5 text-sm font-semibold text-white hover:bg-[#1e40af] sm:w-[110px]">Cari</button>
                                        <button type="button" data-klasifikasi-reset class="h-[42px] rounded-md border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50 sm:w-[110px]">Reset</button>
                                    </div>
                                </div>
                                <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-6 pt-4 sm:px-6" data-klasifikasi-content>
                                    <div class="grid gap-3 md:grid-cols-2" id="klasifikasi-list">
                                        @foreach ($klasifikasis as $klasifikasi)
                                            <label class="klasifikasi-option flex cursor-pointer gap-3 rounded-lg border border-slate-200 p-3 text-sm hover:border-blue-300 hover:bg-blue-50" data-search="{{ strtolower($klasifikasi->kode . ' ' . $klasifikasi->judul . ' ' . $klasifikasi->uraian) }}">
                                                <input type="radio" name="klasifikasi-picker" value="{{ $klasifikasi->id }}" data-code="{{ $klasifikasi->kode }}" data-label="{{ $klasifikasi->kode }} — {{ $klasifikasi->judul }}" class="mt-1 accent-blue-600">
                                                <span class="flex-1">
                                                    <b class="text-slate-900">{{ $klasifikasi->kode }}</b><br>
                                                    <span class="text-slate-600">{{ $klasifikasi->judul }}</span>
                                                    @if ($klasifikasi->uraian)
                                                        <br><small class="text-slate-500">{{ $klasifikasi->uraian }}</small>
                                                    @endif
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p id="klasifikasi-empty" class="hidden py-8 text-center text-sm text-slate-500">Kode klasifikasi tidak ditemukan.</p>
                                </div>
                            </section>
                        </div>
                    </div>
                    @error('klasifikasi_arsip_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-bold text-black mb-2">Keterangan</label>
                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="6"
                        required
                        placeholder="Keterangan"
                        class="w-full border border-gray-400 bg-white rounded-md px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 resize-none"
                    >{{ old('keterangan', old('keperluan')) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <div id="file-drop-box" class="relative w-full h-48 border border-blue-400 bg-[#f8f9fa] rounded-lg flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-blue-50 transition overflow-hidden">
                        <input type="file" id="file_pengajuan" name="file_pengajuan" accept=".pdf,.doc,.docx,.zip,.rar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" aria-label="Upload file pengajuan peminjaman">
                        <div class="z-0 text-center px-4">
                            <div id="file-drop-text" class="text-blue-600 font-bold text-base mb-2">Select File</div>
                            <div id="file-drop-subtext" class="text-gray-500 text-sm">Format yang diizinkan: PDF, DOC, DOCX, ZIP, RAR (maksimal 5 MB)</div>
                            <div id="file-drop-meta" class="mt-3 text-xs text-gray-500 hidden"></div>
                        </div>
                    </div>
                    @error('file_pengajuan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button id="submit-peminjaman" type="submit" class="w-full h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-bold flex items-center justify-center gap-2 transition shadow-sm disabled:cursor-not-allowed disabled:opacity-75">
                    <span id="submit-button-text">Submit Peminjaman</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </main>

    <script>
        function toggleMenuArsip() {
            const submenu = document.getElementById('submenu-arsip');
            const ikonPanah = document.getElementById('ikon-panah');

            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                ikonPanah.classList.add('rotate-180');
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikonPanah.classList.remove('rotate-180');
            }
        }

        const fileInput = document.getElementById('file_pengajuan');
        const fileDropText = document.getElementById('file-drop-text');
        const fileDropSubtext = document.getElementById('file-drop-subtext');
        const fileDropMeta = document.getElementById('file-drop-meta');
        const submitButton = document.getElementById('submit-peminjaman');
        const submitButtonText = document.getElementById('submit-button-text');

        fileInput.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                fileDropText.textContent = 'Select File';
                fileDropSubtext.textContent = 'Format yang diizinkan: PDF, DOC, DOCX, ZIP, RAR (maksimal 5 MB)';
                fileDropMeta.classList.add('hidden');
                fileDropMeta.textContent = '';
                return;
            }

            const maxSize = 5 * 1024 * 1024;
            const sizeLabel = `${(file.size / 1024 / 1024).toFixed(2)} MB`;
            fileDropText.textContent = 'File Terpilih';
            fileDropSubtext.textContent = file.name;
            fileDropMeta.textContent = `${sizeLabel} • ${file.type || 'Format tidak diketahui'}`;
            fileDropMeta.classList.remove('hidden');

            if (file.size > maxSize) {
                fileDropText.textContent = 'Ukuran file terlalu besar';
                fileDropSubtext.textContent = 'Silakan pilih file dengan ukuran maksimal 5 MB.';
                fileDropMeta.textContent = `${sizeLabel}`;
                this.value = '';
            }
        });

        const form = document.getElementById('peminjaman-form');
        form.addEventListener('submit', function (event) {
            if (fileInput.files && fileInput.files[0] && fileInput.files[0].size > 5 * 1024 * 1024) {
                event.preventDefault();
                fileInput.value = '';
                fileDropText.textContent = 'Select File';
                fileDropSubtext.textContent = 'Format yang diizinkan: PDF, DOC, DOCX, ZIP, RAR (maksimal 5 MB)';
                fileDropMeta.classList.add('hidden');
                alert('Ukuran file maksimal 5 MB.');
                return;
            }

            submitButton.disabled = true;
            submitButtonText.textContent = 'Mengirim...';
        });

        const klasifikasiModal = document.querySelector('[data-klasifikasi-modal]');
        const klasifikasiTrigger = document.querySelector('[data-klasifikasi-trigger]');
        const klasifikasiClose = document.querySelector('[data-klasifikasi-close]');
        const klasifikasiSearch = document.querySelector('[data-klasifikasi-search]');
        const klasifikasiReset = document.querySelector('[data-klasifikasi-reset]');
        const klasifikasiSearchButton = document.querySelector('[data-klasifikasi-search-button]');
        const klasifikasiInput = document.querySelector('input[name="klasifikasi_arsip_id"][data-klasifikasi-value]');
        const klasifikasiLabel = document.querySelector('[data-klasifikasi-label]');
        const klasifikasiEmpty = document.getElementById('klasifikasi-empty');

        function closeKlasifikasiModal() {
            klasifikasiModal.classList.add('hidden');
            klasifikasiTrigger.setAttribute('aria-expanded', 'false');
        }

        function openKlasifikasiModal() {
            klasifikasiModal.classList.remove('hidden');
            klasifikasiTrigger.setAttribute('aria-expanded', 'true');
            setTimeout(() => klasifikasiSearch.focus(), 50);
        }

        klasifikasiTrigger.addEventListener('click', function () {
            if (klasifikasiModal.classList.contains('hidden')) {
                openKlasifikasiModal();
            } else {
                closeKlasifikasiModal();
            }
        });

        klasifikasiClose.addEventListener('click', closeKlasifikasiModal);

        function applyKlasifikasiFilter() {
            const query = (klasifikasiSearch.value || '').toLowerCase().trim();
            let visible = 0;

            document.querySelectorAll('.klasifikasi-option').forEach((option) => {
                const haystack = (option.dataset.search || '').toLowerCase();
                const isMatch = !query || haystack.includes(query);
                option.classList.toggle('hidden', !isMatch);
                if (isMatch) visible++;
            });

            klasifikasiEmpty.classList.toggle('hidden', visible > 0);
        }

        klasifikasiSearch.addEventListener('input', applyKlasifikasiFilter);
        klasifikasiSearchButton.addEventListener('click', applyKlasifikasiFilter);

        klasifikasiReset.addEventListener('click', function () {
            klasifikasiSearch.value = '';
            document.querySelectorAll('.klasifikasi-option').forEach((option) => option.classList.remove('hidden'));
            document.querySelectorAll('input[name="klasifikasi-picker"]').forEach((radio) => (radio.checked = false));
            klasifikasiInput.value = '';
            klasifikasiLabel.textContent = 'Pilih Kode Klasifikasi';
            klasifikasiEmpty.classList.add('hidden');
            klasifikasiSearch.focus();
        });

        document.querySelectorAll('input[name="klasifikasi-picker"]').forEach((radio) => {
            radio.addEventListener('change', function () {
                klasifikasiInput.value = this.value;
                klasifikasiLabel.textContent = this.dataset.code || 'Pilih Kode Klasifikasi';
                closeKlasifikasiModal();
            });
        });

        document.addEventListener('click', function (event) {
            const root = document.querySelector('[data-klasifikasi-root]');
            if (!root.contains(event.target)) {
                closeKlasifikasiModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !klasifikasiModal.classList.contains('hidden')) {
                closeKlasifikasiModal();
            }
        });
    </script>
    <x-logout-confirmation />
</body>
</html>
