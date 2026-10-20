<?php

namespace App\Http\Controllers;

use App\Models\KategoriArsip;
use App\Models\KlasifikasiArsip;
use App\Models\Arsip;
use App\Models\Peminjaman;
use App\Models\Pokja;
use App\Models\SuratMasukBalasan;
use App\Models\SifatSurat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SuperadminPeminjamanController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'pokja_id' => ['nullable', 'integer', 'exists:pokjas,id'],
        ]);

        $pokjas = Pokja::orderBy('name')->get();
        $selectedPokja = $pokjas->firstWhere('id', $validated['pokja_id'] ?? null)
            ?? $pokjas->first();

        abort_unless($selectedPokja, 404, 'Belum ada kelompok kerja yang terdaftar.');

        return redirect()
            ->route('superadmin.surat-masuk', $selectedPokja->slug)
            ->with('info', 'Pilih permohonan peminjaman yang telah disetujui untuk mengirim balasan.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'arsip_id' => [
                'nullable',
                'integer',
                Rule::exists('arsips', 'id')
                    ->where('pokja_id', $request->input('pokja_id'))
                    ->where('status', 'aktif')
                    ->whereNull('deleted_at'),
            ],
            'nama_surat' => ['required', 'string', 'max:255'],
            'kategori_id' => ['required', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['nullable', 'integer', 'exists:sifat_surats,id'],
            'pokja_id' => ['required', 'integer', 'exists:pokjas,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'klasifikasi_arsip_id' => ['nullable', 'integer', 'exists:klasifikasi_arsips,id'],
            'keterangan' => ['required', 'string', 'max:10000'],
            'file_pengajuan' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:5120'],
        ], [
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'file_pengajuan.mimes' => 'File harus berformat PDF, DOC, DOCX, ZIP, atau RAR.',
            'file_pengajuan.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $requester = User::query()
            ->where('pokja_id', $validated['pokja_id'])
            ->where('status', 'active')
            ->whereHas('role', fn ($query) => $query->where('slug', 'admin_pokja'))
            ->orderBy('id')
            ->first();

        if (! $requester) {
            return back()
                ->withErrors(['pokja_id' => 'Pokja yang dipilih belum memiliki akun Admin Pokja aktif.'])
                ->withInput();
        }

        $filePath = null;

        try {
            DB::transaction(function () use ($request, $validated, $requester, &$filePath): void {
                if ($request->hasFile('file_pengajuan')) {
                    $filePath = $request->file('file_pengajuan')->store('pengajuan-peminjaman', 'public');
                    if (! is_string($filePath) || $filePath === '') {
                        throw new \RuntimeException('File pengajuan gagal disimpan.');
                    }
                }

                Peminjaman::create([
                    'arsip_id' => $validated['arsip_id'] ?? null,
                    'nama_surat' => $validated['nama_surat'],
                    'kategori_id' => $validated['kategori_id'],
                    'sifat_surat_id' => $validated['sifat_surat_id'] ?? null,
                    'klasifikasi_arsip_id' => $validated['klasifikasi_arsip_id'] ?? null,
                    'user_id' => $requester->id,
                    'keperluan' => $validated['keterangan'],
                    'tanggal_mulai' => $validated['tanggal_mulai'],
                    'tanggal_selesai' => $validated['tanggal_selesai'],
                    'keterangan' => $validated['keterangan'],
                    'file_pengajuan' => $filePath,
                    'status' => 'menunggu_validasi',
                ]);
            });
        } catch (\Throwable $exception) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            throw $exception;
        }

        return redirect()
            ->route('superadmin.peminjaman.create', ['pokja_id' => $validated['pokja_id']])
            ->with('success', 'Pengajuan berhasil dikirim ke Pokja dan menunggu proses validasi.');
    }

    public function storeReply(Request $request, Peminjaman $peminjaman)
    {
        $validated = $request->validate([
            'nama_surat' => ['required', 'string', 'max:255'],
            'kategori_id' => ['required', 'integer', 'exists:jenis_surats,id'],
            'sifat_surat_id' => ['required', 'integer', 'exists:sifat_surats,id'],
            'klasifikasi_arsip_id' => ['required', 'integer', 'exists:klasifikasi_arsips,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'isi_balasan' => ['required', 'string', 'max:10000'],
            'lampiran_balasan' => ['nullable', 'file', 'mimes:pdf,doc,docx,zip,rar', 'max:5120'],
        ], [
            'lampiran_balasan.mimes' => 'Lampiran harus berformat PDF, DOC, DOCX, ZIP, atau RAR.',
            'lampiran_balasan.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        $attachmentPath = null;

        try {
            if ($request->hasFile('lampiran_balasan')) {
                $attachmentPath = $request->file('lampiran_balasan')->store('balasan-surat', 'public');
                if (! is_string($attachmentPath) || $attachmentPath === '') {
                    throw new \RuntimeException('Lampiran balasan gagal disimpan.');
                }
            }

            DB::transaction(function () use ($peminjaman, $request, $validated, $attachmentPath): void {
                $lockedPeminjaman = Peminjaman::query()
                    ->whereKey($peminjaman->getKey())
                    ->whereIn('status', ['disetujui', 'aktif', 'selesai'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedPeminjaman->update([
                    'nama_surat' => $validated['nama_surat'],
                    'kategori_id' => $validated['kategori_id'],
                    'sifat_surat_id' => $validated['sifat_surat_id'],
                    'klasifikasi_arsip_id' => $validated['klasifikasi_arsip_id'],
                    'tanggal_mulai' => $validated['tanggal_mulai'],
                    'tanggal_selesai' => $validated['tanggal_selesai'],
                ]);

                SuratMasukBalasan::create([
                    'arsip_id' => $lockedPeminjaman->arsip_id,
                    'peminjaman_id' => $lockedPeminjaman->id,
                    'user_id' => $request->user()->id,
                    'isi_balasan' => $validated['isi_balasan'],
                    'attachment_path' => $attachmentPath,
                ]);
            });
        } catch (\Throwable $exception) {
            if ($attachmentPath && Storage::disk('public')->exists($attachmentPath)) {
                Storage::disk('public')->delete($attachmentPath);
            }

            throw $exception;
        }

        return back()->with('success', 'Balasan untuk peminjaman berhasil disimpan.');
    }
}
