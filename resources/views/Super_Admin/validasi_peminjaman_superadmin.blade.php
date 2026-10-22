<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Usulan Upload - Super Admin</title>
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
<body class="flex bg-[#f4f7f6] font-sans text-gray-800">
    @include('Super_Admin.partials.sidebar_superadmin')
    <main id="superadmin-main" class="flex min-h-screen flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 bg-[#f4f7f6] px-5 py-5 sm:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button id="sidebar-toggle" type="button" aria-label="Ciutkan atau buka sidebar" aria-expanded="true" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
                <h1 class="truncate text-xl font-bold text-gray-900 sm:text-2xl">Validasi Usulan Upload</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-900">Super Admin</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <div class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8">
            @if (session('success'))
                <div data-flash-success="{{ session('success') }}" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 shadow-sm">
                    <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm">{{ $errors->first() }}</div>
            @endif

            <form method="GET" action="{{ route('superadmin.peminjaman.validasi') }}" class="mb-5 space-y-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Nama Surat" aria-label="Nama Surat" class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <div class="relative w-full">
                        <select name="kategori_id" aria-label="Jenis Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="">Jenis Surat</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" @selected((string) $kategoriId === (string) $kategori->id)>{{ $kategori->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500"></i>
                    </div>
                    <div class="relative w-full">
                        <select name="sifat_surat_id" aria-label="Sifat Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="">Sifat Surat</option>
                            @foreach ($sifatSurats as $sifatSurat)
                                <option value="{{ $sifatSurat->id }}" @selected((string) $sifatSuratId === (string) $sifatSurat->id)>{{ $sifatSurat->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500"></i>
                    </div>
                    <button type="submit" class="h-[42px] rounded-md bg-blue-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Filter</button>
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="relative w-full">
                        <select name="pokja_id" aria-label="Kelompok Kerja" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="">Kelompok Kerja</option>
                            @foreach ($pokjas as $pokja)
                                <option value="{{ $pokja->id }}" @selected((string) $pokjaId === (string) $pokja->id)>{{ $pokja->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500"></i>
                    </div>
                    <div class="relative w-full">
                        <input type="date" name="tanggal_dokumen" value="{{ $tanggalDokumen }}" aria-label="Tanggal Dokumen" class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <i class="fa-regular fa-calendar-days pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500"></i>
                    </div>
                    @php
                        $selectedKlasifikasi = $klasifikasis->first(
                            fn ($item) => (string) $item->id === (string) ($klasifikasiId ?? '')
                        );
                    @endphp
                    <div class="relative w-full" data-klasifikasi-root>
                        <input type="hidden" name="klasifikasi_arsip_id" value="{{ $klasifikasiId ?? '' }}" data-klasifikasi-value>
                        <button type="button" data-klasifikasi-trigger aria-expanded="false" aria-controls="klasifikasi-filter-modal" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi ? $selectedKlasifikasi->kode . ' - ' . ($selectedKlasifikasi->judul ?? $selectedKlasifikasi->uraian ?? '-') : 'Kode Klasifikasi' }}</span>
                            <i class="fa-solid fa-chevron-down ml-3 shrink-0 text-xs text-gray-500" aria-hidden="true"></i>
                        </button>
                        <div id="klasifikasi-filter-modal" class="fixed inset-0 z-[100] hidden p-4 sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                            <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                            <section class="relative mx-auto flex max-h-[88vh] w-full max-w-[1100px] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)]">
                                <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        <input type="search" data-klasifikasi-search placeholder="Cari Kode Klasifikasi" aria-label="Cari Kode Klasifikasi" class="h-[42px] w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                        <button type="button" data-klasifikasi-clear class="h-[42px] shrink-0 rounded-md border border-slate-300 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Hapus Pilihan</button>
                                    </div>
                                </div>
                                <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-6 pt-4 sm:px-6">
                                    @foreach ($klasifikasis->groupBy(fn ($item) => ($item->bab_urutan ?? 999) . '|' . trim((string) ($item->bab ?? ''))) as $group)
                                        @php $first = $group->first(); @endphp
                                        <div class="mb-6">
                                            <h3 class="mb-3 text-sm font-bold text-slate-700">{{ $first->bab ?? 'Kode Klasifikasi' }}</h3>
                                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                                @foreach ($group as $klasifikasi)
                                                    @php
                                                        $klasifikasiLabel = $klasifikasi->kode . ' - ' . ($klasifikasi->judul ?? $klasifikasi->uraian ?? '-');
                                                    @endphp
                                                    <button type="button" data-klasifikasi-option data-value="{{ $klasifikasi->id }}" data-label="{{ $klasifikasiLabel }}" data-search="{{ strtolower($klasifikasi->kode . ' ' . ($klasifikasi->judul ?? '') . ' ' . ($klasifikasi->uraian ?? '')) }}" class="rounded-lg border border-slate-200 bg-white p-3 text-left text-sm text-slate-700 transition hover:border-blue-300 hover:bg-blue-50">
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
                    <a href="{{ route('superadmin.peminjaman.validasi') }}" class="flex h-[42px] items-center justify-center rounded-md border border-blue-600 bg-white px-6 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">Reset</a>
                </div>
            </form>

            <p class="mb-4 text-sm text-slate-600" role="status" aria-live="polite">
                {{ $pengajuan->total() > 0 ? 'Ditemukan ' . $pengajuan->total() . ' usulan menunggu validasi.' : 'Tidak ditemukan usulan yang sesuai.' }}
            </p>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1000px] border-collapse text-left">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Nama Arsip</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Jenis Surat</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Sifat Surat</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Kelompok Kerja</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Kode Klasifikasi</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Tanggal Mulai</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Tanggal Selesai</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Status</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @forelse ($pengajuan as $item)
                                @php
                                    $filePath = $item->file_pengajuan ?: $item->arsip?->file_path;
                                    $namaArsip = $item->arsip?->nama_arsip ?? $item->nama_surat ?? '-';
                                    $jenisSurat = $item->kategori?->name ?? $item->arsip?->kategori?->name;
                                    $sifatSurat = $item->sifatSurat?->name ?? $item->arsip?->sifatSurat?->name;
                                    $kelompokKerja = $item->user?->pokja?->name ?? $item->arsip?->pokja?->name;
                                    $kodeKlasifikasi = $item->klasifikasiArsip?->kode ?? $item->arsip?->klasifikasiArsip?->kode;
                                @endphp
                                <tr class="transition hover:bg-gray-50">
                                    <td class="px-5 py-4 font-semibold text-gray-700">{{ $namaArsip }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $jenisSurat ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $sifatSurat ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $kelompokKerja ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $kodeKlasifikasi ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $item->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $item->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 font-semibold text-blue-700"><span class="h-2 w-2 rounded-full bg-blue-500"></span>Menunggu Validasi</span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            @if ($filePath)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($filePath) }}" target="_blank" rel="noopener noreferrer" class="rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                            @else
                                                <span class="text-xs text-gray-400">File tidak tersedia</span>
                                            @endif
                                            <form method="POST" action="{{ route('superadmin.peminjaman.decision', $item) }}" data-confirm-title="Apakah Anda yakin ingin menolak usulan ini?" data-confirm-button="Ya, Tolak" data-confirm-color="#dc2626">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="decision" value="reject">
                                                <button type="submit" class="rounded-md bg-red-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">Tolak</button>
                                            </form>
                                            <form method="POST" action="{{ route('superadmin.peminjaman.decision', $item) }}" data-confirm-title="Apakah Anda yakin ingin menerima usulan ini?" data-confirm-button="Ya, Terima" data-confirm-color="#16a34a">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="decision" value="approve">
                                                <button type="submit" class="rounded-md bg-green-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-green-700">Terima</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada usulan yang menunggu validasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-6 flex flex-col items-center gap-3">
                <p class="text-sm text-gray-500">Menampilkan {{ $pengajuan->firstItem() ?? 0 }}-{{ $pengajuan->lastItem() ?? 0 }} dari {{ $pengajuan->total() }} usulan</p>
                @if ($pengajuan->hasPages())
                    <nav class="flex items-center justify-center gap-2" aria-label="Pagination">
                        @if ($pengajuan->previousPageUrl())
                            <a href="{{ $pengajuan->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-left"></i></a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center text-gray-400"><i class="fa-solid fa-arrow-left"></i></span>
                        @endif
                        @foreach ($pengajuan->getUrlRange(max(1, $pengajuan->currentPage() - 2), min($pengajuan->lastPage(), $pengajuan->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}" @class(['flex h-9 min-w-9 items-center justify-center rounded-md px-2 text-sm font-semibold transition', 'bg-blue-600 text-white' => $page === $pengajuan->currentPage(), 'text-gray-800 hover:bg-gray-200' => $page !== $pengajuan->currentPage()]) @if ($page === $pengajuan->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                        @endforeach
                        @if ($pengajuan->nextPageUrl())
                            <a href="{{ $pengajuan->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-right"></i></a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center text-gray-400"><i class="fa-solid fa-arrow-right"></i></span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const pageBody = document.body;
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const resizeHandle = document.getElementById('sidebar-resize-handle');
        const desktopQuery = window.matchMedia('(min-width: 768px)');

        const setSidebarWidth = (width) => {
            const boundedWidth = Math.min(380, Math.max(220, width));
            document.documentElement.style.setProperty('--sidebar-width', `${boundedWidth}px`);
            localStorage.setItem('superadmin-sidebar-width', String(boundedWidth));
        };

        const setCollapsed = (collapsed) => {
            pageBody.classList.toggle('sidebar-collapsed', collapsed);
            sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
            localStorage.setItem('superadmin-sidebar-collapsed', String(collapsed));
        };

        if (desktopQuery.matches) {
            const savedWidth = Number(localStorage.getItem('superadmin-sidebar-width'));
            const savedCollapsed = localStorage.getItem('superadmin-sidebar-collapsed') === 'true';
            if (savedWidth >= 220 && savedWidth <= 380) {
                document.documentElement.style.setProperty('--sidebar-width', `${savedWidth}px`);
            }
            setCollapsed(savedCollapsed);
        }

        sidebarToggle.addEventListener('click', () => {
            if (desktopQuery.matches) {
                setCollapsed(!pageBody.classList.contains('sidebar-collapsed'));
                return;
            }

            pageBody.classList.add('sidebar-mobile-open');
            sidebarBackdrop.classList.remove('hidden');
            sidebarToggle.setAttribute('aria-expanded', 'true');
        });

        const closeMobileSidebar = () => {
            pageBody.classList.remove('sidebar-mobile-open');
            sidebarBackdrop.classList.add('hidden');
            sidebarToggle.setAttribute('aria-expanded', 'false');
        };
        sidebarBackdrop.addEventListener('click', closeMobileSidebar);
        window.addEventListener('resize', () => {
            if (desktopQuery.matches) {
                closeMobileSidebar();
                const savedWidth = Number(localStorage.getItem('superadmin-sidebar-width'));
                if (savedWidth >= 220 && savedWidth <= 380) {
                    document.documentElement.style.setProperty('--sidebar-width', `${savedWidth}px`);
                }
                const savedCollapsed = localStorage.getItem('superadmin-sidebar-collapsed') === 'true';
                pageBody.classList.toggle('sidebar-collapsed', savedCollapsed);
            } else {
                pageBody.classList.remove('sidebar-collapsed');
                closeMobileSidebar();
            }
        });

        resizeHandle.addEventListener('pointerdown', (event) => {
            if (!desktopQuery.matches || pageBody.classList.contains('sidebar-collapsed')) {
                return;
            }
            event.preventDefault();
            resizeHandle.setPointerCapture(event.pointerId);
            const updateWidth = (pointerEvent) => setSidebarWidth(pointerEvent.clientX);
            const stopResize = () => {
                resizeHandle.removeEventListener('pointermove', updateWidth);
                resizeHandle.removeEventListener('pointerup', stopResize);
                resizeHandle.removeEventListener('pointercancel', stopResize);
            };
            resizeHandle.addEventListener('pointermove', updateWidth);
            resizeHandle.addEventListener('pointerup', stopResize);
            resizeHandle.addEventListener('pointercancel', stopResize);
        });

        function toggleSidebarMenu(submenuId, iconId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(iconId);
            const button = icon.closest('button');

            if (submenu.classList.contains('max-h-0')) {
                submenu.classList.remove('max-h-0');
                submenu.classList.add('max-h-96');
                icon.classList.add('rotate-90');
                button.setAttribute('aria-expanded', 'true');
            } else {
                submenu.classList.add('max-h-0');
                submenu.classList.remove('max-h-96');
                icon.classList.remove('rotate-90');
                button.setAttribute('aria-expanded', 'false');
            }
        }

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

        document.querySelectorAll('form[data-confirm-title]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                Swal.fire({
                    title: form.dataset.confirmTitle,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: form.dataset.confirmButton,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: form.dataset.confirmColor,
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        @if (session('success'))
            Swal.fire({ icon: 'success', title: @json(session('success')), confirmButtonColor: '#2563eb' });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: @json(session('error')), confirmButtonColor: '#2563eb' });
        @endif
    </script>
    <x-logout-confirmation />
</body>
</html>
