<?php

namespace App\Http\Controllers;

use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;
use App\Models\Peminjaman;
use App\Models\Pokja;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuperadminSuratKeluarController extends Controller
{
    private const POKJA_VIEWS = [
        'anti-dosa-pendidikan-dan-integritas-akademik' => ['view' => 'surat_keluar_adia', 'title' => 'ADIA'],
        'pembelajaran-kemahasiswaan-dan-prestasi' => ['view' => 'surat_keluar_belmawa', 'title' => 'BELMAWA'],
        'hukum-kepegawaian-dan-tata-laksana' => ['view' => 'surat_keluar_hkt', 'title' => 'HKT'],
        'hubungan-masyarakat-dan-kerja-sama' => ['view' => 'surat_keluar_humas', 'title' => 'HUMAS'],
        'kelembagaan-dan-kemitraan' => ['view' => 'surat_keluar_kelembagaan', 'title' => 'Kelembagaan'],
        'perencanaan-dan-keuangan' => ['view' => 'surat_keluar_keuangan', 'title' => 'Keuangan'],
        'penjaminan-mutu' => ['view' => 'surat_keluar_penjamin_mutu', 'title' => 'Penjamin Mutu'],
        'riset-dan-pengabdian-masyarakat' => ['view' => 'surat_keluar_rpm', 'title' => 'RPM'],
        'sistem-informasi-dan-pddikti' => ['view' => 'surat_keluar_si_pddikti', 'title' => 'SI PDDIKTI'],
        'sumber-daya' => ['view' => 'surat_keluar_sumber_daya', 'title' => 'Sumber Daya'],
    ];

    public function index(Request $request, string $pokjaSlug)
    {
        $slug = Str::slug($pokjaSlug);
        $config = self::POKJA_VIEWS[$slug] ?? null;
        abort_unless($config, 404);

        $pokja = Pokja::where('slug', $slug)->firstOrFail();
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
        ]);
        $search = trim($filters['search'] ?? '');

        $suratKeluar = Peminjaman::with([
                'user.pokja',
                'kategori',
                'klasifikasiArsip',
                'arsip.kategori',
                'arsip.klasifikasiArsip',
                'balasans.user',
            ])
            ->whereIn('status', ['disetujui', 'aktif', 'selesai'])
            ->whereHas('user', fn ($query) => $query->where('pokja_id', $pokja->id))
            ->whereHas('balasans')
            ->when($search !== '', function ($query) use ($search) {
                $pattern = '%' . mb_strtolower($search) . '%';
                $query->where(function ($searchQuery) use ($pattern) {
                    $searchQuery->whereRaw('LOWER(nama_surat) LIKE ?', [$pattern])
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern]));
                });
            })
            ->when($filters['jenis_surat_id'] ?? null, function ($query, $jenisSuratId) {
                $query->where(function ($jenisQuery) use ($jenisSuratId) {
                    $jenisQuery->where('kategori_id', $jenisSuratId)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $jenisSuratId));
                });
            })
            ->when($filters['klasifikasi_arsip_id'] ?? null, function ($query, $klasifikasiId) {
                $query->where(function ($klasifikasiQuery) use ($klasifikasiId) {
                    $klasifikasiQuery->where('klasifikasi_arsip_id', $klasifikasiId)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('klasifikasi_arsip_id', $klasifikasiId));
                });
            })
            ->when($filters['tanggal_dokumen'] ?? null, fn ($query, $date) => $query->whereHas(
                'balasans',
                fn ($balasanQuery) => $balasanQuery->whereDate('created_at', $date)
            ))
            ->orderByDesc(
                \App\Models\SuratMasukBalasan::query()
                    ->select('created_at')
                    ->whereColumn('peminjaman_id', 'peminjamans.id')
                    ->latest()
                    ->limit(1)
            )
            ->orderByDesc('peminjamans.id')
            ->paginate(10)
            ->withQueryString();

        return view('Super_Admin.' . $config['view'], [
            'pokja' => $pokja,
            'pokjaTitle' => $config['title'],
            'pokjas' => Pokja::orderBy('name')->get(),
            'user' => $request->user(),
            'suratKeluar' => $suratKeluar,
            'filters' => $filters,
            'search' => $search,
            'jenisSurats' => KategoriArsip::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
        ]);
    }
}
