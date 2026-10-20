<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Super Admin</title>
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
            
            <!-- Menu -->
            <nav class="space-y-1 px-4 pb-4">
                <a href="{{ route('superadmin.dashboard') }}" title="Dashboard" class="mt-1 flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg><span data-sidebar-label>Dashboard</span>
                </a>
                
                <div class="pt-1">
                    <button type="button" onclick="toggleSidebarMenu('submenu-arsip', 'ikon-panah-arsip')" aria-expanded="false" class="w-full flex items-center justify-between rounded-lg px-4 py-3 text-sm font-medium text-gray-200 transition-colors hover:bg-gray-800 hover:text-white focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i><span data-sidebar-label>Kelola Arsip</span></span>
                        <i id="ikon-panah-arsip" class="fa-solid fa-chevron-right text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <div id="submenu-arsip" class="overflow-hidden transition-all duration-300 max-h-0">
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
        <header class="sticky top-0 z-10 flex items-center justify-between bg-[#f4f7f6] px-5 py-6 sm:px-8">
            <button id="sidebar-toggle" type="button" aria-label="Ciutkan atau buka sidebar" aria-expanded="true" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
            <div class="flex items-center gap-3">
                <div class="relative" data-notifications>
                    <button type="button" data-notification-toggle aria-expanded="false" aria-controls="notification-dropdown" aria-label="Buka notifikasi" class="relative flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm transition hover:border-blue-500 hover:text-blue-600">
                        <i class="fa-regular fa-bell text-lg" aria-hidden="true"></i>
                        @if ($notificationCount > 0)
                            <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[10px] font-bold leading-none text-white">{{ $notificationCount > 99 ? '99+' : $notificationCount }}</span>
                        @endif
                    </button>
                    <section id="notification-dropdown" data-notification-dropdown aria-label="Notifikasi" class="invisible pointer-events-none absolute right-0 top-full z-50 mt-3 w-80 max-w-[calc(100vw-2rem)] origin-top-right translate-y-2 scale-95 overflow-hidden rounded-xl border border-gray-200 bg-white opacity-0 shadow-xl transition duration-200">
                        <header class="border-b border-gray-100 px-4 py-3">
                            <h2 class="text-sm font-bold text-gray-900">Notifikasi</h2>
                        </header>
                        <div class="max-h-[65vh] overflow-y-auto">
                            @forelse ($notifications as $notification)
                                <a href="{{ $notification['url'] }}" class="block border-b border-gray-100 px-4 py-3 transition last:border-b-0 hover:bg-blue-50">
                                    <p class="text-xs font-semibold text-gray-500">{{ $notification['category'] }}</p>
                                    <p class="mt-1 text-sm font-semibold leading-5 text-gray-800">{{ $notification['message'] }}</p>
                                    <p class="mt-2 text-xs text-gray-400" title="{{ $notification['occurred_at']?->format('d/m/Y H:i') ?? '' }}">{{ $notification['occurred_at']?->format('H.i') ?? '-' }}</p>
                                </a>
                            @empty
                                <p class="px-4 py-8 text-center text-sm text-gray-500">Tidak ada notifikasi baru.</p>
                            @endforelse
                        </div>
                        @if ($pendingValidationCount > 0 || $suratMasukNotificationCount > 0)
                            <footer class="flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 px-4 py-3">
                                @if ($pendingValidationCount > 0)
                                    <a href="{{ route('superadmin.peminjaman.validasi') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua Peminjaman</a>
                                @endif
                                @if ($suratMasukIndexUrl)
                                    <a href="{{ $suratMasukIndexUrl }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua Surat Masuk</a>
                                @endif
                            </footer>
                        @endif
                    </section>
                </div>
                <span class="text-base font-bold text-gray-900">{{ $user?->name ?? $user?->username ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black"></i>
            </div>
        </header>

        <div class="w-full max-w-[1600px] px-5 pb-10 sm:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif

            <h2 class="text-3xl font-bold mb-6 text-black">Dashboard Super Admin</h2>

            <div class="grid grid-cols-1 gap-6 mb-10 md:grid-cols-4">
                <a href="{{ route('superadmin.arsip.laporan', ['sumber' => 'kelompok_kerja']) }}" class="block rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-blue-500 hover:shadow-md">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip Pokja</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalArsip }}</p>
                    <p class="text-xs text-gray-400 font-semibold">Dokumen</p>
                </a>
                <a href="{{ route('superadmin.peminjaman.aktif') }}" class="block rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-blue-500 hover:shadow-md">
                    <p class="text-gray-400 text-sm font-bold mb-2">Peminjaman Aktif</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalPeminjamanAktif }}</p>
                    <p class="text-xs text-gray-400 font-semibold">Peminjaman</p>
                </a>
                <a href="{{ route('superadmin.users') }}" class="block rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition hover:border-blue-500 hover:shadow-md">
                    <p class="text-gray-400 text-sm font-bold mb-2">User Aktif</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalUserAktif }}</p>
                    <p class="text-xs text-gray-400 font-semibold">User</p>
                </a>
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Pokja</p>
                    <p class="text-4xl font-bold text-gray-800 mb-1">{{ $totalPokja }}</p>
                    <p class="text-xs text-gray-400 font-semibold">Kelompok</p>
                </div>
            </div>

            <h3 class="mb-4 text-2xl font-bold text-black">Arsip Terbaru Pokja</h3>
            <div class="mb-10 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1180px] border-collapse text-left">
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
                            @forelse ($arsipTerbaru as $arsip)
                                @php
                                    $arsipStatus = strtolower((string) $arsip->status);
                                    $arsipAktif = $arsipStatus === 'aktif';
                                @endphp
                                <tr class="text-sm transition hover:bg-gray-50">
                                    <td class="px-5 py-4 font-semibold text-gray-700">{{ $arsip->nama_arsip }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->kategori?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->sifatSurat?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->pokja?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $arsip->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 font-semibold {{ $arsipAktif ? 'text-blue-600' : 'text-gray-600' }}">
                                            <span class="h-2 w-2 rounded-full {{ $arsipAktif ? 'bg-blue-500' : 'bg-gray-400' }}"></span>{{ $arsipAktif ? 'Aktif' : ucfirst(str_replace('_', ' ', $arsipStatus ?: 'tidak diketahui')) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if ($arsip->file_path)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($arsip->file_path) }}" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                        @else
                                            <span class="text-xs text-gray-400">File tidak tersedia</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada arsip Pokja.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 text-center"><a href="{{ route('superadmin.arsip.laporan', ['sumber' => 'kelompok_kerja']) }}" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua</a></div>
            </div>

            <h3 class="mb-4 text-2xl font-bold text-black">Peminjaman Menunggu Validasi</h3>
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1320px] border-collapse text-left">
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
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($peminjamanTerbaru as $peminjaman)
                                @php
                                    $filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path;
                                    $namaArsip = $peminjaman->arsip?->nama_arsip ?? $peminjaman->nama_surat;
                                    $jenisSurat = $peminjaman->kategori?->name ?? $peminjaman->arsip?->kategori?->name;
                                    $sifatSurat = $peminjaman->sifatSurat?->name ?? $peminjaman->arsip?->sifatSurat?->name;
                                    $kodeKlasifikasi = $peminjaman->klasifikasiArsip?->kode ?? $peminjaman->arsip?->klasifikasiArsip?->kode;
                                @endphp
                                <tr class="text-sm transition hover:bg-gray-50">
                                    <td class="px-5 py-4 font-semibold text-gray-700">{{ $namaArsip ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $jenisSurat ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $sifatSurat ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $peminjaman->user?->pokja?->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-gray-500">{{ $kodeKlasifikasi ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $peminjaman->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4 text-gray-500">{{ $peminjaman->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 font-semibold text-amber-700">
                                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>Menunggu Validasi
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-2">
                                            @if ($filePath)
                                                <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($filePath) }}" target="_blank" rel="noopener noreferrer" class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                            @else
                                                <span class="text-xs text-gray-400">File tidak tersedia</span>
                                            @endif
                                            <form method="POST" action="{{ route('superadmin.peminjaman.decision', $peminjaman) }}" data-confirm-title="Apakah Anda yakin ingin menolak peminjaman ini?" data-confirm-button="Ya, Tolak" data-confirm-color="#dc2626">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="decision" value="reject">
                                                <button type="submit" class="rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">Tolak</button>
                                            </form>
                                            <form method="POST" action="{{ route('superadmin.peminjaman.decision', $peminjaman) }}" data-confirm-title="Apakah Anda yakin ingin menerima peminjaman ini?" data-confirm-button="Ya, Terima" data-confirm-color="#16a34a">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="decision" value="approve">
                                                <button type="submit" class="rounded-md bg-green-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-green-700">Terima</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="px-6 py-8 text-center text-sm text-gray-500">Tidak ada peminjaman yang menunggu validasi.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4 text-center"><a href="{{ route('superadmin.peminjaman.validasi') }}" class="text-sm font-bold text-blue-600 hover:underline">Lihat Semua</a></div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const pageBody = document.body;
        const sidebar = document.getElementById('superadmin-sidebar');
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

        const notificationRoot = document.querySelector('[data-notifications]');
        const notificationToggle = notificationRoot.querySelector('[data-notification-toggle]');
        const notificationDropdown = notificationRoot.querySelector('[data-notification-dropdown]');

        function setNotificationOpen(isOpen) {
            notificationToggle.setAttribute('aria-expanded', String(isOpen));
            notificationDropdown.classList.toggle('invisible', !isOpen);
            notificationDropdown.classList.toggle('pointer-events-none', !isOpen);
            notificationDropdown.classList.toggle('translate-y-2', !isOpen);
            notificationDropdown.classList.toggle('scale-95', !isOpen);
            notificationDropdown.classList.toggle('opacity-0', !isOpen);
        }

        notificationToggle.addEventListener('click', (event) => {
            event.stopPropagation();
            setNotificationOpen(notificationToggle.getAttribute('aria-expanded') !== 'true');
        });

        document.addEventListener('click', (event) => {
            if (!notificationRoot.contains(event.target)) {
                setNotificationOpen(false);
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                setNotificationOpen(false);
            }
        });

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
    </script>
    <x-logout-confirmation />
</body>
</html>