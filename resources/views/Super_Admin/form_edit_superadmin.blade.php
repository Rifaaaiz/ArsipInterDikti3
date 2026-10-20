<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit File Kearsipan - Super Admin</title>
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
            #sidebar-resize-handle { display: none; }
        }
    </style>
</head>
<body class="flex bg-[#f4f7f9] font-sans text-gray-800">
    <aside id="superadmin-sidebar" class="fixed left-0 top-0 z-30 flex h-screen flex-col bg-[#0a1120] text-white">
        <div class="min-h-0 flex-1 overflow-y-auto">
            <div id="sidebar-logo" class="mb-2 flex items-center gap-3 px-6 py-6">
                <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100">
                    <i class="fa-solid fa-book-open text-sm text-blue-600"></i>
                </div>
                <h1 data-sidebar-label class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>
            <nav class="space-y-1 px-4 pb-4">
                <a href="{{ route('superadmin.dashboard') }}" title="Dashboard" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-semibold text-gray-200 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-solid fa-house w-5"></i><span data-sidebar-label>Dashboard</span>
                </a>
                <div class="pt-1">
                    <button type="button" onclick="toggleSidebarMenu('submenu-arsip', 'ikon-panah-arsip')" aria-expanded="true" class="flex w-full items-center justify-between rounded-lg bg-blue-600 px-4 py-3 text-sm font-semibold text-white">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i><span data-sidebar-label>Kelola Arsip</span></span>
                        <i id="ikon-panah-arsip" class="fa-solid fa-chevron-right rotate-90 text-xs transition-transform"></i>
                    </button>
                    <div id="submenu-arsip" class="max-h-40 overflow-hidden transition-all duration-300">
                        <div class="flex flex-col gap-1 py-1">
                            <a href="{{ route('superadmin.arsip.penghapusan') }}" class="block py-2 pl-12 text-sm font-medium text-gray-400 transition hover:text-white">Penghapusan Arsip</a>
                            <a href="{{ route('superadmin.arsip.laporan') }}" aria-current="page" class="mx-2 block rounded-lg bg-blue-600 px-4 py-2.5 pl-8 text-sm font-semibold text-white shadow-sm">Laporan Arsip</a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('superadmin.peminjaman.validasi') }}" title="Validasi Peminjaman" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-regular fa-circle-check w-5"></i><span data-sidebar-label>Validasi Peminjaman</span>
                </a>
                <a href="{{ route('superadmin.users') }}" title="Kelola User" class="mt-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-800 hover:text-white">
                    <i class="fa-solid fa-users w-5"></i><span data-sidebar-label>Kelola User</span>
                </a>
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
                <span class="text-sm font-bold text-gray-900">Super Admin</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <section class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8">
            <div class="mb-6 flex items-center gap-3">
                <a href="{{ route('superadmin.arsip.laporan') }}" aria-label="Kembali ke Laporan Arsip" class="flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 transition hover:border-blue-500 hover:text-blue-600">
                    <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl">Edit File Kearsipan</h1>
            </div>

            <div class="w-full rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('superadmin.arsip.update', $arsip) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <label for="nama_arsip" class="mb-1.5 block text-sm font-bold text-slate-800">Nama Surat <span class="text-red-600">*</span></label>
                        <input type="text" id="nama_arsip" name="nama_arsip" value="{{ old('nama_arsip', $arsip->nama_arsip) }}" placeholder="Nama Surat" required class="w-full rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                        @error('nama_arsip')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5 grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label for="kategori_arsip_id" class="mb-1.5 block text-sm font-bold text-slate-800">Jenis Surat <span class="text-red-600">*</span></label>
                            <div class="relative">
                                <select id="kategori_arsip_id" name="kategori_arsip_id" required class="w-full appearance-none rounded-md border border-slate-300 bg-slate-50 px-4 py-3 pr-10 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    <option value="" disabled {{ old('kategori_arsip_id', $arsip->kategori_arsip_id) ? '' : 'selected' }}>Pilih Jenis Surat</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" @selected((string) old('kategori_arsip_id', $arsip->kategori_arsip_id) === (string) $kategori->id)>{{ $kategori->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500"></i>
                            </div>
                            @error('kategori_arsip_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="sifat_surat_id" class="mb-1.5 block text-sm font-bold text-slate-800">Sifat Surat <span class="text-red-600">*</span></label>
                            <div class="relative">
                                <select id="sifat_surat_id" name="sifat_surat_id" required class="w-full appearance-none rounded-md border border-slate-300 bg-slate-50 px-4 py-3 pr-10 text-sm text-slate-700 focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    <option value="" disabled {{ old('sifat_surat_id', $arsip->sifat_surat_id) ? '' : 'selected' }}>Pilih Sifat Surat</option>
                                    @foreach ($sifatSurats as $sifatSurat)
                                        <option value="{{ $sifatSurat->id }}" @selected((string) old('sifat_surat_id', $arsip->sifat_surat_id) === (string) $sifatSurat->id)>{{ $sifatSurat->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fa-solid fa-chevron-down pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500"></i>
                            </div>
                            @error('sifat_surat_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="mb-1.5 block text-sm font-bold text-slate-800">Kode Klasifikasi</label>
                        @php
                            $klasifikasiValue = old('klasifikasi_arsip_id', $arsip->klasifikasi_arsip_id ?? '');
                            $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) $klasifikasiValue);
                        @endphp
                        <div class="relative" data-klasifikasi-root>
                            <input type="hidden" name="klasifikasi_arsip_id" value="{{ $klasifikasiValue }}" data-klasifikasi-value>
                            <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex w-full items-center justify-between rounded-md border border-slate-300 bg-slate-50 px-4 py-3 text-left text-sm text-slate-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none">
                                <span data-klasifikasi-label>{{ $selectedKlasifikasi ? $selectedKlasifikasi->kode . ' - ' . ($selectedKlasifikasi->judul ?? $selectedKlasifikasi->uraian ?? '-') : 'Pilih Kode Klasifikasi' }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-slate-500"></i>
                            </button>

                            <div class="fixed inset-0 z-[100] hidden p-4 sm:p-6" data-klasifikasi-modal role="dialog" aria-modal="true" aria-label="Pilih Kode Klasifikasi">
                                <button type="button" class="fixed inset-0 cursor-default bg-slate-950/45" data-klasifikasi-close aria-label="Tutup pemilih kode klasifikasi"></button>
                                <section class="relative mx-auto flex max-h-[88vh] w-full max-w-[1100px] flex-col overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_20px_60px_rgba(15,23,42,0.18)]">
                                    <div class="sticky top-0 z-10 border-b border-slate-200 bg-white px-4 pb-3 pt-5 sm:px-6">
                                        <input type="search" data-klasifikasi-search placeholder="Cari Kode Klasifikasi" class="h-[42px] w-full rounded-md border border-slate-300 bg-slate-50 px-4 text-sm text-slate-800 placeholder:text-slate-500 focus:border-blue-600 focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-600">
                                    </div>
                                    <div class="min-h-0 flex-1 overflow-y-auto px-4 pb-6 pt-4 sm:px-6">
                                        @foreach ($klasifikasis->groupBy(fn ($item) => ($item->bab_urutan ?? 999) . '|' . trim((string) ($item->bab ?? ''))) as $group)
                                            @php $first = $group->first(); @endphp
                                            <div class="mb-6">
                                                <h2 class="mb-3 text-sm font-bold text-slate-700">{{ $first->bab ?? 'BAB' }}</h2>
                                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                                    @foreach ($group as $item)
                                                        <button type="button" data-klasifikasi-option data-value="{{ $item->id }}" data-label="{{ $item->kode }} - {{ $item->judul ?? $item->uraian ?? '-' }}" class="rounded-lg border border-slate-200 bg-white p-3 text-left text-sm text-slate-700 transition hover:border-blue-300 hover:bg-blue-50">
                                                            <span class="block font-bold text-slate-900">{{ $item->kode }}</span>
                                                            <span class="block text-xs text-slate-600">{{ $item->judul ?? $item->uraian ?? '-' }}</span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </section>
                            </div>
                        </div>
                        @error('klasifikasi_arsip_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-5">
                        <label for="file_arsip" class="mb-1.5 block text-sm font-bold text-slate-800">Ganti File</label>
                        <label class="relative flex min-h-44 cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-5 py-8 text-center transition hover:border-blue-500 hover:bg-blue-50/40">
                            <input type="file" id="file_arsip" name="file_arsip" accept=".pdf,.doc,.docx,.zip,.rar,.jpg,.jpeg,.png,.gif,.bmp,.webp" class="absolute inset-0 h-full w-full cursor-pointer opacity-0">
                            <span class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-blue-600"><i class="fa-solid fa-cloud-arrow-up text-xl"></i></span>
                            <span class="text-base font-semibold text-slate-800">Select File</span>
                            <span id="selected-file-name" class="mt-1 text-sm text-slate-500">Upload File Kearsipan Disini (PDF, Doc, DOCX, ZIP, RAR - Maks 5 Mb)</span>
                        </label>
                        <p class="mt-2 text-xs text-slate-500">Maximal upload file 5 MB. (Jika Lebih Bisa Di Kompress Filenya Terlebih Dahulu)</p>
                        @if ($arsip->file_path)
                            <div class="mt-3 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-white p-3">
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">File saat ini</p>
                                    <p class="text-xs text-slate-500">{{ basename($arsip->file_path) }}</p>
                                </div>
                                <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" rel="noopener" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-slate-700">Lihat File</a>
                            </div>
                            <label class="mt-3 inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-red-600">
                                <input type="checkbox" name="remove_existing_file" value="1" class="h-4 w-4 rounded border-red-300 text-red-600 focus:ring-red-500">
                                Hapus file yang tersimpan
                            </label>
                        @endif
                        @error('file_arsip')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-lg bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">
                        <span>Submit Peminjaman</span>
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </section>
    </main>

    <script>
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const sidebarResizeHandle = document.getElementById('sidebar-resize-handle');
        const isMobileViewport = () => window.matchMedia('(max-width: 767px)').matches;

        const setMobileSidebar = (open) => {
            document.body.classList.toggle('sidebar-mobile-open', open);
            sidebarBackdrop.classList.toggle('hidden', !open);
            sidebarToggle.setAttribute('aria-expanded', String(open));
        };

        sidebarToggle.addEventListener('click', () => {
            if (isMobileViewport()) {
                setMobileSidebar(!document.body.classList.contains('sidebar-mobile-open'));
                return;
            }

            const collapsed = document.body.classList.toggle('sidebar-collapsed');
            sidebarToggle.setAttribute('aria-expanded', String(!collapsed));
        });
        sidebarBackdrop.addEventListener('click', () => setMobileSidebar(false));
        window.addEventListener('resize', () => {
            if (!isMobileViewport()) setMobileSidebar(false);
        });

        let resizingSidebar = false;
        sidebarResizeHandle.addEventListener('pointerdown', (event) => {
            if (isMobileViewport()) return;
            resizingSidebar = true;
            sidebarResizeHandle.setPointerCapture(event.pointerId);
            document.body.classList.remove('sidebar-collapsed');
            document.body.style.userSelect = 'none';
        });
        sidebarResizeHandle.addEventListener('pointermove', (event) => {
            if (!resizingSidebar) return;
            document.body.style.setProperty('--sidebar-width', `${Math.min(360, Math.max(220, event.clientX))}px`);
        });
        sidebarResizeHandle.addEventListener('pointerup', () => {
            resizingSidebar = false;
            document.body.style.userSelect = '';
        });

        window.toggleSidebarMenu = (submenuId, iconId) => {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(iconId);
            const button = icon?.closest('button');
            if (!submenu || !icon || !button) return;
            const isOpen = Number.parseFloat(window.getComputedStyle(submenu).maxHeight) > 0;
            submenu.style.maxHeight = isOpen ? '0px' : `${submenu.scrollHeight}px`;
            icon.classList.toggle('rotate-90', !isOpen);
            button.setAttribute('aria-expanded', String(!isOpen));
        };

        const fileInput = document.getElementById('file_arsip');
        const fileNameLabel = document.getElementById('selected-file-name');
        if (fileInput && fileNameLabel) {
            fileInput.addEventListener('change', () => {
                fileNameLabel.textContent = fileInput.files?.[0]?.name || 'Upload File Kearsipan Disini (PDF, Doc, DOCX, ZIP, RAR - Maks 5 Mb)';
            });
        }

        document.querySelectorAll('[data-klasifikasi-root]').forEach((root) => {
            const trigger = root.querySelector('[data-klasifikasi-trigger]');
            const modal = root.querySelector('[data-klasifikasi-modal]');
            const closeButton = root.querySelector('[data-klasifikasi-close]');
            const input = root.querySelector('[data-klasifikasi-value]');
            const label = root.querySelector('[data-klasifikasi-label]');
            const search = root.querySelector('[data-klasifikasi-search]');
            const options = root.querySelectorAll('[data-klasifikasi-option]');
            if (!trigger || !modal || !closeButton || !input || !label || !search) return;

            const close = () => {
                modal.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('overflow-hidden');
            };
            const applyFilter = () => {
                const query = search.value.trim().toLowerCase();
                options.forEach((option) => {
                    option.style.display = query && !(option.dataset.label || '').toLowerCase().includes(query) ? 'none' : '';
                });
            };

            trigger.addEventListener('click', () => {
                modal.classList.remove('hidden');
                trigger.setAttribute('aria-expanded', 'true');
                document.body.classList.add('overflow-hidden');
                setTimeout(() => search.focus(), 50);
            });
            closeButton.addEventListener('click', close);
            search.addEventListener('input', applyFilter);
            options.forEach((option) => {
                option.addEventListener('click', () => {
                    input.value = option.dataset.value || '';
                    label.textContent = option.dataset.label || 'Pilih Kode Klasifikasi';
                    close();
                });
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.classList.contains('hidden')) close();
            });
        });
    </script>
</body>
</html>
