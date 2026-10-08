<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($isEdit) && $isEdit ? 'Edit Arsip - Super Admin' : 'Upload Arsip - Super Admin' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f9] flex font-sans text-gray-800">
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>

            <nav class="px-4 space-y-1">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>

                <div class="pt-1">
                    <button class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i> Kelola Arsip</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>

                    <div class="flex flex-col gap-1 pt-1 pb-2">
                        <a href="{{ route('superadmin.arsip.penghapusan') }}" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Penghapusan Arsip</a>
                        <a href="{{ route('superadmin.arsip.laporan') }}" class="block px-4 py-2.5 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-semibold transition shadow-sm">Laporan Arsip</a>
                    </div>
                </div>

                <a href="{{ route('superadmin.peminjaman.validasi') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-regular fa-circle-check w-5"></i> Validasi Peminjaman
                </a>
                <a href="{{ route('superadmin.users') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-users w-5"></i> Kelola User
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-regular fa-file-lines w-5"></i> Panduan Penggunaan
                </a>
            </nav>
        </div>

        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
            @csrf
            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:text-red-400 transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
            </button>
        </form>
    </aside>

    <main class="flex-1 ml-64 min-h-screen">
        <header class="px-8 py-6 flex justify-end items-center">
            <span class="font-bold text-gray-900">{{ auth()->user()->name ?? 'Super Admin' }}</span>
            <i class="fa-solid fa-circle-user text-2xl ml-3"></i>
        </header>

        <div class="px-8 pb-10">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ route('superadmin.arsip.laporan') }}" class="text-black hover:text-blue-600 text-lg transition">
                    <i class="fa-solid fa-chevron-left"></i>
                </a>
                <span class="text-sm font-bold text-gray-500">{{ isset($isEdit) && $isEdit ? 'Edit Arsip - Super Admin' : 'Tambah Arsip - Super Admin' }}</span>
            </div>

            <div class="max-w-5xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-3xl font-bold mb-6 text-gray-900">{{ isset($isEdit) && $isEdit ? 'Edit File Kearsipan' : 'Upload File Kearsipan' }}</h2>

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('superadmin.arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($isEdit) && $isEdit)
                        @method('PUT')
                    @endif

                    <div class="mb-5">
                        <label for="nama_arsip" class="block text-sm font-bold text-black mb-1.5">Nama Arsip</label>
                        <input type="text" id="nama_arsip" name="nama_arsip" value="{{ old('nama_arsip', $arsip->nama_arsip ?? '') }}" placeholder="Nama Arsip" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                        @error('nama_arsip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-5">
                        <div>
                            <label for="nomor_dokumen" class="block text-sm font-bold text-black mb-1.5">Nomor Dokumen</label>
                            <input type="text" id="nomor_dokumen" name="nomor_dokumen" value="{{ old('nomor_dokumen', $arsip->nomor_dokumen ?? '') }}" placeholder="Nomor Dokumen (opsional)" class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            @error('nomor_dokumen')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kategori_arsip_id" class="block text-sm font-bold text-black mb-1.5">Kategori</label>
                            <select id="kategori_arsip_id" name="kategori_arsip_id" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                                <option value="" disabled {{ old('kategori_arsip_id', $arsip->kategori_arsip_id ?? '') ? '' : 'selected' }}>Kategori Surat</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_arsip_id', $arsip->kategori_arsip_id ?? '') == $kategori->id ? 'selected' : '' }}>{{ $kategori->name }}</option>
                                @endforeach
                            </select>
                            @error('kategori_arsip_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tanggal_dokumen" class="block text-sm font-bold text-black mb-1.5">Tanggal Dokumen</label>
                            <input type="date" id="tanggal_dokumen" name="tanggal_dokumen" value="{{ old('tanggal_dokumen', $arsip->tanggal_dokumen?->format('Y-m-d') ?? '') }}" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            @error('tanggal_dokumen')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-bold text-black mb-1.5">Kode Klasifikasi</label>
                        @php
                            $klasifikasiValue = old('klasifikasi_arsip_id', $arsip->klasifikasi_arsip_id ?? '');
                            $selectedKlasifikasi = $klasifikasis->first(fn ($item) => (string) $item->id === (string) $klasifikasiValue);
                        @endphp
                        <div class="relative" data-klasifikasi-root="superadmin-klasifikasi">
                            <input type="hidden" name="klasifikasi_arsip_id" value="{{ $klasifikasiValue }}" data-klasifikasi-value>
                            <button type="button" data-klasifikasi-trigger aria-expanded="false" class="flex h-[42px] w-full items-center justify-between rounded-md border border-gray-500 bg-[#f8f9fa] px-4 text-sm text-gray-700 transition hover:border-blue-600 focus:border-blue-600 focus:outline-none">
                                <span data-klasifikasi-label>{{ $selectedKlasifikasi ? $selectedKlasifikasi->kode . ' - ' . ($selectedKlasifikasi->judul ?? $selectedKlasifikasi->uraian ?? '-') : 'Pilih Kode Klasifikasi' }}</span>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-500"></i>
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
                                                <h3 class="mb-3 text-sm font-bold text-slate-700">{{ $first->bab ?? 'BAB' }}</h3>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    @foreach ($group as $item)
                                                        <button type="button" data-klasifikasi-option data-value="{{ $item->id }}" data-label="{{ $item->kode }} - {{ $item->judul ?? $item->uraian ?? '-' }}" class="rounded-lg border border-slate-200 bg-white p-3 text-left transition hover:border-blue-300 hover:bg-blue-50 text-sm text-slate-700">
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
                        @error('klasifikasi_arsip_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="pokja_id" class="block text-sm font-bold text-black mb-1.5">Kelompok Kerja / Pokja</label>
                        <select id="pokja_id" name="pokja_id" required class="w-full border border-gray-500 bg-[#f8f9fa] rounded-md px-4 py-2.5 text-sm text-gray-700 focus:outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600">
                            <option value="" disabled {{ old('pokja_id', $arsip->pokja_id ?? '') ? '' : 'selected' }}>Pilih Kelompok Kerja</option>
                            @foreach ($pokjas as $pokja)
                                <option value="{{ $pokja->id }}" {{ old('pokja_id', $arsip->pokja_id ?? '') == $pokja->id ? 'selected' : '' }}>{{ $pokja->name }}</option>
                            @endforeach
                        </select>
                        @error('pokja_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="file_arsip" class="block text-sm font-bold text-black mb-1.5">{{ isset($isEdit) && $isEdit ? 'Ganti File' : 'Upload File' }}</label>
                        <input type="file" id="file_arsip" name="file_arsip" accept=".pdf,.doc,.docx,.zip,.rar,.jpg,.jpeg,.png,.gif,.bmp,.webp" {{ isset($isEdit) && $isEdit ? '' : 'required' }} class="block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-blue-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-blue-700">
                        @if (isset($isEdit) && $isEdit && $arsip->file_path)
                            <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 p-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-slate-700">File saat ini</p>
                                        <p class="text-xs text-slate-500">{{ basename($arsip->file_path) }}</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" class="rounded-md bg-slate-900 px-3 py-1.5 text-xs font-bold text-white hover:bg-slate-700">Lihat</a>
                                        <label class="inline-flex items-center gap-2 rounded-md border border-red-200 bg-red-50 px-3 py-1.5 text-xs font-bold text-red-600 cursor-pointer">
                                            <input type="checkbox" name="remove_existing_file" value="1" class="h-4 w-4 rounded border-red-300 text-red-600 focus:ring-red-500">
                                            Hapus file
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <p class="mt-1 text-xs text-gray-500">Maksimal ukuran file 5 MB. Format yang didukung: PDF, DOC, DOCX, ZIP, RAR, JPG, PNG, GIF, BMP, WEBP.</p>
                        @error('file_arsip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3">
                        <a href="{{ route('superadmin.arsip.laporan') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">Batal</a>
                        <button type="submit" class="rounded-lg bg-[#2563eb] px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-blue-700">{{ isset($isEdit) && $isEdit ? 'Perbarui Arsip' : 'Simpan Arsip' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        document.querySelectorAll('[data-klasifikasi-root]').forEach((root) => {
            const trigger = root.querySelector('[data-klasifikasi-trigger]');
            const modal = root.querySelector('[data-klasifikasi-modal]');
            const closeButton = root.querySelector('[data-klasifikasi-close]');
            const input = root.querySelector('[data-klasifikasi-value]');
            const label = root.querySelector('[data-klasifikasi-label]');
            const search = root.querySelector('[data-klasifikasi-search]');
            const options = root.querySelectorAll('[data-klasifikasi-option]');

            if (!trigger || !modal || !input || !label || !search) return;

            const close = () => {
                modal.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('overflow-hidden');
            };

            const applyFilter = () => {
                const query = search.value.trim().toLowerCase();
                options.forEach((option) => {
                    const text = (option.dataset.label || '').toLowerCase();
                    option.style.display = query && !text.includes(query) ? 'none' : '';
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
                    const value = option.dataset.value || '';
                    const text = option.dataset.label || 'Pilih Kode Klasifikasi';
                    input.value = value;
                    label.textContent = text;
                    close();
                });
            });
        });
    </script>
</body>
</html>