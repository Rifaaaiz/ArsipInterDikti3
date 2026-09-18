<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KetuaPokjaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $pokjaId = $user->pokja_id;

        $arsipQuery = Arsip::where('pokja_id', $pokjaId)
            ->whereNotIn('status', ['dihapus']);

        $peminjamanQuery = Peminjaman::whereHas('user', function ($query) use ($pokjaId) {
            $query->where('pokja_id', $pokjaId);
        });

        $peminjamanMenungguKetua = Peminjaman::with(['user', 'arsip', 'kategori'])
            ->needsKetuaValidation()
            ->whereHas('user', function ($query) use ($pokjaId) {
                $query->where('pokja_id', $pokjaId);
            });

        $totalArsip = (clone $arsipQuery)->count();
        $totalPeminjaman = (clone $peminjamanQuery)->count();
        $peminjamanAktif = (clone $peminjamanQuery)
            ->activeNow()
            ->count();
        $menungguValidasi = (clone $peminjamanMenungguKetua)->count();
        $disetujui = (clone $peminjamanQuery)
            ->where('status', 'disetujui')
            ->count();
        $ditolak = (clone $peminjamanQuery)
            ->where('status', 'ditolak')
            ->count();

        $arsipTerbaru = (clone $arsipQuery)
            ->with('kategori')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $peminjamanTerbaru = (clone $peminjamanMenungguKetua)
            ->latest('created_at')
            ->limit(5)
            ->get();

        return view('Ketua_Pokja.dashboard_ketuapokja', compact(
            'user',
            'totalArsip',
            'totalPeminjaman',
            'peminjamanAktif',
            'menungguValidasi',
            'disetujui',
            'ditolak',
            'arsipTerbaru',
            'peminjamanTerbaru'
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

        $arsips = Arsip::with('kategori')
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
        $filter = $request->query('filter', 'semua');

        if (! in_array($filter, ['semua', 'arsip', 'peminjaman'], true)) {
            $filter = 'semua';
        }

        $arsipQuery = DB::table('arsips')
            ->leftJoin('kategori_arsips', 'kategori_arsips.id', '=', 'arsips.kategori_arsip_id')
            ->where('arsips.pokja_id', $user->pokja_id)
            ->whereNotIn('arsips.status', ['dihapus'])
            ->select([
                DB::raw("'arsip' as jenis"),
                'arsips.id',
                'arsips.nama_arsip as nama',
                'kategori_arsips.name as kategori',
                'arsips.created_at as tanggal',
                'arsips.status',
                'arsips.file_path as file_path',
            ]);

        $peminjamanQuery = DB::table('peminjamans')
            ->leftJoin('arsips as arsip_peminjaman', 'arsip_peminjaman.id', '=', 'peminjamans.arsip_id')
            ->leftJoin('kategori_arsips as kategori_peminjaman', 'kategori_peminjaman.id', '=', 'peminjamans.kategori_id')
            ->leftJoin('users', 'users.id', '=', 'peminjamans.user_id')
            ->where('users.pokja_id', $user->pokja_id)
            ->when($filter === 'peminjaman', function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('peminjamans.status', 'selesai')
                        ->orWhere(function ($dateQuery) {
                            $dateQuery->where('peminjamans.status', 'aktif')
                                ->whereDate('peminjamans.tanggal_selesai', '<', now()->toDateString());
                        });
                });
            })
            ->select([
                DB::raw("'peminjaman' as jenis"),
                'peminjamans.id',
                DB::raw('COALESCE(arsip_peminjaman.nama_arsip, peminjamans.nama_surat) as nama'),
                DB::raw('COALESCE(kategori_peminjaman.name, kategori_arsip.name) as kategori'),
                'peminjamans.created_at as tanggal',
                'peminjamans.status',
                DB::raw('COALESCE(arsip_peminjaman.file_path, peminjamans.file_pengajuan) as file_path'),
            ])
            ->leftJoin('kategori_arsips as kategori_arsip', 'kategori_arsip.id', '=', 'arsip_peminjaman.kategori_arsip_id');

        $historyQuery = match ($filter) {
            'arsip' => $arsipQuery,
            'peminjaman' => $peminjamanQuery,
            default => $arsipQuery->unionAll($peminjamanQuery),
        };

        $history = DB::query()
            ->fromSub($historyQuery, 'history')
            ->orderByDesc('tanggal')
            ->orderByDesc('id')
            ->paginate(10);

        return view('Ketua_Pokja.history_ketuapokja', compact('history', 'filter'));
    }

    public function riwayatPeminjamanTerakhir()
    {
        $user = Auth::user();

        $peminjamans = Peminjaman::with(['kategori', 'arsip', 'user'])
            ->whereHas('user', function ($query) use ($user) {
                $query->where('pokja_id', $user->pokja_id);
            })
            ->latest('created_at')
            ->paginate(10);

        return view('Ketua_Pokja.riwayat_peminjaman_terakhir_ketuapokja', compact('peminjamans'));
    }

    public function riwayatArsipTerakhir()
    {
        $user = Auth::user();

        $arsips = Arsip::with('kategori')
            ->where('pokja_id', $user->pokja_id)
            ->whereNotIn('status', ['dihapus'])
            ->latest('created_at')
            ->paginate(10);

        return view('Ketua_Pokja.riwayat_arsip_terakhir_ketuapokja', compact('arsips'));
    }

    public function peminjamanAktif()
    {
        $user = Auth::user();
        $pokjaId = $user->pokja_id;

        $peminjamans = Peminjaman::with(['user', 'kategori'])
            ->whereHas('user', function ($query) use ($pokjaId) {
                $query->where('pokja_id', $pokjaId);
            })
            ->activeNow()
            ->latest('tanggal_mulai')
            ->get();

        return view('Ketua_Pokja.peminjaman_aktif_ketuapokja', compact('peminjamans'));
    }
}
