<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Masuk ADIA - Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media (min-width: 768px) {
            #superadmin-main { margin-left: 16rem; }
        }
    </style>
</head>
<body class="flex min-h-screen bg-[#f4f7f6] font-sans text-gray-800">
    @php
        $activePokja = $pokja;
        $activeMenu = 'surat-masuk';
    @endphp
    @include('Super_Admin.partials.sidebar_superadmin')

    <main id="superadmin-main" class="flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="sticky top-0 z-10 flex items-center justify-between gap-4 bg-[#f4f7f6] px-5 py-5 sm:px-8">
            <div class="flex min-w-0 items-center gap-3">
                <button type="button" data-sidebar-toggle aria-label="Buka navigasi" aria-expanded="false" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-700 shadow-sm md:hidden">
                    <i class="fa-solid fa-bars" aria-hidden="true"></i>
                </button>
                <h1 class="truncate text-xl font-bold text-gray-900 sm:text-2xl">Surat Masuk ADIA</h1>
            </div>
            <div class="flex shrink-0 items-center gap-3">
                <span class="text-sm font-bold text-gray-900">{{ $user?->name ?? 'Super Admin' }}</span>
                <i class="fa-solid fa-circle-user text-2xl text-black" aria-hidden="true"></i>
            </div>
        </header>

        <section class="w-full min-w-0 flex-1 px-5 pb-10 sm:px-8">
            @if (session('success'))
                <div role="status" class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">{{ session('success') }}</div>
            @endif

            <div class="mb-3 flex items-center justify-between gap-3">
                <h2 class="text-lg font-bold text-gray-900 sm:text-xl">Surat Masuk Terbaru ADIA</h2>
                <span class="shrink-0 text-sm text-gray-500">{{ $suratMasuk->total() }} surat</span>
            </div>

            <div class="overflow-hidden rounded-lg border border-gray-300 bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1050px] border-collapse text-left">
                        <thead class="bg-gray-50">
                            <tr class="border-b border-gray-200">
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Nama Arsip</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Jenis Surat</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Sifat Surat</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Kode Klasifikasi</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Tanggal Persetujuan</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Tanggal Mulai</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Tanggal Selesai</th>
                                <th class="px-4 py-3 text-xs font-bold text-indigo-900">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-bold text-indigo-900">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($suratMasuk as $item)
                                @php
                                    $arsip = $item->arsip;
                                    $status = match (strtolower((string) $item->status)) {
                                        'disetujui', 'acc' => 'Disetujui',
                                        'aktif' => 'Aktif',
                                        'selesai' => 'Selesai',
                                        default => ucfirst(str_replace('_', ' ', (string) $item->status)),
                                    };
                                    $isApproved = in_array(strtolower((string) $item->status), ['disetujui', 'aktif', 'acc'], true);
                                    $statusColor = $isApproved ? 'text-green-700' : 'text-gray-600';
                                    $statusDot = $isApproved ? 'bg-green-500' : 'bg-gray-400';
                                    $namaSurat = $item->nama_surat ?: $arsip?->nama_arsip;
                                    $tanggalPersetujuan = $item->validations->sortByDesc('validated_at')->first()?->validated_at;
                                @endphp
                                <tr class="text-xs text-gray-600 transition hover:bg-gray-50">
                                    <td class="px-4 py-2.5 font-medium">{{ $namaSurat ?: '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $item->kategori?->name ?? $arsip?->kategori?->name ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $item->sifatSurat?->name ?? $arsip?->sifatSurat?->name ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $item->klasifikasiArsip?->kode ?? $arsip?->klasifikasiArsip?->kode ?? '-' }}</td>
                                    <td class="whitespace-nowrap px-4 py-2.5">{{ $tanggalPersetujuan?->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $item->tanggal_mulai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-4 py-2.5">{{ $item->tanggal_selesai?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-4 py-2.5">
                                        <span class="inline-flex items-center gap-1.5 whitespace-nowrap {{ $statusColor }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $statusDot }}"></span>{{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex justify-center gap-1">
                                            <a href="{{ route('superadmin.surat-masuk.peminjaman.detail', [$pokja->slug, $item]) }}" class="rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700">Lihat</a>
                                            <a href="{{ route('superadmin.surat-masuk.peminjaman.balas', [$pokja->slug, $item]) }}" class="rounded-md border border-blue-400 px-3 py-1.5 text-xs font-semibold text-blue-700 transition hover:bg-blue-50">Balas</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-5 py-12 text-center text-sm text-gray-500">Belum ada surat peminjaman yang disetujui untuk Pokja ADIA.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-5">{{ $suratMasuk->links() }}</div>
        </section>
    </main>
</body>
</html>
