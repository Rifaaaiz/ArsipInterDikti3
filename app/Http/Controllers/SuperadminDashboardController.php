<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Models\Pokja;
use App\Models\User;
use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;
use App\Models\SifatSurat;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperadminDashboardController extends Controller
{
    public function users(Request $request)
    {
        $search = $this->normalizeSearch($request->query('search'));
        $searchPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($search));
        $roleId = $request->integer('role_id') ?: null;
        $pokjaId = $request->integer('pokja_id') ?: null;
        $deleteMode = filter_var($request->query('delete_mode', false), FILTER_VALIDATE_BOOLEAN);

        $users = User::with(['role', 'pokja'])
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(name) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(username) LIKE ?', [$pattern]);
                    });
                }
            })
            ->when($roleId, fn ($query) => $query->where('role_id', $roleId))
            ->when($pokjaId, fn ($query) => $query->where('pokja_id', $pokjaId))
            ->latest('created_at')
            ->paginate(10);
        $users->appends($request->query());

        $allowedRoles = \App\Models\Role::whereIn('slug', ['admin_pokja', 'ketua_pokja', 'admin_tu', 'superadmin'])
            ->orderBy('name')
            ->get();

        return view('Super_Admin.kelola_user_superadmin', [
            'users' => $users,
            'roles' => $allowedRoles,
            'pokjas' => Pokja::orderBy('name')->get(),
            'filters' => compact('search', 'roleId', 'pokjaId'),
            'deleteMode' => $deleteMode,
        ]);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'different:username'],
            'role_id' => ['required', 'exists:roles,id'],
            'pokja_id' => ['nullable', 'exists:pokjas,id'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.different' => 'Username dan password tidak boleh sama.',
            'role_id.required' => 'Role wajib dipilih.',
            'pokja_id.exists' => 'Kelompok Kerja tidak valid.',
        ]);

        $role = \App\Models\Role::findOrFail($validated['role_id']);
        $pokjaId = in_array($role->slug, ['admin_pokja', 'ketua_pokja'], true)
            ? $validated['pokja_id']
            : null;

        if (in_array($role->slug, ['admin_pokja', 'ketua_pokja'], true) && ! $pokjaId) {
            return back()->withInput()->withErrors(['pokja_id' => 'Kelompok Kerja wajib dipilih untuk role ini.']);
        }

        User::create([
            'name' => $validated['username'],
            'username' => $validated['username'],
            'email' => $validated['username'] . '@simarsip.local',
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'pokja_id' => $pokjaId,
            'status' => 'active',
        ]);

        return redirect()->route('superadmin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function deleteUser(Request $request, User $user)
    {
        abort_unless(auth()->user()?->role?->slug === 'superadmin', 403);

        if ($user->is(auth()->user())) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak dapat menghapus akun yang sedang digunakan.'], 422);
            }

            return back()->with('error', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->peminjamans()->exists()
            || $user->arsips()->exists()
            || $user->validations()->exists()
            || $user->suratMasukBalasans()->exists()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Gagal menghapus user. User masih memiliki data terkait dan tidak dapat dihapus.'], 422);
            }

            return back()->with('error', 'Gagal menghapus user. User masih memiliki data terkait dan tidak dapat dihapus.');
        }

        $user->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Selamat, anda telah menghapus user ini.']);
        }

        return back()->with('success', 'Selamat, anda telah menghapus user ini.');
    }

    public function activeLoans(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'pokja_id' => ['nullable', 'integer', 'exists:pokjas,id'],
            'tanggal_dokumen' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
        ]);
        $search = $this->normalizeSearch($filters['search'] ?? null);
        $searchPatterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($search));
        $jenisSuratId = $filters['jenis_surat_id'] ?? null;
        $sifatSuratId = $filters['sifat_surat_id'] ?? null;
        $pokjaId = $filters['pokja_id'] ?? null;
        $tanggalDokumen = $filters['tanggal_dokumen'] ?? null;
        $klasifikasiId = $filters['klasifikasi_arsip_id'] ?? null;

        $peminjamans = Peminjaman::with([
                'user.role',
                'user.pokja',
                'kategori',
                'sifatSurat',
                'klasifikasiArsip',
                'balasans.user',
                'arsip.kategori',
                'arsip.sifatSurat',
                'arsip.klasifikasiArsip',
            ])
            ->activeNow()
            ->when($pokjaId, fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->where('pokja_id', $pokjaId)))
            ->when($searchPatterns !== [], function ($query) use ($searchPatterns) {
                foreach ($searchPatterns as $pattern) {
                    $query->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(nama_surat) LIKE ?', [$pattern])
                            ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern]));
                    });
                }
            })
            ->when($jenisSuratId, fn ($query) => $query->where(function ($jenisQuery) use ($jenisSuratId) {
                $jenisQuery->where('peminjamans.kategori_id', $jenisSuratId)
                    ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('kategori_arsip_id', $jenisSuratId));
            }))
            ->when($sifatSuratId, fn ($query) => $query->where(function ($sifatQuery) use ($sifatSuratId) {
                $sifatQuery->where('peminjamans.sifat_surat_id', $sifatSuratId)
                    ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('sifat_surat_id', $sifatSuratId));
            }))
            ->when($tanggalDokumen, fn ($query) => $query->whereHas('arsip', fn ($arsipQuery) => $arsipQuery->whereDate('tanggal_dokumen', $tanggalDokumen)))
            ->when($klasifikasiId, fn ($query) => $query->where(function ($klasifikasiQuery) use ($klasifikasiId) {
                $klasifikasiQuery->where('peminjamans.klasifikasi_arsip_id', $klasifikasiId)
                    ->orWhereHas('arsip', fn ($arsipQuery) => $arsipQuery->where('klasifikasi_arsip_id', $klasifikasiId));
            }))
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('Super_Admin.peminjaman_aktif_superadmin', [
            'peminjamans' => $peminjamans,
            'search' => $search,
            'filters' => $filters,
            'jenisSurats' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
        ]);
    }

    public function createArchive(Request $request)
    {
        $selectedPokjaId = $request->validate([
            'pokja_id' => ['nullable', 'integer', 'exists:pokjas,id'],
        ])['pokja_id'] ?? null;
        $kategoris = KategoriArsip::orderBy('name')->get();
        $sifatSurats = SifatSurat::orderBy('name')->get();
        $klasifikasis = KlasifikasiArsip::orderedForSelection()->get();
        $pokjas = Pokja::orderBy('name')->get();
        $arsip = null;
        $isEdit = false;
        $submissionToken = old('submission_token', (string) Str::uuid());

        return view('Super_Admin.form_upload_superadmin', compact(
            'kategoris',
            'sifatSurats',
            'klasifikasis',
            'pokjas',
            'arsip',
            'isEdit',
            'selectedPokjaId',
            'submissionToken'
        ));
    }

    public function storeArchive(Request $request)
    {
        $validatedData = $request->validate([
            'nama_arsip' => ['required', 'string', 'max:255'],
            'kategori_arsip_id' => ['required', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['required', 'exists:sifat_surats,id'],
            'klasifikasi_arsip_id' => ['nullable', 'exists:klasifikasi_arsips,id'],
            'pokja_id' => ['required', 'exists:pokjas,id'],
            'tanggal_dokumen' => ['required', 'date'],
            'file_arsip' => ['required', 'file', 'mimes:pdf,doc,docx,zip,rar,jpg,jpeg,png,gif,bmp,webp', 'max:5120'],
            'submission_token' => ['required', 'uuid', 'unique:arsips,submission_token'],
        ], [
            'file_arsip.max' => 'Ukuran file maksimal 5 MB.',
            'pokja_id.required' => 'Arsip tujuan / kelompok kerja wajib dipilih.',
        ]);

        $file = $request->file('file_arsip');
        $storedPath = null;

        try {
            DB::transaction(function () use ($file, $validatedData, &$storedPath) {
                $storedPath = $file->store('arsip', 'public');
                if (! is_string($storedPath) || $storedPath === '') {
                    throw new \RuntimeException('File arsip gagal disimpan.');
                }

                Arsip::create([
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
                    'submission_token' => $validatedData['submission_token'],
                ]);
            });
        } catch (QueryException $e) {
            if ($storedPath && Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->delete($storedPath);
            }

            $existingArchive = Arsip::where('submission_token', $validatedData['submission_token'])->first();
            if ($existingArchive) {
                return redirect()
                    ->route('superadmin.surat-masuk', Pokja::findOrFail($existingArchive->pokja_id)->slug)
                    ->with('success', 'Pengiriman ganda diabaikan; arsip sudah tersimpan.');
            }

            throw $e;
        } catch (\Throwable $e) {
            if ($storedPath && Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->delete($storedPath);
            }

            throw $e;
        }

        return redirect()
            ->route('superadmin.surat-masuk', Pokja::findOrFail($validatedData['pokja_id'])->slug)
            ->with('success', 'Arsip berhasil dikirim ke kelompok kerja.');
    }

    public function editArchive(Arsip $arsip)
    {
        $arsip->load(['kategori', 'pokja', 'klasifikasiArsip', 'sifatSurat']);

        return view('Super_Admin.form_edit_superadmin', [
            'arsip' => $arsip,
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
            'isEdit' => true,
        ]);
    }

    public function updateArchive(Request $request, Arsip $arsip)
    {
        $validatedData = $request->validate([
            'nama_arsip' => ['required', 'string', 'max:255'],
            'kategori_arsip_id' => ['required', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['required', 'exists:sifat_surats,id'],
            'klasifikasi_arsip_id' => ['nullable', 'exists:klasifikasi_arsips,id'],
            'file_arsip' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar,jpg,jpeg,png,gif,bmp,webp', 'max:5120'],
            'remove_existing_file' => ['nullable', 'boolean'],
        ], [
            'file_arsip.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $newFilePath = $arsip->file_path;
        $oldFilePath = $arsip->file_path;
        $removeExistingFile = filter_var($request->boolean('remove_existing_file'), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('file_arsip')) {
            $file = $request->file('file_arsip');
            $newFilePath = $file->store('arsip', 'public');
            if (! is_string($newFilePath) || $newFilePath === '') {
                throw new \RuntimeException('File arsip gagal disimpan.');
            }
        }

        if ($removeExistingFile && ! $request->hasFile('file_arsip')) {
            $newFilePath = null;
        }

        try {
            DB::transaction(function () use ($request, $arsip, $validatedData, $newFilePath, $oldFilePath, $removeExistingFile) {
                if ($request->hasFile('file_arsip') && $oldFilePath && $oldFilePath !== $newFilePath && Storage::disk('public')->exists($oldFilePath)) {
                    // keep old file until new upload is successfully persisted
                }

                $arsip->update([
                    'nama_arsip' => $validatedData['nama_arsip'],
                    'kategori_arsip_id' => $validatedData['kategori_arsip_id'],
                    'sifat_surat_id' => $validatedData['sifat_surat_id'],
                    'klasifikasi_arsip_id' => $validatedData['klasifikasi_arsip_id'] ?? null,
                    'file_path' => $newFilePath,
                    'file_type' => $request->hasFile('file_arsip') ? $request->file('file_arsip')->getClientOriginalExtension() : ($removeExistingFile ? null : $arsip->file_type),
                    'file_size' => $request->hasFile('file_arsip') ? $request->file('file_arsip')->getSize() : ($removeExistingFile ? null : $arsip->file_size),
                ]);

                if ($request->hasFile('file_arsip') && $oldFilePath && $oldFilePath !== $newFilePath && Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }

                if ($removeExistingFile && $oldFilePath && Storage::disk('public')->exists($oldFilePath)) {
                    Storage::disk('public')->delete($oldFilePath);
                }
            });
        } catch (\Throwable $e) {
            if ($request->hasFile('file_arsip') && $newFilePath && Storage::disk('public')->exists($newFilePath)) {
                Storage::disk('public')->delete($newFilePath);
            }

            throw $e;
        }

        return redirect()->route('superadmin.arsip.laporan')->with('success', 'Arsip berhasil diperbarui.');
    }

    public function deleteArchiveFile(Request $request, Arsip $arsip)
    {
        if (! $arsip->file_path) {
            return response()->json(['success' => false, 'message' => 'Arsip tidak memiliki file.']);
        }

        $filePath = $arsip->file_path;

        DB::transaction(function () use ($arsip, $filePath) {
            $arsip->update([
                'file_path' => null,
                'file_type' => null,
                'file_size' => null,
            ]);
        });

        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        return response()->json(['success' => true, 'message' => 'File arsip berhasil dihapus.']);
    }

    public function archiveReport(Request $request)
    {
        $filters = $this->archiveFilters($request);
        $arsips = $this->archiveQuery($request)->paginate(10)->withQueryString();
        $statistics = $this->archiveStatistics();
        $statistics['totalArsipTu'] = (clone Arsip::whereNotIn('status', ['dihapus']))
            ->whereHas('pokja', fn ($query) => $query->where('name', 'Tata Usaha dan Barang Milik Negara'))
            ->count();

        return view('Super_Admin.laporan_arsip_superadmin', [
            'arsips' => $arsips,
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
            ...$statistics,
            'filters' => $filters,
        ]);
    }

    public function deletionArchive(Request $request)
    {
        $request->merge(['sumber' => $request->query('sumber', 'semua')]);

        return view('Super_Admin.penghapusan_arsip_superadmin', [
            'arsips' => $this->archiveQuery($request)->paginate(10)->withQueryString(),
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
            ...$this->archiveStatistics(),
            'filters' => $this->archiveFilters($request),
        ]);
    }

    public function deleteArchive(Arsip $arsip)
    {
        $filePath = $arsip->file_path;

        try {
            DB::transaction(fn () => $arsip->delete());

            if ($filePath && Storage::disk('public')->exists($filePath) && ! Storage::disk('public')->delete($filePath)) {
                DB::transaction(fn () => $arsip->restore());

                return back()->withErrors(['arsip' => 'File arsip tidak dapat dihapus dari storage.']);
            }
        } catch (\Throwable $exception) {
            if ($arsip->trashed()) {
                DB::transaction(fn () => $arsip->restore());
            }

            throw $exception;
        }

        return back()->with('success', 'Arsip berhasil dihapus.');
    }

    private function archiveQuery(Request $request)
    {
        $filters = $this->archiveFilters($request);

        return Arsip::with(['kategori', 'pokja', 'uploader.role', 'sifatSurat', 'klasifikasiArsip'])
            ->whereNotIn('status', ['dihapus'])
            ->when($filters['search'] !== '', function ($builder) use ($filters) {
                $patterns = array_map(fn ($token) => $this->searchLikePattern($token), $this->searchTokens($filters['search']));

                foreach ($patterns as $pattern) {
                    $builder->where(function ($searchQuery) use ($pattern) {
                        $searchQuery->whereRaw('LOWER(nama_arsip) LIKE ?', [$pattern])
                            ->orWhereRaw('LOWER(nomor_dokumen) LIKE ?', [$pattern])
                            ->orWhereHas('kategori', fn ($kategoriQuery) => $kategoriQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('sifatSurat', fn ($sifatQuery) => $sifatQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('pokja', fn ($pokjaQuery) => $pokjaQuery->whereRaw('LOWER(name) LIKE ?', [$pattern]))
                            ->orWhereHas('klasifikasiArsip', function ($klasifikasiQuery) use ($pattern) {
                                $klasifikasiQuery->whereRaw('LOWER(kode) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(judul) LIKE ?', [$pattern])
                                    ->orWhereRaw('LOWER(uraian) LIKE ?', [$pattern]);
                            });
                    });
                }
            })
            ->when($filters['tanggal'], fn ($builder) => $builder->whereDate('tanggal_dokumen', $filters['tanggal']))
            ->when($filters['kategoriId'], fn ($builder) => $builder->where('kategori_arsip_id', $filters['kategoriId']))
            ->when($filters['sifatSuratId'], fn ($builder) => $builder->where('sifat_surat_id', $filters['sifatSuratId']))
            ->when($filters['klasifikasiId'], fn ($builder) => $builder->where('klasifikasi_arsip_id', $filters['klasifikasiId']))
            ->when($filters['pokjaId'], fn ($builder) => $builder->where('pokja_id', $filters['pokjaId']))
            ->when($filters['source'] === 'kelompok_kerja', fn ($builder) => $builder->whereNotNull('pokja_id'))
            ->when($filters['source'] === 'tu', function ($builder) {
                $builder->whereNull('pokja_id')->whereHas('uploader.role', fn ($role) => $role->where('slug', 'admin_tu'));
            })
            ->latest('created_at');
    }

    private function archiveFilters(Request $request): array
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['nullable', 'date'],
            'kategori_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
            'pokja_id' => ['nullable', 'integer', 'exists:pokjas,id'],
            'sumber' => ['nullable', 'in:semua,kelompok_kerja,tu'],
        ]);

        return [
            'search' => $this->normalizeSearch($validated['search'] ?? null),
            'tanggal' => $validated['tanggal'] ?? null,
            'kategoriId' => $validated['kategori_id'] ?? null,
            'sifatSuratId' => $validated['sifat_surat_id'] ?? null,
            'klasifikasiId' => $validated['klasifikasi_arsip_id'] ?? null,
            'pokjaId' => $validated['pokja_id'] ?? null,
            'source' => $validated['sumber'] ?? 'semua',
        ];
    }

    private function archiveStatistics(): array
    {
        $activeArchives = Arsip::whereNotIn('status', ['dihapus']);

        return [
            'totalArsip' => (clone $activeArchives)->count(),
            'totalArsipPokja' => (clone $activeArchives)->whereNotNull('pokja_id')->count(),
            'totalArsipTu' => (clone $activeArchives)
                ->whereNull('pokja_id')
                ->whereHas('uploader.role', fn ($role) => $role->where('slug', 'admin_tu'))
                ->count(),
        ];
    }

    public function dashboard()
    {
        $user = auth()->user();
        $totalArsip = Arsip::whereNotIn('status', ['dihapus'])
            ->whereNotNull('pokja_id')
            ->count();

        // Reuse scope yang sudah ada di Model Peminjaman (sama seperti
        // yang dipakai PeminjamanApprovalController::tuDashboard()),
        // supaya definisi "aktif" konsisten di seluruh dashboard.
        $totalPeminjamanAktif = Peminjaman::activeNow()->count();

        $totalUserAktif = User::where('status', 'active')->count();

        $totalPokja = Pokja::count();
        $pokjas = Pokja::orderBy('name')->get();

        $arsipTerbaru = Arsip::with(['kategori', 'pokja', 'sifatSurat', 'klasifikasiArsip'])
            ->whereNotIn('status', ['dihapus'])
            ->whereNotNull('pokja_id')
            ->latest('created_at')
            ->limit(5)
            ->get();

        $peminjamanTerbaru = Peminjaman::with([
                'user.pokja',
                'kategori',
                'sifatSurat',
                'klasifikasiArsip',
                'arsip.kategori',
                'arsip.sifatSurat',
                'arsip.klasifikasiArsip',
            ])
            ->readyForFinalValidation()
            ->latest('created_at')
            ->limit(5)
            ->get();

        $pendingValidationCount = Peminjaman::readyForFinalValidation()->count();
        $ketuaApproval = fn ($query) => $query
            ->where('status', 'disetujui')
            ->whereHas('validator.role', fn ($role) => $role->where('slug', 'ketua_pokja'));
        $pendingValidationNotifications = Peminjaman::with([
                'user.pokja',
                'validations' => fn ($query) => $query
                    ->where('status', 'disetujui')
                    ->whereHas('validator.role', fn ($role) => $role->where('slug', 'ketua_pokja'))
                    ->with('validator.role')
                    ->latest('validated_at'),
            ])
            ->readyForFinalValidation()
            ->withMax(['validations as ketua_validated_at' => $ketuaApproval], 'validated_at')
            ->orderByDesc('ketua_validated_at')
            ->limit(5)
            ->get();
        $adminTuApproval = fn ($query) => $query
            ->where('status', 'disetujui')
            ->whereHas('validator.role', fn ($role) => $role->where('slug', 'admin_tu'));
        $suratMasukQuery = Peminjaman::query()
            ->whereIn('status', ['disetujui', 'aktif', 'selesai'])
            ->whereHas('user.pokja')
            ->whereHas('validations', $adminTuApproval);
        $suratMasukNotificationCount = (clone $suratMasukQuery)->count();
        $suratMasukTerbaru = (clone $suratMasukQuery)
            ->with([
                'user.pokja',
                'validations' => fn ($query) => $query
                    ->where('status', 'disetujui')
                    ->whereHas('validator.role', fn ($role) => $role->where('slug', 'admin_tu'))
                    ->with('validator.role')
                    ->latest('validated_at'),
            ])
            ->withMax(['validations as admin_tu_validated_at' => $adminTuApproval], 'validated_at')
            ->orderByDesc('admin_tu_validated_at')
            ->limit(5)
            ->get();

        $notifications = $pendingValidationNotifications->map(fn (Peminjaman $peminjaman) => [
            'category' => 'Peminjaman',
            'message' => 'Peminjaman dari Pokja ' . ($peminjaman->user?->pokja?->name ?? '-') . ' menunggu validasi Super Admin',
            'occurred_at' => $peminjaman->validations->first()?->validated_at ?? $peminjaman->created_at,
            'url' => route('superadmin.peminjaman.validasi', ['peminjaman_id' => $peminjaman->id]),
        ])->concat($suratMasukTerbaru->map(fn (Peminjaman $peminjaman) => [
            'category' => 'Surat Masuk',
            'message' => 'Surat Masuk Sudah Di Validasi dan masuk ke ' . $peminjaman->user->pokja->name,
            'occurred_at' => $peminjaman->validations->first()?->validated_at ?? $peminjaman->created_at,
            'url' => route('superadmin.surat-masuk.peminjaman.detail', [
                $peminjaman->user->pokja->slug,
                $peminjaman,
            ]),
        ]))
            ->sortByDesc('occurred_at')
            ->take(5)
            ->values();
        $notificationCount = $pendingValidationCount + $suratMasukNotificationCount;
        $suratMasukIndexUrl = $suratMasukTerbaru->first()
            ? route('superadmin.surat-masuk', $suratMasukTerbaru->first()->user->pokja->slug)
            : null;

        return view('Super_Admin.dashboard_superadmin', compact(
            'user',
            'totalArsip',
            'totalPeminjamanAktif',
            'totalUserAktif',
            'totalPokja',
            'pokjas',
            'arsipTerbaru',
            'peminjamanTerbaru',
            'notifications',
            'notificationCount',
            'pendingValidationCount',
            'suratMasukNotificationCount',
            'suratMasukIndexUrl'
        ));
    }
}