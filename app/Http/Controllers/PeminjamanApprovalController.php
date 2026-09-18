<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\PeminjamanValidation;
use App\Models\Arsip;
use App\Models\Pokja;
use App\Models\KategoriArsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeminjamanApprovalController extends Controller
{
    public function tuActiveLoans()
    {
        $user = Auth::user();

        $peminjamans = Peminjaman::with(['user.role', 'user.pokja', 'kategori', 'arsip'])
            ->activeNow()
            ->latest('tanggal_mulai')
            ->paginate(10);

        return view('Admin_TU.peminjaman_aktif_admintu', compact('peminjamans', 'user'));
    }

    public function tuArchiveReport(Request $request)
    {
        $pokjaId = $request->integer('pokja_id') ?: null;

        $arsips = Arsip::with(['pokja', 'kategori', 'uploader'])
            ->whereNotIn('status', ['dihapus'])
            ->when($pokjaId, fn ($query) => $query->where('pokja_id', $pokjaId))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $pokjas = Pokja::orderBy('name')->get();
        $totalArsip = Arsip::whereNotIn('status', ['dihapus'])->count();
        $totalArsipPokja = $pokjaId
            ? Arsip::where('pokja_id', $pokjaId)->whereNotIn('status', ['dihapus'])->count()
            : $totalArsip;

        return view('Admin_TU.laporanarsip_admintu', compact(
            'arsips',
            'pokjas',
            'pokjaId',
            'totalArsip',
            'totalArsipPokja'
        ));
    }

    public function tuDashboard()
    {
        $menungguValidasi = Peminjaman::with(['user.role', 'user.pokja', 'kategori', 'arsip'])
            ->readyForFinalValidation()
            ->latest('created_at')
            ->limit(5)
            ->get();

        $peminjamanAktif = Peminjaman::with(['user.role', 'user.pokja', 'kategori', 'arsip'])
            ->activeNow()
            ->latest('tanggal_mulai')
            ->limit(5)
            ->get();

        $totalPeminjamanAktif = Peminjaman::activeNow()->count();
        $totalMenungguValidasi = Peminjaman::readyForFinalValidation()->count();
        $totalArsip = Arsip::whereNotIn('status', ['dihapus'])->count();

        return view('Admin_TU.dashboard_admintu', compact(
            'menungguValidasi',
            'peminjamanAktif',
            'totalPeminjamanAktif',
            'totalMenungguValidasi',
            'totalArsip'
        ));
    }

    public function ketuaIndex(Request $request)
    {
        $user = Auth::user();

        $pengajuan = Peminjaman::with(['user', 'kategori', 'arsip'])
            ->needsKetuaValidation()
            ->whereHas('user', function ($query) use ($user) {
                $query->where('pokja_id', $user->pokja_id);
            })
            ->latest('created_at')
            ->paginate(10);

        return view('Ketua_Pokja.validasi_upload_ketuapokja', compact('pengajuan'));
    }

    public function ketuaDecision(Request $request, Peminjaman $peminjaman)
    {
        $user = Auth::user();

        abort_unless($user && $user->role && $user->role->slug === 'ketua_pokja', 403, 'Hanya Ketua Pokja yang berhak membuka halaman validasi ini.');
        abort_if(! $peminjaman->user, 404, 'Data pengaju peminjaman tidak ditemukan.');
        abort_if($peminjaman->user_id === $user->id, 403, 'Ketua Pokja tidak dapat memvalidasi pengajuannya sendiri.');
        abort_if((int) $peminjaman->user->pokja_id !== (int) $user->pokja_id, 403, 'Pengajuan ini bukan milik pokja yang Anda kelola.');
        abort_unless($peminjaman->status === 'menunggu_validasi', 409, 'Peminjaman sudah diproses sebelumnya.');
        abort_unless(
            Peminjaman::needsKetuaValidation()->whereKey($peminjaman->id)->exists(),
            403,
            'Pengajuan ini tidak sedang menunggu validasi Ketua Pokja.'
        );

        $validated = $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'alasan' => ['nullable', 'string', 'max:500'],
        ]);

        $isApprove = $validated['decision'] === 'approve';

        PeminjamanValidation::create([
            'peminjaman_id' => $peminjaman->id,
            'validator_id' => $user->id,
            'status' => $isApprove ? 'disetujui' : 'ditolak',
            'alasan_penolakan' => $isApprove ? null : ($validated['alasan'] ?? null),
            'validated_at' => now(),
        ]);

        if (! $isApprove) {
            $peminjaman->update(['status' => 'ditolak']);
        }

        return back()->with(
            'success',
            $isApprove
                ? 'Usulan disetujui Ketua Pokja dan diteruskan ke Admin TU/Superadmin untuk keputusan akhir.'
                : 'Peminjaman ditolak.'
        );
    }

    public function tuIndex(Request $request)
    {
        $user = Auth::user();
        $kategoriId = $request->integer('kategori_id') ?: null;
        $pokjaId = $request->integer('pokja_id') ?: null;

        $pengajuan = Peminjaman::with(['user', 'kategori', 'arsip'])
            ->readyForFinalValidation()
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->when($pokjaId, function ($query) use ($pokjaId) {
                $query->whereHas('user', function ($userQuery) use ($pokjaId) {
                    $userQuery->where('pokja_id', $pokjaId);
                });
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $kategoris = KategoriArsip::orderBy('name')->get();
        $pokjas = Pokja::orderBy('name')->get();

        $view = Auth::user()->role?->slug === 'superadmin'
            ? 'Super_Admin.validasi_peminjaman_superadmin'
            : 'Admin_TU.validasi_admintu';

        return view($view, compact('pengajuan', 'kategoris', 'pokjas', 'kategoriId', 'pokjaId', 'user'));
    }

    public function tuDecision(Request $request, Peminjaman $peminjaman)
    {
        $user = Auth::user();

        abort_unless(
            $user && in_array($user->role?->slug, ['admin_tu', 'superadmin'], true),
            403,
            'Hanya Admin TU atau Superadmin yang dapat memberikan keputusan akhir.'
        );
        abort_unless($peminjaman->status === 'menunggu_validasi', 409, 'Peminjaman sudah diproses sebelumnya.');
        abort_unless(
            Peminjaman::readyForFinalValidation()->whereKey($peminjaman->id)->exists(),
            403
        );

        $validated = $request->validate([
            'decision' => ['required', 'in:approve,reject'],
        ]);

        $isApprove = $validated['decision'] === 'approve';

        PeminjamanValidation::create([
            'peminjaman_id' => $peminjaman->id,
            'validator_id' => $user->id,
            'status' => $isApprove ? 'disetujui' : 'ditolak',
            'alasan_penolakan' => null,
            'validated_at' => now(),
        ]);

        $peminjaman->update([
            'status' => $isApprove ? 'aktif' : 'ditolak',
        ]);

        return back()->with('success', 'Status peminjaman berhasil diperbarui.');
    }
}