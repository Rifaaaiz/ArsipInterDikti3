<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;

class AdminPokjaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // Pastikan user punya pokja
        $pokjaId = $user->pokja_id;

        // Total arsip milik Pokja yang sedang login
        $totalArsip = Arsip::where('pokja_id', $pokjaId)
            ->whereNotIn('status', ['dihapus'])
            ->count();

        // Total peminjaman aktif oleh user/Pokja
        $peminjamanAktif = Peminjaman::where('user_id', $user->id)
            ->activeNow()
            ->count();

        // Total arsip yang diupload oleh user tersebut
        $uploadArsip = Arsip::where('uploaded_by', $user->id)
            ->whereNotIn('status', ['dihapus'])
            ->count();

        // 5 arsip terbaru milik Pokja
        $arsipTerbaru = Arsip::with('kategori')
            ->where('pokja_id', $pokjaId)
            ->whereNotIn('status', ['dihapus'])
            ->latest()
            ->take(5)
            ->get();

        return view('Admin_Pokja.dashboard_pokja', compact(
            'totalArsip',
            'peminjamanAktif',
            'uploadArsip',
            'arsipTerbaru'
        ));
    }

    public function upload()
    {
        $user = Auth::user();
        $pokjaId = $user->pokja_id;

        // Daftar arsip milik Pokja yang sedang login, arsip terbaru di atas.
        // Relasi kategori arsip di Model Arsip bernama "kategori", bukan
        // "kategoriArsip", jadi eager-load-nya harus mengikuti nama itu.
        $arsips = Arsip::with('kategori')
            ->where('pokja_id', $pokjaId)
            ->whereNotIn('status', ['dihapus'])
            ->latest()
            ->paginate(10);

        return view('Admin_Pokja.upload_arsip_pokja', compact('arsips'));
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $filter = $request->query('filter', 'semua');

        if (! in_array($filter, ['semua', 'arsip', 'peminjaman'], true)) {
            $filter = 'semua';
        }

        $arsipQuery = DB::table('arsips')
            ->leftJoin(
                'kategori_arsips',
                'kategori_arsips.id',
                '=',
                'arsips.kategori_arsip_id'
            )
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
            ->where('peminjamans.user_id', $user->id)
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
                DB::raw("CASE WHEN peminjamans.status = 'aktif' AND peminjamans.tanggal_selesai < CURRENT_DATE THEN 'selesai' ELSE peminjamans.status END as status"),
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

        return view(
            'Admin_Pokja.history_pokja',
            compact('history', 'filter')
        );
    }

    public function profil()
    {
        return view('Admin_Pokja.profile_pokja');
    }

    public function formUpload()
    {
        $user = Auth::user();
        $kategoris = KategoriArsip::all();
        $klasifikasis = KlasifikasiArsip::orderBy('bab_urutan')
            ->orderBy('urutan')
            ->orderBy('kode')
            ->orderBy('nomor')
            ->orderBy('id')
            ->get();

        $view = $user && $user->role && $user->role->slug === 'ketua_pokja'
            ? 'Ketua_Pokja.form_upload_ketuapokja'
            : 'Admin_Pokja.form_upload_pokja';

        return view($view, compact('kategoris', 'klasifikasis'));
    }

    public function storeArsip(Request $request)
    {
        $user = Auth::user();
        $pokjaId = $user->pokja_id;

        if (! $pokjaId) {
            return back()->withErrors([
                'pokja_id' => 'Akun Anda belum terhubung dengan Pokja manapun. Hubungi Super Admin untuk mengatur Pokja Anda.',
            ]);
        }

        $validatedData = $request->validate([
            'nama_arsip' => 'required|string|max:255',
            'nomor_dokumen' => 'nullable|string|max:255',
            'kategori_arsip_id' => 'required|exists:kategori_arsips,id',
            'klasifikasi_arsip_id' => 'nullable|exists:klasifikasi_arsips,id',
            'tanggal_dokumen' => 'required|date',
            'file_arsip' => ['required', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:5120'],
        ], [
            'file_arsip.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('file_arsip');
        $storedPath = null;

        try {
            DB::transaction(function () use ($file, $validatedData, $pokjaId, $user, &$storedPath) {
                $storedPath = $file->store('arsip', 'public');

                Arsip::create([
                    'nomor_dokumen' => $validatedData['nomor_dokumen'] ?? null,
                    'nama_arsip' => $validatedData['nama_arsip'],
                    'kategori_arsip_id' => $validatedData['kategori_arsip_id'],
                    'klasifikasi_arsip_id' => $validatedData['klasifikasi_arsip_id'] ?? null,
                    'pokja_id' => $pokjaId,
                    'tanggal_dokumen' => $validatedData['tanggal_dokumen'],
                    'file_path' => $storedPath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'status' => 'aktif',
                    'uploaded_by' => $user->id,
                ]);
            });
        } catch (\Throwable $e) {
            if ($storedPath && Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->delete($storedPath);
            }

            throw $e;
        }

        $redirectRoute = $user && $user->role && $user->role->slug === 'ketua_pokja'
            ? 'ketua.upload'
            : 'arsip.index';

        return redirect()
            ->route($redirectRoute)
            ->with('success', 'Arsip berhasil diupload.');
    }

    public function riwayatPeminjamanTerakhir(Request $request)
    {
        $search = $this->normalizeSearch($request->query('search'));
        $searchPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($search));
        $kategoriId = $request->integer('kategori_id') ?: null;

        $riwayatPeminjaman = Peminjaman::with(['arsip', 'kategori'])
            ->where('user_id', Auth::id())
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

        return view(
            'Admin_Pokja.riwayat_peminjaman_terakhir_pokja',
            [
                'riwayatPeminjaman' => $riwayatPeminjaman,
                'kategoris' => KategoriArsip::orderBy('name')->get(),
                'search' => $search,
                'kategoriId' => $kategoriId,
            ]
        );
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

        $riwayatArsip = Arsip::with(['kategori', 'klasifikasiArsip'])
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

        return view(
            'Admin_Pokja.riwayat_arsip_terakhir_pokja',
            [
                'riwayatArsip' => $riwayatArsip,
                'kategoris' => KategoriArsip::orderBy('name')->get(),
                'search' => $search,
                'nomorDokumen' => $nomorDokumen,
                'kategoriId' => $kategoriId,
                'tanggalDokumen' => $tanggalDokumen,
            ]
        );
    }

    public function peminjamanAktif()
    {
        $user = Auth::user();

        $peminjamans = Peminjaman::with(['user', 'kategori'])
            ->whereHas('user', function ($query) use ($user) {
                $query->where('pokja_id', $user->pokja_id);
            })
            ->activeNow()
            ->latest('tanggal_mulai')
            ->paginate(10);

        return view('Admin_Pokja.peminjaman_aktif_pokja', compact('peminjamans'));
    }

    public function ajukan()
    {
        $dataPeminjaman = Peminjaman::with('kategori')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view(
            'Admin_Pokja.ajukan_peminjaman',
            compact('dataPeminjaman')
        );
    }

    public function formPeminjaman()
    {
        $user = Auth::user();

        $arsips = Arsip::where('pokja_id', $user->pokja_id)
            ->where('status', 'aktif')
            ->with('kategori')
            ->latest()
            ->get();

        $kategoris = \App\Models\KategoriArsip::all();

        $view = $user->role?->slug === 'ketua_pokja'
            ? 'Ketua_Pokja.form_peminjaman_ketuapokja'
            : 'Admin_Pokja.form_peminjaman_pokja';

        return view($view, compact('kategoris'));
    }


    public function storePeminjaman(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'nama_surat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_arsips,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keperluan' => 'required|string',
            'file_pengajuan' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ], [
            'file_pengajuan.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $filePath = null;

        try {
            DB::transaction(function () use ($request, $user, $validatedData, &$filePath) {
                if ($request->hasFile('file_pengajuan')) {
                    $filePath = $request
                        ->file('file_pengajuan')
                        ->store('pengajuan-peminjaman', 'public');
                }

                Peminjaman::create([
                    'arsip_id' => null,
                    'nama_surat' => $validatedData['nama_surat'],
                    'kategori_id' => $validatedData['kategori_id'],
                    'user_id' => $user->id,
                    'keperluan' => $validatedData['keperluan'],
                    'tanggal_mulai' => $validatedData['tanggal_mulai'],
                    'tanggal_selesai' => $validatedData['tanggal_selesai'],
                    'keterangan' => null,
                    'file_pengajuan' => $filePath,
                    'status' => 'menunggu_validasi',
                ]);
            });
        } catch (\Throwable $e) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            throw $e;
        }

        return redirect()
            ->route($user->role?->slug === 'ketua_pokja'
                ? 'ketua.ajukan'
                : 'peminjaman.ajukan')
            ->with(
                'success',
                'Pengajuan peminjaman berhasil dikirim dan menunggu approval Admin TU/Superadmin.'
            );
    }
}
