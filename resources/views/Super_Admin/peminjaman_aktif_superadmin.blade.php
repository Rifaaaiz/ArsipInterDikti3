<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Aktif - Super Admin</title>
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
        .sidebar-collapsed #superadmin-sidebar nav > div > button { justify-content: center; padding-left: .5rem; padding-right: .5rem; }
        .sidebar-collapsed #superadmin-sidebar nav > div > button > span { justify-content: center; }
        .sidebar-collapsed #superadmin-sidebar nav > div > button > i,
        .sidebar-collapsed #superadmin-sidebar nav > div > div { display: none; }
        .sidebar-collapsed #superadmin-sidebar form button { justify-content: center; }
        @media (max-width: 767px) {
            #superadmin-sidebar { width: 256px; transform: translateX(-100%); }
            #superadmin-main { margin-left: 0; }
            .sidebar-mobile-open #superadmin-sidebar { transform: translateX(0); }
            #sidebar-resize-handle { display: none; }
        }
    </style>
</head>
<body class="bg-[#f4f7f6] flex font-sans text-gray-800">

    <aside id="superadmin-sidebar" class="fixed left-0 top-0 z-30 flex h-screen flex-col bg-[#0a1120] text-white">
        <div class="min-h-0 flex-1 overflow-y-auto">
            <!-- Logo -->
            <div id="sidebar-logo" class="mb-2 flex items-center gap-3 px-6 py-6">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 data-sidebar-label class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>
            
            <!-- Menu Sidebar -->
            <nav class="space-y-1 px-4 pb-4">
                <a href="{{ route('superadmin.dashboard') }}" title="Dashboard" class="mt-1 flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg><span data-sidebar-label>Dashboard</span>
                </a>
                
                <!-- Dropdown Kelola Arsip -->
                <div class="pt-1">
                    <button type="button" onclick="toggleSidebarMenu('submenu-arsip', 'ikon-panah-arsip')" aria-expanded="true" class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-medium text-gray-200 transition-colors hover:bg-gray-800 hover:text-white focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i><span data-sidebar-label>Kelola Arsip</span></span>
                        <i id="ikon-panah-arsip" class="fa-solid fa-chevron-right rotate-90 text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <div id="submenu-arsip" class="max-h-40 overflow-hidden transition-all duration-300">
                        <div class="flex flex-col gap-1 pt-1 pb-2">
                            <a href="{{ route('superadmin.arsip.penghapusan') }}" class="block py-2 pl-12 text-sm font-medium text-gray-400 transition hover:text-white">Penghapusan Arsip</a>
                            <a href="{{ route('superadmin.arsip.laporan') }}" class="block py-2 pl-12 text-sm font-medium text-gray-400 transition hover:text-white">Laporan Arsip</a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('superadmin.peminjaman.validasi') }}" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-regular fa-circle-check w-5"></i><span data-sidebar-label>Validasi Peminjaman</span>
                </a>

                <a href="{{ route('superadmin.users') }}" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-solid fa-users w-5"></i><span data-sidebar-label>Kelola User</span>
                </a>

                <p data-sidebar-heading class="px-4 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-gray-500">Tim Pokja</p>
                @foreach ($pokjas as $pokja)
                    <div>
                        <button type="button" onclick="toggleSidebarMenu('submenu-pokja-{{ $pokja->id }}', 'ikon-panah-pokja-{{ $pokja->id }}')" aria-expanded="false" class="flex w-full items-center justify-between rounded-lg px-4 py-2.5 text-left text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                            <span class="flex min-w-0 items-center gap-3"><i class="fa-solid fa-people-group w-5 shrink-0"></i><span data-sidebar-label class="truncate">{{ $pokja->name }}</span></span>
                            <i id="ikon-panah-pokja-{{ $pokja->id }}" class="fa-solid fa-chevron-right text-[10px] transition-transform duration-300"></i>
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

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');" class="border-t border-gray-800 p-4">
            @csrf
            <button type="submit" title="Logout" class="flex w-full items-center gap-2 rounded-lg px-4 py-2.5 text-left text-sm font-semibold text-red-500 transition hover:bg-red-950 hover:text-red-400">
                <i class="fa-solid fa-arrow-right-from-bracket"></i><span data-sidebar-label>Logout</span>
            </button>
        </form>
        <button id="sidebar-resize-handle" type="button" aria-label="Ubah lebar sidebar" title="Tarik untuk mengubah lebar sidebar" class="absolute right-0 top-0 h-full w-1.5 cursor-col-resize bg-transparent transition hover:bg-blue-500/60"></button>
    </aside>

    <div id="sidebar-backdrop" class="fixed inset-0 z-20 hidden bg-black/50 md:hidden"></div>
    <main id="superadmin-main" class="flex min-h-screen flex-1 flex-col">
        
        <!-- Header Atas -->
        <header class="sticky top-0 z-10 flex items-center justify-between bg-[#f4f7f6] px-5 py-5 sm:px-8">
            <button id="sidebar-toggle" type="button" aria-label="Ciutkan atau buka sidebar" aria-expanded="true" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <div class="flex items-center gap-3">
                <span class="text-sm font-bold text-gray-900">{{ auth()->user()?->name ?? auth()->user()?->username ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <!-- KONTEN HALAMAN -->
        <div class="w-full max-w-[1600px] px-5 pb-10 sm:px-8">
            
            <!-- Judul & Tombol Back Sesuai Screenshot -->
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('superadmin.dashboard') }}" class="text-black hover:text-blue-600 text-2xl transition font-bold">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <h2 class="text-3xl font-extrabold text-black tracking-tight">Peminjaman Aktif</h2>
            </div>

            @if (session('success'))
                <div role="status" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div role="alert" class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @php
                $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) (($filters['klasifikasi_arsip_id'] ?? '') ?: ''));
            @endphp
            <form method="GET" action="{{ route('superadmin.peminjaman.aktif') }}" class="mb-8 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                    <input type="search" name="search" value="{{ $search }}" placeholder="Nama Surat" aria-label="Nama Surat"
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
                        <select name="pokja_id" aria-label="Kelompok Kerja" class="h-[42px] w-full appearance-none rounded-md border border-gray-300 bg-white px-3 pr-9 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <option value="">Kelompok Kerja</option>
                            @foreach ($pokjas as $pokja)
                                <option value="{{ $pokja->id }}" @selected(($filters['pokja_id'] ?? null) == $pokja->id)>{{ $pokja->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-down pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-500" aria-hidden="true"></i>
                    </div>

                    <div class="relative w-full">
                        <input type="date" name="tanggal_dokumen" value="{{ $filters['tanggal_dokumen'] ?? '' }}" aria-label="Tanggal Dokumen"
                            class="h-[42px] w-full rounded-md border border-gray-300 bg-white px-3 text-sm text-gray-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                    </div>

                    <div class="relative w-full" data-klasifikasi-root="superadmin-active-classification">
                        <input type="hidden" name="klasifikasi_arsip_id" value="{{ $filters['klasifikasi_arsip_id'] ?? '' }}" data-klasifikasi-value>
                        <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-300 bg-white px-3 text-left text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                            <span data-klasifikasi-label class="truncate">{{ $selectedKlasifikasi ? ($selectedKlasifikasi->kode . ' — ' . $selectedKlasifikasi->judul) : 'Kode Klasifikasi' }}</span>
                            <i class="fa-solid fa-chevron-down text-xs text-gray-500 transition-transform" aria-hidden="true"></i>
                        </button>
                        <div class="fixed inset-0 z-[100] hidden p-4 opacity-0 transition-opacity duration-200 ease-out sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                            <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih klasifikasi"></button>
                            <section class="relative mx-auto flex max-h-[88vh] w-full max-w-[1100px] translate-y-2 scale-[0.98] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)] transition duration-200 ease-out">
                                <div class="border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
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
                                                <span class="flex-1"><b class="text-slate-900">{{ $klasifikasi->kode }}</b><br><span class="text-slate-600">{{ $klasifikasi->judul }}</span>@if ($klasifikasi->uraian)<br><small class="text-slate-500">{{ $klasifikasi->uraian }}</small>@endif</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="hidden py-8 text-center text-sm text-slate-500" data-klasifikasi-empty>Kode klasifikasi tidak ditemukan.</p>
                                </div>
                            </section>
                        </div>
                    </div>

                    <a href="{{ route('superadmin.peminjaman.aktif') }}" class="flex h-[42px] w-full items-center justify-center rounded-md border border-blue-600 bg-white text-sm font-semibold text-blue-700 transition hover:bg-blue-50">Reset</a>
                </div>
            </form>
            <p class="mb-4 text-sm text-slate-600" role="status" aria-live="polite">
                {{ $peminjamans->total() > 0 ? 'Ditemukan ' . $peminjamans->total() . ' peminjaman aktif.' : 'Tidak ditemukan peminjaman aktif yang sesuai.' }}
            </p>

            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                <table class="min-w-[1320px] w-full border-collapse text-left">
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
                        @forelse ($peminjamans as $peminjaman)
                            @php
                                $filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path;
                                $namaArsip = $peminjaman->arsip?->nama_arsip ?? $peminjaman->nama_surat;
                                $jenisSurat = $peminjaman->kategori?->name ?? $peminjaman->arsip?->kategori?->name;
                                $sifatSurat = $peminjaman->sifatSurat?->name ?? $peminjaman->arsip?->sifatSurat?->name;
                                $kodeKlasifikasi = $peminjaman->klasifikasiArsip?->kode ?? $peminjaman->arsip?->klasifikasiArsip?->kode;
                                $status = $peminjaman->display_status;
                                $statusStyles = [
                                    'Ditolak' => ['text' => 'text-red-600', 'dot' => 'bg-red-500'],
                                    'Diterima' => ['text' => 'text-green-600', 'dot' => 'bg-green-500'],
                                    'Menunggu' => ['text' => 'text-yellow-600', 'dot' => 'bg-yellow-500'],
                                    'Aktif' => ['text' => 'text-blue-600', 'dot' => 'bg-blue-500'],
                                    'Selesai' => ['text' => 'text-gray-600', 'dot' => 'bg-gray-500'],
                                ];
                                $statusStyle = $statusStyles[$status] ?? $statusStyles['Aktif'];
                            @endphp
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-5 py-4 font-semibold text-gray-700">{{ $namaArsip ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ $jenisSurat ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ $sifatSurat ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ $peminjaman->user?->pokja?->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-500">{{ $kodeKlasifikasi ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 font-semibold {{ $statusStyle['text'] }}">
                                        <span class="h-2 w-2 rounded-full {{ $statusStyle['dot'] }}"></span>{{ $status }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex min-w-[190px] items-center gap-2">
                                        <button type="button" data-loan-modal-open="loan-detail-{{ $peminjaman->id }}" class="inline-flex rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700">Lihat</button>
                                        <button type="button" data-loan-modal-open="loan-replies-{{ $peminjaman->id }}" class="inline-flex rounded-md border border-blue-600 bg-white px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-50">Lihat Balasan</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">
                                    {{ $search !== '' || collect($filters)->except('search')->filter()->isNotEmpty() ? 'Tidak ditemukan peminjaman aktif yang sesuai dengan filter.' : 'Belum ada peminjaman aktif.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @foreach ($peminjamans as $peminjaman)
                @php
                    $filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path;
                    $namaArsip = $peminjaman->arsip?->nama_arsip ?? $peminjaman->nama_surat;
                @endphp
                <div id="loan-detail-{{ $peminjaman->id }}" data-loan-modal class="fixed inset-0 z-[110] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="loan-detail-title-{{ $peminjaman->id }}">
                    <button type="button" data-loan-modal-close class="absolute inset-0 bg-slate-950/50" aria-label="Tutup detail peminjaman"></button>
                    <section class="relative max-h-[88vh] w-full max-w-3xl overflow-y-auto rounded-xl bg-white p-5 shadow-2xl sm:p-7">
                        <div class="mb-5 flex items-start justify-between gap-4">
                            <h2 id="loan-detail-title-{{ $peminjaman->id }}" class="text-xl font-bold text-gray-900">Detail Peminjaman</h2>
                            <button type="button" data-loan-modal-close aria-label="Tutup" class="rounded-md px-3 py-1 text-gray-500 hover:bg-gray-100"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                        </div>
                        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div><dt class="text-xs font-semibold uppercase text-gray-500">Nama Arsip / Surat</dt><dd class="mt-1 text-sm font-medium text-gray-900">{{ $namaArsip ?: '-' }}</dd></div>
                            <div><dt class="text-xs font-semibold uppercase text-gray-500">Jenis Surat</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->kategori?->name ?? $peminjaman->arsip?->kategori?->name ?? '-' }}</dd></div>
                            <div><dt class="text-xs font-semibold uppercase text-gray-500">Sifat Surat</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->sifatSurat?->name ?? $peminjaman->arsip?->sifatSurat?->name ?? '-' }}</dd></div>
                            <div><dt class="text-xs font-semibold uppercase text-gray-500">Kelompok Kerja</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->user?->pokja?->name ?? '-' }}</dd></div>
                            <div><dt class="text-xs font-semibold uppercase text-gray-500">Kode Klasifikasi</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->klasifikasiArsip?->kode ?? $peminjaman->arsip?->klasifikasiArsip?->kode ?? '-' }}</dd></div>
                            <div><dt class="text-xs font-semibold uppercase text-gray-500">Status</dt><dd class="mt-1 text-sm font-semibold text-blue-700">{{ $peminjaman->display_status }}</dd></div>
                            <div><dt class="text-xs font-semibold uppercase text-gray-500">Tanggal Mulai</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }}</dd></div>
                            <div><dt class="text-xs font-semibold uppercase text-gray-500">Tanggal Selesai</dt><dd class="mt-1 text-sm text-gray-800">{{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</dd></div>
                            <div class="sm:col-span-2"><dt class="text-xs font-semibold uppercase text-gray-500">Keperluan</dt><dd class="mt-1 whitespace-pre-line text-sm text-gray-800">{{ $peminjaman->keperluan ?: $peminjaman->keterangan ?: '-' }}</dd></div>
                        </dl>
                        <div class="mt-6">
                            @if ($filePath)
                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($filePath) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"><i class="fa-regular fa-eye" aria-hidden="true"></i>Lihat Dokumen</a>
                            @else
                                <span class="text-sm text-gray-500">File tidak tersedia.</span>
                            @endif
                        </div>
                    </section>
                </div>

                <div id="loan-replies-{{ $peminjaman->id }}" data-loan-modal class="fixed inset-0 z-[110] hidden items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true" aria-labelledby="loan-replies-title-{{ $peminjaman->id }}">
                    <button type="button" data-loan-modal-close class="absolute inset-0 bg-slate-950/50" aria-label="Tutup balasan"></button>
                    <section class="relative flex max-h-[88vh] w-full max-w-3xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl">
                        <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                            <h2 id="loan-replies-title-{{ $peminjaman->id }}" class="text-lg font-bold text-gray-900">Balasan — {{ $namaArsip ?: 'Peminjaman' }}</h2>
                            <button type="button" data-loan-modal-close aria-label="Tutup" class="rounded-md px-3 py-1 text-gray-500 hover:bg-gray-100"><i class="fa-solid fa-xmark" aria-hidden="true"></i></button>
                        </div>
                        <div class="min-h-0 flex-1 space-y-3 overflow-y-auto p-5">
                            @forelse ($peminjaman->balasans as $balasan)
                                <article class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                    <div class="mb-2 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500">
                                        <span class="font-semibold text-gray-700">{{ $balasan->user?->name ?? 'Pengguna' }}</span>
                                        <time datetime="{{ $balasan->created_at->toIso8601String() }}">{{ $balasan->created_at->format('d/m/Y H:i') }}</time>
                                    </div>
                                    <p class="whitespace-pre-line text-sm text-gray-700">{{ $balasan->isi_balasan }}</p>
                                </article>
                            @empty
                                <p class="rounded-lg border border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500">Belum ada balasan untuk permohonan ini.</p>
                            @endforelse
                        </div>
                        <form method="POST" action="{{ route('superadmin.peminjaman.balasan.store', $peminjaman) }}" class="border-t border-gray-200 p-5">
                            @csrf
                            <input type="hidden" name="peminjaman_id" value="{{ $peminjaman->id }}">
                            <label for="isi_balasan-{{ $peminjaman->id }}" class="mb-2 block text-sm font-semibold text-gray-800">Kirim Balasan</label>
                            <textarea id="isi_balasan-{{ $peminjaman->id }}" name="isi_balasan" rows="3" maxlength="10000" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('peminjaman_id') == $peminjaman->id ? old('isi_balasan') : '' }}</textarea>
                            @if (old('peminjaman_id') == $peminjaman->id)
                                @error('isi_balasan')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            @endif
                            <button type="submit" class="mt-3 inline-flex items-center gap-2 rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"><i class="fa-solid fa-reply" aria-hidden="true"></i>Simpan Balasan</button>
                        </form>
                    </section>
                </div>
            @endforeach

            <div class="mt-6 flex flex-col items-center gap-3">
                <p class="text-sm text-gray-500">Menampilkan {{ $peminjamans->firstItem() ?? 0 }}-{{ $peminjamans->lastItem() ?? 0 }} dari {{ $peminjamans->total() }} peminjaman</p>
                @if ($peminjamans->hasPages())
                    <nav class="flex items-center justify-center gap-2" aria-label="Pagination">
                        @if ($peminjamans->previousPageUrl())
                            <a href="{{ $peminjamans->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-left"></i></a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center text-gray-400"><i class="fa-solid fa-arrow-left"></i></span>
                        @endif
                        @foreach ($peminjamans->getUrlRange(max(1, $peminjamans->currentPage() - 2), min($peminjamans->lastPage(), $peminjamans->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}" @class(['flex h-9 min-w-9 items-center justify-center rounded-md px-2 text-sm font-semibold transition', 'bg-blue-600 text-white' => $page === $peminjamans->currentPage(), 'text-gray-800 hover:bg-gray-200' => $page !== $peminjamans->currentPage()]) @if ($page === $peminjamans->currentPage()) aria-current="page" @endif>{{ $page }}</a>
                        @endforeach
                        @if ($peminjamans->nextPageUrl())
                            <a href="{{ $peminjamans->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="flex h-9 w-9 items-center justify-center rounded-md text-gray-800 transition hover:bg-gray-200"><i class="fa-solid fa-arrow-right"></i></a>
                        @else
                            <span class="flex h-9 w-9 items-center justify-center text-gray-400"><i class="fa-solid fa-arrow-right"></i></span>
                        @endif
                    </nav>
                @endif
            </div>
            
        </div>
    </main>

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
            if (savedWidth >= 220 && savedWidth <= 380) {
                document.documentElement.style.setProperty('--sidebar-width', `${savedWidth}px`);
            }
            setCollapsed(localStorage.getItem('superadmin-sidebar-collapsed') === 'true');
        }

        const closeMobileSidebar = () => {
            pageBody.classList.remove('sidebar-mobile-open');
            sidebarBackdrop.classList.add('hidden');
            sidebarToggle.setAttribute('aria-expanded', 'false');
        };

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
                const width = Number(localStorage.getItem('superadmin-sidebar-width'));
                if (width >= 220 && width <= 380) {
                    document.documentElement.style.setProperty('--sidebar-width', `${width}px`);
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
            const resize = (pointerEvent) => setSidebarWidth(pointerEvent.clientX);
            const stopResize = () => {
                resizeHandle.removeEventListener('pointermove', resize);
                resizeHandle.removeEventListener('pointerup', stopResize);
                resizeHandle.removeEventListener('pointercancel', stopResize);
            };
            resizeHandle.addEventListener('pointermove', resize);
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
            root.querySelectorAll('[data-klasifikasi-close]').forEach((button) => button.addEventListener('click', closeModal));
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

        const loanModals = [...document.querySelectorAll('[data-loan-modal]')];
        const closeLoanModal = (modal) => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };
        document.querySelectorAll('[data-loan-modal-open]').forEach((button) => {
            button.addEventListener('click', () => {
                const modal = document.getElementById(button.dataset.loanModalOpen);
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.querySelector('textarea')?.focus();
            });
        });
        loanModals.forEach((modal) => {
            modal.querySelectorAll('[data-loan-modal-close]').forEach((button) => {
                button.addEventListener('click', () => closeLoanModal(modal));
            });
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                loanModals.forEach((modal) => closeLoanModal(modal));
            }
        });
        const replyLoanId = @json(old('peminjaman_id'));
        if (replyLoanId) {
            const replyModal = document.getElementById(`loan-replies-${replyLoanId}`);
            if (replyModal) {
                replyModal.classList.remove('hidden');
                replyModal.classList.add('flex');
            }
        }
    </script>
    <x-logout-confirmation />
</body>
</html>
