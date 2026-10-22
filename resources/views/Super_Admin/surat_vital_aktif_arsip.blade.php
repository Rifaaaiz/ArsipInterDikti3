<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Vital Aktif - Super Admin</title>
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
<body class="flex min-h-screen bg-[#f4f7f9] font-sans text-gray-800">
    @include('Super_Admin.partials.sidebar_superadmin')
    <main id="superadmin-main" class="flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 bg-[#f4f7f9] px-5 py-5 sm:px-8">
            <button id="sidebar-toggle" data-sidebar-toggle type="button" aria-label="Ciutkan atau buka sidebar" aria-expanded="true" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-900">{{ $user?->name ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <section class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8">
            @if (session('success'))
                <div role="status" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('superadmin.dashboard') }}" aria-label="Kembali ke Dashboard" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 transition hover:border-blue-500 hover:text-blue-600">
                        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                    </a>
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Surat Vital Aktif</h1>
                </div>
                <a href="{{ route('superadmin.arsip-vital.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                    <i class="fa-solid fa-plus" aria-hidden="true"></i> Tambah Arsip
                </a>
            </div>

            @php
                $selectedKlasifikasi = $klasifikasis->first(
                    fn ($item) => (string) $item->id === (string) ($filters['klasifikasiId'] ?? '')
                );
            @endphp
            <form method="GET" action="{{ route('superadmin.arsip-vital.aktif') }}" class="mb-6 grid min-w-0 grid-cols-1 gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-5" data-klasifikasi-root>
                <input type="search" name="nama_arsip" value="{{ $filters['nama_arsip'] }}" placeholder="Nama Surat" aria-label="Nama Surat" class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                <input type="search" name="nomor_dokumen" value="{{ $filters['nomor_dokumen'] }}" placeholder="Nomor Dokumen" aria-label="Nomor Dokumen" class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                <div class="relative w-full">
                    <select name="jenis_surat_id" aria-label="Jenis Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Jenis Surat</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) $filters['jenisSuratId'] === (string) $kategori->id)>{{ $kategori->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500"></i>
                </div>
                <div class="relative w-full">
                    <select name="sifat_surat_id" aria-label="Sifat Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Sifat Surat</option>
                        @foreach ($sifatSurats as $sifatSurat)
                            <option value="{{ $sifatSurat->id }}" @selected((string) $filters['sifatSuratId'] === (string) $sifatSurat->id)>{{ $sifatSurat->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500"></i>
                </div>
                <button type="submit" class="h-[42px] rounded-md bg-blue-600 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Filter</button>

                <div class="relative w-full">
                    <select name="kategori" aria-label="Kategori" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Kategori</option>
                        <option value="konvensional" @selected($filters['kategori'] === 'konvensional')>Konvensional</option>
                        <option value="digital" @selected($filters['kategori'] === 'digital')>Digital</option>
                    </select>
                    <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500"></i>
                </div>
                <input type="date" name="tanggal_upload" value="{{ $filters['tanggalUpload'] }}" aria-label="Tanggal Upload" class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                <div class="relative w-full lg:col-span-2">
                    <input type="hidden" name="klasifikasi_arsip_id" value="{{ $filters['klasifikasiId'] ?? '' }}" data-klasifikasi-value>
                    <button type="button" data-klasifikasi-trigger aria-expanded="false" aria-controls="vital-klasifikasi-modal" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi ? $selectedKlasifikasi->kode . ' - ' . ($selectedKlasifikasi->judul ?? $selectedKlasifikasi->uraian ?? '-') : 'Kode Klasifikasi' }}</span>
                        <i class="fa-solid fa-chevron-down ml-3 shrink-0 text-xs text-gray-500" aria-hidden="true"></i>
                    </button>
                    <div id="vital-klasifikasi-modal" class="fixed inset-0 z-[100] hidden p-4 sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
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
                <a href="{{ route('superadmin.arsip-vital.aktif') }}" class="flex h-[42px] items-center justify-center rounded-md border border-blue-600 bg-white px-5 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">Reset</a>
            </form>

            <div class="mb-3 text-sm text-gray-500">
                {{ $arsipVitals->total() }} arsip vital aktif
            </div>
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] border-collapse text-left">
                        <thead class="bg-[#eef0f8]">
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Nama Arsip</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Nomor Dokumen</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Jenis Surat</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Sifat Surat</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Kode Klasifikasi</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Tanggal Upload</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-indigo-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($arsipVitals as $arsipVital)
                                @php $arsip = $arsipVital->arsip; @endphp
                                <tr class="text-xs text-gray-600 transition hover:bg-gray-50">
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $arsip->nama_arsip }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">{{ $arsip->nomor_dokumen ?: '-' }}</td>
                                    <td class="px-4 py-3">{{ $arsip->kategori?->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $arsip->sifatSurat?->name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $arsip->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="inline-flex items-center gap-1.5 font-semibold text-green-700">
                                            <span class="h-2 w-2 rounded-full bg-green-500"></span>{{ ucfirst($arsipVital->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <div class="flex items-center justify-center gap-1.5">
                                            @if ($arsip->file_path)
                                                <a href="{{ route('superadmin.arsip-vital.show', $arsipVital) }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                            @else
                                                <span class="text-xs text-gray-400">File tidak tersedia</span>
                                            @endif
                                            <a href="{{ route('superadmin.arsip.edit', ['arsip' => $arsip, 'return_to' => 'arsip-vital', 'return_query' => http_build_query(request()->query())]) }}" class="inline-flex rounded-md bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                                        {{ collect($filters)->filter(fn ($value) => $value !== '')->isNotEmpty() ? 'Tidak ada arsip vital aktif yang sesuai dengan filter.' : 'Belum ada arsip vital aktif.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">{{ $arsipVitals->links() }}</div>
        </section>
    </main>
<script>
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

        modal.querySelectorAll('[data-klasifikasi-close]').forEach((button) => button.addEventListener('click', close));
        search.addEventListener('input', filterOptions);
        clear.addEventListener('click', () => {
            input.value = '';
            label.textContent = 'Kode Klasifikasi';
            close();
        });
        options.forEach((option) => {
            option.addEventListener('click', () => {
                input.value = option.dataset.value || '';
                label.textContent = option.dataset.label || 'Kode Klasifikasi';
                close();
            });
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
        });
    })();
</script>
</body>
</html>