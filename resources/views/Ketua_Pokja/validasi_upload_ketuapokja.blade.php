<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Usulan Upload - SIMARSIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#f8f9fa] flex font-sans text-gray-800">
    <aside class="w-64 bg-[#0a1120] text-white min-h-screen flex flex-col justify-between fixed top-0 left-0 z-20">
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
                <!-- Dashboard -->
                <a href="/ketua-pokja" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition">
                    <svg class="w-4 h-4 fill-none stroke-current stroke-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="9 22 9 12 15 12 15 22"></polyline>
                    </svg> Dashboard
                </a>

                <!-- Validasi Usulan (Menu Aktif - Biru) -->
                <a href="{{ url('/ketua-pokja/validasi') }}" class="flex items-center gap-3 bg-blue-600 text-white px-4 py-3 rounded-lg text-sm font-semibold transition shadow-sm mt-1">
                    <i class="fa-regular fa-circle-check"></i> Validasi Upload
                </a>

                <!-- Profil -->
                <a href="{{ url('/ketua-pokja/profil') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-user"></i> Profil
                </a>
                
                <!-- History -->
                <a href="{{ url('/ketua-pokja/history') }}" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
                    <i class="fa-regular fa-clock"></i> History
                </a>
                
                <!-- Panduan Penggunaan -->
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-200 hover:text-white hover:bg-gray-800 rounded-lg transition mt-1">
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

    <main class="ml-64 flex min-h-screen flex-1 flex-col bg-[#f4f4f5] p-5 sm:p-8">
        <header class="mb-8 flex justify-end">
            <div class="flex items-center gap-3">
                <span class="text-base font-bold text-gray-900">Ketua Pokja Kelembagaan</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>
        <h2 class="mb-5 text-3xl font-bold text-black">Validasi Usulan Upload</h2>
        @if (session('success'))
            <div data-flash-success="{{ session('success') }}" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
        @endif
        @if (session('info'))
            <div data-flash-info="{{ session('info') }}" class="mb-5 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">{{ session('info') }}</div>
        @endif

        <form id="approve-all-form" method="POST" action="{{ route('ketua.peminjaman.approve-all') }}" class="mb-5">
            @csrf
            <button type="submit" class="w-full rounded-lg bg-blue-600 px-6 py-3.5 text-base font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Terima Semua
            </button>
        </form>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-[1050px] w-full border-collapse text-left">
                    <thead class="border-b border-gray-200 bg-gray-50"><tr>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Nama Arsip</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Jenis Surat</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Kode Klasifikasi</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Tanggal Mulai</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Tanggal Selesai</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Status</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-700">Aksi</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse ($pengajuan as $item)
                            @php
                                $filePath = $item->file_pengajuan ?: $item->arsip?->file_path;
                                $jenisSurat = $item->kategori?->name ?? $item->arsip?->kategori?->name;
                                $kodeKlasifikasi = $item->klasifikasiArsip?->kode ?? $item->arsip?->klasifikasiArsip?->kode;
                            @endphp
                            <tr class="transition hover:bg-gray-50">
                                <td class="px-6 py-4 font-semibold text-gray-700">{{ $item->arsip?->nama_arsip ?? $item->nama_surat ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $jenisSurat ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $kodeKlasifikasi ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ $item->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ $item->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 font-semibold text-amber-700">
                                        <span class="h-2 w-2 rounded-full bg-amber-500"></span>Menunggu Validasi
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                    @if ($filePath)
                                        <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($filePath) }}" target="_blank" rel="noopener noreferrer" class="rounded-md bg-blue-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                    @else
                                        <span class="text-xs text-gray-400">File tidak tersedia</span>
                                    @endif
                                    <form method="POST" action="{{ route('ketua.peminjaman.decision', $item) }}" data-confirm-title="Apakah anda yakin untuk menvalidasi surat ini?" data-confirm-button="Ya, Terima" data-confirm-color="#16a34a">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="decision" value="approve">
                                        <button type="submit" class="rounded-md bg-green-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-green-700">Terima</button>
                                    </form>
                                    <form method="POST" action="{{ route('ketua.peminjaman.decision', $item) }}" data-confirm-title="Apakah anda yakin untuk menolak surat ini?" data-confirm-button="Ya, Tolak" data-confirm-color="#dc2626">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="decision" value="reject">
                                        <button type="submit" class="rounded-md bg-red-600 px-4 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700">Tolak</button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada usulan yang perlu divalidasi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-5 flex flex-col items-center gap-3">
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
    </main>
    <x-logout-confirmation />
</body>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        const askConfirmation = (form, title, confirmButtonText, confirmColor) => {
            Swal.fire({
                title,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText,
                cancelButtonText: 'Batal',
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        };

        document.getElementById('approve-all-form')?.addEventListener('submit', (event) => {
            event.preventDefault();
            askConfirmation(event.currentTarget, 'Apakah anda yakin untuk terima semua surat ini?', 'Ya, Terima Semua', '#2563eb');
        });

        document.querySelectorAll('form[data-confirm-title]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();
                askConfirmation(
                    event.currentTarget,
                    event.currentTarget.dataset.confirmTitle,
                    event.currentTarget.dataset.confirmButton,
                    event.currentTarget.dataset.confirmColor
                );
            });
        });

        @if (session('success'))
            Swal.fire({ icon: 'success', title: @json(session('success')), confirmButtonColor: '#2563eb' });
        @elseif (session('info'))
            Swal.fire({ icon: 'info', title: @json(session('info')), confirmButtonColor: '#2563eb' });
        @endif
    </script>
</html>
