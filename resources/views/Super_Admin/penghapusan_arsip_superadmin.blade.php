<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penghapusan Arsip - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-width: 256px; }
        #superadmin-sidebar { width: var(--sidebar-width); transition: width 200ms ease-out, transform 200ms ease-out; }
        #superadmin-main { margin-left: var(--sidebar-width); transition: margin-left 200ms ease-out; }
        .sidebar-collapsed { --sidebar-width: 76px; }
        .sidebar-collapsed [data-sidebar-label] { display: none; }
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
<body class="bg-[#f4f7f9] flex font-sans text-gray-800">
    <aside id="superadmin-sidebar" class="fixed left-0 top-0 z-30 flex h-screen flex-col bg-[#0a1120] text-white">
        <div class="min-h-0 flex-1 overflow-y-auto">
            <div id="sidebar-logo" class="mb-2 flex items-center gap-3 px-6 py-6">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 data-sidebar-label class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>
            <nav class="space-y-1 px-4 pb-4">
                <a href="{{ route('superadmin.dashboard') }}" title="Dashboard" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-gray-200 transition hover:bg-gray-800 hover:text-white">
                    <svg class="h-4 w-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg><span data-sidebar-label>Dashboard</span>
                </a>
                <div class="pt-1">
                    <button id="sidebar-archive-toggle" type="button" aria-expanded="true" class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-medium text-gray-200 transition-colors hover:bg-gray-800 hover:text-white focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i><span data-sidebar-label>Kelola Arsip</span></span>
                        <i id="sidebar-archive-chevron" class="fa-solid fa-chevron-right rotate-90 text-xs transition-transform duration-300"></i>
                    </button>
                    <div id="sidebar-archive-submenu" class="max-h-40 overflow-hidden transition-all duration-300">
                        <div class="flex flex-col gap-1 pt-1 pb-2">
                            <a href="{{ route('superadmin.arsip.penghapusan') }}" aria-current="page" class="mx-2 block rounded-lg bg-blue-600 px-4 py-2.5 pl-8 text-sm font-semibold text-white shadow-sm">Penghapusan Arsip</a>
                            <a href="{{ route('superadmin.arsip.laporan') }}" class="block py-2 pl-12 text-sm font-medium text-gray-400 transition hover:text-white">Laporan Arsip</a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('superadmin.peminjaman.validasi') }}" title="Validasi Peminjaman" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-regular fa-circle-check w-5"></i><span data-sidebar-label>Validasi Peminjaman</span>
                </a>
                <a href="{{ route('superadmin.users') }}" title="Kelola User" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-solid fa-users w-5"></i><span data-sidebar-label>Kelola User</span>
                </a>
                <p data-sidebar-label class="px-4 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-gray-500">Tim Pokja</p>
                @foreach ($pokjas as $pokja)
                    <div>
                        <button type="button" onclick="togglePokjaMenu('submenu-pokja-{{ $pokja->id }}', 'ikon-pokja-{{ $pokja->id }}')" aria-expanded="false" class="flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-left text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                            <span class="flex min-w-0 items-center gap-3"><i class="fa-solid fa-people-group w-5 shrink-0"></i><span data-sidebar-label class="truncate">{{ $pokja->name }}</span></span>
                            <i id="ikon-pokja-{{ $pokja->id }}" class="fa-solid fa-chevron-right text-[10px] transition-transform duration-300"></i>
                        </button>
                        <div id="submenu-pokja-{{ $pokja->id }}" class="max-h-0 overflow-hidden transition-all duration-300">
                            <div class="flex flex-col gap-1 py-1">
                                <a href="{{ route('superadmin.surat-masuk', $pokja->slug) }}" class="block py-1.5 pl-12 text-xs font-medium text-gray-400 transition hover:text-white">Surat Masuk</a>
                                <a href="{{ route('superadmin.surat-keluar', $pokja->slug) }}" class="block py-1.5 pl-12 text-xs font-medium text-gray-400 transition hover:text-white">Surat Keluar</a>
                            </div>
                        </div>
                    </div>
                @endforeach
                <a href="#" title="Panduan Penggunaan" class="mt-3 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-regular fa-file-lines w-5"></i><span data-sidebar-label>Panduan Penggunaan</span>
                </a>
                <a href="#" title="Pengaturan Sistem" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-solid fa-gear w-5"></i><span data-sidebar-label>Pengaturan Sistem</span>
                </a>
            </nav>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-800 p-4">
            @csrf
            <button type="submit" title="Logout" class="flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-left text-sm font-semibold text-red-500 transition hover:bg-red-950 hover:text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket"></i><span data-sidebar-label>Logout</span>
            </button>
        </form>
        <button id="sidebar-resize-handle" type="button" aria-label="Ubah lebar sidebar" title="Tarik untuk mengubah lebar sidebar" class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize bg-transparent transition hover:bg-blue-500/60"></button>
    </aside>

    <div id="sidebar-backdrop" class="fixed inset-0 z-20 hidden bg-black/50 md:hidden"></div>
    <main id="superadmin-main" class="flex min-h-screen w-full min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between bg-[#f4f7f9] px-5 py-5 sm:px-8">
            <button id="sidebar-toggle" type="button" aria-label="Ciutkan atau buka sidebar" aria-expanded="true" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <div class="flex items-center gap-3">
                <span class="font-bold text-gray-900 text-sm">Super Admin</span>
                <i class="fa-solid fa-circle-user text-3xl text-gray-800"></i>
            </div>
        </header>

        <div class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif 
            
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
                </div>
            @endif

            <h2 class="text-3xl font-bold mb-6 text-black">Penghapusan Arsip</h2>
            
            <!-- CARD STATISTIK -->
            <div class="mb-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
                <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalArsip }}</p>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Dokumen</p>
                </div>
                <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip Kelompok Kerja</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalArsipPokja }}</p>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Dokumen</p>
                </div>
                <div class="min-w-0 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip TU</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalArsipTu }}</p>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Dokumen</p>
                </div>
            </div>

            <!-- PERINGATAN HAPUS ARSIP -->
            <div class="mb-6 w-full">
                <button id="delete-mode-toggle" type="button" aria-pressed="false" class="flex h-12 w-full items-center justify-center gap-2 rounded-lg border border-red-300 bg-white px-5 text-sm font-bold text-red-600 shadow-sm transition hover:bg-red-50">
                    <i class="fa-solid fa-trash" aria-hidden="true"></i><span>Hapus Arsip</span>
                </button>
            </div>

            <form method="GET" action="{{ route('superadmin.arsip.penghapusan') }}" class="mb-6 grid min-w-0 grid-cols-1 gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:grid-cols-2 xl:grid-cols-4">
                @if ($filters['source'] !== 'semua')
                    <input type="hidden" name="sumber" value="{{ $filters['source'] }}">
                @endif
                <input type="search" name="search" value="{{ $filters['search'] }}" placeholder="Nama Surat" aria-label="Nama Surat" class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 placeholder:text-gray-400 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                <div class="relative w-full">
                    <select name="kategori_id" aria-label="Jenis Surat" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Jenis Surat</option>
                        @foreach ($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" @selected((string) $filters['kategoriId'] === (string) $kategori->id)>{{ $kategori->name }}</option>
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
                <button type="submit" class="h-[42px] rounded-md bg-blue-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Filter</button>

                <div class="relative w-full">
                    <select name="pokja_id" aria-label="Kelompok Kerja" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        <option value="">Kelompok Kerja</option>
                        @foreach ($pokjas as $pokja)
                            <option value="{{ $pokja->id }}" @selected((string) $filters['pokjaId'] === (string) $pokja->id)>{{ $pokja->name }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500"></i>
                </div>
                <div class="relative w-full">
                    <input type="date" name="tanggal" value="{{ $filters['tanggal'] }}" aria-label="Tanggal Dokumen" class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    <i class="fa-regular fa-calendar pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-500"></i>
                </div>
                @php
                    $selectedKlasifikasi = $klasifikasis->first(
                        fn ($item) => (string) $item->id === (string) ($filters['klasifikasiId'] ?? '')
                    );
                @endphp
                <div class="relative w-full" data-klasifikasi-root>
                    <input type="hidden" name="klasifikasi_arsip_id" value="{{ $filters['klasifikasiId'] ?? '' }}" data-klasifikasi-value>
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
                <a href="{{ route('superadmin.arsip.penghapusan', $filters['source'] !== 'semua' ? ['sumber' => $filters['source']] : []) }}" class="flex h-[42px] items-center justify-center rounded-md border border-blue-600 bg-white text-sm font-semibold text-blue-700 transition hover:bg-blue-50">Reset</a>
            </form>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1100px] border-collapse text-left">
                        <thead class="border-b border-gray-200 bg-gray-50">
                            <tr>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Nama Arsip</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Jenis Surat</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Sifat Surat</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Kelompok Kerja</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Kode Klasifikasi</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Tanggal Upload</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Status</th>
                                <th class="px-5 py-4 text-sm font-bold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($arsips as $arsip)
                                @php
                                    $status = strtolower((string) ($arsip->status ?? ''));
                                    $isActive = $status === 'aktif';
                                @endphp
                                <tr class="text-sm transition hover:bg-gray-50">
                                    <td class="px-5 py-4 font-semibold text-gray-700">{{ $arsip->nama_arsip }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->kategori?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->sifatSurat?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->pokja?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 font-semibold {{ $isActive ? 'text-blue-600' : 'text-gray-600' }}">
                                            <span class="h-2 w-2 rounded-full {{ $isActive ? 'bg-blue-500' : 'bg-gray-400' }}"></span>{{ $isActive ? 'Aktif' : ucfirst(str_replace('_', ' ', $status ?: 'tidak diketahui')) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            @if ($arsip->file_path)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($arsip->file_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">
                                                    Lihat
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">Tidak tersedia</span>
                                            @endif
                                            <form method="POST" action="{{ route('superadmin.arsip.delete', $arsip) }}" class="delete-archive-form hidden">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md bg-red-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
                                        {{ $filters['search'] || $filters['tanggal'] || $filters['kategoriId'] || $filters['sifatSuratId'] || $filters['klasifikasiId'] || $filters['pokjaId'] || $filters['source'] !== 'semua' ? 'Tidak ada arsip yang sesuai dengan filter.' : 'Belum ada data arsip.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-6 flex flex-col items-center gap-3">
                <p class="text-sm text-gray-500">Menampilkan {{ $arsips->firstItem() ?? 0 }}-{{ $arsips->lastItem() ?? 0 }} dari {{ $arsips->total() }} arsip</p>
                @if ($arsips->hasPages())
                    <nav class="flex items-center justify-center gap-2" aria-label="Pagination">
                        @if ($arsips->previousPageUrl())
                            <a href="{{ $arsips->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></a>
                        @else
                            <span aria-disabled="true" class="flex h-9 w-9 items-center justify-center text-gray-400"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></span>
                        @endif
                        @foreach ($arsips->getUrlRange(max(1, $arsips->currentPage() - 2), min($arsips->lastPage(), $arsips->currentPage() + 2)) as $page => $url)
                            @if ($page === $arsips->currentPage())
                                <span aria-current="page" class="flex h-9 min-w-9 items-center justify-center rounded-md bg-blue-600 px-2 text-sm font-semibold text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="flex h-9 min-w-9 items-center justify-center rounded-md px-2 text-sm font-semibold text-gray-800 transition hover:bg-gray-200">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if ($arsips->nextPageUrl())
                            <a href="{{ $arsips->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
                        @else
                            <span aria-disabled="true" class="flex h-9 w-9 items-center justify-center text-gray-400"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                        @endif
                    </nav>
                @endif
            </div>
        </div>
    </main>
    <div id="delete-confirmation" class="fixed inset-0 z-[110] hidden items-center justify-center p-4" aria-hidden="true">
        <div data-delete-overlay class="absolute inset-0 bg-slate-950/50 opacity-0 transition-opacity duration-200"></div>
        <section data-delete-dialog class="relative w-full max-w-sm translate-y-2 scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-2xl transition duration-200" role="dialog" aria-modal="true" aria-labelledby="delete-confirmation-title">
            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-600"><i class="fa-solid fa-trash" aria-hidden="true"></i></div>
            <h2 id="delete-confirmation-title" class="text-lg font-bold text-slate-900">Konfirmasi Hapus Arsip</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Apakah Anda yakin ingin menghapus arsip ini?</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" data-delete-cancel class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button>
                <button type="button" data-delete-confirm class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">Ya, Hapus</button>
            </div>
        </section>
    </div>
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

        (() => {
            const toggle = document.getElementById('delete-mode-toggle');
            const modal = document.getElementById('delete-confirmation');
            const overlay = modal.querySelector('[data-delete-overlay]');
            const dialog = modal.querySelector('[data-delete-dialog]');
            const cancel = modal.querySelector('[data-delete-cancel]');
            const confirm = modal.querySelector('[data-delete-confirm]');
            const toggleLabel = toggle.querySelector('span');
            let selectedForm = null;

            const close = () => {
                overlay.classList.remove('opacity-100');
                dialog.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
                modal.setAttribute('aria-hidden', 'true');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
                selectedForm = null;
            };

            toggle.addEventListener('click', () => {
                const deleteModeEnabled = toggle.getAttribute('aria-pressed') !== 'true';
                toggle.setAttribute('aria-pressed', String(deleteModeEnabled));
                toggleLabel.textContent = deleteModeEnabled ? 'Batalkan Mode Hapus' : 'Hapus Arsip';
                document.querySelectorAll('.delete-archive-form').forEach((form) => {
                    form.classList.toggle('hidden', !deleteModeEnabled);
                });
            });

            document.querySelectorAll('.delete-archive-form').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    selectedForm = form;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    modal.setAttribute('aria-hidden', 'false');
                    requestAnimationFrame(() => {
                        overlay.classList.add('opacity-100');
                        dialog.classList.add('opacity-100', 'translate-y-0', 'scale-100');
                        cancel.focus();
                    });
                });
            });

            cancel.addEventListener('click', close);
            overlay.addEventListener('click', close);
            confirm.addEventListener('click', () => {
                if (selectedForm) {
                    selectedForm.submit();
                }
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                    close();
                }
            });
        })();

        (() => {
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

            const closeMobileSidebar = () => {
                pageBody.classList.remove('sidebar-mobile-open');
                sidebarBackdrop.classList.add('hidden');
                sidebarToggle.setAttribute('aria-expanded', 'false');
            };

            if (desktopQuery.matches) {
                const savedWidth = Number(localStorage.getItem('superadmin-sidebar-width'));
                if (savedWidth >= 220 && savedWidth <= 380) {
                    document.documentElement.style.setProperty('--sidebar-width', `${savedWidth}px`);
                }
                setCollapsed(localStorage.getItem('superadmin-sidebar-collapsed') === 'true');
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

            sidebarBackdrop.addEventListener('click', closeMobileSidebar);
            window.addEventListener('resize', () => {
                if (desktopQuery.matches) {
                    closeMobileSidebar();
                    const savedWidth = Number(localStorage.getItem('superadmin-sidebar-width'));
                    if (savedWidth >= 220 && savedWidth <= 380) {
                        document.documentElement.style.setProperty('--sidebar-width', `${savedWidth}px`);
                    }
                    pageBody.classList.toggle('sidebar-collapsed', localStorage.getItem('superadmin-sidebar-collapsed') === 'true');
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

            document.getElementById('sidebar-archive-toggle').addEventListener('click', (event) => {
                const button = event.currentTarget;
                const submenu = document.getElementById('sidebar-archive-submenu');
                const chevron = document.getElementById('sidebar-archive-chevron');
                const expanded = button.getAttribute('aria-expanded') === 'true';
                submenu.classList.toggle('max-h-0', expanded);
                submenu.classList.toggle('max-h-40', !expanded);
                chevron.classList.toggle('rotate-90', !expanded);
                button.setAttribute('aria-expanded', String(!expanded));
            });
        })();

        function togglePokjaMenu(submenuId, iconId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(iconId);
            const button = icon.closest('button');
            const expanded = button.getAttribute('aria-expanded') === 'true';
            submenu.classList.toggle('max-h-0', expanded);
            submenu.classList.toggle('max-h-96', !expanded);
            icon.classList.toggle('rotate-90', !expanded);
            button.setAttribute('aria-expanded', String(!expanded));
        }
    </script>
    <x-logout-confirmation />
</body>
</html>
