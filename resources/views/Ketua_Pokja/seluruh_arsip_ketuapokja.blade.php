<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seluruh Arsip - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex min-h-screen bg-[#f4f4f5] font-sans text-gray-800">
    <aside class="fixed left-0 top-0 z-20 flex min-h-screen w-64 flex-col justify-between bg-[#0a1120] text-white">
        <div>
            <div class="mb-2 flex items-center gap-3 px-6 py-6">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                    <i class="fa-solid fa-book-open text-sm text-blue-600"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>
            <nav class="space-y-1 px-4">
                <a href="/ketua-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 transition hover:text-white">
                    <svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Dashboard
                </a>
                <a href="/ketua-pokja/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 transition hover:text-white">
                    <i class="fa-regular fa-circle-check"></i> Validasi Upload
                </a>
                <a href="/ketua-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 transition hover:text-white">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                <a href="{{ route('ketua.history') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 transition hover:text-white">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                <a href="{{ route('ketua.arsip.seluruh') }}" aria-current="page" class="flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition">
                    <i class="fa-solid fa-box-archive"></i> Seluruh Arsip
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 transition hover:text-white">
                    <i class="fa-regular fa-file-lines"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
            @csrf
            <button type="submit" class="flex w-full items-center gap-2 px-4 py-2.5 text-left text-sm text-red-600 transition hover:bg-red-50">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </button>
        </form>
    </aside>

    <main class="ml-64 flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between bg-[#f4f4f5] px-5 py-6 sm:px-8">
            <a href="{{ route('ketua.dashboard') }}" aria-label="Kembali ke Dashboard Ketua Pokja" class="text-lg text-black transition hover:text-blue-600">
            </a>
            <div class="flex min-w-0 items-center gap-3">
                <span class="truncate text-right text-sm font-bold text-gray-900 sm:text-base">Ketua Pokja {{ auth()->user()->pokja?->name ?? '-' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <section class="box-border w-full min-w-0 px-5 pb-10 sm:px-8">
            <h2 class="mb-6 text-2xl font-bold text-black sm:text-3xl">Seluruh Arsip</h2>

            <form method="GET" action="{{ route('ketua.arsip.seluruh') }}" class="mb-8 box-border w-full min-w-0 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">
                    <input type="search" name="nama_surat" value="{{ $filters['nama_surat'] ?? '' }}" placeholder="Nama Surat" aria-label="Nama Surat"
                        class="h-[42px] w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <input type="text" name="nomor_dokumen" value="{{ $filters['nomor_dokumen'] ?? '' }}" placeholder="Nomor Dokumen" aria-label="Nomor Dokumen"
                        class="h-[42px] w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <select name="jenis_surat_id" aria-label="Jenis Surat"
                        class="h-[42px] w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Jenis Surat</option>
                        @foreach ($jenisSurats as $jenisSurat)
                            <option value="{{ $jenisSurat->id }}" @selected(($filters['jenis_surat_id'] ?? '') == $jenisSurat->id)>{{ $jenisSurat->name }}</option>
                        @endforeach
                    </select>
                    <select name="sifat_surat_id" aria-label="Sifat Surat"
                        class="h-[42px] w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Sifat Surat</option>
                        @foreach ($sifatSurats as $sifatSurat)
                            <option value="{{ $sifatSurat->id }}" @selected(($filters['sifat_surat_id'] ?? '') == $sifatSurat->id)>{{ $sifatSurat->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="h-[42px] w-full rounded-md bg-blue-600 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Filter</button>
                </div>

                <div class="mt-3 grid min-w-0 grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    <input type="date" name="tanggal_dokumen" value="{{ $filters['tanggal_dokumen'] ?? '' }}" aria-label="Tanggal Dokumen"
                        class="h-[42px] w-full min-w-0 rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">

                    <div class="relative w-full min-w-0" data-klasifikasi-root>
                        @php
                            $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) (($filters['klasifikasi_arsip_id'] ?? '') ?: ''));
                        @endphp
                        <input type="hidden" name="klasifikasi_arsip_id" value="{{ $filters['klasifikasi_arsip_id'] ?? '' }}" data-klasifikasi-value>
                        <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex h-[42px] w-full min-w-0 items-center justify-between rounded-md border border-gray-300 bg-white px-3 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi ? ($selectedKlasifikasi->kode . ' — ' . ($selectedKlasifikasi->judul ?? '')) : 'Kode Klasifikasi' }}</span>
                            <i class="fa-solid fa-chevron-down ml-2 shrink-0 text-xs text-gray-500" aria-hidden="true"></i>
                        </button>
                        <div class="fixed inset-0 z-[100] hidden items-center justify-center p-4 opacity-0 transition-opacity duration-200 sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                            <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                            <section data-klasifikasi-panel class="relative mx-auto flex max-h-[88vh] w-full max-w-[1100px] scale-95 flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)] transition-transform duration-200">
                                <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <input type="search" data-klasifikasi-search placeholder="Cari kode atau nama klasifikasi" class="h-[42px] w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <button type="button" data-klasifikasi-search-button class="h-[42px] rounded-md bg-blue-700 px-5 text-sm font-semibold text-white hover:bg-blue-800 sm:w-[110px]">Cari</button>
                                        <button type="button" data-klasifikasi-reset class="h-[42px] rounded-md border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50 sm:w-[110px]">Reset</button>
                                    </div>
                                </div>
                                <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-6 pt-4 sm:px-6">
                                    <div class="grid gap-3 md:grid-cols-2">
                                        @foreach ($klasifikasis as $klasifikasi)
                                            <label class="klasifikasi-option flex cursor-pointer gap-3 rounded-lg border border-slate-200 p-3 text-sm hover:border-blue-300 hover:bg-blue-50" data-search="{{ strtolower($klasifikasi->kode . ' ' . $klasifikasi->judul . ' ' . $klasifikasi->uraian) }}">
                                                <input type="radio" name="filter-klasifikasi-picker" value="{{ $klasifikasi->id }}" data-label="{{ $klasifikasi->kode }} — {{ $klasifikasi->judul }}" class="mt-1 accent-blue-600" @checked(($filters['klasifikasi_arsip_id'] ?? null) == $klasifikasi->id)>
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
                                    <p class="hidden py-8 text-center text-sm text-slate-500" data-klasifikasi-empty>Kode klasifikasi tidak ditemukan.</p>
                                </div>
                            </section>
                        </div>
                    </div>
                    <a href="{{ route('ketua.arsip.seluruh') }}" class="flex h-[42px] w-full items-center justify-center rounded-md border border-blue-600 bg-white px-4 text-sm font-semibold text-blue-700 shadow-sm transition hover:bg-blue-50">Reset</a>
                </div>
            </form>

            <div class="mb-8 w-full min-w-0 overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                <table class="min-w-[1200px] w-full border-collapse text-left">
                    <thead class="bg-slate-100 text-gray-700">
                        <tr>
                            <th class="px-5 py-4 text-sm font-bold">Nama Arsip</th>
                            <th class="px-5 py-4 text-sm font-bold">Nomor Dokumen</th>
                            <th class="px-5 py-4 text-sm font-bold">Jenis Surat</th>
                            <th class="px-5 py-4 text-sm font-bold">Sifat Surat</th>
                            <th class="px-5 py-4 text-sm font-bold">Kelompok Kerja</th>
                            <th class="px-5 py-4 text-sm font-bold">Kode Klasifikasi</th>
                            <th class="px-5 py-4 text-sm font-bold">Tanggal Upload</th>
                            <th class="px-5 py-4 text-sm font-bold">Status</th>
                            <th class="px-5 py-4 text-sm font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($arsips as $arsip)
                            @php
                                $status = ucfirst(str_replace('_', ' ', (string) $arsip->status));
                                $statusColor = match (strtolower((string) $arsip->status)) {
                                    'aktif' => 'text-blue-600',
                                    'ditolak', 'nonaktif' => 'text-red-600',
                                    'menunggu', 'menunggu_validasi' => 'text-yellow-600',
                                    default => 'text-gray-600',
                                };
                            @endphp
                            <tr class="transition hover:bg-gray-50">
                                <td class="break-words px-5 py-3.5 font-medium text-gray-700">{{ $arsip->nama_arsip }}</td>
                                <td class="break-words px-5 py-3.5 text-gray-600">{{ $arsip->nomor_dokumen ?: '-' }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $arsip->kategori?->name ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $arsip->sifatSurat?->name ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $arsip->pokja?->name ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-gray-600">{{ $arsip->klasifikasiArsip?->kode ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 text-gray-600">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-3.5 font-semibold {{ $statusColor }}">{{ $status }}</td>
                                <td class="px-5 py-3.5">
                                    <button type="button" data-metadata-button data-url="{{ route('ketua.arsip.seluruh.metadata', ['arsipId' => $arsip->id]) }}" class="inline-flex rounded-md bg-blue-600 px-5 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-500">Tidak ada arsip yang sesuai dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($arsips->hasPages())
                <nav class="flex items-center justify-center gap-2" aria-label="Pagination">
                    @if ($arsips->previousPageUrl())
                        <a href="{{ $arsips->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-left"></i></a>
                    @else
                        <span class="flex h-9 w-9 items-center justify-center text-gray-400" aria-hidden="true"><i class="fa-solid fa-arrow-left"></i></span>
                    @endif
                    @foreach ($arsips->getUrlRange(max(1, $arsips->currentPage() - 2), min($arsips->lastPage(), $arsips->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}" @class(['flex h-9 min-w-9 items-center justify-center rounded-md px-2 text-sm font-semibold transition', 'bg-blue-600 text-white' => $page === $arsips->currentPage(), 'text-gray-800 hover:bg-gray-200' => $page !== $arsips->currentPage()]) @if ($page === $arsips->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                    @endforeach
                    @if ($arsips->nextPageUrl())
                        <a href="{{ $arsips->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-right"></i></a>
                    @else
                        <span class="flex h-9 w-9 items-center justify-center text-gray-400" aria-hidden="true"><i class="fa-solid fa-arrow-right"></i></span>
                    @endif
                </nav>
            @endif
        </section>
    </main>

    <div id="metadata-modal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-slate-950/50 p-4 opacity-0 transition-opacity duration-200 sm:p-6" aria-hidden="true">
        <button type="button" class="fixed inset-0 cursor-default" data-metadata-overlay aria-label="Tutup detail arsip"></button>
        <section data-metadata-panel role="dialog" aria-modal="true" aria-labelledby="metadata-title" class="relative z-10 flex max-h-[90vh] w-full max-w-2xl scale-95 flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-2xl transition-transform duration-200">
            <header class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                <h2 id="metadata-title" class="text-lg font-bold text-gray-900">Detail Arsip</h2>
                <button type="button" data-metadata-close aria-label="Tutup" class="flex h-9 w-9 items-center justify-center rounded-md text-2xl text-gray-500 transition hover:bg-gray-100 hover:text-gray-900">&times;</button>
            </header>
            <div class="overflow-y-auto p-5">
                <p data-metadata-message class="hidden rounded-md bg-red-50 p-3 text-sm text-red-700" role="alert"></p>
                <dl data-metadata-fields class="grid grid-cols-1 gap-4 sm:grid-cols-2"></dl>
            </div>
        </section>
    </div>

    <script>
        const klasifikasiRoot = document.querySelector('[data-klasifikasi-root]');
        const klasifikasiModal = klasifikasiRoot.querySelector('[data-klasifikasi-modal]');
        const klasifikasiTrigger = klasifikasiRoot.querySelector('[data-klasifikasi-trigger]');
        const klasifikasiSearch = klasifikasiRoot.querySelector('[data-klasifikasi-search]');
        const klasifikasiValue = klasifikasiRoot.querySelector('[data-klasifikasi-value]');
        const klasifikasiLabel = klasifikasiRoot.querySelector('[data-klasifikasi-label]');
        const klasifikasiEmpty = klasifikasiRoot.querySelector('[data-klasifikasi-empty]');
        const klasifikasiPanel = klasifikasiRoot.querySelector('[data-klasifikasi-panel]');
        let klasifikasiCloseTimer;

        function closeKlasifikasiModal() {
            klasifikasiModal.classList.add('opacity-0');
            klasifikasiPanel.classList.add('scale-95');
            klasifikasiTrigger.setAttribute('aria-expanded', 'false');
            window.clearTimeout(klasifikasiCloseTimer);
            klasifikasiCloseTimer = window.setTimeout(() => klasifikasiModal.classList.add('hidden'), 200);
        }

        function openKlasifikasiModal() {
            window.clearTimeout(klasifikasiCloseTimer);
            klasifikasiModal.classList.remove('hidden');
            klasifikasiTrigger.setAttribute('aria-expanded', 'true');
            requestAnimationFrame(() => {
                klasifikasiModal.classList.remove('opacity-0');
                klasifikasiPanel.classList.remove('scale-95');
            });
            window.setTimeout(() => klasifikasiSearch.focus(), 50);
        }

        function filterKlasifikasi() {
            const term = klasifikasiSearch.value.trim().toLowerCase();
            let matches = 0;
            klasifikasiRoot.querySelectorAll('.klasifikasi-option').forEach((option) => {
                const visible = !term || (option.dataset.search || '').includes(term);
                option.classList.toggle('hidden', !visible);
                if (visible) matches += 1;
            });
            klasifikasiEmpty.classList.toggle('hidden', matches !== 0);
        }

        klasifikasiTrigger.addEventListener('click', openKlasifikasiModal);
        klasifikasiRoot.querySelector('[data-klasifikasi-close]').addEventListener('click', closeKlasifikasiModal);
        klasifikasiRoot.querySelector('[data-klasifikasi-search-button]').addEventListener('click', filterKlasifikasi);
        klasifikasiSearch.addEventListener('input', filterKlasifikasi);
        klasifikasiRoot.querySelector('[data-klasifikasi-reset]').addEventListener('click', () => {
            klasifikasiSearch.value = '';
            klasifikasiValue.value = '';
            klasifikasiLabel.textContent = 'Kode Klasifikasi';
            klasifikasiRoot.querySelectorAll('.klasifikasi-option').forEach((option) => option.classList.remove('hidden'));
            klasifikasiRoot.querySelectorAll('input[name="filter-klasifikasi-picker"]').forEach((radio) => {
                radio.checked = false;
            });
            klasifikasiEmpty.classList.add('hidden');
            klasifikasiSearch.focus();
        });
        klasifikasiRoot.querySelectorAll('input[name="filter-klasifikasi-picker"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                klasifikasiValue.value = radio.value;
                klasifikasiLabel.textContent = radio.dataset.label || 'Kode Klasifikasi';
                closeKlasifikasiModal();
            });
        });

        const metadataModal = document.getElementById('metadata-modal');
        const metadataPanel = metadataModal.querySelector('[data-metadata-panel]');
        const metadataFields = metadataModal.querySelector('[data-metadata-fields]');
        const metadataMessage = metadataModal.querySelector('[data-metadata-message]');
        const metadataLabels = [
            ['nama_arsip', 'Nama Surat'],
            ['nomor_dokumen', 'Nomor Dokumen'],
            ['jenis_surat', 'Jenis Surat'],
            ['sifat_surat', 'Sifat Surat'],
            ['pokja', 'Kelompok Kerja'],
            ['kode_klasifikasi', 'Kode Klasifikasi'],
            ['tanggal_upload', 'Tanggal Upload'],
            ['masa_aktif', 'Masa Aktif'],
            ['masa_inaktif', 'Masa Inaktif'],
            ['tanda_tangan', 'Tanda Tangan'],
            ['keterangan', 'Keterangan'],
            ['status', 'Status'],
        ];
        let activeMetadataButton;
        let metadataCloseTimer;

        function closeMetadataModal() {
            metadataModal.classList.add('opacity-0');
            metadataPanel.classList.add('scale-95');
            metadataModal.setAttribute('aria-hidden', 'true');
            window.clearTimeout(metadataCloseTimer);
            metadataCloseTimer = window.setTimeout(() => {
                metadataModal.classList.add('hidden');
                activeMetadataButton?.focus();
            }, 200);
        }

        function openMetadataModal() {
            window.clearTimeout(metadataCloseTimer);
            metadataModal.classList.remove('hidden');
            metadataModal.classList.add('flex');
            metadataModal.setAttribute('aria-hidden', 'false');
            requestAnimationFrame(() => {
                metadataModal.classList.remove('opacity-0');
                metadataPanel.classList.remove('scale-95');
            });
        }

        document.querySelectorAll('[data-metadata-button]').forEach((button) => {
            button.addEventListener('click', async () => {
                activeMetadataButton = button;
                metadataFields.replaceChildren();
                metadataMessage.classList.add('hidden');
                openMetadataModal();
                try {
                    const response = await fetch(button.dataset.url, {
                        headers: { Accept: 'application/json' },
                        credentials: 'same-origin',
                    });
                    if (!response.ok) throw new Error('Metadata arsip gagal dimuat.');
                    const metadata = await response.json();
                    metadataLabels.forEach(([key, label]) => {
                        const wrapper = document.createElement('div');
                        const term = document.createElement('dt');
                        const value = document.createElement('dd');
                        term.className = 'text-xs font-semibold uppercase tracking-wide text-gray-500';
                        value.className = 'mt-1 whitespace-pre-wrap break-words text-sm text-gray-900';
                        term.textContent = label;
                        value.textContent = metadata[key] || '-';
                        wrapper.append(term, value);
                        metadataFields.append(wrapper);
                    });
                } catch (error) {
                    metadataMessage.textContent = error instanceof Error ? error.message : 'Metadata arsip gagal dimuat.';
                    metadataMessage.classList.remove('hidden');
                }
            });
        });

        metadataModal.querySelector('[data-metadata-close]').addEventListener('click', closeMetadataModal);
        metadataModal.querySelector('[data-metadata-overlay]').addEventListener('click', closeMetadataModal);
        document.addEventListener('click', (event) => {
            if (!klasifikasiModal.classList.contains('hidden') && !klasifikasiRoot.contains(event.target)) {
                closeKlasifikasiModal();
            }
        });
        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            if (!klasifikasiModal.classList.contains('hidden')) {
                closeKlasifikasiModal();
                klasifikasiTrigger.focus();
            }
            if (!metadataModal.classList.contains('hidden')) closeMetadataModal();
        });
    </script>
    <x-logout-confirmation />
</body>
</html>