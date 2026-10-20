<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;
use App\Models\SifatSurat;

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

        $peminjamanAktif = Peminjaman::whereHas('user', function ($query) use ($pokjaId) {
            $query->where('pokja_id', $pokjaId);
        })
            ->activeNow()
            ->count();

        // Hitung arsip Pokja yang benar-benar memiliki file tersimpan.
        $uploadArsip = Arsip::where('pokja_id', $pokjaId)
            ->whereNotIn('status', ['dihapus'])
            ->whereNotNull('file_path')
            ->count();

        $arsipTerbaru = Arsip::with(['kategori', 'klasifikasiArsip', 'sifatSurat', 'latestPeminjaman'])
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
        $arsips = Arsip::with(['kategori', 'sifatSurat'])
            ->where('pokja_id', $pokjaId)
            ->whereNotIn('status', ['dihapus'])
            ->latest()
            ->paginate(10);

        return view('Admin_Pokja.upload_arsip_pokja', compact('arsips'));
    }

    public function history(Request $request)
    {
        $user = Auth::user();
        $filters = $request->validate([
            'filter' => ['nullable', 'in:semua,arsip,peminjaman'],
            'search' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
        ]);
        $filter = $filters['filter'] ?? 'semua';
        $search = $this->normalizeSearch($filters['search'] ?? '');
        $searchPatterns = array_map(
            fn ($token) => $this->searchLikePattern($token),
            $this->searchTokens($search)
        );

        $arsipQuery = DB::table('arsips as arsip')
            ->leftJoin('jenis_surats as jenis_arsip', 'jenis_arsip.id', '=', 'arsip.kategori_arsip_id')
            ->leftJoin('sifat_surats as sifat_arsip', 'sifat_arsip.id', '=', 'arsip.sifat_surat_id')
            ->leftJoin('klasifikasi_arsips as klasifikasi_arsip', 'klasifikasi_arsip.id', '=', 'arsip.klasifikasi_arsip_id')
            ->where('arsip.pokja_id', $user->pokja_id)
            ->whereNotIn('arsip.status', ['dihapus'])
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(arsip.nama_arsip) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(jenis_arsip.name) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(sifat_arsip.name) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_arsip.kode) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_arsip.judul) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_arsip.uraian) LIKE ?', [$pattern]);
                    });
                }
            })
            ->when($filters['jenis_surat_id'] ?? null, fn ($query, $id) => $query->where('arsip.kategori_arsip_id', $id))
            ->when($filters['sifat_surat_id'] ?? null, fn ($query, $id) => $query->where('arsip.sifat_surat_id', $id))
            ->when($filters['tanggal_dokumen'] ?? null, fn ($query, $date) => $query->whereDate('arsip.tanggal_dokumen', $date))
            ->when($filters['klasifikasi_arsip_id'] ?? null, fn ($query, $id) => $query->where('arsip.klasifikasi_arsip_id', $id))
            ->select([
                DB::raw("'arsip' as jenis"),
                'arsip.id',
                'arsip.nama_arsip as nama',
                'jenis_arsip.name as jenis_surat',
                'sifat_arsip.name as sifat_surat',
                'klasifikasi_arsip.kode as kode_klasifikasi',
                'arsip.created_at as tanggal_upload',
                'arsip.status',
                'arsip.file_path',
            ]);

        $today = now()->toDateString();
        $peminjamanQuery = DB::table('peminjamans')
            ->join('users as peminjam_user', 'peminjam_user.id', '=', 'peminjamans.user_id')
            ->leftJoin('arsips as arsip_peminjaman', 'arsip_peminjaman.id', '=', 'peminjamans.arsip_id')
            ->leftJoin('jenis_surats as jenis_peminjaman', 'jenis_peminjaman.id', '=', 'peminjamans.kategori_id')
            ->leftJoin('jenis_surats as jenis_arsip_peminjaman', 'jenis_arsip_peminjaman.id', '=', 'arsip_peminjaman.kategori_arsip_id')
            ->leftJoin('sifat_surats as sifat_peminjaman', 'sifat_peminjaman.id', '=', 'peminjamans.sifat_surat_id')
            ->leftJoin('sifat_surats as sifat_arsip_peminjaman', 'sifat_arsip_peminjaman.id', '=', 'arsip_peminjaman.sifat_surat_id')
            ->leftJoin('klasifikasi_arsips as klasifikasi_peminjaman', 'klasifikasi_peminjaman.id', '=', 'peminjamans.klasifikasi_arsip_id')
            ->leftJoin('klasifikasi_arsips as klasifikasi_arsip_peminjaman', 'klasifikasi_arsip_peminjaman.id', '=', 'arsip_peminjaman.klasifikasi_arsip_id')
            ->where('peminjam_user.pokja_id', $user->pokja_id ?? 0)
            ->where(function ($query) use ($today) {
                $query->whereIn('peminjamans.status', ['ditolak', 'selesai', 'dikembalikan'])
                    ->orWhere(function ($expiredQuery) use ($today) {
                        $expiredQuery->whereIn('peminjamans.status', ['aktif', 'disetujui'])
                            ->whereDate('peminjamans.tanggal_selesai', '<', $today);
                    });
            })
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(peminjamans.nama_surat) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(arsip_peminjaman.nama_arsip) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(jenis_peminjaman.name) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(jenis_arsip_peminjaman.name) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(sifat_peminjaman.name) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(sifat_arsip_peminjaman.name) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_peminjaman.kode) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_arsip_peminjaman.kode) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_peminjaman.judul) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_arsip_peminjaman.judul) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_peminjaman.uraian) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(klasifikasi_arsip_peminjaman.uraian) LIKE ?', [$pattern]);
                    });
                }
            })
            ->when($filters['jenis_surat_id'] ?? null, function ($query, $id) {
                $query->where(function ($subQuery) use ($id) {
                    $subQuery->where('peminjamans.kategori_id', $id)
                        ->orWhere('arsip_peminjaman.kategori_arsip_id', $id);
                });
            })
            ->when($filters['sifat_surat_id'] ?? null, function ($query, $id) {
                $query->where(function ($subQuery) use ($id) {
                    $subQuery->where('peminjamans.sifat_surat_id', $id)
                        ->orWhere('arsip_peminjaman.sifat_surat_id', $id);
                });
            })
            ->when($filters['tanggal_dokumen'] ?? null, fn ($query, $date) => $query->whereDate('arsip_peminjaman.tanggal_dokumen', $date))
            ->when($filters['klasifikasi_arsip_id'] ?? null, function ($query, $id) {
                $query->where(function ($subQuery) use ($id) {
                    $subQuery->where('peminjamans.klasifikasi_arsip_id', $id)
                        ->orWhere('arsip_peminjaman.klasifikasi_arsip_id', $id);
                });
            })
            ->select([
                DB::raw("'peminjaman' as jenis"),
                'peminjamans.id',
                DB::raw('COALESCE(arsip_peminjaman.nama_arsip, peminjamans.nama_surat) as nama'),
                DB::raw('COALESCE(jenis_peminjaman.name, jenis_arsip_peminjaman.name) as jenis_surat'),
                DB::raw('COALESCE(sifat_peminjaman.name, sifat_arsip_peminjaman.name) as sifat_surat'),
                DB::raw('COALESCE(klasifikasi_peminjaman.kode, klasifikasi_arsip_peminjaman.kode) as kode_klasifikasi'),
                'peminjamans.updated_at as tanggal_upload',
                DB::raw("CASE WHEN peminjamans.status IN ('aktif', 'disetujui') AND peminjamans.tanggal_selesai < '{$today}' THEN 'selesai' ELSE peminjamans.status END as status"),
                DB::raw('COALESCE(arsip_peminjaman.file_path, peminjamans.file_pengajuan) as file_path'),
            ]);

        $historyQuery = match ($filter) {
            'arsip' => $arsipQuery,
            'peminjaman' => $peminjamanQuery,
            default => $arsipQuery->unionAll($peminjamanQuery),
        };

        $history = DB::query()
            ->fromSub($historyQuery, 'history')
            ->orderByDesc('tanggal_upload')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $jenisSurats = KategoriArsip::orderBy('name')->get();
        $sifatSurats = SifatSurat::orderBy('name')->get();
        $klasifikasis = KlasifikasiArsip::orderedForSelection()->get();

        return view('Admin_Pokja.history_pokja', compact(
            'history',
            'filter',
            'filters',
            'search',
            'jenisSurats',
            'sifatSurats',
            'klasifikasis'
        ));
    }

    public function profil()
    {
        return view('Admin_Pokja.profile_pokja');
    }

    public function formUpload()
    {
        $user = Auth::user();
        $jenisSurats = KategoriArsip::orderBy('name')->get();
        $sifatSurats = SifatSurat::orderBy('name')->get();
        $klasifikasis = KlasifikasiArsip::orderedForSelection()->get();

        $view = $user && $user->role && $user->role->slug === 'ketua_pokja'
            ? 'Ketua_Pokja.form_upload_ketuapokja'
            : 'Admin_Pokja.form_upload_pokja';

        return view($view, [
            'jenisSurats' => $jenisSurats,
            'kategoris' => $jenisSurats,
            'klasifikasis' => $klasifikasis,
            'sifatSurats' => $sifatSurats,
        ]);
    }

    public function storeArsip(Request $request)
    {
        $user = Auth::user();
        $pokjaId = $user->pokja_id;

        if (! $request->filled('jenis_surat_id') && $request->filled('kategori_arsip_id')) {
            $request->merge(['jenis_surat_id' => $request->input('kategori_arsip_id')]);
        }

        if (! $pokjaId) {
            return back()->withErrors([
                'pokja_id' => 'Akun Anda belum terhubung dengan Pokja manapun. Hubungi Super Admin untuk mengatur Pokja Anda.',
            ]);
        }

        $validatedData = $request->validate([
            'nama_arsip' => ['required', 'string', 'max:255'],
            'jenis_surat_id' => ['required', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['required', 'integer', 'exists:sifat_surats,id'],
            'tanggal_dokumen' => ['required', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
            'file_arsip' => ['required', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:5120'],
        ], [
            'nama_arsip.required' => 'Nama surat wajib diisi.',
            'nama_arsip.string' => 'Nama surat harus berupa teks.',
            'nama_arsip.max' => 'Nama surat maksimal 255 karakter.',
            'jenis_surat_id.required' => 'Jenis surat wajib dipilih.',
            'jenis_surat_id.integer' => 'Jenis surat yang dipilih tidak valid.',
            'jenis_surat_id.exists' => 'Jenis surat yang dipilih tidak tersedia.',
            'sifat_surat_id.required' => 'Sifat surat wajib dipilih.',
            'sifat_surat_id.integer' => 'Sifat surat yang dipilih tidak valid.',
            'sifat_surat_id.exists' => 'Sifat surat yang dipilih tidak tersedia.',
            'tanggal_dokumen.required' => 'Tanggal dokumen wajib diisi.',
            'tanggal_dokumen.date' => 'Tanggal dokumen tidak valid.',
            'klasifikasi_arsip_id.integer' => 'Kode klasifikasi yang dipilih tidak valid.',
            'klasifikasi_arsip_id.exists' => 'Kode klasifikasi yang dipilih tidak tersedia.',
            'file_arsip.required' => 'File arsip wajib diunggah.',
            'file_arsip.file' => 'File arsip yang diunggah tidak valid.',
            'file_arsip.mimes' => 'File arsip harus berformat PDF, DOC, DOCX, ZIP, atau RAR.',
            'file_arsip.max' => 'Ukuran file maksimal 5 MB.',
        ], [
            'nama_arsip' => 'nama surat',
            'jenis_surat_id' => 'jenis surat',
            'sifat_surat_id' => 'sifat surat',
            'tanggal_dokumen' => 'tanggal dokumen',
            'klasifikasi_arsip_id' => 'kode klasifikasi',
            'file_arsip' => 'file arsip',
        ]);

        $file = $request->file('file_arsip');
        $storedPath = null;

        try {
            DB::transaction(function () use ($file, $validatedData, $pokjaId, $user, &$storedPath) {
                $storedPath = $file->store('arsip', 'public');

                Arsip::create([
                    'nama_arsip' => $validatedData['nama_arsip'],
                    'kategori_arsip_id' => $validatedData['jenis_surat_id'],
                    'sifat_surat_id' => $validatedData['sifat_surat_id'],
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
        $user = Auth::user();
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'kategori_arsip_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
        ]);

        $search = $this->normalizeSearch($filters['search'] ?? '');
        $searchPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($search));

        $riwayatPeminjaman = Peminjaman::with([
                'arsip.kategori',
                'arsip.sifatSurat',
                'arsip.klasifikasiArsip',
                'kategori',
                'sifatSurat',
                'klasifikasiArsip',
                'user',
            ])
            ->whereHas('user', function ($query) use ($user) {
                $query->where('pokja_id', $user?->pokja_id);
            })
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(peminjamans.nama_surat) LIKE ?', [$pattern])
                            ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern]))
                            ->orWhereHas('kategori', fn ($kategoriQuery) => $kategoriQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('klasifikasiArsip', fn ($klasifikasiQuery) => $klasifikasiQuery->where(function ($subQuery) use ($pattern) {
                                $subQuery->whereRaw('LOWER(kode) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(judul) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(uraian) LIKE ?', [$pattern]);
                            }));
                    });
                }
            })
            ->when($filters['jenis_surat_id'] ?? null, function ($query, $id) {
                $query->where(function ($sub) use ($id) {
                    $sub->where('peminjamans.kategori_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $id));
                });
            })
            ->when($filters['sifat_surat_id'] ?? null, function ($query, $id) {
                $query->where(function ($sub) use ($id) {
                    $sub->where('peminjamans.sifat_surat_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('sifat_surat_id', $id));
                });
            })
            ->when($filters['kategori_arsip_id'] ?? null, function ($query, $id) {
                $query->where(function ($sub) use ($id) {
                    $sub->where('peminjamans.kategori_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $id));
                });
            })
            ->when($filters['tanggal_dokumen'] ?? null, function ($query, $date) {
                $dateString = \Carbon\Carbon::parse($date)->toDateString();
                $query->where(function ($sub) use ($dateString) {
                    $sub->whereDate('peminjamans.tanggal_mulai', $dateString)
                        ->orWhereDate('peminjamans.tanggal_selesai', $dateString)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereDate('tanggal_dokumen', $dateString));
                });
            })
            ->when($filters['klasifikasi_arsip_id'] ?? null, function ($query, $id) {
                $query->where(function ($sub) use ($id) {
                    $sub->where('peminjamans.klasifikasi_arsip_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('klasifikasi_arsip_id', $id));
                });
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $jenisSurats = KategoriArsip::orderBy('name')->get();
        $sifatSurats = \App\Models\SifatSurat::orderBy('name')->get();
        $klasifikasis = KlasifikasiArsip::orderedForSelection()->get();

        return view(
            'Admin_Pokja.riwayat_peminjaman_terakhir_pokja',
            [
                'riwayatPeminjaman' => $riwayatPeminjaman,
                'kategoris' => $jenisSurats,
                'jenisSurats' => $jenisSurats,
                'sifatSurats' => $sifatSurats,
                'klasifikasis' => $klasifikasis,
                'filters' => $filters,
                'search' => $search,
            ]
        );
    }

    public function riwayatArsipTerakhir(Request $request)
    {
        $user = Auth::user();
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
        ]);

        $search = $this->normalizeSearch($filters['search'] ?? '');
        $searchPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($search));

        $riwayatArsip = Arsip::with(['kategori', 'sifatSurat', 'klasifikasiArsip'])
            ->where('pokja_id', $user?->pokja_id)
            ->whereNotIn('status', ['dihapus'])
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern])
                            ->orWhereHas('kategori', fn ($kategoriQuery) => $kategoriQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('sifatSurat', fn ($sifatQuery) => $sifatQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('klasifikasiArsip', function ($klasifikasiQuery) use ($pattern) {
                                $klasifikasiQuery->whereRaw('LOWER(kode) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(judul) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(uraian) LIKE ?', [$pattern]);
                            });
                    });
                }
            })
            ->when($filters['jenis_surat_id'] ?? null, fn ($query, $id) => $query->where('kategori_arsip_id', $id))
            ->when($filters['sifat_surat_id'] ?? null, fn ($query, $id) => $query->where('sifat_surat_id', $id))
            ->when($filters['klasifikasi_arsip_id'] ?? null, fn ($query, $id) => $query->where('klasifikasi_arsip_id', $id))
            ->when($filters['tanggal_dokumen'] ?? null, fn ($query, $date) => $query->whereDate('tanggal_dokumen', $date))
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        return view(
            'Admin_Pokja.riwayat_arsip_terakhir_pokja',
            [
                'riwayatArsip' => $riwayatArsip,
                'jenisSurats' => KategoriArsip::orderBy('name')->get(),
                'sifatSurats' => SifatSurat::orderBy('name')->get(),
                'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
                'filters' => $filters,
                'search' => $search,
            ]
        );
    }

    public function peminjamanAktif(Request $request)
    {
        $user = Auth::user();

        $filters = $request->validate([
            'nama_surat' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'kategori_arsip_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
        ]);

        $peminjamanQuery = Peminjaman::with([
            'user',
            'kategori',
            'sifatSurat',
            'klasifikasiArsip',
            'arsip.kategori',
            'arsip.sifatSurat',
            'arsip.klasifikasiArsip',
        ])
            ->whereHas('user', function ($query) use ($user) {
                $query->where('pokja_id', $user->pokja_id);
            })
            ->where(function ($query) {
                $query->activeNow()
                    ->orWhere('status', 'menunggu_validasi');
            });

        if (! $user || ! $user->pokja_id) {
            $peminjamanQuery->whereRaw('1 = 0');
        }

        $peminjamans = $peminjamanQuery
            ->when($filters['nama_surat'] ?? null, function ($query, $namaSurat) {
                $pattern = '%' . mb_strtolower(trim($namaSurat)) . '%';
                $query->where(function ($nameQuery) use ($pattern) {
                    $nameQuery->whereRaw('LOWER(peminjamans.nama_surat) LIKE ?', [$pattern])
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern]));
                });
            })
            ->when($filters['jenis_surat_id'] ?? null, function ($query, $id) {
                $query->where(function ($sub) use ($id) {
                    $sub->where('peminjamans.kategori_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $id));
                });
            })
            ->when($filters['sifat_surat_id'] ?? null, function ($query, $id) {
                $query->where(function ($sifatQuery) use ($id) {
                    $sifatQuery->where('peminjamans.sifat_surat_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('sifat_surat_id', $id));
                });
            })
            ->when($filters['kategori_arsip_id'] ?? null, function ($query, $id) {
                $query->where(function ($sub) use ($id) {
                    $sub->where('peminjamans.kategori_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $id));
                });
            })
            ->when($filters['tanggal_dokumen'] ?? null, function ($query, $date) {
                $formattedDate = \Carbon\Carbon::parse($date)->toDateString();
                // Tanggal dokumen merupakan atribut arsip, bukan periode peminjaman.
                $query->whereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereDate('tanggal_dokumen', $formattedDate));
            })
            ->when($filters['klasifikasi_arsip_id'] ?? null, function ($query, $id) {
                $query->where(function ($klasifikasiQuery) use ($id) {
                    $klasifikasiQuery->where('peminjamans.klasifikasi_arsip_id', $id)
                        ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('klasifikasi_arsip_id', $id));
                });
            })
            ->latest('tanggal_mulai')
            ->paginate(10)
            ->withQueryString();
        $peminjamans->getCollection()->each(function (Peminjaman $peminjaman) use ($user): void {
            if ((int) $peminjaman->user_id === (int) $user->id) {
                $peminjaman->load('balasans.user');
            }
        });

        $jenisSurats = KategoriArsip::orderBy('name')->get();
        $kategoris = $jenisSurats;
        $sifatSurats = SifatSurat::orderBy('id')->get();
        $klasifikasis = KlasifikasiArsip::orderedForSelection()->get();

        return view('Admin_Pokja.peminjaman_aktif_pokja', compact(
            'peminjamans',
            'filters',
            'jenisSurats',
            'kategoris',
            'sifatSurats',
            'klasifikasis'
        ));
    }

    public function detailPeminjamanAktif(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        abort_unless($user?->pokja_id, 404);
        abort_unless(
            $peminjaman->user()->where('pokja_id', $user->pokja_id)->exists(),
            404
        );

        $peminjaman->load([
            'user',
            'kategori',
            'sifatSurat',
            'klasifikasiArsip',
            'validations.validator',
            'arsip.kategori',
            'arsip.sifatSurat',
            'arsip.klasifikasiArsip',
        ]);

        return view('Admin_Pokja.detail_peminjaman_pokja', [
            'peminjaman' => $peminjaman,
            'user' => $user,
        ]);
    }

    public function showPeminjamanReplyFile(Peminjaman $peminjaman)
    {
        $user = Auth::user();
        abort_unless(
            $user
                && $user->pokja_id
                && (int) $peminjaman->user_id === (int) $user->id
                && $peminjaman->user()->where('pokja_id', $user->pokja_id)->exists(),
            404
        );

        $balasan = $peminjaman->balasans()
            ->whereNotNull('attachment_path')
            ->latest('created_at')
            ->first();
        abort_unless(
            $balasan?->attachment_path
                && Storage::disk('public')->exists($balasan->attachment_path),
            404
        );

        return response()->file(Storage::disk('public')->path($balasan->attachment_path));
    }

    public function suratMasuk()
    {
        return redirect()->route('pokja.peminjaman.aktif');
    }

    public function suratKeluar()
    {
        return redirect()->route('pokja.peminjaman.aktif');
    }

    public function ajukan()
    {
        $user = Auth::user();

        $dataPeminjaman = Peminjaman::with([
                'kategori',
                'arsip.kategori',
                'arsip.sifatSurat',
                'arsip.klasifikasiArsip',
            ])
            ->where('user_id', Auth::id())
            ->whereHas('user', fn ($query) => $query->where('pokja_id', $user?->pokja_id))
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

        $kategoris = \App\Models\KategoriArsip::orderBy('name')->get();
        $sifatSurats = SifatSurat::orderBy('name')->get();
        $klasifikasis = KlasifikasiArsip::orderedForSelection()->get();

        return view('Admin_Pokja.form_peminjaman_pokja', compact(
            'arsips',
            'kategoris',
            'sifatSurats',
            'klasifikasis'
        ));
    }


    public function storePeminjaman(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'arsip_id' => [
                'nullable',
                'integer',
                Rule::exists('arsips', 'id')
                    ->where('pokja_id', $user->pokja_id)
                    ->where('status', 'aktif')
                    ->whereNull('deleted_at'),
            ],
            'nama_surat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:jenis_surats,id',
            'sifat_surat_id' => 'nullable|exists:sifat_surats,id',
            'klasifikasi_arsip_id' => 'nullable|exists:klasifikasi_arsips,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan' => 'required|string',
            'file_pengajuan' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:5120'],
        ], [
            'file_pengajuan.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $keterangan = trim((string) ($validatedData['keterangan'] ?? $request->input('keperluan', '')));

        $filePath = null;

        try {
            DB::transaction(function () use ($request, $user, $validatedData, $keterangan, &$filePath) {
                if ($request->hasFile('file_pengajuan')) {
                    $filePath = $request
                        ->file('file_pengajuan')
                        ->store('pengajuan-peminjaman', 'public');
                    if (! is_string($filePath) || $filePath === '') {
                        throw new \RuntimeException('File pengajuan gagal disimpan.');
                    }
                }

                Peminjaman::create([
                    'arsip_id' => $validatedData['arsip_id'] ?? null,
                    'nama_surat' => $validatedData['nama_surat'],
                    'kategori_id' => $validatedData['kategori_id'],
                    'sifat_surat_id' => $validatedData['sifat_surat_id'] ?? null,
                    'klasifikasi_arsip_id' => $validatedData['klasifikasi_arsip_id'] ?? null,
                    'user_id' => $user->id,
                    'keperluan' => $keterangan,
                    'tanggal_mulai' => $validatedData['tanggal_mulai'],
                    'tanggal_selesai' => $validatedData['tanggal_selesai'],
                    'keterangan' => $keterangan,
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
