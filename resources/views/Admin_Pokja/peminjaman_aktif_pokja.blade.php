<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Aktif - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
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
                <a href="/dashboard-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold bg-blue-600 text-white rounded-lg transition shadow-sm">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Dashboard
                </a>

                <div class="pt-2">
                    <button onclick="toggleMenuArsip()" class="w-full flex items-center justify-between px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder"></i> Arsip Pokja</span>
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>

                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
                        <div class="flex flex-col gap-2 pt-1 pb-2">
                            <a href="/dashboard-pokja/ajukan" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Ajukan Peminjaman</a>
                            <a href="/dashboard-pokja/upload" class="block pl-11 py-1.5 text-sm font-medium text-gray-300 hover:text-white transition">Upload File Arsip</a>
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
                <a href="/dashboard-pokja" class="text-black hover:text-blue-600 text-lg transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">{{ auth()->user()?->name ?? auth()->user()?->username }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <div class="box-border w-full min-w-0 max-w-none px-5 pb-10 sm:px-8">
            <h2 class="text-3xl font-bold mb-6 text-black">Peminjaman Aktif</h2>

            <form method="GET" action="{{ route('pokja.peminjaman.aktif') }}" class="mb-8 grid grid-cols-1 gap-3 rounded-xl border border-gray-200 bg-white p-4 md:grid-cols-5">
                <input name="nama_surat" value="{{ $filters['nama_surat'] ?? '' }}" placeholder="Nama Surat" class="rounded-md border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                <input type="text" name="nomor_dokumen" value="{{ $filters['nomor_dokumen'] ?? '' }}" placeholder="Nomor Dokumen" aria-label="Nomor Dokumen" class="rounded-md border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">

                <select name="jenis_surat_id" class="rounded-md border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <option value="">Jenis Surat</option>
                    @foreach ($jenisSurats as $jenis)
                        <option value="{{ $jenis->id }}" @selected(($filters['jenis_surat_id'] ?? null) == $jenis->id)>{{ $jenis->name }}</option>
                    @endforeach
                </select>

                <select name="sifat_surat_id" class="rounded-md border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <option value="">Sifat Surat</option>
                    @foreach ($sifatSurats as $sifat)
                        <option value="{{ $sifat->id }}" @selected(($filters['sifat_surat_id'] ?? null) == $sifat->id)>{{ $sifat->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="rounded-md bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Filter</button>

                <select name="kategori_arsip_id" class="rounded-md border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <option value="">Kategori</option>
                    @foreach ($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" @selected(($filters['kategori_arsip_id'] ?? null) == $kategori->id)>{{ $kategori->name }}</option>
                    @endforeach
                </select>

                <input type="date" name="tanggal_dokumen" value="{{ $filters['tanggal_dokumen'] ?? '' }}" class="rounded-md border border-gray-300 px-3 py-2.5 text-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">

                <div class="relative md:col-span-2" data-klasifikasi-root="peminjaman-filter-klasifikasi">
                    @php
                        $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) (($filters['klasifikasi_arsip_id'] ?? '') ?: ''));
                    @endphp
                    <input type="hidden" name="klasifikasi_arsip_id" value="{{ $filters['klasifikasi_arsip_id'] ?? '' }}" data-klasifikasi-value>
                    <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none">
                        <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi ? ($selectedKlasifikasi->kode . ' — ' . $selectedKlasifikasi->judul) : 'Pilih Kode Klasifikasi' }}</span>
                        <i class="fa-solid fa-chevron-down text-xs text-gray-500"></i>
                    </button>

                    <div class="fixed inset-0 z-[100] hidden p-4 sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                        <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                        <section class="relative mx-auto flex max-h-[88vh] w-full max-w-[1100px] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)]">
                            <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                                <div class="flex flex-col gap-3 sm:flex-row">
                                    <input type="search" data-klasifikasi-search placeholder="Cari Kode Klasifikasi" class="h-[42px] w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    <button type="button" data-klasifikasi-search-button class="h-[42px] rounded-md bg-[#1d4ed8] px-5 text-sm font-semibold text-white hover:bg-[#1e40af] sm:w-[110px]">Cari</button>
                                    <button type="button" data-klasifikasi-reset class="h-[42px] rounded-md border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50 sm:w-[110px]">Reset</button>
                                </div>
                            </div>
                            <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-6 pt-4 sm:px-6">
                                <div class="grid gap-3 md:grid-cols-2">
                                    @foreach ($klasifikasis as $klasifikasi)
                                        <label class="klasifikasi-option flex cursor-pointer gap-3 rounded-lg border border-slate-200 p-3 text-sm hover:border-blue-300 hover:bg-blue-50" data-search="{{ strtolower($klasifikasi->kode . ' ' . $klasifikasi->judul . ' ' . $klasifikasi->uraian) }}">
                                            <input type="radio" name="filter-klasifikasi-picker" value="{{ $klasifikasi->id }}" data-code="{{ $klasifikasi->kode }}" data-label="{{ $klasifikasi->kode }} — {{ $klasifikasi->judul }}" class="mt-1 accent-blue-600" @checked(($filters['klasifikasi_arsip_id'] ?? null) == $klasifikasi->id)>
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

                <a href="{{ route('pokja.peminjaman.aktif') }}" class="rounded-md border border-gray-300 px-6 py-2.5 text-center text-sm font-semibold text-gray-600 hover:bg-gray-50">Reset</a>
            </form>

            <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-200 mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Nama Arsip</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Nomor Dokumen</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Jenis Surat</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Sifat Surat</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Kode Klasifikasi</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Mulai</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Tanggal Selesai</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Status</th>
                                <th class="py-4 px-6 text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($peminjamans as $peminjaman)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-3.5 px-6 text-gray-600 font-medium">{{ $peminjaman->arsip?->nama_arsip ?? $peminjaman->nama_surat }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->arsip?->nomor_dokumen ?: '-' }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->kategori?->name ?? $peminjaman->arsip?->kategori?->name ?? '-' }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->sifatSurat?->name ?? $peminjaman->arsip?->sifatSurat?->name ?? '-' }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ $peminjaman->klasifikasiArsip?->kode ?? $peminjaman->arsip?->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ optional($peminjaman->tanggal_mulai)->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-6 text-gray-500">{{ optional($peminjaman->tanggal_selesai)->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-3.5 px-6">
                                        @php
                                            $status = $peminjaman->display_status;
                                            $statusStyles = [
                                                'Ditolak' => ['text' => 'text-red-600', 'dot' => 'bg-red-500'],
                                                'Diterima' => ['text' => 'text-green-600', 'dot' => 'bg-green-500'],
                                                'Menunggu' => ['text' => 'text-yellow-600', 'dot' => 'bg-yellow-500'],
                                                'Aktif' => ['text' => 'text-blue-600', 'dot' => 'bg-blue-500'],
                                                'Selesai' => ['text' => 'text-gray-600', 'dot' => 'bg-gray-500'],
                                            ];
                                            $statusStyle = $statusStyles[$status] ?? ['text' => 'text-gray-600', 'dot' => 'bg-gray-500'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 font-medium {{ $statusStyle['text'] }}">
                                            <span class="w-2 h-2 rounded-full {{ $statusStyle['dot'] }}"></span> {{ $status }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-6">
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" data-detail-url="{{ route('pokja.peminjaman.aktif.detail', $peminjaman) }}" class="rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700" aria-haspopup="dialog">Lihat</button>
                                            @if ((int) $peminjaman->user_id === (int) auth()->id() && $peminjaman->balasans->contains(fn ($balasan) => $balasan->attachment_path))
                                                <a href="{{ route('pokja.peminjaman.aktif.balasan.file', $peminjaman) }}" target="_blank" rel="noopener noreferrer" class="rounded-md border border-blue-500 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-50">Lihat Surat Balasan</a>
                                            @elseif ((int) $peminjaman->user_id === (int) auth()->id() && $peminjaman->balasans->isNotEmpty())
                                                <span class="self-center text-xs text-gray-400">Balasan belum memiliki lampiran</span>
                                            @elseif ((int) $peminjaman->user_id === (int) auth()->id())
                                                <span class="self-center text-xs text-gray-400">Belum ada balasan</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 px-6 text-center text-sm text-gray-500">Belum ada pengajuan menunggu validasi atau peminjaman aktif.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($peminjamans->hasPages())
                <div class="flex justify-center gap-2">
                    @if (! $peminjamans->onFirstPage())
                        <a href="{{ $peminjamans->previousPageUrl() }}" class="rounded border px-3 py-2">‹</a>
                    @endif
                    @foreach ($peminjamans->getUrlRange(max(1, $peminjamans->currentPage() - 2), min($peminjamans->lastPage(), $peminjamans->currentPage() + 2)) as $page => $url)
                        <a href="{{ $url }}" @class(['rounded px-3 py-2 text-sm font-semibold', 'bg-blue-600 text-white' => $page === $peminjamans->currentPage(), 'border text-gray-700' => $page !== $peminjamans->currentPage()])>{{ $page }}</a>
                    @endforeach
                    @if ($peminjamans->hasMorePages())
                        <a href="{{ $peminjamans->nextPageUrl() }}" class="rounded border px-3 py-2">›</a>
                    @endif
                </div>
            @endif
        </div>
    </main>

    <div id="detail-peminjaman-modal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-slate-950/50 p-4 opacity-0 transition-opacity duration-200" aria-hidden="true">
        <section data-detail-modal-panel role="dialog" aria-modal="true" aria-labelledby="detail-peminjaman-title" class="flex max-h-[90vh] w-full max-w-3xl translate-y-2 scale-[.98] flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl transition duration-200">
            <header class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 id="detail-peminjaman-title" class="text-lg font-bold text-slate-900">Detail Peminjaman</h2>
                <button type="button" data-detail-modal-close class="rounded-md px-2 text-2xl leading-none text-slate-500 transition hover:bg-slate-100 hover:text-slate-900" aria-label="Tutup detail">&times;</button>
            </header>
            <div id="detail-peminjaman-content" class="min-h-0 overflow-y-auto p-5 sm:p-6" aria-live="polite"></div>
            <footer class="flex justify-end border-t border-slate-200 px-5 py-3">
                <button type="button" data-detail-modal-close class="rounded-md border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Tutup</button>
            </footer>
        </section>
    </div>

    <script>
        function toggleMenuArsip() {
            const submenu = document.getElementById('submenu-arsip');
            const ikonPanah = document.getElementById('ikon-panah');

            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                ikonPanah.classList.add('rotate-90');
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikonPanah.classList.remove('rotate-90');
            }
        }

        const klasifikasiRoot = document.querySelector('[data-klasifikasi-root]');
        const klasifikasiModal = klasifikasiRoot?.querySelector('[data-klasifikasi-modal]');
        const klasifikasiTrigger = klasifikasiRoot?.querySelector('[data-klasifikasi-trigger]');
        const klasifikasiClose = klasifikasiRoot?.querySelector('[data-klasifikasi-close]');
        const klasifikasiSearch = klasifikasiRoot?.querySelector('[data-klasifikasi-search]');
        const klasifikasiSearchButton = klasifikasiRoot?.querySelector('[data-klasifikasi-search-button]');
        const klasifikasiReset = klasifikasiRoot?.querySelector('[data-klasifikasi-reset]');
        const klasifikasiValue = klasifikasiRoot?.querySelector('[data-klasifikasi-value]');
        const klasifikasiLabel = klasifikasiRoot?.querySelector('[data-klasifikasi-label]');
        const klasifikasiEmpty = klasifikasiRoot?.querySelector('[data-klasifikasi-empty]');

        function closeKlasifikasiModal() {
            if (!klasifikasiModal || !klasifikasiTrigger) return;
            klasifikasiModal.classList.add('hidden');
            klasifikasiTrigger.setAttribute('aria-expanded', 'false');
        }

        function openKlasifikasiModal() {
            if (!klasifikasiModal || !klasifikasiTrigger) return;
            klasifikasiModal.classList.remove('hidden');
            klasifikasiTrigger.setAttribute('aria-expanded', 'true');
            setTimeout(() => klasifikasiSearch?.focus(), 50);
        }

        klasifikasiTrigger?.addEventListener('click', function () {
            if (klasifikasiModal.classList.contains('hidden')) {
                openKlasifikasiModal();
            } else {
                closeKlasifikasiModal();
            }
        });

        klasifikasiClose?.addEventListener('click', closeKlasifikasiModal);

        function applyKlasifikasiFilter() {
            const query = (klasifikasiSearch?.value || '').toLowerCase().trim();
            let visible = 0;

            klasifikasiRoot?.querySelectorAll('.klasifikasi-option').forEach((option) => {
                const haystack = (option.dataset.search || '').toLowerCase();
                const isMatch = !query || haystack.includes(query);
                option.classList.toggle('hidden', !isMatch);
                if (isMatch) visible++;
            });

            if (klasifikasiEmpty) {
                klasifikasiEmpty.classList.toggle('hidden', visible > 0);
            }
        }

        klasifikasiSearch?.addEventListener('input', applyKlasifikasiFilter);
        klasifikasiSearchButton?.addEventListener('click', applyKlasifikasiFilter);

        klasifikasiReset?.addEventListener('click', function () {
            if (!klasifikasiSearch || !klasifikasiValue || !klasifikasiLabel) return;
            klasifikasiSearch.value = '';
            klasifikasiValue.value = '';
            klasifikasiLabel.textContent = 'Pilih Kode Klasifikasi';
            klasifikasiRoot.querySelectorAll('.klasifikasi-option').forEach((option) => option.classList.remove('hidden'));
            klasifikasiRoot.querySelectorAll('input[name="filter-klasifikasi-picker"]').forEach((radio) => (radio.checked = false));
            if (klasifikasiEmpty) klasifikasiEmpty.classList.add('hidden');
            klasifikasiSearch.focus();
        });

        klasifikasiRoot?.querySelectorAll('input[name="filter-klasifikasi-picker"]').forEach((radio) => {
            radio.addEventListener('change', function () {
                if (!klasifikasiValue || !klasifikasiLabel) return;
                klasifikasiValue.value = this.value;
                klasifikasiLabel.textContent = this.dataset.label || this.dataset.code || 'Pilih Kode Klasifikasi';
                closeKlasifikasiModal();
            });
        });

        document.addEventListener('click', function (event) {
            if (!klasifikasiRoot || !klasifikasiModal) return;
            if (!klasifikasiRoot.contains(event.target)) {
                closeKlasifikasiModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && klasifikasiModal && !klasifikasiModal.classList.contains('hidden')) {
                closeKlasifikasiModal();
            }
        });

        const detailModal = document.getElementById('detail-peminjaman-modal');
        const detailModalPanel = detailModal.querySelector('[data-detail-modal-panel]');
        const detailModalContent = document.getElementById('detail-peminjaman-content');
        let detailRequest = null;
        let detailCloseTimer = null;
        let detailOpener = null;

        function closeDetailModal() {
            if (detailModal.classList.contains('hidden')) return;

            detailRequest?.abort();
            detailRequest = null;
            detailModal.setAttribute('aria-hidden', 'true');
            detailModal.classList.add('opacity-0');
            detailModalPanel.classList.add('translate-y-2', 'scale-[.98]');
            document.body.classList.remove('overflow-hidden');
            window.clearTimeout(detailCloseTimer);
            detailCloseTimer = window.setTimeout(() => {
                detailModal.classList.add('hidden');
                detailModal.classList.remove('flex');
                detailOpener?.focus();
            }, 200);
        }

        async function openDetailModal(url, opener) {
            window.clearTimeout(detailCloseTimer);
            detailRequest?.abort();
            detailRequest = new AbortController();
            detailOpener = opener;

            detailModal.classList.remove('hidden');
            detailModal.classList.add('flex');
            detailModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
            detailModalContent.innerHTML = '<p class="py-8 text-center text-sm text-slate-500">Memuat detail peminjaman…</p>';
            detailModal.querySelector('[data-detail-modal-close]').focus();
            window.requestAnimationFrame(() => {
                if (detailModal.getAttribute('aria-hidden') !== 'false') return;
                detailModal.classList.remove('opacity-0');
                detailModalPanel.classList.remove('translate-y-2', 'scale-[.98]');
            });

            const request = detailRequest;

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    signal: request.signal,
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const html = await response.text();
                if (detailRequest === request && !request.signal.aborted) {
                    detailModalContent.innerHTML = html;
                }
            } catch (error) {
                if (error.name === 'AbortError') return;
                if (detailRequest === request) {
                    detailModalContent.textContent = 'Detail peminjaman gagal dimuat. Silakan tutup popup dan coba lagi.';
                }
            }
        }

        document.addEventListener('click', function (event) {
            const detailButton = event.target.closest('[data-detail-url]');
            if (detailButton) {
                openDetailModal(detailButton.dataset.detailUrl, detailButton);
                return;
            }

            if (event.target === detailModal || event.target.closest('[data-detail-modal-close]')) {
                closeDetailModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !detailModal.classList.contains('hidden')) {
                closeDetailModal();
            }
        });
    </script>
    <x-logout-confirmation />
</body>
</html>
