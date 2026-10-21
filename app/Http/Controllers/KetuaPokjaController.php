<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;
use App\Models\Peminjaman;
use App\Models\SifatSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KetuaPokjaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user()->loadMissing('pokja');
        $pokjaId = $user->pokja_id;

        $arsipQuery = Arsip::where('pokja_id', $pokjaId)
            ->whereNotIn('status', ['dihapus']);

        $peminjamanQuery = Peminjaman::whereHas('user', function ($query) use ($pokjaId) {
            $query->where('pokja_id', $pokjaId);
        });

        $peminjamanMenungguKetua = Peminjaman::with([
            'user.pokja',
            'kategori',
            'sifatSurat',
            'klasifikasiArsip',
            'arsip.kategori',
            'arsip.pokja',
            'arsip.sifatSurat',
            'arsip.klasifikasiArsip',
        ])
            ->needsKetuaValidation()
            ->whereHas('user', function ($query) use ($pokjaId) {
                $query->where('pokja_id', $pokjaId);
            })
            ->where('user_id', '!=', $user->id)
            ->when(! $pokjaId, fn ($query) => $query->whereRaw('1 = 0'));

        $totalArsip = (clone $arsipQuery)->count();
        $peminjamanAktif = (clone $peminjamanQuery)
            ->activeNow()
            ->count();
        $menungguValidasi = (clone $peminjamanMenungguKetua)->count();

        $arsipTerbaru = (clone $arsipQuery)
            ->with([
                'kategori',
                'sifatSurat',
                'klasifikasiArsip',
            ])
            ->latest('created_at')
            ->limit(2)
            ->get();

        $pengajuanMenungguKetua = (clone $peminjamanMenungguKetua)
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('Ketua_Pokja.dashboard_ketuapokja', compact(
            'user',
            'totalArsip',
            'peminjamanAktif',
            'menungguValidasi',
            'arsipTerbaru',
            'pengajuanMenungguKetua'
        ));
    }

    public function ajukan()
    {
        $peminjamans = Peminjaman::with(['kategori', 'arsip'])
            ->where('user_id', Auth::id())
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('Ketua_Pokja.ajukan_peminjaman_ketuapokja', compact('peminjamans'));
    }

    public function upload()
    {
        $user = Auth::user();

        $arsips = Arsip::with(['kategori', 'sifatSurat'])
            ->where('pokja_id', $user->pokja_id)
            ->whereNotIn('status', ['dihapus'])
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('Ketua_Pokja.upload_arsip_ketuapokja', compact('arsips'));
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $filters = $request->validate([
            'filter' => ['nullable', 'in:semua,arsip,peminjaman'],
            'nama_surat' => ['nullable', 'string', 'max:255'],
            'nomor_dokumen' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
        ]);
        $filter = $filters['filter'] ?? 'semua';
        $search = trim($filters['nama_surat'] ?? '');
        $nomorDokumen = trim($filters['nomor_dokumen'] ?? '');
        $jenisSuratId = $filters['jenis_surat_id'] ?? null;
        $sifatSuratId = $filters['sifat_surat_id'] ?? null;
        $tanggalDokumen = $filters['tanggal_dokumen'] ?? null;
        $klasifikasiId = $filters['klasifikasi_arsip_id'] ?? null;

        $arsipQuery = DB::table('arsips')
            ->leftJoin('jenis_surats as arsip_jenis', 'arsip_jenis.id', '=', 'arsips.kategori_arsip_id')
            ->leftJoin('sifat_surats as arsip_sifat', 'arsip_sifat.id', '=', 'arsips.sifat_surat_id')
            ->leftJoin('klasifikasi_arsips as arsip_klasifikasi', 'arsip_klasifikasi.id', '=', 'arsips.klasifikasi_arsip_id')
            ->leftJoin('pokjas as arsip_pokja', 'arsip_pokja.id', '=', 'arsips.pokja_id')
            ->where('arsips.pokja_id', $user->pokja_id)
            ->whereNotIn('arsips.status', ['dihapus'])
            ->when($search !== '', fn ($query) => $query->whereRaw('LOWER(arsips.nama_arsip) LIKE ?', ['%' . mb_strtolower($search) . '%']))
            ->when($nomorDokumen !== '', fn ($query) => $query->whereRaw('LOWER(arsips.nomor_dokumen) LIKE ?', ['%' . mb_strtolower($nomorDokumen) . '%']))
            ->when($jenisSuratId, fn ($query) => $query->where('arsips.kategori_arsip_id', $jenisSuratId))
            ->when($sifatSuratId, fn ($query) => $query->where('arsips.sifat_surat_id', $sifatSuratId))
            ->when($tanggalDokumen, fn ($query) => $query->whereDate('arsips.tanggal_dokumen', $tanggalDokumen))
            ->when($klasifikasiId, fn ($query) => $query->where('arsips.klasifikasi_arsip_id', $klasifikasiId))
            ->select([
                DB::raw("'arsip' as jenis"),
                'arsips.id',
                'arsips.nama_arsip as nama',
                'arsips.nomor_dokumen',
                'arsip_jenis.name as jenis_surat',
                'arsip_sifat.name as sifat_surat',
                'arsip_pokja.name as pokja',
                'arsip_klasifikasi.kode as kode_klasifikasi',
                'arsips.created_at as tanggal',
                'arsips.status',
                'arsips.file_path as file_path',
            ]);

        $today = now()->toDateString();
        $peminjamanQuery = DB::table('peminjamans')
            ->leftJoin('arsips as arsip_peminjaman', 'arsip_peminjaman.id', '=', 'peminjamans.arsip_id')
            ->leftJoin('jenis_surats as jenis_peminjaman', 'jenis_peminjaman.id', '=', 'peminjamans.kategori_id')
            ->leftJoin('jenis_surats as jenis_arsip_peminjaman', 'jenis_arsip_peminjaman.id', '=', 'arsip_peminjaman.kategori_arsip_id')
            ->leftJoin('sifat_surats as sifat_peminjaman', 'sifat_peminjaman.id', '=', 'peminjamans.sifat_surat_id')
            ->leftJoin('sifat_surats as sifat_arsip_peminjaman', 'sifat_arsip_peminjaman.id', '=', 'arsip_peminjaman.sifat_surat_id')
            ->leftJoin('klasifikasi_arsips as klasifikasi_peminjaman', 'klasifikasi_peminjaman.id', '=', 'peminjamans.klasifikasi_arsip_id')
            ->leftJoin('klasifikasi_arsips as klasifikasi_arsip_peminjaman', 'klasifikasi_arsip_peminjaman.id', '=', 'arsip_peminjaman.klasifikasi_arsip_id')
            ->leftJoin('users', 'users.id', '=', 'peminjamans.user_id')
            ->leftJoin('pokjas as pokja_peminjaman', 'pokja_peminjaman.id', '=', 'users.pokja_id')
            ->where('users.pokja_id', $user->pokja_id)
            ->where(function ($query) use ($today) {
                $query->whereIn('peminjamans.status', ['ditolak', 'selesai', 'dikembalikan'])
                    ->orWhere(function ($expiredQuery) use ($today) {
                        $expiredQuery->whereIn('peminjamans.status', ['aktif', 'disetujui'])
                            ->whereDate('peminjamans.tanggal_selesai', '<', $today);
                    });
            })
            ->when($search !== '', function ($query) use ($search) {
                $pattern = '%' . mb_strtolower($search) . '%';
                $query->where(function ($searchQuery) use ($pattern) {
                    $searchQuery->whereRaw('LOWER(peminjamans.nama_surat) LIKE ?', [$pattern])
                        ->orWhereRaw('LOWER(arsip_peminjaman.nama_arsip) LIKE ?', [$pattern]);
                });
            })
            ->when($nomorDokumen !== '', fn ($query) => $query->whereRaw('LOWER(arsip_peminjaman.nomor_dokumen) LIKE ?', ['%' . mb_strtolower($nomorDokumen) . '%']))
            ->when($jenisSuratId, fn ($query) => $query->where(function ($jenisQuery) use ($jenisSuratId) {
                $jenisQuery->where('peminjamans.kategori_id', $jenisSuratId)
                    ->orWhere('arsip_peminjaman.kategori_arsip_id', $jenisSuratId);
            }))
            ->when($sifatSuratId, fn ($query) => $query->where(function ($sifatQuery) use ($sifatSuratId) {
                $sifatQuery->where('peminjamans.sifat_surat_id', $sifatSuratId)
                    ->orWhere('arsip_peminjaman.sifat_surat_id', $sifatSuratId);
            }))
            ->when($tanggalDokumen, fn ($query) => $query->where(function ($dateQuery) use ($tanggalDokumen) {
                $dateQuery->whereDate('arsip_peminjaman.tanggal_dokumen', $tanggalDokumen)
                    ->orWhere(function ($standaloneQuery) use ($tanggalDokumen) {
                        $standaloneQuery->whereNull('peminjamans.arsip_id')
                            ->whereDate('peminjamans.created_at', $tanggalDokumen);
                    });
            }))
            ->when($klasifikasiId, fn ($query) => $query->where(function ($klasifikasiQuery) use ($klasifikasiId) {
                $klasifikasiQuery->where('peminjamans.klasifikasi_arsip_id', $klasifikasiId)
                    ->orWhere('arsip_peminjaman.klasifikasi_arsip_id', $klasifikasiId);
            }))
            ->select([
                DB::raw("'peminjaman' as jenis"),
                'peminjamans.id',
                DB::raw('COALESCE(arsip_peminjaman.nama_arsip, peminjamans.nama_surat) as nama'),
                'arsip_peminjaman.nomor_dokumen',
                DB::raw('COALESCE(jenis_peminjaman.name, jenis_arsip_peminjaman.name) as jenis_surat'),
                DB::raw('COALESCE(sifat_peminjaman.name, sifat_arsip_peminjaman.name) as sifat_surat'),
                'pokja_peminjaman.name as pokja',
                DB::raw('COALESCE(klasifikasi_peminjaman.kode, klasifikasi_arsip_peminjaman.kode) as kode_klasifikasi'),
                'peminjamans.updated_at as tanggal',
                DB::raw("CASE WHEN peminjamans.status IN ('aktif', 'disetujui') AND peminjamans.tanggal_selesai < '{$today}' THEN 'selesai' ELSE peminjamans.status END as status"),
                DB::raw('COALESCE(peminjamans.file_pengajuan, arsip_peminjaman.file_path) as file_path'),
            ]);

        $historyQuery = match ($filter) {
            'arsip' => $arsipQuery,
            'peminjaman' => $peminjamanQuery,
            default => $arsipQuery->unionAll($peminjamanQuery),
        };

        $history = DB::query()
            ->fromSub($historyQuery, 'history')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('Ketua_Pokja.history_ketuapokja', [
            'history' => $history,
            'filter' => $filter,
            'filters' => $filters,
            'jenisSurats' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
        ]);
    }

    public function riwayatPeminjamanTerakhir(Request $request)
    {
        $user = Auth::user();
        $search = $this->normalizeSearch($request->query('search'));
        $searchPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($search));
        $kategoriId = $request->integer('kategori_id') ?: null;

        $peminjamans = Peminjaman::with(['kategori', 'arsip', 'user'])
            ->whereHas('user', function ($query) use ($user) {
                $query->where('pokja_id', $user->pokja_id);
            })
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(nama_surat) LIKE ?', [$pattern])
                            ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern]))
                            ->orWhereHas('kategori', fn ($kategoriQuery) => $kategoriQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]));
                    });
                }
            })
            ->when($kategoriId, fn ($query) => $query->where('kategori_id', $kategoriId))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('Ketua_Pokja.riwayat_peminjaman_terakhir_ketuapokja', [
            'peminjamans' => $peminjamans,
            'kategoris' => \App\Models\KategoriArsip::orderBy('name')->get(),
            'search' => $search,
            'kategoriId' => $kategoriId,
        ]);
    }

    public function riwayatArsipTerakhir(Request $request)
    {
        $user = Auth::user();
        $search = $this->normalizeSearch($request->query('search'));
        $searchPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($search));
        $nomorDokumen = $this->normalizeSearch($request->query('nomor_dokumen'));
        $documentPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($nomorDokumen));
        $kategoriId = $request->integer('kategori_id') ?: null;
        $tanggalDokumen = $request->query('tanggal_dokumen');

        $arsips = Arsip::with(['kategori', 'klasifikasiArsip', 'sifatSurat'])
            ->where('pokja_id', $user->pokja_id)
            ->whereNotIn('status', ['dihapus'])
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern])
                            ->orWhereHas('kategori', fn ($kategoriQuery) => $kategoriQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('klasifikasiArsip', function ($klasifikasiQuery) use ($pattern) {
                                $klasifikasiQuery->whereRaw('LOWER(kode) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(judul) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(uraian) LIKE ?', [$pattern]);
                            });
                    });
                }
            })
            ->when($documentPatterns !== [], function ($query) use ($documentPatterns) {
                foreach ($documentPatterns as $pattern) {
                    $query->whereRaw('LOWER(nomor_dokumen) LIKE ?', [$pattern]);
                }
            })
            ->when($kategoriId, fn ($query) => $query->where('kategori_arsip_id', $kategoriId))
            ->when($tanggalDokumen, fn ($query) => $query->whereDate('tanggal_dokumen', $tanggalDokumen))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('Ketua_Pokja.riwayat_arsip_terakhir_ketuapokja', [
            'arsips' => $arsips,
            'kategoris' => \App\Models\KategoriArsip::orderBy('name')->get(),
            'search' => $search,
            'nomorDokumen' => $nomorDokumen,
            'kategoriId' => $kategoriId,
            'tanggalDokumen' => $tanggalDokumen,
        ]);
    }

    public function peminjamanAktif(Request $request)
    {
        $user = Auth::user();
        $pokjaId = $user->pokja_id;
        $filters = $request->validate([
            'nama_surat' => ['nullable', 'string', 'max:255'],
            'nomor_dokumen' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'kategori_arsip_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
        ]);
        $nomorDokumen = $this->normalizeSearch($filters['nomor_dokumen'] ?? '');

        $peminjamanQuery = Peminjaman::with([
            'user',
            'kategori',
            'sifatSurat',
            'klasifikasiArsip',
            'arsip.kategori',
            'arsip.sifatSurat',
            'arsip.klasifikasiArsip',
        ])
            ->whereHas('user', function ($query) use ($pokjaId) {
                $query->where('pokja_id', $pokjaId);
            })
            ->activeNow();

        if (! $pokjaId) {
            $peminjamanQuery->whereRaw('1 = 0');
        }

        $peminjamans = $peminjamanQuery
            ->when($filters['nama_surat'] ?? null, function ($query, $namaSurat) {
                $pattern = '%' . mb_strtolower(trim($namaSurat)) . '%';
                $query->where(function ($searchQuery) use ($pattern) {
                    $searchQuery->whereRaw('LOWER(peminjamans.nama_surat) LIKE ?', [$pattern])
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern]));
                });
            })
            ->when($nomorDokumen !== '', function ($query) use ($nomorDokumen) {
                $pattern = $this->searchLikePattern(mb_strtolower($nomorDokumen));
                $query->whereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereRaw('LOWER(nomor_dokumen) LIKE ?', [$pattern]));
            })
            ->when($filters['jenis_surat_id'] ?? null, function ($query, $id) {
                $query->where(function ($jenisQuery) use ($id) {
                    $jenisQuery->where('peminjamans.kategori_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $id));
                });
            })
            ->when($filters['sifat_surat_id'] ?? null, function ($query, $id) {
                $query->where(function ($sifatQuery) use ($id) {
                    $sifatQuery->where('peminjamans.sifat_surat_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('sifat_surat_id', $id));
                });
            })
            ->when($filters['kategori_arsip_id'] ?? null, fn ($query, $id) => $query->whereHas(
                'arsip',
                fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $id)
            ))
            ->when($filters['tanggal_dokumen'] ?? null, fn ($query, $date) => $query->whereHas(
                'arsip',
                fn ($arsipQuery) => $arsipQuery->whereDate('tanggal_dokumen', $date)
            ))
            ->when($filters['klasifikasi_arsip_id'] ?? null, function ($query, $id) {
                $query->where(function ($klasifikasiQuery) use ($id) {
                    $klasifikasiQuery->where('peminjamans.klasifikasi_arsip_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('klasifikasi_arsip_id', $id));
                });
            })
            ->latest('tanggal_mulai')
            ->paginate(10)
            ->withQueryString();

        $jenisSurats = KategoriArsip::orderBy('name')->get();
        $kategoris = KategoriArsip::orderBy('name')->get();
        $sifatSurats = SifatSurat::orderBy('name')->get();
        $klasifikasis = KlasifikasiArsip::orderedForSelection()->get();

        return view('Ketua_Pokja.peminjaman_aktif_ketuapokja', compact(
            'peminjamans',
            'filters',
            'jenisSurats',
            'kategoris',
            'sifatSurats',
            'klasifikasis'
        ));
    }
}
