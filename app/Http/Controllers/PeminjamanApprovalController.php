<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\PeminjamanValidation;
use App\Models\Arsip;
use App\Models\Pokja;
use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;
use App\Models\SifatSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

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
        $namaSurat = $this->normalizeSearch($request->query('search'));
        $searchTokens = $this->searchTokens($namaSurat);
        $kodeKlasifikasi = trim((string) $request->query('kode_klasifikasi', ''));
        if ($request->query('pakai_klasifikasi') === '0') {
            $kodeKlasifikasi = '';
        }
        $tanggalDokumen = $request->query('tanggal');
        $kategoriId = $request->integer('kategori_id') ?: null;
        $pokjaId = $request->integer('pokja_id') ?: null;

        $arsips = Arsip::with(['pokja', 'kategori', 'uploader', 'klasifikasiArsip', 'sifatSurat'])
            ->whereNotIn('status', ['dihapus'])
            ->when($searchTokens !== [], function ($query) use ($searchTokens) {
                foreach ($searchTokens as $token) {
                    $pattern = $this->searchLikePattern($token);

                    $query->where(function ($tokenQuery) use ($pattern) {
                        $tokenQuery->whereRaw('LOWER(arsips.nama_arsip) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(arsips.nomor_dokumen) LIKE ?', [$pattern])
                            ->orWhereHas('klasifikasiArsip', function ($klasifikasiQuery) use ($pattern) {
                                $klasifikasiQuery->whereRaw('LOWER(kode) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(judul) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(uraian) LIKE ?', [$pattern]);
                            })
                            ->orWhereHas('kategori', fn ($kategoriQuery) => $kategoriQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('pokja', fn ($pokjaQuery) => $pokjaQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]));
                    });
                }
            })
            ->when($kodeKlasifikasi !== '', fn ($query) => $query->whereHas('klasifikasiArsip', fn ($klasifikasi) => $klasifikasi->whereRaw('LOWER(kode) = ?', [mb_strtolower($kodeKlasifikasi)])))
            ->when($tanggalDokumen, fn ($query) => $query->whereDate('tanggal_dokumen', $tanggalDokumen))
            ->when($kategoriId, fn ($query) => $query->where('kategori_arsip_id', $kategoriId))
            ->when($pokjaId, fn ($query) => $query->where('pokja_id', $pokjaId))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $arsips->getCollection()->each(function (Arsip $arsip): void {
            $klasifikasi = $arsip->klasifikasiArsip;

            if (! $klasifikasi || $klasifikasi->masa_aktif_tahun === null) {
                $arsip->setAttribute('retensi_status', null);
                $arsip->setAttribute('retensi_keterangan', $klasifikasi?->keterangan);
                return;
            }

            $tanggalAcuan = $arsip->tanggal_dokumen ?? $arsip->created_at;
            $masihAktif = Carbon::parse($tanggalAcuan)->addYears($klasifikasi->masa_aktif_tahun)->endOfDay()->isFuture();

            $arsip->setAttribute('retensi_status', $masihAktif ? 'Aktif' : 'Inaktif');
            $arsip->setAttribute('retensi_keterangan', $masihAktif ? 'Masih Aktif' : $klasifikasi->keterangan);
        });

        $pokjas = Pokja::orderBy('name')->get();
        $kategoris = KategoriArsip::orderBy('name')->get();
        $klasifikasis = KlasifikasiArsip::orderedForSelection()->get();
        $totalArsip = Arsip::whereNotIn('status', ['dihapus'])->count();
        $totalArsipPokja = $pokjaId
            ? Arsip::where('pokja_id', $pokjaId)->whereNotIn('status', ['dihapus'])->count()
            : $totalArsip;

        return view('Admin_TU.laporanarsip_admintu', compact(
            'arsips',
            'pokjas',
            'kategoris',
            'namaSurat',
            'kodeKlasifikasi',
            'tanggalDokumen',
            'kategoriId',
            'pokjaId',
            'totalArsip',
            'totalArsipPokja'
        ) + ['filters' => [
            'search' => $namaSurat,
            'tanggal' => $tanggalDokumen,
            'kategoriId' => $kategoriId,
            'pokjaId' => $pokjaId,
            'kodeKlasifikasi' => $kodeKlasifikasi,
        ], 'klasifikasis' => $klasifikasis]);
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

    public function tuArchiveForm()
    {
        return view('Admin_TU.form_upload_admintu', [
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
        ]);
    }

    public function storeTuArchive(Request $request)
    {
        $validatedData = $request->validate([
            'nama_arsip' => ['required', 'string', 'max:255'],
            'nomor_dokumen' => ['nullable', 'string', 'max:255'],
            'kategori_arsip_id' => ['required', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['required', 'exists:sifat_surats,id'],
            'klasifikasi_arsip_id' => ['nullable', 'exists:klasifikasi_arsips,id'],
            'pokja_id' => ['required', 'exists:pokjas,id'],
            'tanggal_dokumen' => ['required', 'date'],
            'file_arsip' => ['required', 'file', 'mimes:pdf,doc,docx,zip,rar,jpg,jpeg,png,gif,bmp,webp', 'max:5120'],
        ], [
            'file_arsip.max' => 'Ukuran file maksimal 5 MB.',
            'pokja_id.required' => 'Arsip tujuan / kelompok kerja wajib dipilih.',
        ]);

        $file = $request->file('file_arsip');
        $storedPath = null;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($file, $validatedData, &$storedPath) {
                $storedPath = $file->store('arsip', 'public');

                Arsip::create([
                    'nomor_dokumen' => $validatedData['nomor_dokumen'] ?? null,
                    'nama_arsip' => $validatedData['nama_arsip'],
                    'kategori_arsip_id' => $validatedData['kategori_arsip_id'],
                    'sifat_surat_id' => $validatedData['sifat_surat_id'],
                    'klasifikasi_arsip_id' => $validatedData['klasifikasi_arsip_id'] ?? null,
                    'pokja_id' => $validatedData['pokja_id'],
                    'tanggal_dokumen' => $validatedData['tanggal_dokumen'],
                    'file_path' => $storedPath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'status' => 'aktif',
                    'uploaded_by' => auth()->id(),
                ]);
            });
        } catch (\Throwable $e) {
            if ($storedPath && \Illuminate\Support\Facades\Storage::disk('public')->exists($storedPath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($storedPath);
            }

            throw $e;
        }

        return redirect()->route('admin-tu.laporan.arsip')->with('success', 'Arsip berhasil ditambahkan.');
    }

    public function ketuaIndex(Request $request)
    {
        $user = Auth::user();

        $pengajuan = Peminjaman::with([
                'user',
                'kategori',
                'sifatSurat',
                'klasifikasiArsip',
                'arsip.kategori',
                'arsip.pokja',
                'arsip.sifatSurat',
                'arsip.klasifikasiArsip',
            ])
            ->needsKetuaValidation()
            ->whereHas('user', function ($query) use ($user) {
                $query->where('pokja_id', $user->pokja_id);
            })
            ->where('user_id', '!=', $user->id)
            ->when(! $user->pokja_id, fn ($query) => $query->whereRaw('1 = 0'))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('Ketua_Pokja.validasi_upload_ketuapokja', compact('pengajuan'));
    }

    public function ketuaApproveAll()
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->role && $user->role->slug === 'ketua_pokja',
            403,
            'Hanya Ketua Pokja yang berhak memvalidasi usulan.'
        );
        abort_if(! $user->pokja_id, 403, 'Akun Ketua Pokja belum terhubung dengan Pokja.');

        $approvedCount = DB::transaction(function () use ($user) {
            $pengajuan = Peminjaman::needsKetuaValidation()
                ->where('user_id', '!=', $user->id)
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('pokja_id', $user->pokja_id);
                })
                ->lockForUpdate()
                ->get();

            foreach ($pengajuan as $peminjaman) {
                PeminjamanValidation::create([
                    'peminjaman_id' => $peminjaman->id,
                    'validator_id' => $user->id,
                    'status' => 'disetujui',
                    'alasan_penolakan' => null,
                    'validated_at' => now(),
                ]);
            }

            return $pengajuan->count();
        });

        if ($approvedCount === 0) {
            return back()->with('info', 'Tidak ada usulan yang menunggu validasi.');
        }

        return back()->with('success', 'Berhasil menerima semua usulan surat!');
    }

    public function ketuaDecision(Request $request, Peminjaman $peminjaman)
    {
        $user = Auth::user();

        abort_unless($user && $user->role && $user->role->slug === 'ketua_pokja', 403, 'Hanya Ketua Pokja yang berhak membuka halaman validasi ini.');
        abort_if(! $user->pokja_id, 403, 'Akun Ketua Pokja belum terhubung dengan Pokja.');
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

        DB::transaction(function () use ($peminjaman, $user, $isApprove, $validated): void {
            $lockedPeminjaman = Peminjaman::query()
                ->whereKey($peminjaman->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                Peminjaman::needsKetuaValidation()->whereKey($lockedPeminjaman->id)->exists(),
                409,
                'Peminjaman sudah diproses sebelumnya.'
            );

            PeminjamanValidation::create([
                'peminjaman_id' => $lockedPeminjaman->id,
                'validator_id' => $user->id,
                'status' => $isApprove ? 'disetujui' : 'ditolak',
                'alasan_penolakan' => $isApprove ? null : ($validated['alasan'] ?? null),
                'validated_at' => now(),
            ]);

            if (! $isApprove) {
                $lockedPeminjaman->update(['status' => 'ditolak']);
            }
        });

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
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'peminjaman_id' => ['nullable', 'integer', 'exists:peminjamans,id'],
            'kategori_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'pokja_id' => ['nullable', 'integer', 'exists:pokjas,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
        ]);
        $search = $this->normalizeSearch($filters['search'] ?? null);
        $peminjamanId = $filters['peminjaman_id'] ?? null;
        $searchPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($search));
        $kategoriId = $filters['kategori_id'] ?? null;
        $sifatSuratId = $filters['sifat_surat_id'] ?? null;
        $pokjaId = $filters['pokja_id'] ?? null;
        $tanggalDokumen = $filters['tanggal_dokumen'] ?? null;
        $klasifikasiId = $filters['klasifikasi_arsip_id'] ?? null;

        $pengajuan = Peminjaman::with([
                'user.role',
                'user.pokja',
                'kategori',
                'sifatSurat',
                'klasifikasiArsip',
                'arsip.kategori',
                'arsip.pokja',
                'arsip.sifatSurat',
                'arsip.klasifikasiArsip',
            ])
            ->readyForFinalValidation()
            ->when($peminjamanId, fn ($query) => $query->whereKey($peminjamanId))
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(nama_surat) LIKE ?', [$pattern])
                            ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern]))
                            ->orWhereHas('kategori', fn ($kategoriQuery) => $kategoriQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('sifatSurat', fn ($sifatQuery) => $sifatQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('klasifikasiArsip', function ($klasifikasiQuery) use ($pattern) {
                                $klasifikasiQuery->whereRaw('LOWER(kode) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(judul) LIKE ?', [$pattern]);
                            })
                            ->orWhereHas('arsip.sifatSurat', fn ($sifatQuery) => $sifatQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('arsip.klasifikasiArsip', function ($klasifikasiQuery) use ($pattern) {
                                $klasifikasiQuery->whereRaw('LOWER(kode) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(judul) LIKE ?', [$pattern]);
                            })
                            ->orWhereHas('arsip.pokja', fn ($pokjaQuery) => $pokjaQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('user', function ($userQuery) use ($pattern) {
                                $userQuery->whereRaw('LOWER(name) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(username) LIKE ?', [$pattern])
                                    ->orWhereHas('pokja', fn ($pokjaQuery) => $pokjaQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]));
                            });
                    });
                }
            })
            ->when($kategoriId, function ($query) use ($kategoriId) {
                $query->where(function ($kategoriQuery) use ($kategoriId) {
                    $kategoriQuery->where('kategori_id', $kategoriId)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $kategoriId));
                });
            })
            ->when($sifatSuratId, function ($query) use ($sifatSuratId) {
                $query->where(function ($sifatQuery) use ($sifatSuratId) {
                    $sifatQuery->where('sifat_surat_id', $sifatSuratId)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('sifat_surat_id', $sifatSuratId));
                });
            })
            ->when($tanggalDokumen, function ($query) use ($tanggalDokumen) {
                $query->whereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereDate('tanggal_dokumen', $tanggalDokumen));
            })
            ->when($klasifikasiId, function ($query) use ($klasifikasiId) {
                $query->where(function ($klasifikasiQuery) use ($klasifikasiId) {
                    $klasifikasiQuery->where('klasifikasi_arsip_id', $klasifikasiId)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('klasifikasi_arsip_id', $klasifikasiId));
                });
            })
            ->when($pokjaId, function ($query) use ($pokjaId) {
                $query->where(function ($pokjaQuery) use ($pokjaId) {
                    $pokjaQuery->whereHas('user', function ($userQuery) use ($pokjaId) {
                        $userQuery->where('pokja_id', $pokjaId);
                    })->orWhereHas('arsip', function ($arsipQuery) use ($pokjaId) {
                        $arsipQuery->where('pokja_id', $pokjaId);
                    });
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

        return view($view, [
            'pengajuan' => $pengajuan,
            'kategoris' => $kategoris,
            'pokjas' => $pokjas,
            'kategoriId' => $kategoriId,
            'pokjaId' => $pokjaId,
            'user' => $user,
            'search' => $search,
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
            'sifatSuratId' => $sifatSuratId,
            'tanggalDokumen' => $tanggalDokumen,
            'klasifikasiId' => $klasifikasiId,
        ]);
    }

    public function tuDecision(Request $request, Peminjaman $peminjaman)
    {
        $user = Auth::user();

        abort_unless(
            $user && in_array($user->role?->slug, ['admin_tu', 'superadmin'], true),
            403,
            'Hanya Admin TU atau Superadmin yang dapat memberikan keputusan akhir.'
        );
        $validated = $request->validate([
            'decision' => ['required', 'in:approve,reject'],
            'alasan' => ['nullable', 'string', 'max:500'],
        ]);

        $isApprove = $validated['decision'] === 'approve';

        DB::transaction(function () use ($peminjaman, $user, $isApprove) {
            $lockedPeminjaman = Peminjaman::query()
                ->whereKey($peminjaman->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $lockedPeminjaman->status === 'menunggu_validasi',
                409,
                'Peminjaman sudah diproses sebelumnya.'
            );
            abort_unless(
                Peminjaman::readyForFinalValidation()->whereKey($lockedPeminjaman->id)->exists(),
                403
            );

            PeminjamanValidation::create([
                'peminjaman_id' => $lockedPeminjaman->id,
                'validator_id' => $user->id,
                'status' => $isApprove ? 'disetujui' : 'ditolak',
                'alasan_penolakan' => $isApprove ? null : ($validated['alasan'] ?? null),
                'validated_at' => now(),
            ]);

            $lockedPeminjaman->update([
                'status' => $isApprove ? 'disetujui' : 'ditolak',
            ]);
        });

        return back()->with(
            'success',
            $isApprove ? 'Usulan peminjaman berhasil diterima.' : 'Usulan peminjaman berhasil ditolak.'
        );
    }
}
