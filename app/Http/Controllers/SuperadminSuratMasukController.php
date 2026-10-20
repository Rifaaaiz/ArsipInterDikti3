<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;
use App\Models\Peminjaman;
use App\Models\Pokja;
use App\Models\SifatSurat;
use App\Models\SuratMasukBalasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SuperadminSuratMasukController extends Controller
{
    private const POKJA_VIEWS = [
        'adia' => ['slug' => 'anti-dosa-pendidikan-dan-integritas-akademik', 'view' => 'surat_masuk_adia', 'title' => 'ADIA'],
        'anti-dosa-pendidikan-dan-integritas-akademik' => ['slug' => 'anti-dosa-pendidikan-dan-integritas-akademik', 'view' => 'surat_masuk_adia', 'title' => 'ADIA'],
        'penjamin-mutu' => ['slug' => 'penjaminan-mutu', 'view' => 'surat_masuk_penjamin_mutu', 'title' => 'Penjamin Mutu'],
        'penjaminan-mutu' => ['slug' => 'penjaminan-mutu', 'view' => 'surat_masuk_penjamin_mutu', 'title' => 'Penjamin Mutu'],
        'sumber-daya' => ['slug' => 'sumber-daya', 'view' => 'surat_masuk_sumber_daya', 'title' => 'Sumber Daya'],
        'belmawa' => ['slug' => 'pembelajaran-kemahasiswaan-dan-prestasi', 'view' => 'surat_masuk_belmawa', 'title' => 'BELMAWA'],
        'pembelajaran-kemahasiswaan-dan-prestasi' => ['slug' => 'pembelajaran-kemahasiswaan-dan-prestasi', 'view' => 'surat_masuk_belmawa', 'title' => 'BELMAWA'],
        'si-pddikti' => ['slug' => 'sistem-informasi-dan-pddikti', 'view' => 'surat_masuk_si_pddikti', 'title' => 'SI PDDIKTI'],
        'sistem-informasi-dan-pddikti' => ['slug' => 'sistem-informasi-dan-pddikti', 'view' => 'surat_masuk_si_pddikti', 'title' => 'SI PDDIKTI'],
        'rpm' => ['slug' => 'riset-dan-pengabdian-masyarakat', 'view' => 'surat_masuk_rpm', 'title' => 'RPM'],
        'riset-dan-pengabdian-masyarakat' => ['slug' => 'riset-dan-pengabdian-masyarakat', 'view' => 'surat_masuk_rpm', 'title' => 'RPM'],
        'keuangan' => ['slug' => 'perencanaan-dan-keuangan', 'view' => 'surat_masuk_keuangan', 'title' => 'Keuangan'],
        'perencanaan-dan-keuangan' => ['slug' => 'perencanaan-dan-keuangan', 'view' => 'surat_masuk_keuangan', 'title' => 'Keuangan'],
        'hkt' => ['slug' => 'hukum-kepegawaian-dan-tata-laksana', 'view' => 'surat_masuk_hkt', 'title' => 'HKT'],
        'hukum-kepegawaian-dan-tata-laksana' => ['slug' => 'hukum-kepegawaian-dan-tata-laksana', 'view' => 'surat_masuk_hkt', 'title' => 'HKT'],
        'humas' => ['slug' => 'hubungan-masyarakat-dan-kerja-sama', 'view' => 'surat_masuk_humas', 'title' => 'HUMAS'],
        'hubungan-masyarakat-dan-kerja-sama' => ['slug' => 'hubungan-masyarakat-dan-kerja-sama', 'view' => 'surat_masuk_humas', 'title' => 'HUMAS'],
        'kelembagaan' => ['slug' => 'kelembagaan-dan-kemitraan', 'view' => 'surat_masuk_kelembagaan', 'title' => 'Kelembagaan'],
        'kelembagaan-dan-kemitraan' => ['slug' => 'kelembagaan-dan-kemitraan', 'view' => 'surat_masuk_kelembagaan', 'title' => 'Kelembagaan'],
        'tata-usaha-dan-barang-milik-negara' => ['slug' => 'tata-usaha-dan-barang-milik-negara', 'view' => 'surat_masuk', 'title' => 'Tata Usaha dan Barang Milik Negara'],
    ];

    public function index(string $pokjaSlug)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);

        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        $user = auth()->user();
        $pokjas = Pokja::orderBy('name')->get();

        $suratMasuk = Peminjaman::with([
                'user',
                'validations.validator.role',
                'kategori',
                'sifatSurat',
                'klasifikasiArsip',
                'arsip.kategori',
                'arsip.sifatSurat',
                'arsip.klasifikasiArsip',
                'balasans.user',
            ])
            ->whereIn('status', ['disetujui', 'aktif', 'selesai'])
            ->whereHas('user', fn ($query) => $query->where('pokja_id', $pokja->id))
            ->latest('created_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('Super_Admin.' . $config['view'], [
            'pokja' => $pokja,
            'pokjas' => $pokjas,
            'suratMasuk' => $suratMasuk,
            'user' => $user,
            'pokjaTitle' => $config['title'],
        ]);
    }

    public function showPeminjaman(string $pokjaSlug, Peminjaman $peminjaman)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);
        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        $this->ensureApprovedPeminjamanBelongsToPokja($peminjaman, $pokja);

        $peminjaman->load([
            'user',
            'validations.validator.role',
            'kategori',
            'sifatSurat',
            'klasifikasiArsip',
            'arsip.kategori',
            'arsip.sifatSurat',
            'arsip.klasifikasiArsip',
            'balasans.user',
        ]);
        $filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path;
        $hasFile = $filePath && Storage::disk('public')->exists($filePath);

        return view('Super_Admin.detail_surat_masuk', [
            'peminjaman' => $peminjaman,
            'pokja' => $pokja,
            'pokjas' => Pokja::orderBy('name')->get(),
            'hasFile' => $hasFile,
            'user' => auth()->user(),
            'jenisSurats' => KategoriArsip::orderBy('name')->get(),
            'sifatSurats' => SifatSurat::orderBy('name')->get(),
            'klasifikasis' => KlasifikasiArsip::orderedForSelection()->get(),
        ]);
    }

    public function replyPeminjamanForm(string $pokjaSlug, Peminjaman $peminjaman)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);
        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        $this->ensureApprovedPeminjamanBelongsToPokja($peminjaman, $pokja);

        $peminjaman->load([
            'user',
            'kategori',
            'sifatSurat',
            'klasifikasiArsip',
            'arsip',
            'balasans.user',
        ]);
        $filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path;
        $hasFile = $filePath && Storage::disk('public')->exists($filePath);

        return view('Super_Admin.form_balas_peminjaman', [
            'peminjaman' => $peminjaman,
            'pokja' => $pokja,
            'pokjas' => Pokja::orderBy('name')->get(),
            'balasans' => $peminjaman->balasans()->with('user')->paginate(10)->withQueryString(),
            'hasFile' => $hasFile,
            'user' => auth()->user(),
        ]);
    }

    public function storePeminjamanReply(Request $request, string $pokjaSlug, Peminjaman $peminjaman)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);
        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        $this->ensureApprovedPeminjamanBelongsToPokja($peminjaman, $pokja);

        return app(SuperadminPeminjamanController::class)->storeReply($request, $peminjaman);
    }

    public function showPeminjamanFile(string $pokjaSlug, Peminjaman $peminjaman)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);
        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        $this->ensureApprovedPeminjamanBelongsToPokja($peminjaman, $pokja);

        $filePath = $peminjaman->file_pengajuan ?: $peminjaman->arsip?->file_path;
        abort_unless($filePath && Storage::disk('public')->exists($filePath), 404);

        return response()->file(Storage::disk('public')->path($filePath));
    }

    private function ensureApprovedPeminjamanBelongsToPokja(Peminjaman $peminjaman, Pokja $pokja): void
    {
        abort_unless(
            in_array($peminjaman->status, ['disetujui', 'aktif', 'selesai'], true)
                && $peminjaman->user()->where('pokja_id', $pokja->id)->exists(),
            404
        );
    }

    public function indexAdia()
    {
        return $this->index('adia');
    }

    public function replyForm(string $pokjaSlug, Arsip $arsip)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);
        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        abort_unless((int) $arsip->pokja_id === (int) $pokja->id && $arsip->status !== 'dihapus', 404);

        $arsip->load(['kategori', 'sifatSurat', 'klasifikasiArsip']);
        $balasans = $arsip->balasans()
            ->with('user')
            ->paginate(10)
            ->withQueryString();
        $hasFile = $arsip->file_path && Storage::disk('public')->exists($arsip->file_path);

        return view('Super_Admin.form_reply_surat_masuk', [
            'pokja' => $pokja,
            'pokjas' => Pokja::orderBy('name')->get(),
            'arsip' => $arsip,
            'balasans' => $balasans,
            'hasFile' => $hasFile,
            'user' => auth()->user(),
        ]);
    }

    public function show(string $pokjaSlug, Arsip $arsip)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);
        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        abort_unless((int) $arsip->pokja_id === (int) $pokja->id && $arsip->status !== 'dihapus', 404);

        $arsip->load([
            'kategori',
            'sifatSurat',
            'klasifikasiArsip',
            'latestPeminjaman',
            'uploader',
        ]);
        $hasFile = $arsip->file_path && Storage::disk('public')->exists($arsip->file_path);

        return view('Super_Admin.detail_surat_masuk', [
            'arsip' => $arsip,
            'pokja' => $pokja,
            'pokjas' => Pokja::orderBy('name')->get(),
            'hasFile' => $hasFile,
            'user' => auth()->user(),
        ]);
    }

    public function storeReply(Request $request, string $pokjaSlug, Arsip $arsip)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);
        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        abort_unless((int) $arsip->pokja_id === (int) $pokja->id && $arsip->status !== 'dihapus', 404);

        $validated = $request->validate([
            'isi_balasan' => ['required', 'string', 'max:10000'],
            'lampiran_balasan' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx', 'max:5120'],
        ], [
            'lampiran_balasan.mimes' => 'Lampiran harus berformat JPG, JPEG, PNG, GIF, WEBP, PDF, DOC, atau DOCX.',
            'lampiran_balasan.max' => 'Ukuran lampiran balasan maksimal 5 MB.',
        ]);

        $attachmentPath = null;

        try {
            if ($request->hasFile('lampiran_balasan')) {
                $attachmentPath = $request->file('lampiran_balasan')->store('balasan-surat', 'public');
                if (! is_string($attachmentPath) || $attachmentPath === '') {
                    throw new \RuntimeException('Lampiran balasan gagal disimpan.');
                }
            }

            SuratMasukBalasan::create([
                'arsip_id' => $arsip->id,
                'user_id' => $request->user()->id,
                'isi_balasan' => $validated['isi_balasan'],
                'attachment_path' => $attachmentPath,
            ]);
        } catch (\Throwable $exception) {
            if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                Storage::disk('public')->delete($attachmentPath);
            }

            throw $exception;
        }

        return redirect()
            ->route('superadmin.surat-masuk.balas', [$pokja->slug, $arsip])
            ->with('success', 'Balasan berhasil disimpan.');
    }

    public function showFile(string $pokjaSlug, Arsip $arsip)
    {
        $config = self::POKJA_VIEWS[Str::slug($pokjaSlug)] ?? null;
        abort_unless($config, 404);
        $pokja = Pokja::where('slug', $config['slug'])->firstOrFail();
        abort_unless((int) $arsip->pokja_id === (int) $pokja->id && $arsip->status !== 'dihapus', 404);
        abort_unless($arsip->file_path && Storage::disk('public')->exists($arsip->file_path), 404);

        return Storage::disk('public')->response(
            $arsip->file_path,
            basename($arsip->file_path),
            [
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
            ],
            'inline'
        );
    }
}
