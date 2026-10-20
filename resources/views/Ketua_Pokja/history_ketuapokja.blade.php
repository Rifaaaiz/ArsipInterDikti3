<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Arsip Ketua Pokja - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] flex font-sans text-gray-800">

    <!-- SIDEBAR KIRI -->
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0">
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
                <a href="/ketua-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>

                <a href="/ketua-pokja/validasi" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-circle-check"></i> Validasi Upload
                </a>

                <a href="/ketua-pokja/profil" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white transition">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- Menu Aktif (Biru Solid) untuk History -->
                <a href="/ketua-pokja/history" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-2">
                    <i class="fa-regular fa-clock"></i> History
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

    <main class="ml-64 flex min-h-screen flex-1 flex-col bg-[#f4f4f5]">
        <header class="sticky top-0 z-10 flex items-center justify-end bg-[#f4f4f5] px-5 py-6 sm:px-8">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">Ketua Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <div class="w-full max-w-7xl px-5 pb-10 sm:px-8">
            <h2 class="mb-6 text-3xl font-bold text-gray-900">History Arsip Pokja</h2>

            @php
                $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) (($filters['klasifikasi_arsip_id'] ?? '') ?: ''));
            @endphp
            <form method="GET" action="{{ route('ketua.history') }}" class="mb-8 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <input type="search" name="nama_surat" value="{{ $filters['nama_surat'] ?? '' }}" placeholder="Nama Surat" aria-label="Nama Surat"
                        class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">

                    <div class="relative w-full">
                        <select name="jenis_surat_id" aria-label="Jenis Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="">Jenis Surat</option>
                            @foreach ($jenisSurats as $jenis)
                                <option value="{{ $jenis->id }}" @selected(($filters['jenis_surat_id'] ?? null) == $jenis->id)>{{ $jenis->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500" aria-hidden="true"></i>
                    </div>

                    <div class="relative w-full">
                        <select name="sifat_surat_id" aria-label="Sifat Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="">Sifat Surat</option>
                            @foreach ($sifatSurats as $sifat)
                                <option value="{{ $sifat->id }}" @selected(($filters['sifat_surat_id'] ?? null) == $sifat->id)>{{ $sifat->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500" aria-hidden="true"></i>
                    </div>

                    <button type="submit" class="h-[42px] w-full rounded-md bg-blue-600 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Filter</button>
                </div>

                <div class="mt-3 grid grid-cols-1 gap-3 md:grid-cols-4">
                    <div class="relative w-full">
                        <select name="filter" aria-label="Kategori Riwayat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="semua" @selected($filter === 'semua')>Kategori: Semua</option>
                            <option value="arsip" @selected($filter === 'arsip')>Arsip</option>
                            <option value="peminjaman" @selected($filter === 'peminjaman')>Peminjaman</option>
                        </select>
                        <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500" aria-hidden="true"></i>
                    </div>

                    <div class="relative w-full">
                        <input type="date" name="tanggal_dokumen" value="{{ $filters['tanggal_dokumen'] ?? '' }}" aria-label="Tanggal Dokumen"
                            class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    </div>

                    <div class="relative w-full" data-klasifikasi-root="ketua-history-klasifikasi">
                        <input type="hidden" name="klasifikasi_arsip_id" value="{{ $filters['klasifikasi_arsip_id'] ?? '' }}" data-klasifikasi-value>
                        <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi ? ($selectedKlasifikasi->kode . ' — ' . $selectedKlasifikasi->judul) : 'Kode Klasifikasi' }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-500 transition-transform" aria-hidden="true"></i>
                        </button>

                        <div class="fixed inset-0 z-[100] hidden p-4 opacity-0 transition-opacity duration-200 ease-out sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                            <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                            <section class="relative mx-auto flex max-h-[88vh] w-full max-w-[1100px] translate-y-2 scale-[0.98] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)] transition duration-200 ease-out">
                                <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <input type="search" data-klasifikasi-search placeholder="Cari Kode Klasifikasi" class="h-[42px] w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <button type="button" data-klasifikasi-search-button class="h-[42px] rounded-md bg-blue-600 px-5 text-sm font-semibold text-white hover:bg-blue-700 sm:w-[110px]">Cari</button>
                                        <button type="button" data-klasifikasi-reset class="h-[42px] rounded-md border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 hover:bg-slate-50 sm:w-[110px]">Reset Pilihan</button>
                                    </div>
                                </div>
                                <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-6 pt-4 sm:px-6">
                                    <div class="grid gap-3 md:grid-cols-2">
                                        @foreach ($klasifikasis as $klasifikasi)
                                            <label class="klasifikasi-option flex cursor-pointer gap-3 rounded-lg border border-slate-200 p-3 text-sm transition hover:border-blue-300 hover:bg-blue-50" data-search="{{ strtolower($klasifikasi->kode . ' ' . $klasifikasi->judul . ' ' . $klasifikasi->uraian) }}">
                                                <input type="radio" name="filter-klasifikasi-picker" value="{{ $klasifikasi->id }}" data-label="{{ $klasifikasi->kode }} — {{ $klasifikasi->judul }}" class="mt-1 accent-blue-600" @checked(($filters['klasifikasi_arsip_id'] ?? null) == $klasifikasi->id)>
                                                <span class="flex-1"><b class="text-slate-900">{{ $klasifikasi->kode }}</b><br><span class="text-slate-600">{{ $klasifikasi->judul }}</span>
                                                    @if ($klasifikasi->uraian)<br><small class="text-slate-500">{{ $klasifikasi->uraian }}</small>@endif
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="hidden py-8 text-center text-sm text-slate-500" data-klasifikasi-empty>Kode klasifikasi tidak ditemukan.</p>
                                </div>
                            </section>
                        </div>
                    </div>

                    <a href="{{ route('ketua.history') }}" class="flex h-[42px] w-full items-center justify-center rounded-md border border-blue-600 bg-white text-sm font-semibold text-blue-700 transition hover:bg-blue-50">Reset</a>
                </div>
            </form>

            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                <table class="min-w-[900px] w-full border-collapse text-left">
                    <thead class="border-b border-gray-200 bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700">Nama Arsip</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700">Jenis Surat</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700">Sifat Surat</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700">Kode Klasifikasi</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700">Tanggal Riwayat</th>
                            <th class="px-6 py-4 text-sm font-bold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($history as $item)
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-700">{{ $item->nama ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $item->jenis_surat ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $item->sifat_surat ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $item->kode_klasifikasi ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($item->file_path)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->file_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-md bg-blue-600 px-5 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                    @else
                                        <span class="text-xs text-gray-400">File tidak tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                    @if ($filter === 'arsip')
                                        Belum ada riwayat arsip yang sesuai.
                                    @elseif ($filter === 'peminjaman')
                                        Belum ada riwayat peminjaman yang sesuai.
                                    @else
                                        Belum ada riwayat arsip atau peminjaman yang sesuai.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5 flex flex-col items-center gap-3">
                <p class="text-sm text-gray-500">Menampilkan {{ $history->firstItem() ?? 0 }}-{{ $history->lastItem() ?? 0 }} dari {{ $history->total() }} data</p>
                @if ($history->hasPages())
                    <nav class="flex items-center justify-center gap-2" aria-label="Pagination">
                        @if ($history->previousPageUrl())
                            <a href="{{ $history->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-left"></i></a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center text-gray-400"><i class="fa-solid fa-arrow-left"></i></span>
                        @endif
                        @foreach ($history->getUrlRange(max(1, $history->currentPage() - 2), min($history->lastPage(), $history->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}" @class(['flex h-9 min-w-9 items-center justify-center rounded-md px-2 text-sm font-semibold transition', 'bg-blue-600 text-white' => $page === $history->currentPage(), 'text-gray-800 hover:bg-gray-200' => $page !== $history->currentPage()]) @if ($page === $history->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                        @endforeach
                        @if ($history->nextPageUrl())
                            <a href="{{ $history->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-right"></i></a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center text-gray-400"><i class="fa-solid fa-arrow-right"></i></span>
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
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-40');
                ikonPanah.classList.add('rotate-90');
            } else {
                submenu.classList.remove('max-h-40');
                submenu.classList.add('max-h-0');
                ikonPanah.classList.remove('rotate-90');
            }
        }

        document.querySelectorAll('[data-klasifikasi-root]').forEach((root) => {
            const trigger = root.querySelector('[data-klasifikasi-trigger]');
            const modal = root.querySelector('[data-klasifikasi-modal]');
            const panel = modal.querySelector('section');
            const hiddenValue = root.querySelector('[data-klasifikasi-value]');
            const label = root.querySelector('[data-klasifikasi-label]');
            const search = root.querySelector('[data-klasifikasi-search]');
            const options = Array.from(root.querySelectorAll('.klasifikasi-option'));
            const emptyMessage = root.querySelector('[data-klasifikasi-empty]');
            let closeTimer;

            const filterOptions = () => {
                const query = search.value.trim().toLowerCase();
                let visibleCount = 0;
                options.forEach((option) => {
                    const visible = option.dataset.search.includes(query);
                    option.classList.toggle('hidden', !visible);
                    visibleCount += visible ? 1 : 0;
                });
                emptyMessage.classList.toggle('hidden', visibleCount > 0);
            };

            const closeModal = () => {
                trigger.setAttribute('aria-expanded', 'false');
                trigger.querySelector('i').classList.remove('rotate-180');
                modal.classList.remove('opacity-100');
                modal.classList.add('opacity-0');
                panel.classList.add('translate-y-2', 'scale-[0.98]');
                panel.classList.remove('translate-y-0', 'scale-100');
                window.clearTimeout(closeTimer);
                closeTimer = window.setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
            };

            trigger.addEventListener('click', () => {
                window.clearTimeout(closeTimer);
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                trigger.setAttribute('aria-expanded', 'true');
                trigger.querySelector('i').classList.add('rotate-180');
                panel.classList.remove('translate-y-2', 'scale-[0.98]');
                panel.classList.add('translate-y-0', 'scale-100');
                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0');
                    modal.classList.add('opacity-100');
                });
                search.focus();
            });

            root.querySelectorAll('[data-klasifikasi-close]').forEach((button) => {
                button.addEventListener('click', closeModal);
            });
            root.querySelector('[data-klasifikasi-search-button]').addEventListener('click', filterOptions);
            search.addEventListener('input', filterOptions);
            root.querySelector('[data-klasifikasi-reset]').addEventListener('click', () => {
                root.querySelectorAll('input[name="filter-klasifikasi-picker"]').forEach((radio) => {
                    radio.checked = false;
                });
                hiddenValue.value = '';
                label.textContent = 'Kode Klasifikasi';
                search.value = '';
                filterOptions();
            });
            root.querySelectorAll('input[name="filter-klasifikasi-picker"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    hiddenValue.value = radio.value;
                    label.textContent = radio.dataset.label;
                    closeModal();
                });
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                    trigger.focus();
                }
            });
        });
    </script>
    <x-logout-confirmation />
</body>
</html>
