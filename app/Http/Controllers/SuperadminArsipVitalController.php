<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\ArsipVital;
use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;
use App\Models\Pokja;
use App\Models\SifatSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuperadminArsipVitalController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->validateVitalFilters($request);
        $arsipVitals = $this->vitalQuery('aktif', $filters)->paginate(10)->withQueryString();

        return view('Super_Admin.surat_vital_aktif_arsip', [
            'arsipVitals' => $arsipVitals,
            'filters' => $filters,
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
            'pokjas' => Pokja::orderBy('name')->get(),
            'user' => $request->user(),
        ]);
    }

    public function indexInactive(Request $request)
    {
        $filters = $this->validateVitalFilters($request);
        $arsipVitals = $this->vitalQuery('inaktif', $filters)->paginate(10)->withQueryString();

        return view('Super_Admin.surat_vital_inaktif_arsip', [
            'arsipVitals' => $arsipVitals,
            'filters' => $filters,
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
            'user' => $request->user(),
        ]);
    }

    public function create(Request $request)
    {
        return view('Super_Admin.form_tambah_arsip_vital_superadmin', [
            'kategoris' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
            'submissionToken' => old('submission_token', (string) Str::uuid()),
            'user' => $request->user(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'submission_token' => ['required', 'uuid', 'unique:arsips,submission_token'],
            'nomor_dokumen' => ['nullable', 'string', 'max:255'],
            'nama_arsip' => ['required', 'string', 'max:255'],
            'kategori_arsip_id' => ['required', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['required', 'exists:sifat_surats,id'],
            'tanggal_dokumen' => ['required', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'exists:klasifikasi_arsips,id'],
            'tanda_tangan' => ['required', 'in:digital,konvensional'],
            'keterangan' => ['nullable', 'string'],
            'file_arsip' => ['required', 'file', 'mimes:pdf,doc,docx,zip,rar,jpg,jpeg,png,gif,bmp,webp', 'max:5120'],
        ], [
            'file_arsip.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $file = $request->file('file_arsip');
        $storedPath = null;

        try {
            DB::transaction(function () use ($file, $validated, &$storedPath) {
                $storedPath = $file->store('arsip', 'public');
                if (! is_string($storedPath) || $storedPath === '') {
                    throw new \RuntimeException('File arsip gagal disimpan.');
                }

                $arsip = Arsip::create([
                    'nomor_dokumen' => $validated['nomor_dokumen'] ?? null,
                    'nama_arsip' => $validated['nama_arsip'],
                    'kategori_arsip_id' => $validated['kategori_arsip_id'],
                    'sifat_surat_id' => $validated['sifat_surat_id'],
                    'pokja_id' => null,
                    'tanggal_dokumen' => $validated['tanggal_dokumen'],
                    'klasifikasi_arsip_id' => $validated['klasifikasi_arsip_id'] ?? null,
                    'tanda_tangan' => $validated['tanda_tangan'],
                    'keterangan' => $validated['keterangan'] ?? null,
                    'file_path' => $storedPath,
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'status' => 'aktif',
                    'uploaded_by' => auth()->id(),
                    'submission_token' => $validated['submission_token'],
                ]);

                $arsip->arsipVital()->create(['status' => 'aktif']);
            });
        } catch (\Throwable $exception) {
            if ($storedPath && Storage::disk('public')->exists($storedPath)) {
                Storage::disk('public')->delete($storedPath);
            }

            throw $exception;
        }

        return redirect()
            ->route('superadmin.arsip-vital.aktif')
            ->with('success', 'Arsip vital berhasil ditambahkan.');
    }

    public function show(ArsipVital $arsipVital)
    {
        abort_unless(in_array($arsipVital->status, ['aktif', 'inaktif'], true), 404);

        $arsip = $arsipVital->arsip;
        abort_unless($arsip && $arsip->file_path && Storage::disk('public')->exists($arsip->file_path), 404);

        return Storage::disk('public')->response($arsip->file_path);
    }

    public function deleteInactive(Request $request, ArsipVital $arsipVital)
    {
        abort_unless($arsipVital->status === 'inaktif', 404);

        $filters = $this->validateVitalFilters($request);
        $arsip = $arsipVital->arsip;
        abort_unless($arsip && ! $arsip->trashed(), 404);

        $filePath = $arsip->file_path;
        $preserveFile = $filePath && (
            $arsip->peminjamans()->exists()
            || Arsip::withTrashed()->where('file_path', $filePath)->where('id', '!=', $arsip->id)->exists()
        );

        try {
            DB::transaction(fn () => $arsip->delete());

            if ($filePath && ! $preserveFile && Storage::disk('public')->exists($filePath) && ! Storage::disk('public')->delete($filePath)) {
                DB::transaction(fn () => $arsip->restore());

                return back()->withErrors(['arsip' => 'Arsip gagal dihapus. Silakan coba lagi.']);
            }
        } catch (\Throwable $exception) {
            if ($arsip->trashed()) {
                DB::transaction(fn () => $arsip->restore());
            }

            throw $exception;
        }

        $requestedPage = max(1, $request->integer('page', 1));
        $remainingCount = $this->vitalQuery('inaktif', $filters)->count();
        $lastPage = max(1, (int) ceil($remainingCount / 10));
        $returnQuery = array_filter([
            'nama_arsip' => $filters['nama_arsip'],
            'nomor_dokumen' => $filters['nomor_dokumen'],
            'jenis_surat_id' => $filters['jenisSuratId'],
            'sifat_surat_id' => $filters['sifatSuratId'],
            'kategori' => $filters['kategori'],
            'tanggal_upload' => $filters['tanggalUpload'],
            'klasifikasi_arsip_id' => $filters['klasifikasiId'],
            'page' => min($requestedPage, $lastPage),
        ], fn ($value) => $value !== '');

        return redirect()
            ->route('superadmin.arsip-vital.inaktif', $returnQuery)
            ->with('success', 'Arsip berhasil dihapus.');
    }

    private function validateVitalFilters(Request $request): array
    {
        $validated = $request->validate([
            'nama_arsip' => ['nullable', 'string', 'max:255'],
            'nomor_dokumen' => ['nullable', 'string', 'max:255'],
            'jenis_surat_id' => ['nullable', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'kategori' => ['nullable', 'in:digital,konvensional'],
            'tanggal_upload' => ['nullable', 'date'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        return [
            'nama_arsip' => $validated['nama_arsip'] ?? '',
            'nomor_dokumen' => $validated['nomor_dokumen'] ?? '',
            'jenisSuratId' => $validated['jenis_surat_id'] ?? '',
            'sifatSuratId' => $validated['sifat_surat_id'] ?? '',
            'kategori' => $validated['kategori'] ?? '',
            'tanggalUpload' => $validated['tanggal_upload'] ?? '',
            'klasifikasiId' => $validated['klasifikasi_arsip_id'] ?? '',
        ];
    }

    private function vitalQuery(string $status, array $filters)
    {
        return ArsipVital::with([
                'arsip.kategori',
                'arsip.sifatSurat',
                'arsip.klasifikasiArsip',
            ])
            ->where('status', $status)
            ->whereHas('arsip', function ($query) use ($filters) {
                $query->whereNotIn('status', ['dihapus'])
                    ->when($filters['nama_arsip'] !== '', fn ($builder) => $builder->where('nama_arsip', 'like', '%' . $filters['nama_arsip'] . '%'))
                    ->when($filters['nomor_dokumen'] !== '', fn ($builder) => $builder->where('nomor_dokumen', 'like', '%' . $filters['nomor_dokumen'] . '%'))
                    ->when($filters['jenisSuratId'] !== '', fn ($builder) => $builder->where('kategori_arsip_id', $filters['jenisSuratId']))
                    ->when($filters['sifatSuratId'] !== '', fn ($builder) => $builder->where('sifat_surat_id', $filters['sifatSuratId']))
                    ->when($filters['kategori'] !== '', fn ($builder) => $builder->where('tanda_tangan', $filters['kategori']))
                    ->when($filters['tanggalUpload'] !== '', fn ($builder) => $builder->whereDate('created_at', $filters['tanggalUpload']))
                    ->when($filters['klasifikasiId'] !== '', fn ($builder) => $builder->where('klasifikasi_arsip_id', $filters['klasifikasiId']));
            })
            ->latest('created_at');
    }
}
