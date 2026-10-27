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
        #admin-tu-sidebar { transition: transform 200ms ease-out; }
        #admin-tu-main { margin-left: 16rem; }
        .dropdown-item label, select.custom-dropdown option {
            font-size: 15px !important;
            line-height: 1.5;
        }
        @media (max-width: 767px) {
            #admin-tu-sidebar { transform: translateX(-100%); }
            #admin-tu-main { margin-left: 0; }
            .admin-tu-sidebar-open #admin-tu-sidebar { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-[#f4f7f9] flex font-sans text-gray-800">

    <!-- SIDEBAR KIRI -->
    <aside id="admin-tu-sidebar" class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
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

    <button id="admin-tu-sidebar-backdrop" type="button" aria-label="Tutup menu" class="fixed inset-0 z-10 hidden bg-slate-950/40 md:hidden"></button>

<main id="admin-tu-main" class="flex min-h-screen min-w-0 flex-1 flex-col">
    <header class="sticky top-0 z-10 flex items-center justify-between bg-[#f4f7f9] px-4 py-5 sm:px-6 lg:px-8">
        <button id="admin-tu-sidebar-toggle" type="button" aria-label="Buka menu" aria-expanded="false" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600 md:hidden">
            <i class="fa-solid fa-bars" aria-hidden="true"></i>
        </button>
        <span class="ml-auto font-bold">{{ auth()->user()->name ?? 'Admin TU' }}</span>
        <i class="fa-solid fa-circle-user ml-3 text-2xl" aria-hidden="true"></i>
    </header>

    <div class="w-full min-w-0 flex-1 px-4 pb-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-6 flex items-center justify-between gap-4">
            <h2 class="text-2xl font-bold text-black sm:text-3xl">Laporan Arsip</h2>
        </div>

        <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
            <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <p class="mb-2 text-sm font-bold text-gray-400">Total Arsip</p>
                <p class="mb-1 text-3xl font-bold text-gray-800 sm:text-4xl">{{ $totalArsip ?? 0 }}</p>
                <p class="mt-2 text-sm font-medium text-gray-400">Dokumen</p>
            </div>
            <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <p class="mb-2 text-sm font-bold text-gray-400">Arsip Kelompok Kerja</p>
                <p class="mb-1 text-3xl font-bold text-gray-800 sm:text-4xl">{{ $totalArsipPokja ?? 0 }}</p>
                <p class="mt-2 text-sm font-medium text-gray-400">Dokumen</p>
            </div>
            <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6">
                <p class="mb-2 text-sm font-bold text-gray-400">Arsip TU</p>
                <p class="mb-1 text-3xl font-bold text-gray-800 sm:text-4xl">{{ $totalArsipTu ?? 0 }}</p>
                <p class="mt-2 text-sm font-medium text-gray-400">Dokumen</p>
            </div>
        </div>

        <!-- FORM FILTER PENCARIAN (Diubah jadi Flexbox menyamping sesuai gambar) -->
        <form id="arsip-search-form" method="GET" action="{{ route('admin-tu.laporan.arsip') }}" class="mb-4 grid min-w-0 grid-cols-1 gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:grid-cols-2 xl:grid-cols-4">
            <input id="arsip-search-input" type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama Surat" aria-label="Nama Surat" class="order-1 h-[42px] w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">

            <div class="relative order-2 w-full min-w-0">
                <select name="kategori_id" aria-label="Jenis Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <option value="">Jenis Surat</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected((string) ($filters['kategoriId'] ?? '') === (string) $kategori->id)>{{ $kategori->name }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500" aria-hidden="true"></i>
            </div>

            <div class="relative order-3 w-full min-w-0">
                <select name="sifat_surat_id" aria-label="Sifat Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <option value="">Sifat Surat</option>
                    @foreach ($sifatSurats as $sifatSurat)
                        <option value="{{ $sifatSurat->id }}" @selected((string) ($filters['sifatSuratId'] ?? '') === (string) $sifatSurat->id)>{{ $sifatSurat->name }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500" aria-hidden="true"></i>
            </div>

            <button id="arsip-search-submit" type="submit" class="order-4 h-[42px] rounded-md bg-blue-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Filter</button>

            <div class="relative order-5 w-full min-w-0">
                <select name="pokja_id" aria-label="Kelompok Kerja" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <option value="">Kelompok Kerja</option>
                    @foreach ($pokjas as $pokja)
                        <option value="{{ $pokja->id }}" @selected((string) ($filters['pokjaId'] ?? '') === (string) $pokja->id)>{{ $pokja->name }}</option>
                    @endforeach
                </select>
                <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500" aria-hidden="true"></i>
            </div>

            <input type="date" name="tanggal" value="{{ $filters['tanggal'] ?? '' }}" aria-label="Tanggal Dokumen" class="order-6 h-[42px] w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">

            @php
                $klasifikasiValue = (string) ($filters['kodeKlasifikasi'] ?? '');
                $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->kode === $klasifikasiValue);
            @endphp
            <div class="relative order-7 w-full min-w-0" data-klasifikasi-root>
                <input type="hidden" name="kode_klasifikasi" value="{{ $klasifikasiValue }}" data-klasifikasi-value>
                <button type="button" data-klasifikasi-trigger aria-expanded="false" aria-controls="klasifikasi-filter-modal" class="flex h-10.5 w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi?->kode ?? 'Kode Klasifikasi' }}</span>
                    <i class="fa-solid fa-chevron-down ml-3 shrink-0 text-xs text-gray-500" aria-hidden="true"></i>
                </button>
                <div id="klasifikasi-filter-modal" class="fixed inset-0 z-100 hidden p-4 sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                    <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                    <section class="relative mx-auto flex max-h-[88vh] w-full max-w-275 flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)]">
                        <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                            <div class="flex flex-col gap-3 sm:flex-row">
                                <input type="search" data-klasifikasi-search placeholder="Cari Kode Klasifikasi" aria-label="Cari Kode Klasifikasi" class="h-10.5 w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                <button type="button" data-klasifikasi-clear class="h-10.5 shrink-0 rounded-md border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Hapus Pilihan</button>
                            </div>
                        </div>
                        <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-6 pt-4 sm:px-6">
                            @foreach ($klasifikasis->groupBy(fn ($item) => ($item->bab_urutan ?? 999) . '|' . trim((string) ($item->bab ?? ''))) as $group)
                                @php $first = $group->first(); @endphp
                                <div class="mb-6">
                                    <h3 class="mb-3 text-sm font-bold text-slate-700">{{ $first->bab ?? 'Kode Klasifikasi' }}</h3>
                                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                        @foreach ($group as $klasifikasi)
                                            @php $label = $klasifikasi->kode . ' - ' . ($klasifikasi->judul ?? $klasifikasi->uraian ?? '-'); @endphp
                                            <button type="button" data-klasifikasi-option data-value="{{ $klasifikasi->kode }}" data-label="{{ $label }}" data-search="{{ strtolower($klasifikasi->kode . ' ' . ($klasifikasi->judul ?? '') . ' ' . ($klasifikasi->uraian ?? '')) }}" class="rounded-lg border border-slate-200 bg-white p-3 text-left text-sm text-slate-700 transition hover:border-blue-300 hover:bg-blue-50">
                                                <span class="block font-bold text-slate-900">{{ $klasifikasi->kode }}</span>
                                                <span class="block text-xs text-slate-600">{{ $klasifikasi->judul ?? $klasifikasi->uraian ?? '-' }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                            <p class="hidden py-8 text-center text-sm text-slate-500" data-klasifikasi-empty>Tidak ditemukan kode klasifikasi.</p>
                        </div>
                    </section>
                </div>
            </div>

            <a href="{{ route('admin-tu.laporan.arsip') }}" class="order-8 flex h-10.5 items-center justify-center rounded-md border border-blue-600 bg-white text-sm font-semibold text-blue-700 transition hover:bg-blue-50">Reset</a>
        </form>
        <p class="mb-4 text-sm text-slate-600" role="status" aria-live="polite">
            {{ $arsips->total() > 0 ? 'Ditemukan ' . $arsips->total() . ' data yang sesuai.' : 'Tidak ditemukan data arsip yang sesuai.' }}
        </p>

        <!-- TABEL DATA LAPORAN (Dibuat Full Width) -->
        <div class="w-full min-w-0 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-300 border-collapse text-left">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="whitespace-nowrap px-5 py-4 text-sm font-bold text-gray-700">Nama Arsip</th>
                            <th class="whitespace-nowrap px-5 py-4 text-sm font-bold text-gray-700">Kode Klasifikasi</th>
                            <th class="whitespace-nowrap px-5 py-4 text-sm font-bold text-gray-700">Kategori</th>
                            <th class="whitespace-nowrap px-5 py-4 text-sm font-bold text-gray-700">Sifat Surat</th>
                            <th class="whitespace-nowrap px-5 py-4 text-sm font-bold text-gray-700">Kelompok Kerja</th>
                            <th class="whitespace-nowrap px-5 py-4 text-sm font-bold text-gray-700">Tanggal Upload</th>
                            <th class="whitespace-nowrap px-5 py-4 text-sm font-bold text-gray-700">Status</th>
                            <th class="whitespace-nowrap px-5 py-4 text-sm font-bold text-gray-700">Keterangan</th>
                            <th class="whitespace-nowrap px-5 py-4 text-center text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if(isset($arsips) && count($arsips) > 0)
                            @foreach ($arsips as $arsip)
                                <tr class="text-sm transition hover:bg-gray-50">
                                    <td class="px-5 py-4 font-semibold text-gray-700">{{ $arsip->nama_arsip }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->kategori?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->sifatSurat?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->pokja?->name ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm">
                                        @if ($arsip->retensi_status === 'Aktif')
                                            <span class="inline-flex items-center gap-1.5 font-semibold text-blue-600"><span class="h-2 w-2 rounded-full bg-blue-500"></span>Aktif</span>
                                        @elseif ($arsip->retensi_status === 'Inaktif')
                                            <span class="inline-flex items-center gap-1.5 font-semibold text-gray-600"><span class="h-2 w-2 rounded-full bg-gray-400"></span>Inaktif</span>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-500">{{ $arsip->retensi_keterangan ?? '-' }}</td>
                                    <td class="px-5 py-4 text-center">
                                        @if ($arsip->file_path)
                                            <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" rel="noopener" 
                                               class="inline-flex rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700">
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
                                <td colspan="9" class="bg-gray-50 p-10 text-center text-sm text-gray-500">
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
            <div class="mt-6 flex w-full justify-center">
                {{ $arsips->withQueryString()->links() }}
            </div>
        @endif

    </div>
</main>

    <script>
        (() => {
            const toggle = document.getElementById('admin-tu-sidebar-toggle');
            const backdrop = document.getElementById('admin-tu-sidebar-backdrop');
            if (!toggle || !backdrop) return;

            const setOpen = (open) => {
                document.body.classList.toggle('admin-tu-sidebar-open', open);
                backdrop.classList.toggle('hidden', !open);
                toggle.setAttribute('aria-expanded', String(open));
            };

            toggle.addEventListener('click', () => setOpen(!document.body.classList.contains('admin-tu-sidebar-open')));
            backdrop.addEventListener('click', () => setOpen(false));
            window.addEventListener('resize', () => {
                if (window.matchMedia('(min-width: 768px)').matches) setOpen(false);
            });
        })();

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

        (() => {
            const root = document.querySelector('[data-klasifikasi-root]');
            if (!root) return;

            const trigger = root.querySelector('[data-klasifikasi-trigger]');
            const modal = root.querySelector('[data-klasifikasi-modal]');
            const input = root.querySelector('[data-klasifikasi-value]');
            const label = root.querySelector('[data-klasifikasi-label]');
            const search = root.querySelector('[data-klasifikasi-search]');
            const clear = root.querySelector('[data-klasifikasi-clear]');
            const emptyState = root.querySelector('[data-klasifikasi-empty]');
            const options = Array.from(root.querySelectorAll('[data-klasifikasi-option]'));
            const closeButtons = root.querySelectorAll('[data-klasifikasi-close]');

            if (!trigger || !modal || !input || !label || !search || !clear || !emptyState) return;

            const close = () => {
                modal.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('overflow-hidden');
            };

            const filterOptions = () => {
                const query = search.value.trim().toLowerCase();
                let visibleCount = 0;

                options.forEach((option) => {
                    const matches = !query || (option.dataset.search || '').includes(query);
                    option.classList.toggle('hidden', !matches);
                    visibleCount += Number(matches);
                });

                modal.querySelectorAll('.mb-6').forEach((group) => {
                    const groupOptions = group.querySelectorAll('[data-klasifikasi-option]');
                    group.classList.toggle('hidden', groupOptions.length > 0 && !Array.from(groupOptions).some((option) => !option.classList.contains('hidden')));
                });
                emptyState.classList.toggle('hidden', visibleCount > 0);
            };

            trigger.addEventListener('click', () => {
                modal.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                document.body.classList.add('overflow-hidden');
                search.value = '';
                filterOptions();
                window.setTimeout(() => search.focus(), 50);
            });

            closeButtons.forEach((button) => button.addEventListener('click', close));
            search.addEventListener('input', filterOptions);

            options.forEach((option) => {
                option.addEventListener('click', () => {
                    input.value = option.dataset.value || '';
                    label.textContent = option.dataset.label || 'Kode Klasifikasi';
                    close();
                });
            });

            clear.addEventListener('click', () => {
                input.value = '';
                label.textContent = 'Kode Klasifikasi';
                close();
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
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