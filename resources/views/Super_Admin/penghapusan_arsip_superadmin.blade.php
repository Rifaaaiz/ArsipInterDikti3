<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penghapusan Arsip - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f4f7f9] flex font-sans text-gray-800">
    
    <!-- SIDEBAR -->
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
        <div>
            <!-- Logo -->
            <div class="flex items-center gap-3 px-6 py-6 mb-2">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-blue-600 text-sm"></i>
                </div>
                <h1 class="text-xl font-bold tracking-wider text-gray-200">SIMARSIP</h1>
            </div>
            
            <!-- Menu Sidebar -->
            <nav class="px-4 space-y-1">
                <a href="{{ route('superadmin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>  Dashboard
                </a>
                
                <!-- Kelola Arsip Dropdown (Terbuka) -->
                <div class="pt-1">
                    <button class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-200 transition-colors focus:outline-none">
                        <span class="flex items-center gap-3"><i class="fa-regular fa-folder w-5"></i> Kelola Arsip</span>
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </button>
                    
                    <div class="flex flex-col gap-1 pt-1 pb-2">
                        <!-- Menu Aktif -->
                        <a href="{{ route('superadmin.arsip.penghapusan') }}" class="block px-4 py-2.5 ml-4 mr-2 bg-blue-600 text-white rounded-lg text-sm font-semibold transition shadow-sm">Penghapusan Arsip</a>
                        <a href="{{ route('superadmin.arsip.laporan') }}" class="block pl-12 py-2 text-sm font-medium text-gray-400 hover:text-white transition">Laporan Arsip</a>
                    </div>
                </div>

                <a href="{{ route('superadmin.peminjaman.validasi') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-regular fa-circle-check w-5"></i> Validasi Peminjaman
                </a>
                <a href="{{ route('superadmin.users')}}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-users w-5"></i> Kelola User
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-regular fa-file-lines w-5"></i> Panduan Penggunaan
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <i class="fa-solid fa-gear w-5"></i> Pengaturan Sistem
                </a>
            </nav>
        </div>
        
        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Apakah Anda yakin ingin logout?');">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-500 hover:text-red-400 transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
                </button>
            </form>
    </aside>

    <!-- AREA KONTEN UTAMA -->
    <main class="flex-1 ml-64 min-h-screen flex flex-col">
        <!-- Header -->
        <header class="px-8 py-5 flex justify-end items-center bg-[#f4f7f9] sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <span class="font-bold text-gray-900 text-sm">Super Admin</span>
                <i class="fa-solid fa-circle-user text-3xl text-gray-800"></i>
            </div>
        </header>

        <div class="px-8 pb-10 w-full max-w-[1400px]">
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

            <h2 class="text-3xl font-extrabold mb-8 text-black tracking-tight">Penghapusan Arsip</h2>
            
            <!-- CARD STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 max-w-4xl">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip</p>
                    <p class="text-5xl font-extrabold text-gray-900">{{ $totalArsip }}</p>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Dokumen</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip Kelompok Kerja</p>
                    <p class="text-5xl font-extrabold text-gray-900">{{ $totalArsipPokja }}</p>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Dokumen</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-gray-400 text-sm font-bold mb-2">Total Arsip TU</p>
                    <p class="text-5xl font-extrabold text-gray-900">{{ $totalArsipTu }}</p>
                    <p class="text-sm text-gray-400 mt-2 font-medium">Dokumen</p>
                </div>
            </div>

            <!-- PERINGATAN HAPUS ARSIP -->
            <div class="mb-6 w-full">
                <button id="delete-mode-toggle" type="button" class="w-full rounded-lg border border-red-300 bg-white py-3 text-sm font-bold tracking-wide text-red-600 shadow-sm transition hover:bg-red-50">
                    Hapus Arsip
                </button>
            </div>

            <!-- FILTER PENCARIAN -->
            <form method="GET" action="{{ route('superadmin.arsip.penghapusan') }}" class="mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="flex flex-col">
                        <input type="text" name="search" value="{{ $filters['search'] }}" placeholder="Nama, nomor, klasifikasi, kategori, atau Pokja" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                    
                    <div class="flex flex-col relative">
                        <select name="sumber" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-500 appearance-none cursor-pointer">
                            <option value="semua" {{ $filters['source'] === 'semua' ? 'selected' : '' }}>Semua Sumber</option>
                            <option value="kelompok_kerja" {{ $filters['source'] === 'kelompok_kerja' ? 'selected' : '' }}>Arsip Kelompok Kerja</option>
                            <option value="tu" {{ $filters['source'] === 'tu' ? 'selected' : '' }}>Arsip TU</option>
                        </select>
                        <i class="fa-solid fa-chevron-right absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>

                    <div class="flex flex-col relative">
                        <input type="date" name="tanggal" value="{{ $filters['tanggal'] }}" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-500 cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col relative">
                        <select name="kategori_id" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-500 appearance-none cursor-pointer">
                            <option value="">Kategori Surat</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ (string) $filters['kategoriId'] === (string) $kategori->id ? 'selected' : '' }}>{{ $kategori->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-right absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>

                    <div class="flex flex-col relative">
                        <select name="pokja_id" class="w-full border border-gray-300 bg-white rounded-lg px-4 py-3 text-sm text-gray-700 focus:outline-none focus:border-blue-500 appearance-none cursor-pointer">
                            <option value="">Kelompok Kerja</option>
                            @foreach ($pokjas as $pokja)
                                <option value="{{ $pokja->id }}" {{ (string) $filters['pokjaId'] === (string) $pokja->id ? 'selected' : '' }}>{{ $pokja->name }}</option>
                            @endforeach
                        </select>
                        <i class="fa-solid fa-chevron-right absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs"></i>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center gap-3">
                        <button type="submit" class="bg-[#2563eb] text-white px-8 py-3 rounded-lg font-bold text-sm hover:bg-blue-700 transition shadow-sm whitespace-nowrap">
                            Filter
                        </button>
                        <a href="{{ route('superadmin.arsip.penghapusan') }}" class="bg-gray-100 text-gray-600 border border-gray-200 px-6 py-3 rounded-lg font-bold text-sm hover:bg-gray-200 hover:text-gray-900 transition text-center whitespace-nowrap">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- TABEL PENGHAPUSAN -->
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[900px] text-left border-collapse">
                        <thead class="bg-slate-50">
                            <tr class="border-b border-gray-200">
                                <th class="py-4 px-4 text-sm font-bold text-[#445585]">Nama Arsip</th>
                                <th class="py-4 px-4 text-sm font-bold text-[#445585]">Kategori</th>
                                <th class="py-4 px-4 text-sm font-bold text-[#445585]">Kelompok Kerja</th>
                                <th class="py-4 px-4 text-sm font-bold text-[#445585]">Tanggal Upload</th>
                                <th class="py-4 px-4 text-sm font-bold text-[#445585]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($arsips as $arsip)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="py-4 px-4 text-sm font-bold text-gray-800">{{ $arsip->nama_arsip }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $arsip->kategori?->name ?? '-' }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $arsip->pokja?->name ?? '-' }}</td>
                                    <td class="py-4 px-4 text-sm text-gray-600 font-medium">{{ $arsip->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-2">
                                            @if ($arsip->file_path)
                                                <a href="{{ asset('storage/' . $arsip->file_path) }}" target="_blank" rel="noopener" class="inline-block bg-[#2563eb] text-white px-5 py-1.5 rounded text-xs font-bold hover:bg-blue-700 transition shadow-sm">
                                                    Lihat
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400 italic px-2">Tidak tersedia</span>
                                            @endif
                                            
                                            <!-- Logic Form Hapus Tetap Utuh -->
                                            <form method="POST" action="{{ route('superadmin.arsip.delete', $arsip) }}" class="delete-archive-form hidden">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-600 text-white px-5 py-1.5 rounded text-xs font-bold hover:bg-red-700 transition shadow-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="bg-white py-12 px-6 text-center text-sm text-gray-500">
                                        {{ $filters['search'] || $filters['tanggal'] || $filters['kategoriId'] || $filters['pokjaId'] || $filters['source'] !== 'semua' ? 'Tidak ada arsip yang sesuai dengan filter.' : 'Belum ada data arsip.' }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $arsips->withQueryString()->links() }}
            </div>
            
        </div>
    </main>
    <div id="delete-confirmation" class="fixed inset-0 z-[110] hidden items-center justify-center p-4" aria-hidden="true">
        <div data-delete-overlay class="absolute inset-0 bg-slate-950/50 opacity-0 transition-opacity duration-200"></div>
        <section data-delete-dialog class="relative w-full max-w-sm translate-y-2 scale-95 rounded-2xl bg-white p-6 opacity-0 shadow-2xl transition duration-200" role="dialog" aria-modal="true" aria-labelledby="delete-confirmation-title">
            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-full bg-red-50 text-red-600"><i class="fa-solid fa-trash" aria-hidden="true"></i></div>
            <h2 id="delete-confirmation-title" class="text-lg font-bold text-slate-900">Konfirmasi Hapus Arsip</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">Apakah Anda yakin ingin menghapus arsip ini?</p>
            <div class="mt-6 flex justify-end gap-3"><button type="button" data-delete-cancel class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Batal</button><button type="button" data-delete-confirm class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">Hapus</button></div>
        </section>
    </div>
    <script>
        (() => {
            const toggle = document.getElementById('delete-mode-toggle');
            const modal = document.getElementById('delete-confirmation');
            const overlay = modal.querySelector('[data-delete-overlay]');
            const dialog = modal.querySelector('[data-delete-dialog]');
            let selectedForm = null;
            const close = () => { overlay.classList.remove('opacity-100'); dialog.classList.remove('opacity-100', 'translate-y-0', 'scale-100'); setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 200); };
            toggle.addEventListener('click', () => {
                const isActive = toggle.getAttribute('aria-pressed') === 'true';
                toggle.setAttribute('aria-pressed', String(!isActive));
                toggle.textContent = isActive ? 'Hapus Arsip' : 'Batalkan Mode Hapus';
                document.querySelectorAll('.delete-archive-form').forEach((form) => form.classList.toggle('hidden', isActive));
            });
            document.querySelectorAll('.delete-archive-form').forEach((form) => form.addEventListener('submit', (event) => { event.preventDefault(); selectedForm = form; modal.classList.remove('hidden'); modal.classList.add('flex'); requestAnimationFrame(() => { overlay.classList.add('opacity-100'); dialog.classList.add('opacity-100', 'translate-y-0', 'scale-100'); }); }));
            modal.querySelector('[data-delete-cancel]').addEventListener('click', close);
            overlay.addEventListener('click', close);
            modal.querySelector('[data-delete-confirm]').addEventListener('click', () => selectedForm?.submit());
        })();

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
    </script>
    <x-logout-confirmation />
</body>
</html>
