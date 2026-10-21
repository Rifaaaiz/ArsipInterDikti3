<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Arsip Pokja - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="flex min-h-screen bg-[#f4f4f5] font-sans text-gray-800">

    <!-- SIDEBAR KIRI -->
    <aside class="fixed left-0 top-0 z-20 flex min-h-screen w-64 flex-col justify-between bg-[#0a1120] text-white">
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
                <!-- Tautan ke Dashboard -->
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
                        <i id="ikon-panah" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <!-- Sub-menu (Tampil tapi tidak aktif) -->
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
                <a href="/dashboard-pokja/history" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold bg-blue-600 text-white rounded-lg transition shadow-sm"">
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

    <!-- AREA KONTEN UTAMA -->
    <main class="ml-64 flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-end bg-[#f4f4f5] px-5 py-6 sm:px-8">
            <div class="flex items-center gap-2 text-sm font-bold text-gray-900">
                <span>admin Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-lg" aria-hidden="true"></i>
            </div>
        </header>

        <div class="box-border w-full min-w-0 max-w-none px-5 pb-10 sm:px-8">
            <h2 class="mb-6 text-3xl font-bold text-black">History Arsip Pokja</h2>

            @php
                $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) (($filters['klasifikasi_arsip_id'] ?? '') ?: ''));
                $hasHistoryFilters = $search !== ''
                    || ($filters['nomor_dokumen'] ?? '')
                    || ($filters['filter'] ?? '')
                    || ($filters['jenis_surat_id'] ?? '')
                    || ($filters['sifat_surat_id'] ?? '')
                    || ($filters['tanggal_dokumen'] ?? '')
                    || ($filters['klasifikasi_arsip_id'] ?? '');
            @endphp
            <form method="GET" action="{{ route('pokja.history') }}" class="mb-8 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-5">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Nama Surat" aria-label="Cari nama surat"
                        class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <input type="text" name="nomor_dokumen" value="{{ $filters['nomor_dokumen'] ?? '' }}" placeholder="Nomor Dokumen" aria-label="Nomor Dokumen"
                        class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">

                    <div class="relative w-full">
                        <select name="jenis_surat_id" aria-label="Jenis Surat"
                            class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="">Jenis Surat</option>
                            @foreach ($jenisSurats as $jenis)
                                <option value="{{ $jenis->id }}" @selected(($filters['jenis_surat_id'] ?? null) == $jenis->id)>{{ $jenis->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-arrow-right pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-800" aria-hidden="true"></i>
                    </div>

                    <div class="relative w-full">
                        <select name="sifat_surat_id" aria-label="Sifat Surat"
                            class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="">Sifat Surat</option>
                            @foreach ($sifatSurats as $sifat)
                                <option value="{{ $sifat->id }}" @selected(($filters['sifat_surat_id'] ?? null) == $sifat->id)>{{ $sifat->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-arrow-right pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-800" aria-hidden="true"></i>
                    </div>

                    <button type="submit" class="h-[42px] w-full rounded-md bg-blue-600 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Filter
                    </button>

                    <div class="relative w-full">
                        <select name="filter" aria-label="Kategori"
                            class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="" @selected($filter === 'semua')>Kategori</option>
                            <option value="arsip" @selected($filter === 'arsip')>Arsip</option>
                            <option value="peminjaman" @selected($filter === 'peminjaman')>Peminjaman</option>
                        </select>
                        <i class="fa-solid fa-arrow-right pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-800" aria-hidden="true"></i>
                    </div>

                    <div class="relative w-full">
                        <input type="date" name="tanggal_dokumen" value="{{ $filters['tanggal_dokumen'] ?? '' }}" aria-label="Tanggal Dokumen"
                            class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    </div>

                    <div class="relative w-full md:col-span-2" data-klasifikasi-root="history-klasifikasi">
                        <input type="hidden" name="klasifikasi_arsip_id" value="{{ $filters['klasifikasi_arsip_id'] ?? '' }}" data-klasifikasi-value>
                        <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi ? ($selectedKlasifikasi->kode . ' — ' . ($selectedKlasifikasi->judul ?? '')) : 'Kode Klasifikasi' }}</span>
                            <i class="fa-solid fa-arrow-right text-sm text-gray-800 transition-transform" aria-hidden="true"></i>
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

                    <a href="{{ route('pokja.history') }}" class="flex h-[42px] w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 text-sm font-semibold text-gray-700 shadow-sm transition hover:border-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        Reset
                    </a>
                </div>
            </form>

            <div class="mb-8 overflow-x-auto rounded-xl border border-gray-300 bg-white/40">
                <table class="min-w-[900px] w-full border-collapse text-left">
                    <thead class="border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-6 text-sm font-semibold text-slate-600">Nama Arsip</th>
                            <th class="py-3 px-6 text-sm font-semibold text-slate-600">Nomor Dokumen</th>
                            <th class="py-3 px-6 text-sm font-semibold text-slate-600">Jenis Surat</th>
                            <th class="py-3 px-6 text-sm font-semibold text-slate-600">Sifat Surat</th>
                            <th class="py-3 px-6 text-sm font-semibold text-slate-600">Kode Klasifikasi</th>
                            <th class="py-3 px-6 text-sm font-semibold text-slate-600">Tanggal Upload</th>
                            <th class="py-3 px-6 text-sm font-semibold text-slate-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($history as $item)
                            <tr class="transition hover:bg-white/70">
                                <td class="px-5 py-3 text-sm font-semibold text-gray-600">{{ $item->nama ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm font-semibold text-gray-600">{{ $item->nomor_dokumen ?: '-' }}</td>
                                <td class="px-5 py-3 text-sm font-semibold text-gray-600">{{ $item->jenis_surat ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm font-semibold text-gray-600">{{ $item->sifat_surat ?? '-' }}</td>
                                <td class="px-5 py-3 text-sm font-semibold text-gray-600">{{ $item->kode_klasifikasi ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-3 text-sm font-semibold text-gray-600">{{ $item->tanggal_upload ? \Illuminate\Support\Carbon::parse($item->tanggal_upload)->format('d/m/Y') : '-' }}</td>
                                <td class="px-5 py-3">
                                    @if ($item->file_path)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->file_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-md bg-blue-600 px-5 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                    @else
                                        <span class="text-xs text-gray-400">File tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                    {{ $hasHistoryFilters ? 'Tidak ada riwayat yang sesuai dengan filter.' : 'Belum ada riwayat arsip.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                <p class="mb-3 text-sm text-gray-500">
                    Menampilkan {{ $history->firstItem() ?? 0 }}-{{ $history->lastItem() ?? 0 }} dari {{ $history->total() }} data
                </p>
                @if ($history->hasPages())
                    <nav class="flex items-center justify-center gap-2" aria-label="Pagination">
                        @if ($history->previousPageUrl())
                            <a href="{{ $history->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-left"></i></a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center text-gray-400" aria-hidden="true"><i class="fa-solid fa-arrow-left"></i></span>
                        @endif
                        @foreach ($history->getUrlRange(max(1, $history->currentPage() - 2), min($history->lastPage(), $history->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}" @class(['flex h-9 min-w-9 items-center justify-center rounded-md px-2 text-sm font-semibold transition', 'bg-blue-600 text-white' => $page === $history->currentPage(), 'text-gray-800 hover:bg-gray-200' => $page !== $history->currentPage()]) @if ($page === $history->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                        @endforeach
                        @if ($history->nextPageUrl())
                            <a href="{{ $history->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-right"></i></a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center text-gray-400" aria-hidden="true"><i class="fa-solid fa-arrow-right"></i></span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
    </main>

    <!-- SCRIPT ANIMASI MENU -->
    <script>
        function toggleMenuArsip() {
            const submenu = document.getElementById('submenu-arsip');
            const ikonPanah = document.getElementById('ikon-panah');
            
            if (submenu.classList.contains('max-h-0')) {
                // Buka menu
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                // Putar ikon panah ke bawah (90 derajat dari posisi kanan)
                ikonPanah.classList.add('rotate-90');
            } else {
                // Tutup menu
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                // Kembalikan ikon panah ke kanan
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

        function closeKlasifikasiModal(restoreFocus = false) {
            if (!klasifikasiModal || !klasifikasiTrigger) return;
            klasifikasiModal.classList.add('hidden');
            klasifikasiTrigger.setAttribute('aria-expanded', 'false');
            if (restoreFocus) klasifikasiTrigger.focus();
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

        klasifikasiClose?.addEventListener('click', () => closeKlasifikasiModal(true));

        function applyKlasifikasiFilter() {
            const query = (klasifikasiSearch?.value || '').toLowerCase().trim();
            let visible = 0;

            klasifikasiRoot?.querySelectorAll('.klasifikasi-option').forEach((option) => {
                const matches = (option.dataset.search || '').toLowerCase().includes(query);
                option.classList.toggle('hidden', !matches);
                if (matches) visible++;
            });

            klasifikasiEmpty?.classList.toggle('hidden', visible > 0);
        }

        klasifikasiSearch?.addEventListener('input', applyKlasifikasiFilter);
        klasifikasiSearchButton?.addEventListener('click', applyKlasifikasiFilter);

        klasifikasiReset?.addEventListener('click', function () {
            if (!klasifikasiSearch || !klasifikasiValue || !klasifikasiLabel) return;
            klasifikasiSearch.value = '';
            klasifikasiValue.value = '';
            klasifikasiLabel.textContent = 'Kode Klasifikasi';
            klasifikasiRoot.querySelectorAll('.klasifikasi-option').forEach((option) => option.classList.remove('hidden'));
            klasifikasiRoot.querySelectorAll('input[name="filter-klasifikasi-picker"]').forEach((radio) => (radio.checked = false));
            klasifikasiEmpty?.classList.add('hidden');
            klasifikasiSearch.focus();
        });

        klasifikasiRoot?.querySelectorAll('input[name="filter-klasifikasi-picker"]').forEach((radio) => {
            radio.addEventListener('change', function () {
                if (!klasifikasiValue || !klasifikasiLabel) return;
                klasifikasiValue.value = this.value;
                klasifikasiLabel.textContent = this.dataset.label || 'Kode Klasifikasi';
                closeKlasifikasiModal(true);
            });
        });

        document.addEventListener('click', function (event) {
            if (klasifikasiRoot && klasifikasiModal && !klasifikasiRoot.contains(event.target)) {
                closeKlasifikasiModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && klasifikasiModal && !klasifikasiModal.classList.contains('hidden')) {
                closeKlasifikasiModal(true);
            }
        });
    </script>
    <x-logout-confirmation />
</body>
</html>
