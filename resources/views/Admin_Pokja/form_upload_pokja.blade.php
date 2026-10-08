<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File Kearsipan - SIMARSIP</title>
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

    <!-- SIDEBAR KIRI -->
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>

            <!-- Menu -->
            <nav class="px-4 space-y-1">
                <a href="/dashboard-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
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

                    <!-- Sub-menu Terbuka -->
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

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition mt-2">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition flex items-center gap-2">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                    </button>
        </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 flex flex-col min-h-screen">

        <!-- Navbar Atas -->
        <header class="px-8 py-6 flex items-center justify-between sticky top-0 z-10 bg-[#f4f4f5]">
            <div class="flex items-center gap-4">
                <a href="/dashboard-pokja/upload" class="text-black hover:text-blue-600 text-lg transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <span class="text-sm font-bold text-gray-500">Upload File Arsip - Admin Pokja</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">{{ auth()->user()?->name ?? auth()->user()?->username }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN FORMULIR UPLOAD -->
        <div class="p-8">
            <h2 class="text-3xl font-bold mb-6 text-gray-900">Upload File Kearsipan</h2>

            <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- PESAN ERROR --}}
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Nama Arsip -->
                <div class="mb-5">
                    <label for="nama_arsip" class="block text-sm font-bold text-black mb-1.5">Nama Arsip</label>
                    <input type="text" id="nama_arsip" name="nama_arsip" value="{{ old('nama_arsip') }}"
                        placeholder="Nama Arsip" required
                        class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                    @error('nama_arsip')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid 3 Kolom: Nomor Dokumen, Kategori, Tanggal Dokumen -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">

                    <!-- Nomor Dokumen -->
                    <div>
                        <label for="nomor_dokumen" class="block text-sm font-bold text-black mb-1.5">Nomor Dokumen</label>
                        <input type="text" id="nomor_dokumen" name="nomor_dokumen" value="{{ old('nomor_dokumen') }}"
                            placeholder="Nomor Dokumen (opsional)"
                            class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('nomor_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori (select asli, terhubung ke tabel kategori_arsips) -->
                    <div>
                        <label for="kategori_arsip_id" class="block text-sm font-bold text-black mb-1.5">Kategori</label>
                        <select id="kategori_arsip_id" name="kategori_arsip_id" required
                            class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            <option value="" disabled {{ old('kategori_arsip_id') ? '' : 'selected' }}>Kategori Surat</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_arsip_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_arsip_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Dokumen -->
                    <div>
                        <label for="tanggal_dokumen" class="block text-sm font-bold text-black mb-1.5">Tanggal Dokumen</label>
                        <input type="date" id="tanggal_dokumen" name="tanggal_dokumen" value="{{ old('tanggal_dokumen') }}" required
                            class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('tanggal_dokumen')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label class="mb-1.5 block text-sm font-bold text-black">Kode Klasifikasi</label>
                    @php
                        $klasifikasiField = 'klasifikasi_arsip_id';
                        $klasifikasiValue = (string) old($klasifikasiField, '');
                        $klasifikasiId = 'pilih-' . $klasifikasiField;
                        $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === $klasifikasiValue);
                        $klasifikasiLabel = $selectedKlasifikasi?->kode ?? 'Pilih Kode Klasifikasi';
                        $babGroups = $klasifikasis
                            ->sortBy([['bab_urutan', 'asc'], ['urutan', 'asc'], ['kode', 'asc'], ['nomor', 'asc'], ['id', 'asc']])
                            ->groupBy(fn ($item) => (string) ($item->bab_urutan ?? 999) . '|' . trim((string) ($item->bab ?? '')));
                    @endphp
                    <div class="relative w-full" data-klasifikasi-root="{{ $klasifikasiId }}">
                        <input type="hidden" name="{{ $klasifikasiField }}" value="{{ $klasifikasiValue }}" data-klasifikasi-value>
                        <button type="button" data-klasifikasi-trigger aria-expanded="false"
                            class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-500 bg-[#f8f9fa] px-4 text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none">
                            <span data-klasifikasi-label class="truncate">{{ $klasifikasiLabel }}</span>
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
                                    @foreach ($babGroups as $babItems)
                                        @php
                                            $babFirst = $babItems->first();
                                            $babBlocks = collect();
                                            foreach ($babItems->groupBy(fn ($item) => $item->kode_induk ?: $item->kode) as $blockItems) {
                                                $first = $blockItems->first();
                                                $babBlocks->push([
                                                    'kode' => $first->kode_induk ?: $first->kode,
                                                    'column' => $first->kolom_tampilan,
                                                    'items' => $blockItems,
                                                    'search' => strtolower($blockItems->map(fn ($item) => $item->kode . ' ' . $item->nomor . ' ' . $item->judul . ' ' . $item->uraian)->implode(' ')),
                                                ]);
                                            }
                                            $columns = [1 => collect(), 2 => collect()];
                                            foreach ($babBlocks->values() as $index => $block) {
                                                $column = (int) ($block['column'] ?? (($index % 2) + 1));
                                                $columns[$column === 2 ? 2 : 1]->push($block);
                                            }
                                        @endphp
                                        <section class="mb-7" data-klasifikasi-bab data-search="{{ strtolower($babFirst->bab ?? '') }}">
                                            <h3 class="mb-3 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-700">{{ $babFirst->bab }}</h3>
                                            <div class="grid gap-x-8 md:grid-cols-2">
                                                @foreach ($columns as $columnBlocks)
                                                    <div class="min-w-0">
                                                        @foreach ($columnBlocks as $block)
                                                            <div class="mb-4" data-klasifikasi-block data-search="{{ strtolower($babFirst->bab . ' ' . $block['kode']) }}">
                                                                <h4 class="mb-1 border-b border-slate-200 pb-1 text-[12px] font-extrabold text-slate-900">{{ $block['kode'] }}</h4>
                                                                @foreach ($block['items']->groupBy(fn ($item) => trim((string) $item->judul)) as $series)
                                                                    <div data-klasifikasi-series>
                                                                        @if (filled($series->first()->nomor) || filled($series->first()->judul))
                                                                            <p class="mt-1 text-[12px] font-bold leading-5 text-slate-900" data-klasifikasi-heading data-search="{{ strtolower($series->first()->nomor . ' ' . $series->first()->judul) }}">@if (filled($series->first()->nomor)){{ $series->first()->nomor }}. @endif{{ $series->first()->judul }}</p>
                                                                        @endif
                                                                        <div class="pl-3">
                                                                            @foreach ($series as $item)
                                                                                @php
                                                                                    $itemValue = (string) $item->id;
                                                                                    $code = (string) ($item->kode ?? '');
                                                                                    $detail = trim((string) ($item->uraian ?? ''));
                                                                                @endphp
                                                                                <label class="flex cursor-pointer items-start gap-2 rounded-sm px-1 py-0.5 text-[11px] leading-5 text-slate-700 hover:bg-slate-50" data-klasifikasi-option data-search="{{ strtolower($code . ' ' . $item->judul . ' ' . $detail) }}">
                                                                                    <input type="radio" name="{{ $klasifikasiId }}-radio" value="{{ $itemValue }}" data-value="{{ $itemValue }}" data-label="{{ $code }}" @checked($itemValue === $klasifikasiValue) class="mt-1 h-3 w-3 shrink-0 accent-blue-600">
                                                                                    <span>{{ $code }}{{ $detail !== '' ? ' - ' . $detail : '' }}</span>
                                                                                </label>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </section>
                                    @endforeach
                                    <p class="hidden py-8 text-center text-sm text-gray-500" data-klasifikasi-empty>Tidak ditemukan kode klasifikasi.</p>
                                    <p class="mt-3 min-h-5 text-xs text-slate-500" data-klasifikasi-search-state aria-live="polite"></p>
                                </div>
                                <div class="absolute inset-x-0 bottom-0 z-20 hidden border-t border-slate-200 bg-white px-4 py-3 shadow-[0_-8px_24px_rgba(15,23,42,0.08)]" data-klasifikasi-confirmation>
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="text-sm font-semibold text-slate-800">Pakai Kode Klasifikasi Ini?</p>
                                        <div class="flex gap-3">
                                            <label data-klasifikasi-choice-label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-blue-400 hover:bg-blue-50">
                                                <input type="radio" name="pakai_klasifikasi" value="0" data-klasifikasi-choice class="h-4 w-4 accent-blue-600"> Tidak
                                            </label>
                                            <label data-klasifikasi-choice-label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-blue-400 hover:bg-blue-50">
                                                <input type="radio" name="pakai_klasifikasi" value="1" data-klasifikasi-choice class="h-4 w-4 accent-blue-600"> Iya
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="pointer-events-none fixed bottom-4 right-4 z-[130] w-[calc(100%-2rem)] max-w-sm translate-y-4 opacity-0 transition duration-300 ease-out" data-klasifikasi-toast role="status" aria-live="polite">
                        <div class="pointer-events-auto flex items-start justify-between gap-4 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-medium text-slate-800 shadow-xl">
                            <span data-klasifikasi-toast-message></span>
                            <button type="button" data-klasifikasi-toast-close class="shrink-0 text-slate-400 transition hover:text-slate-700" aria-label="Tutup notifikasi">&times;</button>
                        </div>
                    </div>
                    <script>
                        (() => {
                            const root = document.querySelector('[data-klasifikasi-root="{{ $klasifikasiId }}"]');
                            if (!root) return;
                            const trigger = root.querySelector('[data-klasifikasi-trigger]');
                            const modal = root.querySelector('[data-klasifikasi-modal]');
                            const input = root.querySelector('[data-klasifikasi-value]');
                            const label = root.querySelector('[data-klasifikasi-label]');
                            const search = root.querySelector('[data-klasifikasi-search]');
                            const empty = root.querySelector('[data-klasifikasi-empty]');
                            const searchState = root.querySelector('[data-klasifikasi-search-state]');
                            const confirmation = root.querySelector('[data-klasifikasi-confirmation]');
                            const toast = root.querySelector('[data-klasifikasi-toast]');
                            const toastMessage = root.querySelector('[data-klasifikasi-toast-message]');
                            let pendingValue = '';
                            let pendingLabel = '';
                            let toastTimer;
                            let searchTimer;
                            const normalize = (value) => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().replace(/[^\p{L}\p{N}]+/gu, ' ').trim().replace(/\s+/g, ' ');
                            const oneEditAway = (left, right) => {
                                if (Math.abs(left.length - right.length) > 1) return false;
                                let leftIndex = 0;
                                let rightIndex = 0;
                                let differences = 0;
                                while (leftIndex < left.length && rightIndex < right.length) {
                                    if (left[leftIndex] === right[rightIndex]) {
                                        leftIndex++;
                                        rightIndex++;
                                        continue;
                                    }
                                    if (++differences > 1) return false;
                                    if (left.length > right.length) leftIndex++;
                                    else if (right.length > left.length) rightIndex++;
                                    else { leftIndex++; rightIndex++; }
                                }
                                return differences + (leftIndex < left.length || rightIndex < right.length ? 1 : 0) <= 1;
                            };
                            const matchesText = (query, text) => {
                                const normalizedQuery = normalize(query);
                                if (!normalizedQuery) return true;
                                const normalizedText = normalize(text);
                                const words = normalizedText.split(' ');
                                return normalizedQuery.split(' ').every((token) =>
                                    normalizedText.includes(token) ||
                                    (token.length >= 5 && words.some((word) => word.length >= 5 && oneEditAway(token, word)))
                                );
                            };
                            const hideToast = () => {
                                toast.classList.add('translate-y-4', 'opacity-0', 'pointer-events-none');
                                toast.classList.remove('translate-y-0', 'opacity-100');
                            };
                            const showToast = (message) => {
                                toastMessage.textContent = message;
                                toast.classList.remove('translate-y-4', 'opacity-0', 'pointer-events-none');
                                toast.classList.add('translate-y-0', 'opacity-100');
                                window.clearTimeout(toastTimer);
                                toastTimer = window.setTimeout(hideToast, 4200);
                            };
                            const applyFilter = () => {
                                const term = normalize(search.value);
                                let count = 0;
                                modal.querySelectorAll('[data-klasifikasi-bab]').forEach((bab) => {
                                    const babMatches = matchesText(term, bab.dataset.search || '');
                                    let hasVisibleBlock = false;
                                    bab.querySelectorAll('[data-klasifikasi-block]').forEach((block) => {
                                        const blockMatches = term === '' || babMatches || matchesText(term, block.dataset.search || '');
                                        let hasVisibleSeries = false;
                                        block.querySelectorAll('[data-klasifikasi-series]').forEach((series) => {
                                            const heading = series.querySelector('[data-klasifikasi-heading]');
                                            const headingMatches = term === '' || babMatches || blockMatches || (heading && matchesText(term, heading.dataset.search || ''));
                                            let hasVisibleOption = false;
                                            series.querySelectorAll('[data-klasifikasi-option]').forEach((option) => {
                                                const matches = term === '' || babMatches || blockMatches || headingMatches || matchesText(term, option.dataset.search || '');
                                                option.classList.toggle('hidden', !matches);
                                                if (matches) hasVisibleOption = true;
                                            });
                                            const seriesMatches = headingMatches || hasVisibleOption;
                                            series.classList.toggle('hidden', !seriesMatches);
                                            if (seriesMatches) hasVisibleSeries = true;
                                        });
                                        const matches = blockMatches || hasVisibleSeries;
                                        block.classList.toggle('hidden', !matches);
                                        if (matches) hasVisibleBlock = true;
                                    });
                                    bab.classList.toggle('hidden', !hasVisibleBlock);
                                    if (hasVisibleBlock) count++;
                                });
                                empty.classList.toggle('hidden', count > 0);
                                searchState.textContent = term === '' ? '' : count > 0 ? `${count} kelompok relevan ditemukan.` : 'Tidak ditemukan kode klasifikasi yang sesuai.';
                            };
                            const close = () => {
                                modal.classList.add('hidden');
                                trigger.setAttribute('aria-expanded', 'false');
                                document.body.classList.remove('overflow-hidden');
                                confirmation.classList.add('hidden');
                                root.querySelectorAll(`input[name="{{ $klasifikasiId }}-radio"]`).forEach((radio) => {
                                    radio.checked = radio.dataset.value === input.value;
                                });
                                pendingValue = '';
                                pendingLabel = '';
                            };
                            trigger.addEventListener('click', () => {
                                modal.classList.remove('hidden');
                                trigger.setAttribute('aria-expanded', 'true');
                                document.body.classList.add('overflow-hidden');
                                search.focus();
                            });
                            root.querySelector('[data-klasifikasi-close]').addEventListener('click', close);
                            root.querySelector('[data-klasifikasi-search-button]').addEventListener('click', applyFilter);
                            search.addEventListener('input', () => {
                                searchState.textContent = 'Mencari...';
                                window.clearTimeout(searchTimer);
                                searchTimer = window.setTimeout(applyFilter, 180);
                            });
                            search.addEventListener('keydown', (event) => {
                                if (event.key === 'Enter') { event.preventDefault(); window.clearTimeout(searchTimer); applyFilter(); }
                            });
                            root.querySelector('[data-klasifikasi-reset]').addEventListener('click', () => {
                                search.value = '';
                                searchState.textContent = '';
                                input.value = '';
                                label.textContent = 'Pilih Kode Klasifikasi';
                                pendingValue = '';
                                pendingLabel = '';
                                root.querySelectorAll(`input[name="{{ $klasifikasiId }}-radio"]`).forEach((radio) => { radio.checked = false; });
                                root.querySelectorAll('[data-klasifikasi-choice]').forEach((choice) => { choice.checked = false; });
                                root.querySelectorAll('[data-klasifikasi-choice-label]').forEach((choiceLabel) => {
                                    choiceLabel.classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700');
                                });
                                confirmation.classList.add('hidden');
                                applyFilter();
                                search.focus();
                            });
                            root.querySelectorAll('input[type="radio"]').forEach((radio) => {
                                if (radio.matches('[data-klasifikasi-choice]')) return;
                                radio.addEventListener('change', () => {
                                    pendingValue = radio.dataset.value;
                                    pendingLabel = radio.dataset.label;
                                    confirmation.classList.remove('hidden');
                                    root.querySelectorAll('[data-klasifikasi-choice]').forEach((choice) => {
                                        choice.checked = false;
                                        choice.closest('[data-klasifikasi-choice-label]').classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700');
                                    });
                                });
                            });
                            root.querySelectorAll('[data-klasifikasi-choice]').forEach((choice) => {
                                choice.addEventListener('change', () => {
                                    const useClassification = choice.value === '1';
                                    input.value = useClassification ? pendingValue : '';
                                    label.textContent = useClassification ? pendingLabel : 'Pilih Kode Klasifikasi';
                                    root.querySelectorAll('[data-klasifikasi-choice-label]').forEach((choiceLabel) => {
                                        const selected = choiceLabel.contains(choice);
                                        choiceLabel.classList.toggle('border-blue-500', selected);
                                        choiceLabel.classList.toggle('bg-blue-50', selected);
                                        choiceLabel.classList.toggle('text-blue-700', selected);
                                    });
                                    if (!useClassification) {
                                        root.querySelectorAll(`input[name="{{ $klasifikasiId }}-radio"]`).forEach((radio) => { radio.checked = false; });
                                    }
                                    confirmation.classList.add('hidden');
                                    close();
                                    showToast(useClassification ? 'Kode klasifikasi akan digunakan.' : 'Kode klasifikasi tidak digunakan.');
                                });
                            });
                            root.querySelector('[data-klasifikasi-toast-close]').addEventListener('click', hideToast);
                            document.addEventListener('keydown', (event) => {
                                if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
                            });
                        })();
                    </script>
                    @error('klasifikasi_arsip_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Area Upload File (Drag & Drop box) -->
                <div class="mb-2 relative">
                    <div id="file-drop-box" class="w-full h-48 border border-blue-400 bg-[#f8f9fa] rounded-lg flex flex-col items-center justify-center cursor-pointer hover:bg-blue-50 transition relative overflow-hidden">
                        <input type="file" id="file_arsip" name="file_arsip" required
                            accept=".pdf,.doc,.docx,.zip,.rar"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <span id="file-drop-text" class="text-blue-600 font-bold text-base mb-2 z-0">Select File</span>
                        <span id="file-drop-subtext" class="text-gray-400 text-sm z-0">Upload File Kearsipan Disini (PDF, DOC, DOCX, ZIP, RAR)</span>
                    </div>
                </div>
                @error('file_arsip')
                    <p class="mb-5 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Tombol Aksi -->
                <button type="submit" class="w-full py-3 rounded-md bg-[#2563eb] text-white font-bold flex items-center justify-center gap-2 hover:bg-blue-700 transition shadow-sm">
                    Upload Arsip <i class="fa-solid fa-arrow-right"></i>
                </button>

            </form>
        </div>
    </main>

    <!-- SCRIPT -->
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

        // Tampilkan nama file yang dipilih di kotak upload
        const fileInput = document.getElementById('file_arsip');
        const fileDropText = document.getElementById('file-drop-text');
        const fileDropSubtext = document.getElementById('file-drop-subtext');

        fileInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                fileDropText.textContent = 'Select File';
                fileDropSubtext.textContent = 'Upload File Kearsipan Disini (PDF, DOC, DOCX, ZIP, RAR)';
                return;
            }

            fileDropText.textContent = 'File Terpilih';
            fileDropSubtext.textContent = file.name;
        });
    </script>
    <x-logout-confirmation />
</body>
</html>
