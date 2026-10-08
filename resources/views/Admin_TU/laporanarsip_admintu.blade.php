<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arsip - Admin TU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .dropdown-item label, select.custom-dropdown option {
            font-size: 15px !important;
            line-height: 1.5;
        }
    </style>
</head>
<body class="bg-[#f4f7f6] flex font-sans text-gray-800">

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
                <a href="/admin-tu" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>  Dashboard
                </a>
                
                <a href="/admin-tu/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Peminjaman
                </a>

                <a href="/admin-tu/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- Menu Laporan Arsip (Aktif/Biru Solid) -->
                <a href="/admin-tu/laporan">
                    <a href="{{ route('admin-tu.laporan.arsip') }}" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-1">
                    <i class="fa-regular fa-file"></i> Laporan Arsip
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:text-red-400 transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<main class="flex-1 ml-64 min-h-screen">
    <header class="px-8 py-6 flex justify-end">
        <span class="font-bold">{{ auth()->user()->name ?? 'Admin TU' }}</span>
        <i class="fa-solid fa-circle-user text-2xl ml-3"></i>
    </header>

    <!-- Hapus max-w-7xl dan mx-auto agar layout mekar memenuhi layar -->
    <div class="px-8 pb-10 w-full">
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between gap-4 flex-wrap">
            <h2 class="text-3xl font-bold text-black">Laporan Arsip</h2>
            <a href="{{ route('admin-tu.arsip.form') }}" class="inline-flex items-center gap-2 rounded-lg bg-[#2563eb] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                <i class="fa-solid fa-plus"></i> Tambah Arsip
            </a>
        </div>

        <!-- Card Statistik: Dibuat lebih lebar dengan grid gap yang pas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 w-full">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center">
                <p class="text-gray-400 text-sm font-bold">Total Semua Arsip</p>
                <p class="text-5xl font-bold mt-3 text-gray-900">{{ $totalArsip ?? 0 }}</p>
                <p class="text-sm text-gray-400 font-semibold mt-2">Dokumen</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center">
                <p class="text-gray-400 text-sm font-bold">Arsip Kelompok Kerja</p>
                <p class="text-5xl font-bold mt-3 text-gray-900">{{ $totalArsipPokja ?? 0 }}</p>
                <p class="text-sm text-gray-400 font-semibold mt-2">Dokumen</p>
            </div>
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center">
                <p class="text-gray-400 text-sm font-bold">Arsip TU</p>
                <p class="text-5xl font-bold mt-3 text-gray-900">{{ $totalArsipTu ?? 0 }}</p>
                <p class="text-sm text-gray-400 font-semibold mt-2">Dokumen</p>
            </div>
        </div>

        <!-- FORM FILTER PENCARIAN (Diubah jadi Flexbox menyamping sesuai gambar) -->
        <form id="arsip-search-form" method="GET" action="{{ route('admin-tu.laporan.arsip') }}" class="mb-6 flex flex-col xl:flex-row items-center gap-4 w-full">
            
            <!-- 1. Nama Surat -->
            <input id="arsip-search-input" type="text" name="search" value="{{ request('search', $filters['search'] ?? '') }}" placeholder="Cari nama, nomor dokumen, klasifikasi, kategori, atau Pokja"
                   class="w-full xl:w-auto flex-1 border border-gray-300 rounded-md px-4 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-500 bg-white h-[42px]">

            <div class="w-full xl:w-auto flex-1 relative">
                @php
                    $klasifikasiField = 'kode_klasifikasi';
                    $klasifikasiValue = (string) ($filters['kodeKlasifikasi'] ?? '');
                    $klasifikasiId = 'pilih-' . $klasifikasiField;
                    $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->kode === $klasifikasiValue);
                    $klasifikasiLabel = $selectedKlasifikasi?->kode ?? ($klasifikasiValue ?: 'Pilih Kode Klasifikasi');
                    $babGroups = $klasifikasis
                        ->sortBy([['bab_urutan', 'asc'], ['urutan', 'asc'], ['kode', 'asc'], ['nomor', 'asc'], ['id', 'asc']])
                        ->groupBy(fn ($item) => (string) ($item->bab_urutan ?? 999) . '|' . trim((string) ($item->bab ?? '')));
                @endphp
                <div class="relative w-full" data-klasifikasi-root="{{ $klasifikasiId }}">
                    <input type="hidden" name="{{ $klasifikasiField }}" value="{{ $klasifikasiValue }}" data-klasifikasi-value>
                    <button type="button" data-klasifikasi-trigger aria-expanded="false"
                        class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-300 bg-white px-4 text-sm text-gray-700 transition hover:border-blue-500 focus:border-blue-500 focus:outline-none">
                        <span data-klasifikasi-label class="truncate">{{ $klasifikasiLabel }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-500"></i>
                    </button>

                    <div class="fixed inset-0 z-[100] hidden p-4 sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                        <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                        <section class="relative mx-auto flex max-h-[88vh] w-full max-w-[1180px] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)]">
                            <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                                <div class="flex flex-col gap-3 sm:flex-row">
                                    <input type="search" data-klasifikasi-search placeholder="Cari Kode Klasifikasi"
                                        class="h-[42px] w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    <button type="button" data-klasifikasi-search-button class="h-[42px] rounded-md bg-[#1d4ed8] px-5 text-sm font-semibold text-white hover:bg-[#1e40af] sm:w-[110px]">Cari</button>
                                    <button type="button" data-klasifikasi-reset class="h-[42px] rounded-md border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50 sm:w-[110px]">Reset</button>
                                </div>
                            </div>

                            <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-28 pt-4 sm:px-6" data-klasifikasi-content>
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
                                                                        <p class="mt-1 text-[12px] font-bold leading-5 text-slate-900" data-klasifikasi-heading data-search="{{ strtolower($series->first()->nomor . ' ' . $series->first()->judul) }}">
                                                                            @if (filled($series->first()->nomor)){{ $series->first()->nomor }}. @endif{{ $series->first()->judul }}
                                                                        </p>
                                                                    @endif
                                                                    <div class="pl-3">
                                                                        @foreach ($series as $item)
                                                                            @php
                                                                                $code = (string) ($item->kode ?? '');
                                                                                $detail = trim((string) ($item->uraian ?? ''));
                                                                            @endphp
                                                                            <label class="flex cursor-pointer items-start gap-2 rounded-sm px-1 py-0.5 text-[11px] leading-5 text-slate-700 hover:bg-slate-50" data-klasifikasi-option data-search="{{ strtolower($code . ' ' . $item->judul . ' ' . $detail) }}">
                                                                                <input type="radio" name="{{ $klasifikasiId }}-radio" value="{{ $code }}" data-value="{{ $code }}" data-label="{{ $code }}" @checked($code === $klasifikasiValue) class="mt-1 h-3 w-3 shrink-0 accent-blue-600">
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
                            <div class="absolute inset-x-0 bottom-0 z-20 hidden border-t border-slate-200 bg-white px-4 py-3 shadow-[0_-8px_24px_rgba(15,23,42,0.08)] sm:px-6" data-klasifikasi-confirmation>
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="text-sm font-semibold text-slate-800">Pakai Kode Klasifikasi Ini?</p>
                                    <div class="flex gap-3">
                                        <label data-klasifikasi-choice-label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-blue-400 hover:bg-blue-50">
                                            <input type="radio" name="pakai_klasifikasi" value="0" data-klasifikasi-choice class="h-4 w-4 accent-blue-600">
                                            Tidak
                                        </label>
                                        <label data-klasifikasi-choice-label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-blue-400 hover:bg-blue-50">
                                            <input type="radio" name="pakai_klasifikasi" value="1" data-klasifikasi-choice class="h-4 w-4 accent-blue-600">
                                            Iya
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
                        const showToast = (message) => {
                            toastMessage.textContent = message;
                            toast.classList.remove('translate-y-4', 'opacity-0', 'pointer-events-none');
                            toast.classList.add('translate-y-0', 'opacity-100');
                            window.clearTimeout(toastTimer);
                            toastTimer = window.setTimeout(hideToast, 4200);
                        };
                        const hideToast = () => {
                            toast.classList.add('translate-y-4', 'opacity-0', 'pointer-events-none');
                            toast.classList.remove('translate-y-0', 'opacity-100');
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
                            if (!radio.matches('[data-klasifikasi-choice]')) {
                                radio.addEventListener('change', () => {
                                    pendingValue = radio.dataset.value;
                                    pendingLabel = radio.dataset.label;
                                    confirmation.classList.remove('hidden');
                                    root.querySelectorAll('[data-klasifikasi-choice]').forEach((choice) => {
                                        choice.checked = false;
                                        choice.closest('[data-klasifikasi-choice-label]').classList.remove('border-blue-500', 'bg-blue-50', 'text-blue-700');
                                    });
                                });
                            }
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
            </div>

            <!-- 3. Tanggal Dokumen -->
            <div class="w-full xl:w-auto flex-1 relative">
                <input type="text" placeholder="Tanggal Dokumen" onfocus="(this.type='date')" onblur="(this.type='text')" name="tanggal" value="{{ request('tanggal', $filters['tanggal'] ?? '') }}" 
                       class="w-full border border-gray-300 rounded-md pl-4 pr-10 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-500 bg-white h-[42px]">
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <i class="fa-regular fa-calendar text-black"></i>
                </div>
            </div>

            <!-- 4. KATEGORI SURAT (CUSTOM DROPDOWN ANIMASI) -->
            <div class="w-full xl:w-auto flex-1 relative" x-data="{ open: false, selectedLabel: 'Kategori Surat', selectedValue: '{{ request('kategori_id', $filters['kategoriId'] ?? '') }}' }" 
                 x-init="@if(isset($kategoris)) @foreach($kategoris as $k) if(selectedValue == '{{ $k->id }}') selectedLabel = '{{ addslashes($k->name) }}'; @endforeach @endif">
                <input type="hidden" name="kategori_id" x-model="selectedValue">
                <button type="button" @click="open = !open" @click.away="open = false" 
                        class="w-full flex justify-between items-center border border-gray-300 rounded-md pl-4 pr-3 py-2.5 text-sm text-gray-700 bg-white h-[42px] focus:outline-none focus:border-blue-500 transition-colors">
                    <span x-text="selectedLabel" class="truncate"></span>
                    <i class="fa-solid fa-chevron-right text-gray-500 text-xs transition-transform duration-300" :class="open ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="open" x-transition class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                    <div @click="selectedValue = ''; selectedLabel = 'Kategori Surat'; open = false" class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors" :class="selectedValue === '' ? 'bg-blue-50 text-blue-600 font-semibold' : ''">Semua Kategori</div>
                    @if(isset($kategoris))
                        @foreach ($kategoris as $kategori)
                        <div @click="selectedValue = '{{ $kategori->id }}'; selectedLabel = '{{ addslashes($kategori->name) }}'; open = false" class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors truncate" :class="selectedValue === '{{ $kategori->id }}' ? 'bg-blue-50 text-blue-600 font-semibold' : ''" title="{{ $kategori->name }}">{{ $kategori->name }}</div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- 5. KELOMPOK KERJA (CUSTOM DROPDOWN ANIMASI) -->
            <div class="w-full xl:w-auto flex-1 relative" x-data="{ open: false, selectedLabel: 'Kelompok Kerja', selectedValue: '{{ request('pokja_id', $filters['pokjaId'] ?? '') }}' }" 
                 x-init="@if(isset($pokjas)) @foreach($pokjas as $p) if(selectedValue == '{{ $p->id }}') selectedLabel = '{{ addslashes($p->name) }}'; @endforeach @endif">
                <input type="hidden" name="pokja_id" x-model="selectedValue">
                <button type="button" @click="open = !open" @click.away="open = false" 
                        class="w-full flex justify-between items-center border border-gray-300 rounded-md pl-4 pr-3 py-2.5 text-sm text-gray-700 bg-white h-[42px] focus:outline-none focus:border-blue-500 transition-colors">
                    <span x-text="selectedLabel" class="truncate"></span>
                    <i class="fa-solid fa-chevron-right text-gray-500 text-xs transition-transform duration-300" :class="open ? 'rotate-90' : ''"></i>
                </button>
                <div x-show="open" x-transition class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                    <div @click="selectedValue = ''; selectedLabel = 'Kelompok Kerja'; open = false" class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors" :class="selectedValue === '' ? 'bg-blue-50 text-blue-600 font-semibold' : ''">Semua Kelompok Kerja</div>
                    @if(isset($pokjas))
                        @foreach ($pokjas as $pokja)
                        <div @click="selectedValue = '{{ $pokja->id }}'; selectedLabel = '{{ addslashes($pokja->name) }}'; open = false" class="cursor-pointer px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors truncate" :class="selectedValue === '{{ $pokja->id }}' ? 'bg-blue-50 text-blue-600 font-semibold' : ''" title="{{ $pokja->name }}">{{ $pokja->name }}</div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Tombol Aksi (Filter & Reset) -->
            <div class="flex gap-2 min-w-max">
                <button id="arsip-search-submit" type="submit" class="bg-blue-600 text-white rounded-md px-6 py-2.5 text-sm font-semibold hover:bg-blue-700 transition shadow-sm h-[42px]">
                    Filter
                </button>
                <a href="{{ route('admin-tu.laporan.arsip') }}" class="border border-gray-300 text-gray-600 bg-white rounded-md px-4 py-2.5 text-sm font-semibold hover:bg-gray-50 transition shadow-sm h-[42px] flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
        <p class="mb-4 text-sm text-slate-600" role="status" aria-live="polite">
            {{ $arsips->total() > 0 ? 'Ditemukan ' . $arsips->total() . ' data yang sesuai.' : 'Tidak ditemukan data arsip yang sesuai.' }}
        </p>

        <!-- TABEL DATA LAPORAN (Dibuat Full Width) -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm w-full">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left border-collapse">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="py-4 px-6 text-sm font-bold text-[#1e3a8a] whitespace-nowrap">Nama Arsip</th>
                            <th class="py-4 px-6 text-sm font-bold text-[#1e3a8a] whitespace-nowrap">Kode Klasifikasi</th>
                            <th class="py-4 px-6 text-sm font-bold text-[#1e3a8a] whitespace-nowrap">Kategori</th>
                            <th class="py-4 px-6 text-sm font-bold text-[#1e3a8a] whitespace-nowrap">Kelompok Kerja</th>
                            <th class="py-4 px-6 text-sm font-bold text-[#1e3a8a] whitespace-nowrap">Tanggal Upload</th>
                            <th class="py-4 px-6 text-sm font-bold text-[#1e3a8a] whitespace-nowrap">Status</th>
                            <th class="py-4 px-6 text-sm font-bold text-[#1e3a8a] whitespace-nowrap">Keterangan</th>
                            <th class="py-4 px-6 text-sm font-bold text-[#1e3a8a] text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if(isset($arsips) && count($arsips) > 0)
                            @foreach ($arsips as $arsip)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-6 text-sm text-gray-700 font-semibold">{{ $arsip->nama_arsip }}</td>
                                    <td class="py-4 px-6 text-sm font-semibold text-gray-600">{{ $arsip->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 font-semibold">{{ $arsip->kategori?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 font-semibold">{{ $arsip->pokja?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm text-gray-600 font-semibold">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-4 px-6 text-sm whitespace-nowrap">
                                        @if ($arsip->retensi_status === 'Aktif')
                                            <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-bold text-green-700">Aktif</span>
                                        @elseif ($arsip->retensi_status === 'Inaktif')
                                            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">Inaktif</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-sm font-medium text-gray-600">{{ $arsip->retensi_keterangan ?? '-' }}</td>
                                    <td class="py-4 px-6 text-center">
                                        @if ($arsip->file_path)
                                            <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" rel="noopener" 
                                               class="inline-block bg-blue-600 text-white px-6 py-1.5 rounded-md text-sm font-bold hover:bg-blue-700 transition shadow-sm">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400">File tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="8" class="p-10 text-center text-sm text-gray-500 bg-gray-50">
                                    {{ $namaSurat !== '' ? 'Tidak ada data arsip yang sesuai dengan pencarian.' : 'Tidak ada data arsip yang ditemukan.' }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION -->
        @if(isset($arsips) && $arsips instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="mt-8 flex justify-center w-full">
                {{ $arsips->withQueryString()->links() }}
            </div>
        @endif

    </div>
</main>

    <script>
        (() => {
            const form = document.getElementById('arsip-search-form');
            const search = document.getElementById('arsip-search-input');
            const submit = document.getElementById('arsip-search-submit');
            if (!form || !search || !submit) return;
            form.addEventListener('submit', () => {
                search.value = search.value.trim().replace(/\s+/g, ' ');
                submit.disabled = true;
                submit.textContent = 'Mencari...';
                submit.setAttribute('aria-busy', 'true');
            });
        })();

        document.addEventListener("DOMContentLoaded", function() {
            const container = document.querySelector('.dropdown-container'); // Ganti dengan class pembungkus list Anda

            if (container) {
                const items = Array.from(container.querySelectorAll('.dropdown-item'));

                items.sort(function(a, b) {
                    const textA = a.textContent.trim().toLowerCase();
                    const textB = b.textContent.trim().toLowerCase();
                    return textA.localeCompare(textB);
                });

                items.forEach(function(item) {
                    container.appendChild(item);
                });
            }
        });
    </script>
    <x-logout-confirmation />
</body>
</html>